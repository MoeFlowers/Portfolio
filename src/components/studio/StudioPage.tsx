"use client";

import dynamic from "next/dynamic";
import Link from "next/link";
import { useCallback, useEffect, useRef, useState } from "react";
import { motion } from "framer-motion";
import Lenis from "lenis";
import { useProgress } from "@react-three/drei";
import { rig } from "@/components/three/cameraPath";
import { heroMetrics, site } from "@/data/site";
import { skillCategories } from "@/data/skills";
import { experience } from "@/data/experience";
import { projects } from "@/data/projects";

const StudioCanvas = dynamic(() => import("@/components/three/StudioCanvas"), { ssr: false });

const featured = projects.filter((p) => p.featured);

const NAV = [
  { href: "#impacto", label: "Impacto" },
  { href: "#stack", label: "Stack" },
  { href: "#proyectos", label: "Proyectos" },
  { href: "#experiencia", label: "Experiencia" },
  { href: "#contacto", label: "Contacto" },
];

const card =
  "rounded-2xl border border-white/10 bg-[#0a0c18]/90 md:bg-[#0a0c18]/75 p-6 shadow-2xl shadow-black/40 backdrop-blur-md md:p-8";

function Reveal({ children, className = "" }: { children: React.ReactNode; className?: string }) {
  return (
    <motion.div
      className={className}
      initial={{ opacity: 0, y: 40 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ amount: 0.35 }}
      transition={{ duration: 0.8, ease: [0.22, 1, 0.36, 1] }}
    >
      {children}
    </motion.div>
  );
}

function Eyebrow({ children }: { children: React.ReactNode }) {
  return (
    <p className="mb-4 font-mono text-xs tracking-[0.25em] text-indigo-300 uppercase">{children}</p>
  );
}

function Loader({ done }: { done: boolean }) {
  const { progress } = useProgress();
  return (
    <div
      className={`fixed inset-0 z-50 flex flex-col items-center justify-center bg-[#05060c] transition-opacity duration-700 ${
        done ? "pointer-events-none opacity-0" : "opacity-100"
      }`}
    >
      <p className="font-mono text-sm tracking-[0.2em] text-red-500">&lt;MoeFlowers.dev/&gt;</p>
      <div className="mt-6 h-px w-48 overflow-hidden bg-white/10">
        <div className="h-full bg-gradient-to-r from-indigo-500 to-cyan-400 transition-all" style={{ width: `${done ? 100 : progress}%` }} />
      </div>
      <p className="mt-3 font-mono text-xs text-zinc-500">Cargando estudio 3D · {done ? 100 : Math.round(progress)}%</p>
    </div>
  );
}

export default function StudioPage() {
  const barRef = useRef<HTMLDivElement>(null);
  const [scrolled, setScrolled] = useState(false);
  const [ready, setReady] = useState(false);
  const onReady = useCallback(() => setReady(true), []);

  // Scroll suave
  useEffect(() => {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const lenis = new Lenis({ autoRaf: true, lerp: 0.09, anchors: true });
    return () => lenis.destroy();
  }, []);

  // Scroll → posición de cámara (rig.t) y proyecto en el monitor
  useEffect(() => {
    let raf = 0;
    const loop = () => {
      const sections = Array.from(document.querySelectorAll<HTMLElement>("[data-cam]"));
      const y = window.scrollY;
      const vh = window.innerHeight;
      let t = 0;
      for (let i = 0; i < sections.length; i++) {
        const top = sections[i].offsetTop;
        const holdEnd = top + Math.max(0, sections[i].offsetHeight - vh);
        const next = sections[i + 1]?.offsetTop ?? Infinity;
        if (y < top) break;
        if (y <= holdEnd) {
          t = i;
          break;
        }
        t = i + Math.min(1, (y - holdEnd) / Math.max(1, next - holdEnd));
      }
      rig.t = t;

      const proj = document.getElementById("proyectos");
      if (proj) {
        const p = (y - proj.offsetTop) / Math.max(1, proj.offsetHeight - vh);
        rig.project = Math.min(featured.length - 1, Math.max(0, Math.floor(p * featured.length)));
      }

      const max = document.documentElement.scrollHeight - vh;
      if (barRef.current) barRef.current.style.transform = `scaleX(${max > 0 ? y / max : 0})`;
      raf = requestAnimationFrame(loop);
    };
    raf = requestAnimationFrame(loop);
    const onScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener("scroll", onScroll);
    };
  }, []);

  return (
    <div className="relative bg-[radial-gradient(ellipse_at_60%_40%,#121733_0%,#05060c_70%)] text-zinc-100">
      <Loader done={ready} />
      <div className="pointer-events-none fixed inset-0 z-0">
        <StudioCanvas projectImages={featured.map((p) => p.image)} onReady={onReady} />
      </div>

      {/* Barra de progreso + navegación */}
      <div
        ref={barRef}
        className="fixed top-0 left-0 z-40 h-0.5 w-full origin-left scale-x-0 bg-gradient-to-r from-indigo-500 to-cyan-400"
      />
      <header
        className={`fixed inset-x-0 top-0 z-30 transition-colors duration-500 ${
          scrolled ? "bg-[#05060c]/60 backdrop-blur-md" : ""
        }`}
      >
        <nav className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
          <a href="#inicio" className="font-mono text-sm font-semibold tracking-wide text-red-500 drop-shadow-[0_0_8px_rgba(255,30,30,0.55)]">
            &lt;MoeFlowers.dev/&gt;
          </a>
          <ul className="hidden gap-7 text-sm text-zinc-400 md:flex">
            {NAV.map((n) => (
              <li key={n.href}>
                <a href={n.href} className="transition-colors hover:text-white">
                  {n.label}
                </a>
              </li>
            ))}
          </ul>
          <Link
            href="/"
            className="rounded-full border border-white/15 px-4 py-1.5 text-xs text-zinc-300 transition hover:border-indigo-400 hover:text-white"
          >
            Versión clásica
          </Link>
        </nav>
      </header>

      <main className="relative z-10">
        {/* 0 · Hero */}
        <section id="inicio" data-cam className="relative flex min-h-screen items-end px-6 pb-24 md:items-center md:pb-0">
          <div className="pointer-events-none absolute inset-y-0 left-0 hidden w-2/3 bg-gradient-to-r from-[#05060c]/90 via-[#05060c]/40 to-transparent md:block" />
          <div className="relative mx-auto w-full max-w-7xl">
            <motion.div
              className="max-w-xl"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 1, delay: 0.6, ease: [0.22, 1, 0.36, 1] }}
            >
              <Eyebrow>Ingeniero en Sistemas · Full-Stack</Eyebrow>
              <h1 className="font-display text-[#F3EBDD] text-5xl leading-[0.95] font-bold tracking-tight md:text-7xl lg:text-8xl">
                Moises
                <br />
                <span className="bg-gradient-to-r from-indigo-300 via-indigo-400 to-cyan-300 bg-clip-text text-transparent">
                  Flores
                </span>
              </h1>
              <p className="mt-6 text-lg text-zinc-300 md:text-xl">Construyo software que ahorra horas de trabajo.</p>
              <p className="mt-3 max-w-md text-sm leading-relaxed text-zinc-400">{site.description}</p>
              <div className="mt-8 flex flex-wrap gap-3">
                <a
                  href="#proyectos"
                  className="rounded-full bg-indigo-500 px-6 py-3 text-sm font-medium text-white shadow-lg shadow-indigo-500/30 transition hover:bg-indigo-400"
                >
                  Ver proyectos
                </a>
                <a
                  href="#contacto"
                  className="rounded-full border border-white/15 px-6 py-3 text-sm font-medium text-zinc-200 transition hover:border-white/40"
                >
                  Hablemos
                </a>
              </div>
            </motion.div>
          </div>
          <div className="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-zinc-500 md:flex">
            <span className="font-mono text-[10px] tracking-[0.3em]">SCROLL</span>
            <span className="h-10 w-px animate-pulse bg-gradient-to-b from-indigo-400 to-transparent" />
          </div>
        </section>

        {/* 1 · Impacto */}
        <section id="impacto" data-cam className="flex min-h-[130vh] items-center px-6">
          <div className="mx-auto w-full max-w-7xl">
            <Reveal className={`${card} max-w-lg`}>
              <Eyebrow>01 · Impacto</Eyebrow>
              <h2 className="font-display text-[#F3EBDD] text-3xl font-semibold md:text-4xl">Resultados medibles, no solo código.</h2>
              <div className="mt-8 grid grid-cols-2 gap-6">
                {heroMetrics.map((m) => (
                  <div key={m.label}>
                    <p className="bg-gradient-to-r from-indigo-200 to-cyan-300 bg-clip-text font-mono text-3xl font-semibold text-transparent md:text-4xl">
                      {m.value}
                    </p>
                    <p className="mt-1 text-sm text-zinc-400">{m.label}</p>
                  </div>
                ))}
              </div>
            </Reveal>
          </div>
        </section>

        {/* 2 · Stack */}
        <section id="stack" data-cam className="flex min-h-[130vh] items-center px-6">
          <div className="mx-auto w-full max-w-7xl">
            <Reveal className={`${card} max-w-lg`}>
              <Eyebrow>02 · Stack</Eyebrow>
              <h2 className="font-display text-[#F3EBDD] text-3xl font-semibold md:text-4xl">Las herramientas de cada día.</h2>
              <div className="mt-6 space-y-5">
                {skillCategories
                  .filter((c) => c.id !== "ai")
                  .map((c) => (
                    <div key={c.id}>
                      <p className="mb-2 font-mono text-xs tracking-wider text-zinc-500 uppercase">{c.title}</p>
                      <div className="flex flex-wrap gap-1.5">
                        {c.skills.map((s) => (
                          <span
                            key={s}
                            className="rounded-md border border-indigo-400/20 bg-indigo-500/10 px-2.5 py-1 text-xs text-indigo-100"
                          >
                            {s}
                          </span>
                        ))}
                      </div>
                    </div>
                  ))}
              </div>
            </Reveal>
          </div>
        </section>

        {/* 3 · Proyectos: la cámara se queda frente al monitor mientras pasan los casos */}
        <section id="proyectos" data-cam className="px-6" style={{ minHeight: `${featured.length * 100 + 30}vh` }}>
          <div className="mx-auto w-full max-w-7xl">
            {featured.map((p, i) => (
              <div key={p.slug} className="flex min-h-screen items-center">
                <Reveal className={`${card} max-w-md`}>
                  <Eyebrow>
                    03 · Proyecto {String(i + 1).padStart(2, "0")}/{String(featured.length).padStart(2, "0")}
                  </Eyebrow>
                  <p className="mb-2 font-mono text-xs text-cyan-300">{p.category}</p>
                  <h2 className="font-display text-[#F3EBDD] text-2xl font-semibold md:text-3xl">{p.title}</h2>
                  <p className="mt-3 text-sm leading-relaxed text-zinc-400">{p.summary}</p>
                  <div className="mt-5 flex gap-6">
                    {p.results.slice(0, 2).map((r) => (
                      <div key={r.label} className="min-w-0">
                        <p className="font-mono text-xl font-semibold text-indigo-200">{r.value}</p>
                        <p className="mt-0.5 text-xs text-zinc-500">{r.label}</p>
                      </div>
                    ))}
                  </div>
                  <div className="mt-5 flex flex-wrap gap-1.5">
                    {p.technologies.map((t) => (
                      <span key={t} className="rounded-md bg-white/5 px-2 py-0.5 font-mono text-[11px] text-zinc-300">
                        {t}
                      </span>
                    ))}
                  </div>
                  <Link
                    href={`/projects/${p.slug}`}
                    className="mt-6 inline-flex items-center gap-2 text-sm font-medium text-indigo-300 transition hover:text-white"
                  >
                    Ver caso de estudio <span aria-hidden>→</span>
                  </Link>
                </Reveal>
              </div>
            ))}
          </div>
        </section>

        {/* 4 · Experiencia */}
        <section id="experiencia" data-cam className="flex min-h-[130vh] items-center px-6">
          <div className="mx-auto flex w-full max-w-7xl justify-end">
            <Reveal className={`${card} max-w-lg`}>
              <Eyebrow>04 · Trayectoria</Eyebrow>
              <h2 className="font-display text-[#F3EBDD] text-3xl font-semibold md:text-4xl">Experiencia y formación.</h2>
              <ol className="mt-6 space-y-6 border-l border-white/10 pl-6">
                {experience.map((e) => (
                  <li key={e.title} className="relative">
                    <span
                      className={`absolute top-1.5 -left-[29px] h-2.5 w-2.5 rounded-full ${
                        e.type === "educacion" ? "bg-cyan-400" : "bg-indigo-400"
                      } shadow-[0_0_12px] shadow-indigo-400/60`}
                    />
                    <p className="font-mono text-xs text-zinc-500">{e.period}</p>
                    <p className="mt-1 font-medium text-zinc-100">{e.title}</p>
                    <p className="text-sm text-zinc-400">{e.org}</p>
                    {e.highlight && <p className="mt-1 font-mono text-xs text-indigo-300">{e.highlight}</p>}
                  </li>
                ))}
              </ol>
            </Reveal>
          </div>
        </section>

        {/* 5 · Contacto */}
        <section id="contacto" data-cam className="flex min-h-screen items-center px-6">
          <div className="mx-auto flex w-full max-w-7xl justify-end">
            <Reveal className={`${card} max-w-lg`}>
              <Eyebrow>05 · Contacto</Eyebrow>
              <h2 className="font-display text-[#F3EBDD] text-4xl font-semibold md:text-5xl">¿Construimos algo juntos?</h2>
              <p className="mt-4 text-zinc-400">
                Disponible para proyectos freelance y oportunidades remotas. Respondo en menos de 24 horas.
              </p>
              <a
                href={`mailto:${site.email}`}
                className="mt-8 block rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-500 px-6 py-4 text-center font-medium text-white shadow-lg shadow-indigo-500/30 transition hover:brightness-110"
              >
                {site.email}
              </a>
              <div className="mt-4 grid grid-cols-2 gap-3 text-sm">
                <a href={site.github} target="_blank" rel="noreferrer" className="rounded-xl border border-white/10 px-4 py-3 text-center text-zinc-300 transition hover:border-indigo-400 hover:text-white">
                  GitHub
                </a>
                <a href={site.linkedin} target="_blank" rel="noreferrer" className="rounded-xl border border-white/10 px-4 py-3 text-center text-zinc-300 transition hover:border-indigo-400 hover:text-white">
                  LinkedIn
                </a>
              </div>
              <p className="mt-8 font-mono text-[11px] text-zinc-600">
                Escena modelada e iluminada en Blender · Three.js · Next.js
              </p>
            </Reveal>
          </div>
        </section>
      </main>
    </div>
  );
}
