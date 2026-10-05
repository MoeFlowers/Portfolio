import { Vector3 } from "three";

type Vec3 = [number, number, number];

export interface CamKey {
  pos: Vec3;
  target: Vec3;
  /** Desplazamiento horizontal del encuadre (fracción del ancho). Negativo = escena a la derecha. */
  shift: number;
}

/** Blender (Z arriba) → three.js (Y arriba). */
const b = (x: number, y: number, z: number): Vec3 => [x, z, -y];

/**
 * Una cámara por sección, en el mismo orden que los `data-cam` de la página.
 * Las posiciones vienen de CAMERAS en blender/build_studio.py.
 */
export const CAMERA_KEYS: CamKey[] = [
  { pos: b(4.6, -5.4, 3.9), target: b(0.0, 0.7, 1.0), shift: -0.2 }, // hero
  { pos: b(2.4, -1.5, 1.85), target: b(-0.4, 1.5, 1.05), shift: -0.14 }, // impacto
  { pos: b(0.55, 0.62, 1.32), target: b(0.02, 1.35, 0.76), shift: -0.18 }, // stack (teclado)
  { pos: b(-0.42, 0.25, 1.28), target: b(0.0, 1.7, 1.2), shift: -0.08 }, // proyectos (monitor)
  { pos: b(0.4, -0.4, 1.75), target: b(-1.7, 1.6, 1.6), shift: 0.16 }, // experiencia (estantes)
  { pos: b(0.8, -5.6, 6.6), target: b(0.0, 0.6, 0.5), shift: 0.18 }, // contacto
];

/** Estado compartido entre el DOM (scroll) y la escena 3D, sin re-renders de React. */
export const rig = {
  /** Posición continua en el recorrido: 0 = hero, 1 = impacto, … */
  t: 0,
  /** Proyecto visible en el monitor. */
  project: 0,
  pointerX: 0,
  pointerY: 0,
};

const ease = (x: number) => (x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2);

const va = new Vector3();
const vb = new Vector3();

export function sampleCamera(t: number, pos: Vector3, target: Vector3): number {
  const last = CAMERA_KEYS.length - 1;
  const clamped = Math.min(Math.max(t, 0), last);
  const i = Math.min(Math.floor(clamped), last - 1);
  const f = ease(clamped - i);
  const a = CAMERA_KEYS[i];
  const c = CAMERA_KEYS[i + 1];
  pos.copy(va.fromArray(a.pos)).lerp(vb.fromArray(c.pos), f);
  target.copy(va.fromArray(a.target)).lerp(vb.fromArray(c.target), f);
  return a.shift + (c.shift - a.shift) * f;
}
