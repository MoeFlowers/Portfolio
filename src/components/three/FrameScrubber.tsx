"use client";

import { useEffect, useRef } from "react";
import { rig } from "./cameraPath";

/** Fotogramas renderizados con blender/build_studio_real.py (versión fotorrealista). */
const BASE = "/3d/v2";
/** Fotogramas decodificados alrededor de la posición actual (el resto queda comprimido en memoria). */
const WINDOW = 20;
const FETCH_CONCURRENCY = 8;

interface Manifest {
  frames: number;
  perSegment: number;
  variants: number;
  projectKey: number;
  width: number;
  height: number;
}

const pad = (n: number) => String(n).padStart(3, "0");

async function fetchAll(urls: string[], onEach: () => void) {
  const out: Blob[] = new Array(urls.length);
  let next = 0;
  const worker = async () => {
    while (next < urls.length) {
      const i = next++;
      out[i] = await fetch(urls[i]).then((r) => r.blob());
      onEach();
    }
  };
  await Promise.all(Array.from({ length: FETCH_CONCURRENCY }, worker));
  return out;
}

/**
 * Reproduce el recorrido de cámara como secuencia de imágenes según el scroll (rig.t).
 *
 * Fluidez: las imágenes se descargan comprimidas, se decodifican fuera del hilo
 * principal (createImageBitmap) solo en una ventana alrededor del fotograma actual,
 * y cada fotograma se funde con el siguiente para que el movimiento sea continuo.
 */
export default function FrameScrubber({
  onReady,
  onProgress,
}: {
  projectImages?: string[];
  onReady?: () => void;
  onProgress?: (percent: number) => void;
}) {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current!;
    const ctx = canvas.getContext("2d", { alpha: false })!;
    let cancelled = false;
    let raf = 0;
    let dirty = true;
    let decodeWidth = 1920;

    const resize = () => {
      // Nunca más píxeles que los del render (1920px) ni más que el dpr de la pantalla
      const s = Math.min(window.devicePixelRatio || 1, 2, Math.max(1, 1920 / window.innerWidth));
      canvas.width = Math.round(window.innerWidth * s);
      canvas.height = Math.round(window.innerHeight * s);
      // Ancho al que se decodifica cada fotograma: lo que ocupa en pantalla (modo cover), máx. 1920
      decodeWidth = Math.min(1920, Math.ceil(Math.max(canvas.width, (canvas.height * 16) / 9)));
      dirty = true;
    };
    resize();
    window.addEventListener("resize", resize);

    const cover = (img: ImageBitmap, alpha: number) => {
      const sc = Math.max(canvas.width / img.width, canvas.height / img.height);
      const w = img.width * sc;
      const h = img.height * sc;
      ctx.globalAlpha = alpha;
      ctx.drawImage(img, (canvas.width - w) / 2, (canvas.height - h) / 2, w, h);
      ctx.globalAlpha = 1;
    };

    (async () => {
      const m: Manifest = await fetch(`${BASE}/manifest.json`).then((r) => r.json());
      const urls = [
        ...Array.from({ length: m.frames }, (_, i) => `${BASE}/f_${pad(i)}.webp`),
        ...Array.from({ length: m.variants }, (_, i) => `${BASE}/p_${i + 1}.webp`),
      ];
      let done = 0;
      const blobs = await fetchAll(urls, () => onProgress?.(Math.round((++done / urls.length) * 95)));
      if (cancelled) return;

      const frameBlobs = blobs.slice(0, m.frames);
      const variants = await Promise.all(blobs.slice(m.frames).map((b) => createImageBitmap(b)));
      const cache = new Map<number, ImageBitmap>();
      const pending = new Set<number>();

      const decode = (i: number) => {
        if (cache.has(i) || pending.has(i)) return;
        pending.add(i);
        createImageBitmap(frameBlobs[i], { resizeWidth: decodeWidth, resizeQuality: "medium" }).then((bm) => {
          pending.delete(i);
          if (cancelled) return bm.close();
          cache.set(i, bm);
          dirty = true;
        });
      };
      // Mantiene decodificada la ventana alrededor de "center", priorizando lo más cercano
      const ensureWindow = (center: number) => {
        for (let d = 0; d <= WINDOW; d++) {
          if (center + d < m.frames) decode(center + d);
          if (center - d >= 0) decode(center - d);
        }
        for (const [i, bm] of cache) {
          if (Math.abs(i - center) > WINDOW + 8) {
            bm.close();
            cache.delete(i);
          }
        }
      };
      const nearest = (i: number) => {
        for (let d = 0; d < m.frames; d++) {
          const a = cache.get(i - d) ?? cache.get(i + d);
          if (a) return a;
        }
        return undefined;
      };

      const holdFrame = m.projectKey * m.perSegment;
      let cur = rig.t * m.perSegment;
      ensureWindow(Math.round(cur));
      // Espera a tener el primer fotograma listo antes de quitar la pantalla de carga
      while (!cache.has(Math.round(cur)) && !cancelled) await new Promise((r) => setTimeout(r, 16));
      if (cancelled) return;
      onProgress?.(100);
      onReady?.();

      const projectImage = (p: number) => (p === 0 ? cache.get(holdFrame) ?? nearest(holdFrame) : variants[p - 1]);
      let drawn = -1;
      let shown = 0;
      let from = 0;
      let fade = 1;
      let last = performance.now();

      const loop = (now: number) => {
        const dt = Math.min(0.05, (now - last) / 1000);
        last = now;
        const target = rig.t * m.perSegment;
        cur += (target - cur) * (1 - Math.exp(-dt * 7));
        if (Math.abs(target - cur) < 0.002) cur = target;
        const center = Math.round(cur);
        ensureWindow(center);

        const atHold = Math.abs(cur - holdFrame) < 0.01;
        if (atHold && fade >= 1 && rig.project !== shown) {
          from = shown;
          shown = rig.project;
          fade = 0;
        }
        if (!atHold && Math.abs(cur - holdFrame) > 2) shown = from = 0;

        if (dirty || cur !== drawn || fade < 1) {
          if (atHold) {
            fade = Math.min(1, fade + dt * 2.5);
            const a = projectImage(from);
            const b = projectImage(shown);
            if (a) cover(a, 1);
            if (b && fade > 0) cover(b, fade);
          } else {
            // Fundido entre el fotograma anterior y el siguiente: movimiento continuo
            const i0 = Math.floor(cur);
            const i1 = Math.min(m.frames - 1, i0 + 1);
            const f = cur - i0;
            const a = cache.get(i0) ?? nearest(i0);
            const b = cache.get(i1);
            if (a) cover(a, 1);
            if (b && f > 0.01) cover(b, f);
          }
          drawn = cur;
          dirty = false;
        }
        raf = requestAnimationFrame(loop);
      };
      raf = requestAnimationFrame(loop);
    })();

    return () => {
      cancelled = true;
      cancelAnimationFrame(raf);
      window.removeEventListener("resize", resize);
    };
  }, [onReady, onProgress]);

  return (
    <canvas
      ref={canvasRef}
      style={{ position: "fixed", inset: 0, width: "100%", height: "100%", background: "#05060c" }}
    />
  );
}
