import Link from "next/link";
import { FiArrowUpRight } from "react-icons/fi";
import type { Project } from "@/data/projects";

/** Año · categoría · estado, en cifras tabulares. */
export function ProjectMeta({ project, className = "" }: { project: Project; className?: string }) {
  return (
    <p className={`tabular text-[0.92rem] text-ink-3 ${className}`}>
      {project.year} · {project.category} · <span className="text-ink-2">{project.status}</span>
    </p>
  );
}

/**
 * "rows": cifra y descripción en una fila, para columnas estrechas.
 * "columns": una columna por resultado, para el ancho completo.
 * En ambos el dt (qué mide) va antes en el DOM y la cifra se muestra primero con order.
 */
export function ResultsList({
  project,
  layout = "columns",
  className = "",
}: {
  project: Project;
  layout?: "rows" | "columns";
  className?: string;
}) {
  if (layout === "rows") {
    return (
      <dl className={`border-t border-line ${className}`}>
        {project.results.map((r) => (
          <div key={r.label} className="grid grid-cols-[minmax(5.5rem,auto)_1fr] items-baseline gap-x-5 border-b border-line py-3">
            <dt className="order-2 text-[0.95rem] leading-snug text-ink-2">{r.label}</dt>
            <dd className="wide tabular order-1 text-[1.25rem] font-bold leading-tight text-ink">{r.value}</dd>
          </div>
        ))}
      </dl>
    );
  }
  return (
    <dl className={`grid gap-x-8 gap-y-6 sm:grid-cols-3 ${className}`}>
      {project.results.map((r) => (
        <div key={r.label} className="flex flex-col">
          <dt className="order-2 mt-1 text-[0.95rem] leading-snug text-ink-3">{r.label}</dt>
          <dd className="wide tabular order-1 text-[clamp(1.6rem,3vw,2.1rem)] font-bold leading-tight text-ink">{r.value}</dd>
        </div>
      ))}
    </dl>
  );
}

export function Stack({ project, className = "" }: { project: Project; className?: string }) {
  return (
    <p className={`text-[0.92rem] text-ink-3 ${className}`}>
      <span className="sr-only">Tecnologías: </span>
      {project.technologies.join(" · ")}
    </p>
  );
}

/** Caso de estudio y, si existen, el enlace en vivo y el código. */
export function ProjectLinks({ project, className = "" }: { project: Project; className?: string }) {
  return (
    <div className={`flex flex-wrap items-center gap-x-6 gap-y-3 text-[0.98rem] ${className}`}>
      <Link href={`/projects/${project.slug}`} className="link-underline font-medium text-ink">
        Ver el caso
      </Link>
      {project.liveLink && (
        <a href={project.liveLink} target="_blank" rel="noopener noreferrer" className="link-underline inline-flex items-center gap-1 text-ink-2">
          {project.liveLabel ?? "Ver en vivo"}
          <FiArrowUpRight aria-hidden="true" className="h-4 w-4" />
          <span className="sr-only">(se abre en otra pestaña)</span>
        </a>
      )}
      {project.githubLink && (
        <a href={project.githubLink} target="_blank" rel="noopener noreferrer" className="link-underline inline-flex items-center gap-1 text-ink-2">
          Código
          <FiArrowUpRight aria-hidden="true" className="h-4 w-4" />
          <span className="sr-only">(se abre en otra pestaña)</span>
        </a>
      )}
    </div>
  );
}
