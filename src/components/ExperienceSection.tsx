import { experience } from "@/data/experience";
import { FiBriefcase, FiBookOpen } from "react-icons/fi";
import SectionHeading from "./ui/SectionHeading";
import Reveal from "./ui/Reveal";

export default function ExperienceSection() {
  return (
    <section id="experience" className="py-24 px-4 sm:px-6 lg:px-8 bg-zinc-50 dark:bg-white/[0.02]">
      <div className="max-w-7xl mx-auto">
        <SectionHeading
          eyebrow="Trayectoria"
          title={
            <>
              Experiencia con <span className="text-accent">resultados medibles</span>
            </>
          }
          description="Cada proyecto entregado con métricas de impacto verificables."
        />

        <ol className="relative max-w-3xl mx-auto border-s border-zinc-200 dark:border-white/10">
          {experience.map((item, i) => (
            <li key={`${item.period}-${item.title}`} className="ms-8 pb-12 last:pb-0 relative">
              <Reveal delay={i * 0.08}>
                {/* Marcador */}
                <span
                  aria-hidden="true"
                  className="absolute -start-[45px] top-0.5 flex items-center justify-center w-9 h-9 rounded-full border border-zinc-200 dark:border-white/10 bg-white dark:bg-[#101018] text-accent dark:text-accent-soft"
                >
                  {item.type === "trabajo" ? (
                    <FiBriefcase className="w-4 h-4" />
                  ) : (
                    <FiBookOpen className="w-4 h-4" />
                  )}
                </span>

                <p className="font-mono text-xs uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
                  {item.period}
                </p>
                <h3 className="mt-1.5 font-display text-xl font-semibold text-zinc-900 dark:text-white">
                  {item.title}
                </h3>
                <p className="text-sm text-accent dark:text-accent-soft font-medium">{item.org}</p>
                <p className="mt-3 text-zinc-600 dark:text-zinc-400 leading-relaxed">
                  {item.description}
                </p>
                {item.highlight && (
                  <p className="mt-3 inline-block font-mono text-xs text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-white/10 rounded-full px-3 py-1.5 bg-white dark:bg-white/[0.04]">
                    {item.highlight}
                  </p>
                )}
              </Reveal>
            </li>
          ))}
        </ol>
      </div>
    </section>
  );
}
