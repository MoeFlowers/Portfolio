"use client";

import dynamic from "next/dynamic";
import Image from "next/image";
import Link from "next/link";
import { useState, useSyncExternalStore } from "react";
import type { StudioVariant } from "./StudioPage";

const StudioPage = dynamic(() => import("./StudioPage"), { ssr: false });
const query = "(prefers-reduced-motion: reduce)";

function subscribe(onChange: () => void) {
  const preference = window.matchMedia(query);
  preference.addEventListener("change", onChange);
  return () => preference.removeEventListener("change", onChange);
}

export default function StudioExperience({ variant }: { variant?: StudioVariant }) {
  const reduced = useSyncExternalStore(subscribe, () => window.matchMedia(query).matches, () => null);
  const [open, setOpen] = useState(false);

  if (open || reduced === false) return <StudioPage variant={variant} />;

  return (
    <main className="site-wrap site-hero route-hero">
      <div>
        <h1>Estudio 3D</h1>
        <p className="lede">Un espacio interactivo modelado en Blender y diseñado para recorrerlo desde el navegador.</p>
        <div className="hero-actions">
          {reduced && <button type="button" className="site-button" onClick={() => setOpen(true)}>Abrir versión animada</button>}
          <Link href="/#projects" className="text-action">Ver proyectos</Link>
        </div>
        <div className="case-image mt-6 max-w-3xl">
          <Image src="/3d/v2/f_000.webp" alt="Escritorio del estudio, renderizado en Blender" fill sizes="(min-width: 800px) 768px, 100vw" />
        </div>
      </div>
    </main>
  );
}
