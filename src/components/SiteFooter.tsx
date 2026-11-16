import Link from "next/link";
import { site } from "@/data/site";
import type { Locale } from "@/data/capabilities";

export default function SiteFooter({ locale = "es" }: { locale?: Locale }) {
  return (
    <footer className="site-footer">
      <div className="site-wrap">
        <span>Moisés Flores · Venezuela (UTC−4)</span>
        <nav aria-label={locale === "en" ? "External links" : "Enlaces externos"}>
          <a href={site.github}>GitHub</a>
          <a href={site.linkedin}>LinkedIn</a>
          <Link href="/3d" lang="es">Estudio 3D</Link>
        </nav>
      </div>
    </footer>
  );
}
