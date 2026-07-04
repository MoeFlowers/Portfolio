"use client";
import Image from "next/image";
import { motion, useReducedMotion } from "framer-motion";
import { site, heroMetrics } from "@/data/site";

export default function HeroSection() {
  const reduceMotion = useReducedMotion();

  const fadeUp = (delay: number) =>
    reduceMotion
      ? {}
      : {
          initial: { opacity: 0, y: 24 },
          animate: { opacity: 1, y: 0 },
          transition: { duration: 0.55, delay, ease: "easeOut" as const },
        };

  return (
    <section className="relative overflow-hidden">
      {/* Fondo: gradiente radial discreto */}
      <div
        aria-hidden="true"
        className="absolute inset-0 -z-10 bg-[radial-gradient(60%_50%_at_50%_0%,rgba(99,102,241,0.10),transparent)] dark:bg-[radial-gradient(60%_50%_at_50%_0%,rgba(99,102,241,0.15),transparent)]"
      />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 md:pt-28 md:pb-24">
        <div className="flex flex-col-reverse md:flex-row items-center justify-between gap-12">
          {/* Texto */}
          <div className="max-w-2xl text-center md:text-left">
            <motion.p
              {...fadeUp(0)}
              className="inline-flex items-center gap-2 font-mono text-xs text-zinc-600 dark:text-zinc-400 border border-zinc-300 dark:border-white/15 rounded-full px-3 py-1.5 mb-6"
            >
              <span aria-hidden="true" className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
              Disponible para nuevos proyectos
            </motion.p>

            <motion.h1
              {...fadeUp(0.1)}
              className="font-display text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-zinc-900 dark:text-white text-balance"
            >
              Construyo productos web que{" "}
              <span className="bg-gradient-to-r from-accent to-cyan-accent bg-clip-text text-transparent">
                ahorran horas
              </span>{" "}
              y sirven a miles de usuarios.
            </motion.h1>

            <motion.p
              {...fadeUp(0.2)}
              className="mt-6 text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed"
            >
              Soy <strong className="text-zinc-900 dark:text-white font-semibold">{site.name}</strong>,{" "}
              {site.role.toLowerCase()} especializado en{" "}
              <strong className="text-zinc-900 dark:text-white font-semibold">React, Next.js y Python</strong>.
              Del backend a la interfaz, con foco en resultados medibles.
            </motion.p>

            <motion.div
              {...fadeUp(0.3)}
              className="mt-8 flex flex-col sm:flex-row gap-3 justify-center md:justify-start"
            >
              <a
                href="#projects"
                className="px-7 py-3 text-sm font-semibold rounded-full bg-accent text-white hover:bg-accent-hover transition-colors duration-200 shadow-lg shadow-accent/25"
              >
                Ver proyectos
              </a>
              <a
                href="#contact"
                className="px-7 py-3 text-sm font-semibold rounded-full border border-zinc-300 dark:border-white/15 text-zinc-700 dark:text-zinc-300 hover:border-accent hover:text-accent dark:hover:text-accent-soft transition-colors duration-200"
              >
                Contactarme
              </a>
            </motion.div>
          </div>

          {/* Foto */}
          <motion.div
            {...(reduceMotion
              ? {}
              : {
                  initial: { opacity: 0, scale: 0.92 },
                  animate: { opacity: 1, scale: 1 },
                  transition: { duration: 0.6, ease: "easeOut" as const },
                })}
            className="relative shrink-0"
          >
            <div
              aria-hidden="true"
              className="absolute -inset-2 rounded-full bg-gradient-to-tr from-accent/60 to-cyan-accent/60 blur-xl opacity-40"
            />
            <div className="relative w-44 h-44 md:w-56 md:h-56 rounded-full overflow-hidden ring-2 ring-zinc-200 dark:ring-white/15">
              <Image
                src="/images/profilephoto.png"
                alt="Foto de Moises Flores"
                fill
                priority
                sizes="(max-width: 768px) 176px, 224px"
                className="object-cover"
              />
            </div>
          </motion.div>
        </div>

        {/* Métricas de impacto */}
        <motion.dl
          {...fadeUp(0.4)}
          className="mt-16 md:mt-20 grid grid-cols-2 md:grid-cols-4 gap-px rounded-2xl overflow-hidden border border-zinc-200 dark:border-white/10 bg-zinc-200 dark:bg-white/10"
        >
          {heroMetrics.map(({ value, label }) => (
            <div
              key={label}
              className="flex flex-col bg-white/90 dark:bg-[#0d0d14]/90 backdrop-blur-sm px-6 py-5 text-center md:text-left"
            >
              <dt className="order-2 text-xs text-zinc-500 dark:text-zinc-400 mt-1">{label}</dt>
              <dd className="order-1 font-mono text-2xl md:text-3xl font-semibold text-zinc-900 dark:text-white">
                {value}
              </dd>
            </div>
          ))}
        </motion.dl>
      </div>
    </section>
  );
}
