import Image from "next/image";
import type { Shot } from "@/data/projects";

interface ShotFrameProps {
  shot: Shot;
  sizes: string;
  priority?: boolean;
  className?: string;
}

/** Captura real en un marco plano: borde fino y fondo hundido mientras carga. */
export default function ShotFrame({ shot, sizes, priority, className = "" }: ShotFrameProps) {
  const ratio = shot.device === "mobile" ? "aspect-[600/1298]" : "aspect-[16/10]";
  return (
    <div className={`relative overflow-hidden rounded-[6px] border border-line bg-sunk ${ratio} ${className}`}>
      <Image src={shot.src} alt={shot.alt} fill sizes={sizes} priority={priority} className="object-cover object-top" />
    </div>
  );
}
