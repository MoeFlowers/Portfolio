import Link from "next/link";
import ThemeToggle from "./ThemeToggle";
import type { Locale } from "@/data/capabilities";

export default function SiteHeader({ locale = "es", contactHref = "/#contact" }: { locale?: Locale; contactHref?: string }) {
  const en = locale === "en";
  return (
    <>
      <a className="skip-link" href="#main-content">{en ? "Skip to content" : "Saltar al contenido"}</a>
      <header className="site-header">
        <div className="site-wrap site-nav">
          <Link href="/" className="site-signature">Moisés Flores<span>.</span></Link>
          <nav aria-label={en ? "Main navigation" : "Navegación principal"}>
            <Link href={en ? "/career" : "/perfil"}>{en ? "Career" : "Perfil"}</Link>
            <Link href="/servicios" lang="es">Servicios</Link>
            <Link href="/#projects">{en ? "Projects" : "Proyectos"}</Link>
            <Link href={contactHref}>{en ? "Contact" : "Contacto"}</Link>
          </nav>
          <ThemeToggle locale={locale} />
        </div>
      </header>
    </>
  );
}
