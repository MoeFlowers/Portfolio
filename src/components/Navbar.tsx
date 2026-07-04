"use client";
import Link from "next/link";
import { useState, useEffect, useRef } from "react";
import { FiGithub, FiLinkedin, FiMail, FiDownload, FiMenu, FiX, FiChevronDown } from "react-icons/fi";
import { site } from "@/data/site";
import ThemeToggle from "./ThemeToggle";

const sections = [
  { id: "skills", label: "Habilidades" },
  { id: "experience", label: "Experiencia" },
  { id: "projects", label: "Proyectos" },
  { id: "contact", label: "Contacto" },
] as const;

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [activeSection, setActiveSection] = useState<string | null>(null);

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 10);
    handleScroll();
    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // Scroll spy: resalta la sección visible
  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        for (const entry of entries) {
          if (entry.isIntersecting) setActiveSection(entry.target.id);
        }
      },
      { rootMargin: "-40% 0px -55% 0px" }
    );
    for (const { id } of sections) {
      const el = document.getElementById(id);
      if (el) observer.observe(el);
    }
    return () => observer.disconnect();
  }, []);

  return (
    <header
      className={`sticky top-0 z-50 transition-all duration-300 ${
        scrolled
          ? "bg-white/80 dark:bg-[#0a0a0f]/80 backdrop-blur-md border-b border-zinc-200/80 dark:border-white/10"
          : "bg-transparent"
      }`}
    >
      <nav aria-label="Principal" className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          {/* Logo */}
          <Link
            href="/"
            className="font-display text-lg font-bold tracking-tight text-zinc-900 dark:text-white hover:text-accent dark:hover:text-accent-soft transition-colors"
            aria-label="Inicio"
          >
            moisesflores<span className="text-accent">.dev</span>
          </Link>

          {/* Menú desktop */}
          <div className="hidden md:flex items-center gap-1">
            {sections.map(({ id, label }) => (
              <a
                key={id}
                href={`/#${id}`}
                aria-current={activeSection === id ? "true" : undefined}
                className={`relative px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 ${
                  activeSection === id
                    ? "text-accent dark:text-accent-soft"
                    : "text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white"
                }`}
              >
                {label}
                {activeSection === id && (
                  <span
                    aria-hidden="true"
                    className="absolute inset-x-3 -bottom-px h-px bg-gradient-to-r from-accent to-cyan-accent"
                  />
                )}
              </a>
            ))}

            <span aria-hidden="true" className="mx-2 h-5 w-px bg-zinc-300 dark:bg-white/10" />

            <IconLink href={site.github} label="GitHub">
              <FiGithub className="w-5 h-5" />
            </IconLink>
            <IconLink href={site.linkedin} label="LinkedIn">
              <FiLinkedin className="w-5 h-5" />
            </IconLink>
            <IconLink href={`mailto:${site.email}`} label="Enviar email">
              <FiMail className="w-5 h-5" />
            </IconLink>
            <ThemeToggle />
            <CvMenu />
          </div>

          {/* Controles móvil */}
          <div className="md:hidden flex items-center gap-1">
            <ThemeToggle />
            <button
              type="button"
              onClick={() => setIsOpen(!isOpen)}
              className="p-2 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200/70 dark:hover:bg-white/10 transition-colors"
              aria-label={isOpen ? "Cerrar menú" : "Abrir menú"}
              aria-expanded={isOpen}
              aria-controls="mobile-menu"
            >
              {isOpen ? <FiX className="w-6 h-6" /> : <FiMenu className="w-6 h-6" />}
            </button>
          </div>
        </div>

        {/* Menú móvil */}
        <div
          id="mobile-menu"
          className={`md:hidden overflow-hidden transition-all duration-300 ease-in-out ${
            isOpen ? "max-h-[500px] pb-6" : "max-h-0"
          }`}
        >
          <div className="flex flex-col gap-1 pt-2">
            {sections.map(({ id, label }) => (
              <a
                key={id}
                href={`/#${id}`}
                onClick={() => setIsOpen(false)}
                className={`px-4 py-3 rounded-lg text-sm font-medium transition-colors ${
                  activeSection === id
                    ? "bg-accent/10 text-accent dark:text-accent-soft"
                    : "text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200/70 dark:hover:bg-white/10"
                }`}
              >
                {label}
              </a>
            ))}

            <div className="mt-3 pt-4 border-t border-zinc-200 dark:border-white/10 flex items-center gap-2 px-2">
              <IconLink href={site.github} label="GitHub">
                <FiGithub className="w-5 h-5" />
              </IconLink>
              <IconLink href={site.linkedin} label="LinkedIn">
                <FiLinkedin className="w-5 h-5" />
              </IconLink>
              <IconLink href={`mailto:${site.email}`} label="Enviar email">
                <FiMail className="w-5 h-5" />
              </IconLink>
            </div>

            <div className="mt-3 grid grid-cols-2 gap-2 px-2">
              <a
                href="/cv-moises-flores-es.pdf"
                download
                className="flex items-center justify-center gap-2 border border-accent text-accent px-4 py-2.5 rounded-full text-sm font-medium hover:bg-accent hover:text-white transition-colors"
              >
                <FiDownload aria-hidden="true" /> CV Español
              </a>
              <a
                href="/cv-moises-flores-en.pdf"
                download
                className="flex items-center justify-center gap-2 border border-accent text-accent px-4 py-2.5 rounded-full text-sm font-medium hover:bg-accent hover:text-white transition-colors"
              >
                <FiDownload aria-hidden="true" /> CV English
              </a>
            </div>
          </div>
        </div>
      </nav>
    </header>
  );
}

/** Menú de descarga de CV accesible: se abre con click/Enter, se cierra con Escape o click fuera. */
function CvMenu() {
  const [open, setOpen] = useState(false);
  const menuRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!open) return;
    const onKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") setOpen(false);
    };
    const onClickOutside = (e: MouseEvent) => {
      if (menuRef.current && !menuRef.current.contains(e.target as Node)) {
        setOpen(false);
      }
    };
    document.addEventListener("keydown", onKeyDown);
    document.addEventListener("mousedown", onClickOutside);
    return () => {
      document.removeEventListener("keydown", onKeyDown);
      document.removeEventListener("mousedown", onClickOutside);
    };
  }, [open]);

  return (
    <div ref={menuRef} className="relative ml-2">
      <button
        type="button"
        onClick={() => setOpen(!open)}
        aria-expanded={open}
        aria-haspopup="menu"
        className="flex items-center gap-2 border border-accent text-accent px-4 py-2 rounded-full text-sm font-medium hover:bg-accent hover:text-white transition-colors duration-200"
      >
        <FiDownload aria-hidden="true" className="w-4 h-4" />
        Descargar CV
        <FiChevronDown
          aria-hidden="true"
          className={`w-3.5 h-3.5 transition-transform ${open ? "rotate-180" : ""}`}
        />
      </button>

      {open && (
        <div
          role="menu"
          className="absolute right-0 mt-2 w-40 rounded-xl border border-zinc-200 dark:border-white/10 bg-white dark:bg-zinc-900 shadow-lg overflow-hidden"
        >
          <a
            role="menuitem"
            href="/cv-moises-flores-es.pdf"
            download
            onClick={() => setOpen(false)}
            className="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
          >
            Español (ES)
          </a>
          <a
            role="menuitem"
            href="/cv-moises-flores-en.pdf"
            download
            onClick={() => setOpen(false)}
            className="block px-4 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
          >
            English (EN)
          </a>
        </div>
      )}
    </div>
  );
}

function IconLink({
  href,
  label,
  children,
}: {
  href: string;
  label: string;
  children: React.ReactNode;
}) {
  const external = href.startsWith("http");
  return (
    <a
      href={href}
      {...(external && { target: "_blank", rel: "noopener noreferrer" })}
      aria-label={label}
      className="p-2 rounded-full text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft hover:bg-zinc-200/70 dark:hover:bg-white/10 transition-colors duration-200"
    >
      {children}
    </a>
  );
}
