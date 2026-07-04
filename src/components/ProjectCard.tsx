import { Project } from "@/data/projects";
import Link from "next/link";
import Image from "next/image";
import { FiGithub, FiExternalLink, FiArrowRight, FiClock } from "react-icons/fi";
import TechChip from "./ui/TechChip";

export default function ProjectCard({ project }: { project: Project }) {
  const kpi = project.results[0];

  return (
    <article className="group relative h-full flex flex-col rounded-2xl overflow-hidden border border-zinc-200 dark:border-white/10 bg-white/70 dark:bg-white/[0.03] backdrop-blur-sm transition-all duration-300 hover:border-accent/40 hover:shadow-xl hover:shadow-accent/5 hover:-translate-y-1">
      {/* Imagen */}
      <div className="relative h-48 w-full overflow-hidden">
        <Image
          src={project.image}
          alt={`Captura del proyecto ${project.title}`}
          fill
          className="object-cover transition-transform duration-500 group-hover:scale-105"
          sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
        />
        <div aria-hidden="true" className="absolute inset-0 bg-gradient-to-t from-zinc-950/40 to-transparent" />
        {/* KPI principal */}
        {kpi && (
          <p className="absolute bottom-3 left-3 font-mono text-xs text-white bg-zinc-950/70 backdrop-blur-sm rounded-full px-3 py-1.5">
            <span className="font-semibold">{kpi.value}</span> {kpi.label}
          </p>
        )}
      </div>

      {/* Contenido */}
      <div className="p-6 flex flex-col flex-grow">
        <div className="flex items-center justify-between gap-2 mb-3">
          <span className="inline-block px-3 py-1 text-xs font-medium rounded-full bg-accent/10 text-accent dark:text-accent-soft">
            {project.category}
          </span>
          <span className="inline-flex items-center gap-1 font-mono text-xs text-zinc-500 dark:text-zinc-400">
            <FiClock aria-hidden="true" className="w-3 h-3" />
            {project.duration}
          </span>
        </div>

        <h3 className="font-display text-xl font-bold text-zinc-900 dark:text-white mb-2 group-hover:text-accent dark:group-hover:text-accent-soft transition-colors">
          <Link href={`/projects/${project.slug}`} className="focus-visible:outline-none">
            {/* El enlace cubre toda la tarjeta */}
            <span className="absolute inset-0" aria-hidden="true" />
            {project.title}
          </Link>
        </h3>

        <p className="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4 flex-grow">
          {project.summary}
        </p>

        <ul className="flex flex-wrap gap-2" aria-label="Tecnologías">
          {project.technologies.map((tech) => (
            <li key={tech}>
              <TechChip tech={tech} />
            </li>
          ))}
        </ul>
      </div>

      {/* Footer */}
      <div className="px-6 pb-5 pt-4 flex justify-between items-center border-t border-zinc-100 dark:border-white/5">
        <div className="flex gap-2 relative z-10">
          {project.githubLink && (
            <a
              href={project.githubLink}
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 rounded-full text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
              aria-label={`Código de ${project.title} en GitHub`}
            >
              <FiGithub className="w-4.5 h-4.5" />
            </a>
          )}
          {project.liveLink && (
            <a
              href={project.liveLink}
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 rounded-full text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
              aria-label={`Demo en vivo de ${project.title}`}
            >
              <FiExternalLink className="w-4.5 h-4.5" />
            </a>
          )}
        </div>

        <span className="flex items-center text-sm font-medium text-accent dark:text-accent-soft">
          Caso de estudio
          <FiArrowRight aria-hidden="true" className="ml-1 transition-transform group-hover:translate-x-1" />
        </span>
      </div>
    </article>
  );
}
