"use client";

import { Suspense, useEffect, useMemo, useRef } from "react";
import { Canvas, useFrame, useThree } from "@react-three/fiber";
import { Sparkles, useGLTF, useTexture } from "@react-three/drei";
import {
  Mesh,
  MeshBasicMaterial,
  NoToneMapping,
  PerspectiveCamera,
  ShaderMaterial,
  SRGBColorSpace,
  Texture,
  Vector3,
} from "three";
import { rig, sampleCamera } from "./cameraPath";
import { createCodeScreen, createTerminalScreen } from "./screenTextures";

const MODEL = "/3d/room.glb";
const BAKED = "/3d/room-baked.webp";

function prepare(tex: Texture) {
  tex.flipY = false;
  tex.colorSpace = SRGBColorSpace;
  tex.anisotropy = 8;
  return tex;
}

/** Las pantallas siempre se dibujan por delante de su marco (sin parpadeo por z-fighting). */
const SCREEN_OFFSET = { polygonOffset: true, polygonOffsetFactor: -2, polygonOffsetUnits: -4 };

/** Fundido entre dos capturas en el monitor + líneas de barrido sutiles. */
function makeMonitorMaterial(first: Texture) {
  return new ShaderMaterial({
    ...SCREEN_OFFSET,
    uniforms: {
      texA: { value: first },
      texB: { value: first },
      mixv: { value: 0 },
      time: { value: 0 },
    },
    vertexShader: /* glsl */ `
      varying vec2 vUv;
      void main() {
        vUv = uv;
        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
      }`,
    fragmentShader: /* glsl */ `
      uniform sampler2D texA;
      uniform sampler2D texB;
      uniform float mixv;
      uniform float time;
      varying vec2 vUv;
      void main() {
        // UV de glTF: vUv.y = 0 arriba. La nueva captura "barre" de arriba hacia abajo.
        float d = vUv.y;
        float showNew = 1.0 - smoothstep(mixv - 0.04, mixv, d);
        vec3 c = mix(texture2D(texA, vUv).rgb, texture2D(texB, vUv).rgb, showNew);
        float edge = (1.0 - smoothstep(0.0, 0.025, abs(d - mixv))) * step(0.001, mixv);
        c += edge * vec3(0.35, 0.4, 1.0);
        c *= 0.94 + 0.06 * sin(vUv.y * 900.0 + time * 6.0);
        gl_FragColor = vec4(c, 1.0);
        #include <colorspace_fragment>
      }`,
  });
}

function Studio({ projectImages, onReady }: { projectImages: string[]; onReady?: () => void }) {
  const { nodes } = useGLTF(MODEL) as unknown as { nodes: Record<string, Mesh> };
  const bakedRaw = useTexture(BAKED);
  const shotsRaw = useTexture(projectImages);
  const baked = useMemo(() => prepare(bakedRaw), [bakedRaw]);
  const shots = useMemo(() => shotsRaw.map(prepare), [shotsRaw]);

  const roomMat = useMemo(() => new MeshBasicMaterial({ map: baked }), [baked]);
  const monitorMat = useMemo(() => makeMonitorMaterial(shots[0]), [shots]);
  const code = useMemo(() => createCodeScreen(), []);
  const term = useMemo(() => createTerminalScreen(), []);
  const codeMat = useMemo(() => new MeshBasicMaterial({ map: code.tex, ...SCREEN_OFFSET }), [code]);
  const termMat = useMemo(() => new MeshBasicMaterial({ map: term.tex, ...SCREEN_OFFSET }), [term]);

  useEffect(() => onReady?.(), [onReady]);

  const shown = useRef(0);
  const transition = useRef({ to: 0, p: 1 });

  useFrame((_, dt) => {
    code.draw(dt);
    term.draw(dt);
    const u = monitorMat.uniforms;
    u.time.value += dt;
    const tr = transition.current;
    if (tr.p >= 1 && rig.project !== shown.current) {
      u.texA.value = shots[shown.current];
      u.texB.value = shots[rig.project];
      tr.to = rig.project;
      tr.p = 0;
    }
    if (tr.p < 1) {
      tr.p = Math.min(1, tr.p + dt * 1.6);
      u.mixv.value = tr.p;
      if (tr.p >= 1) {
        shown.current = tr.to;
        u.texA.value = shots[tr.to];
        u.mixv.value = 0;
      }
    }
  });

  const place = (n: Mesh) => ({
    geometry: n.geometry,
    position: n.position,
    quaternion: n.quaternion,
    scale: n.scale,
  });

  return (
    <group>
      <mesh {...place(nodes.room)} material={roomMat} />
      <mesh {...place(nodes.screen_main)} material={monitorMat} />
      <mesh {...place(nodes.screen_side)} material={codeMat} />
      <mesh {...place(nodes.screen_laptop)} material={termMat} />
      <Sparkles count={70} scale={[5, 3, 4.5]} position={[0, 1.6, 0]} size={2.2} speed={0.25} color="#a5b4fc" opacity={0.6} />
    </group>
  );
}

const desiredPos = new Vector3();
const desiredTarget = new Vector3();
const right = new Vector3();
const up = new Vector3(0, 1, 0);

function CameraRig() {
  const camera = useThree((s) => s.camera) as PerspectiveCamera;
  const state = useRef({ pos: new Vector3(), target: new Vector3(), shift: 0, ready: false });

  useFrame(({ size }, dt) => {
    const s = state.current;
    const mobile = size.width < 768;
    let shift = sampleCamera(rig.t, desiredPos, desiredTarget);
    if (mobile) shift = 0;

    // Paralaje con el puntero, en el plano de la cámara
    right.subVectors(desiredTarget, desiredPos).normalize().cross(up).normalize();
    desiredPos.addScaledVector(right, rig.pointerX * 0.12).addScaledVector(up, rig.pointerY * 0.06);

    const k = s.ready ? 1 - Math.exp(-dt * 4.5) : 1;
    s.pos.lerp(desiredPos, k);
    s.target.lerp(desiredTarget, k);
    s.shift += (shift - s.shift) * k;
    s.ready = true;

    camera.position.copy(s.pos);
    camera.lookAt(s.target);
    // FOV vertical que mantiene el encuadre horizontal de Blender (52°) en pantallas verticales
    const aspect = size.width / size.height;
    const hfov = (52 * Math.PI) / 180;
    camera.fov = Math.min(70, (2 * Math.atan(Math.tan(hfov / 2) / aspect) * 180) / Math.PI);
    camera.setViewOffset(size.width, size.height, s.shift * size.width, 0, size.width, size.height);
    camera.updateProjectionMatrix();
  });
  return null;
}

export default function StudioCanvas({
  projectImages,
  onReady,
}: {
  projectImages: string[];
  onReady?: () => void;
}) {
  useEffect(() => {
    const onMove = (e: PointerEvent) => {
      rig.pointerX = (e.clientX / window.innerWidth) * 2 - 1;
      rig.pointerY = -((e.clientY / window.innerHeight) * 2 - 1);
    };
    window.addEventListener("pointermove", onMove);
    return () => window.removeEventListener("pointermove", onMove);
  }, []);

  return (
    <Canvas
      dpr={[1, 2]}
      gl={{ antialias: true, toneMapping: NoToneMapping, alpha: true }}
      camera={{ fov: 31, near: 0.1, far: 40 }}
      style={{ position: "fixed", inset: 0 }}
    >
      <Suspense fallback={null}>
        <Studio projectImages={projectImages} onReady={onReady} />
      </Suspense>
      <CameraRig />
    </Canvas>
  );
}

useGLTF.preload(MODEL);
