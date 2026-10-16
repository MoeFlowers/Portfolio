"use client";
import Link from "next/link";
import { useEffect, useRef, useState } from "react";
import { usePathname } from "next/navigation";
import { FiDownload, FiMenu, FiX } from "react-icons/fi";
import { cvFiles, site } from "@/data/site";
import ThemeToggle from "./ThemeToggle";

const sections = [
  { id: "work", label: "Trabajo" },
  { id: "experience", label: "Experiencia" },
  { id: "contact", label: "Contacto" },
] as const;

export default function Navbar() {
  const pathname = usePathname();
  const onHome = pathname === "/";
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [active, setActive] = useState<string | null>(null);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  // Marca la sección que está en pantalla (solo en la home)
  useEffect(() => {
    if (!onHome) return;
    const observer = new IntersectionObserver(
      (entries) => {
        for (const entry of entries) if (entry.isIntersecting) setActive(entry.target.id);
      },
      { rootMargin: "-45% 0px -50% 0px" }
    );
    for (const { id } of sections) {
      const el = document.getElementById(id);
      if (el) observer.observe(el);
    }
    return () => observer.disconnect();
  }, [onHome]);

  // El menú móvil se cierra con Escape
  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpen(false);
    document.addEventListener("keydown", onKey);
    return () => document.removeEventListener("keydown", onKey);
  }, [open]);

  return (
    <header
      className={`sticky top-0 z-50 bg-paper transition-[border-color] duration-200 border-b ${
        scrolled || open ? "border-line" : "border-transparent"
      }`}
    >
      <nav aria-label="Principal" className="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-5 sm:px-8">
        <Link href="/" className="wide inline-flex min-h-11 items-center text-[1.05rem] font-bold text-ink" aria-label="Moises Flores, inicio">
          Moises Flores
        </Link>

        <div className="hidden items-center gap-1 md:flex">
          {sections.map(({ id, label }) => {
            const isActive = onHome && active === id;
            return (
              <a
                key={id}
                href={`/#${id}`}
                aria-current={isActive ? "true" : undefined}
                className={`relative px-3 py-2.5 text-[0.95rem] transition-colors ${
                  isActive ? "text-ink" : "text-ink-2 hover:text-ink"
                }`}
              >
                {label}
                <span
                  aria-hidden="true"
                  className={`absolute inset-x-3 bottom-1.5 h-[2px] bg-accent transition-opacity ${isActive ? "opacity-100" : "opacity-0"}`}
                />
              </a>
            );
          })}
          <span aria-hidden="true" className="mx-2 h-5 w-px bg-line" />
          <CvMenu />
          <ThemeToggle />
        </div>

        <div className="flex items-center gap-1 md:hidden">
          <ThemeToggle />
          <button
            type="button"
            onClick={() => setOpen((v) => !v)}
            aria-label={open ? "Cerrar menú" : "Abrir menú"}
            aria-expanded={open}
            aria-controls="menu-movil"
            className="inline-flex h-11 w-11 items-center justify-center rounded-[6px] text-ink hover:bg-sunk"
          >
            {open ? <FiX className="h-5 w-5" aria-hidden="true" /> : <FiMenu className="h-5 w-5" aria-hidden="true" />}
          </button>
        </div>
      </nav>

      {open && (
        <div id="menu-movil" className="border-t border-line px-5 pb-6 pt-2 md:hidden">
          <ul className="flex flex-col">
            {sections.map(({ id, label }) => (
              <li key={id}>
                <a
                  href={`/#${id}`}
                  onClick={() => setOpen(false)}
                  className="block border-b border-line py-3.5 text-lg text-ink"
                >
                  {label}
                </a>
              </li>
            ))}
          </ul>
          <p className="mt-5 text-sm text-ink-3">Currículum</p>
          <div className="mt-2 grid grid-cols-2 gap-2">
            {cvFiles.map((cv) => (
              <a
                key={cv.href}
                href={cv.href}
                download
                className="inline-flex min-h-11 items-center justify-center gap-2 rounded-[6px] border border-control px-4 text-[0.95rem] text-ink"
              >
                <FiDownload aria-hidden="true" className="h-4 w-4" /> {cv.label}
              </a>
            ))}
          </div>
          <a href={`mailto:${site.email}`} className="link-underline mt-5 inline-block text-ink-2">
            {site.email}
          </a>
        </div>
      )}
    </header>
  );
}

/** Descarga del CV: se abre con clic o Enter y se cierra con Escape o clic fuera. */
function CvMenu() {
  const [open, setOpen] = useState(false);
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpen(false);
    const onClick = (e: MouseEvent) => {
      if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("keydown", onKey);
    document.addEventListener("mousedown", onClick);
    return () => {
      document.removeEventListener("keydown", onKey);
      document.removeEventListener("mousedown", onClick);
    };
  }, [open]);

  return (
    <div ref={ref} className="relative">
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        aria-expanded={open}
        aria-haspopup="menu"
        className="inline-flex h-11 items-center gap-2 rounded-[6px] px-3 text-[0.95rem] text-ink-2 transition-colors hover:bg-sunk hover:text-ink"
      >
        <FiDownload aria-hidden="true" className="h-4 w-4" />
        Currículum
      </button>
      {open && (
        <div role="menu" className="absolute right-0 mt-1 w-44 overflow-hidden rounded-[6px] border border-line bg-paper py-1">
          {cvFiles.map((cv) => (
            <a
              key={cv.href}
              role="menuitem"
              href={cv.href}
              download
              onClick={() => setOpen(false)}
              className="block px-4 py-2.5 text-[0.95rem] text-ink hover:bg-sunk"
            >
              {cv.label} (PDF)
            </a>
          ))}
        </div>
      )}
    </div>
  );
}
