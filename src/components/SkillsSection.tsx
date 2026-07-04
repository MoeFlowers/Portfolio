import { skillCategories } from "@/data/skills";
import { techIcons } from "@/data/techIcons";
import SectionHeading from "./ui/SectionHeading";
import Reveal from "./ui/Reveal";

export default function SkillsSection() {
  return (
    <section id="skills" className="py-24 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <SectionHeading
          eyebrow="Habilidades"
          title={
            <>
              Stack con el que <span className="text-accent">trabajo a diario</span>
            </>
          }
          description="Tecnologías aplicadas en proyectos reales con usuarios reales, no solo en tutoriales."
        />

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {skillCategories.map((category, i) => (
            <Reveal key={category.id} delay={i * 0.08}>
              <div className="h-full p-6 rounded-2xl border border-zinc-200 dark:border-white/10 bg-white/70 dark:bg-white/[0.03] backdrop-blur-sm hover:border-accent/40 dark:hover:border-accent/40 transition-colors duration-300">
                <h3 className="font-display text-lg font-semibold text-zinc-900 dark:text-white">
                  {category.title}
                </h3>
                <p className="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                  {category.description}
                </p>
                <ul className="mt-5 flex flex-wrap gap-2" aria-label={`Tecnologías de ${category.title}`}>
                  {category.skills.map((skill) => {
                    const { icon: Icon } = techIcons[skill];
                    return (
                      <li
                        key={skill}
                        className="inline-flex items-center gap-1.5 text-sm text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-white/10 rounded-full px-3 py-1.5 bg-zinc-50 dark:bg-white/[0.04]"
                      >
                        <Icon aria-hidden="true" className="w-3.5 h-3.5 text-accent dark:text-accent-soft" />
                        {skill}
                      </li>
                    );
                  })}
                </ul>
              </div>
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
