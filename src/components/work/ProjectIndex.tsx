"use client";
import Link from "next/link";
import Image from "next/image";
import { useMemo, useState } from "react";
import { kindLabels, projects, type Project, type ProjectKind } from "@/data/projects";

type Filter = "todos" | ProjectKind;

const filters: { id: Filter; label: string }[] = [
  { id: "todos", label: "Todos" },
  { id: "sistemas", label: kindLabels.sistemas },
  { id: "web3d", label: kindLabels.web3d },
  { id: "ia", label: kindLabels.ia },
];

// Más reciente primero; dentro del mismo año, en el orden de data/projects.ts
const ordered = [...projects].sort((a, b) => Number(b.year) - Number(a.year));

export default function ProjectIndex() {
  const [filter, setFilter] = useState<Filter>("todos");
  const list = useMemo(() => (filter === "todos" ? ordered : ordered.filter((p) => p.kind === filter)), [filter]);
  const [activeSlug, setActiveSlug] = useState(ordered[0].slug);
  const active = list.find((p) => p.slug === activeSlug) ?? list[0];

  const count = (f: Filter) => (f === "todos" ? projects.length : projects.filter((p) => p.kind === f).length);

  return (
    <div className="mt-8">
      <div role="group" aria-label="Filtrar proyectos por tipo" className="flex flex-wrap gap-x-1 gap-y-2">
        {filters.map((f) => {
          const pressed = filter === f.id;
          return (
            <button
              key={f.id}
              type="button"
              aria-pressed={pressed}
              onClick={() => {
                setFilter(f.id);
                const first = f.id === "todos" ? ordered[0] : ordered.find((p) => p.kind === f.id);
                if (first) setActiveSlug(first.slug);
              }}
              className={`relative min-h-11 px-3 text-[0.95rem] transition-colors ${
                pressed ? "text-ink" : "text-ink-3 hover:text-ink"
              }`}
            >
              {f.label} <span className="tabular text-ink-3">{count(f.id)}</span>
              <span
                aria-hidden="true"
                className={`absolute inset-x-3 bottom-1 h-[2px] bg-accent transition-opacity ${pressed ? "opacity-100" : "opacity-0"}`}
              />
            </button>
          );
        })}
      </div>

      <div className="mt-6 grid gap-10 lg:grid-cols-12">
        {list.length === 0 ? (
          <p className="text-ink-2 lg:col-span-7">Todavía no hay proyectos en esta categoría. Prueba con «Todos».</p>
        ) : (
          <ul className="border-t border-line lg:col-span-7">
            {list.map((p) => (
              <Row key={p.slug} project={p} active={active?.slug === p.slug} onActivate={() => setActiveSlug(p.slug)} />
            ))}
          </ul>
        )}

        {/* Vista previa: refleja la fila bajo el cursor o con el foco. Decorativa para lectores de pantalla, que ya leen la fila. */}
        <div aria-hidden="true" className="hidden lg:col-span-5 lg:block">
          <div className="sticky top-24">{active && <Preview project={active} />}</div>
        </div>
      </div>
    </div>
  );
}

function Row({ project, active, onActivate }: { project: Project; active: boolean; onActivate: () => void }) {
  return (
    <li className="border-b border-line">
      <Link
        href={`/projects/${project.slug}`}
        onMouseEnter={onActivate}
        onFocus={onActivate}
        className="grid grid-cols-[3.5rem_1fr] gap-x-4 py-4 sm:grid-cols-[3.5rem_1fr_9rem]"
      >
        <span className="tabular pt-0.5 text-[0.95rem] text-ink-3">{project.year}</span>
        <span className="min-w-0">
          <span
            className={`text-[1.1rem] font-semibold text-ink underline-offset-[0.3em] decoration-2 ${
              active ? "underline decoration-accent" : ""
            }`}
          >
            {project.title}
          </span>
          <span className="mt-1 block text-[0.95rem] text-ink-2 lg:sr-only">{project.summary}</span>
          <span className="mt-1 block text-[0.92rem] text-ink-3">{kindLabels[project.kind]}</span>
        </span>
        <span className="col-start-2 mt-1 text-[0.92rem] text-ink-3 sm:col-start-auto sm:mt-0 sm:pt-0.5 sm:text-right">
          {project.status}
        </span>
      </Link>
    </li>
  );
}

function Preview({ project }: { project: Project }) {
  const shot = project.shots.find((s) => s.device !== "mobile");
  return (
    <div key={project.slug} className="fade-in">
      {shot ? (
        <div className="relative aspect-[16/10] overflow-hidden rounded-[6px] border border-line bg-sunk">
          <Image key={shot.src} src={shot.src} alt="" fill sizes="480px" className="object-cover object-top" />
        </div>
      ) : (
        // Sin captura: proyecto anterior a 2026 o código privado. Se muestra el título, no una imagen inventada.
        <div className="flex aspect-[16/10] flex-col justify-end rounded-[6px] border border-line bg-sunk p-7">
          <p className="wide text-[1.9rem] font-bold leading-[0.95] text-ink">{project.title}</p>
          <p className="mt-3 text-[0.92rem] text-ink-3">Sin capturas públicas</p>
        </div>
      )}
      <p className="mt-5 text-ink-2">{project.summary}</p>
      <p className="mt-3 text-[0.92rem] text-ink-3">{project.technologies.join(" · ")}</p>
    </div>
  );
}
