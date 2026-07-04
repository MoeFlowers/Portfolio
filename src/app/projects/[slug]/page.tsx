import type { Metadata } from "next";
import Link from "next/link";
import Image from "next/image";
import { notFound } from "next/navigation";
import { FiArrowLeft, FiGithub, FiExternalLink, FiClock, FiUser, FiCheck } from "react-icons/fi";
import { projects, getProject } from "@/data/projects";
import { site } from "@/data/site";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import TechChip from "@/components/ui/TechChip";

interface CaseStudyProps {
  params: Promise<{ slug: string }>;
}

export function generateStaticParams() {
  return projects.map(({ slug }) => ({ slug }));
}

export async function generateMetadata({ params }: CaseStudyProps): Promise<Metadata> {
  const { slug } = await params;
  const project = getProject(slug);
  if (!project) return {};

  return {
    title: project.title,
    description: project.summary,
    alternates: { canonical: `/projects/${project.slug}` },
    openGraph: {
      title: project.title,
      description: project.summary,
      url: `${site.url}/projects/${project.slug}`,
      images: [{ url: project.image }],
    },
  };
}

export default async function CaseStudyPage({ params }: CaseStudyProps) {
  const { slug } = await params;
  const project = getProject(slug);
  if (!project) notFound();

  return (
    <>
      <a href="#main-content" className="skip-link">
        Saltar al contenido
      </a>
      <Navbar />
      <main id="main-content" className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <Link
          href="/#projects"
          className="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft transition-colors mb-10"
        >
          <FiArrowLeft aria-hidden="true" />
          Volver a proyectos
        </Link>

        {/* Encabezado */}
        <header>
          <p className="font-mono text-xs uppercase tracking-[0.25em] text-accent mb-3">
            {project.category}
          </p>
          <h1 className="font-display text-3xl sm:text-5xl font-bold tracking-tight text-zinc-900 dark:text-white text-balance">
            {project.title}
          </h1>
          <p className="mt-4 text-lg text-zinc-600 dark:text-zinc-400">{project.summary}</p>

          <dl className="mt-6 flex flex-wrap gap-x-8 gap-y-3 text-sm">
            <div className="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
              <FiUser aria-hidden="true" className="text-accent" />
              <dt className="sr-only">Rol</dt>
              <dd>{project.role}</dd>
            </div>
            <div className="flex items-center gap-2 text-zinc-600 dark:text-zinc-400">
              <FiClock aria-hidden="true" className="text-accent" />
              <dt className="sr-only">Duración</dt>
              <dd>{project.duration}</dd>
            </div>
          </dl>

          <ul className="mt-5 flex flex-wrap gap-2" aria-label="Tecnologías utilizadas">
            {project.technologies.map((tech) => (
              <li key={tech}>
                <TechChip tech={tech} />
              </li>
            ))}
          </ul>

          <div className="mt-6 flex flex-wrap gap-3">
            {project.liveLink && (
              <a
                href={project.liveLink}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-full bg-accent text-white hover:bg-accent-hover transition-colors"
              >
                <FiExternalLink aria-hidden="true" />
                Ver demo en vivo
              </a>
            )}
            {project.githubLink && (
              <a
                href={project.githubLink}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-full border border-zinc-300 dark:border-white/15 text-zinc-700 dark:text-zinc-300 hover:border-accent hover:text-accent dark:hover:text-accent-soft transition-colors"
              >
                <FiGithub aria-hidden="true" />
                Ver código
              </a>
            )}
          </div>
        </header>

        {/* Imagen principal */}
        <div className="relative mt-12 aspect-video rounded-2xl overflow-hidden border border-zinc-200 dark:border-white/10">
          <Image
            src={project.image}
            alt={`Captura del proyecto ${project.title}`}
            fill
            priority
            sizes="(max-width: 896px) 100vw, 896px"
            className="object-cover"
          />
        </div>

        {/* Resultados */}
        <section aria-labelledby="results-heading" className="mt-14">
          <h2 id="results-heading" className="font-display text-2xl font-bold text-zinc-900 dark:text-white mb-6">
            Resultados
          </h2>
          <dl className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {project.results.map(({ value, label }) => (
              <div
                key={label}
                className="flex flex-col p-6 rounded-2xl border border-zinc-200 dark:border-white/10 bg-white/70 dark:bg-white/[0.03]"
              >
                <dd className="font-mono text-3xl font-semibold text-accent dark:text-accent-soft">
                  {value}
                </dd>
                <dt className="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{label}</dt>
              </div>
            ))}
          </dl>
        </section>

        {/* Problema */}
        <section aria-labelledby="problem-heading" className="mt-14">
          <h2 id="problem-heading" className="font-display text-2xl font-bold text-zinc-900 dark:text-white mb-4">
            El problema
          </h2>
          <p className="text-zinc-600 dark:text-zinc-400 leading-relaxed">{project.problem}</p>
        </section>

        {/* Solución */}
        <section aria-labelledby="solution-heading" className="mt-14">
          <h2 id="solution-heading" className="font-display text-2xl font-bold text-zinc-900 dark:text-white mb-4">
            La solución
          </h2>
          <ul className="space-y-3">
            {project.solution.map((item) => (
              <li key={item} className="flex items-start gap-3 text-zinc-600 dark:text-zinc-400">
                <FiCheck aria-hidden="true" className="mt-1 shrink-0 text-accent" />
                {item}
              </li>
            ))}
          </ul>
        </section>

        {/* Aprendizajes */}
        {project.learnings && project.learnings.length > 0 && (
          <section aria-labelledby="learnings-heading" className="mt-14">
            <h2 id="learnings-heading" className="font-display text-2xl font-bold text-zinc-900 dark:text-white mb-4">
              Qué aprendí
            </h2>
            <ul className="space-y-3">
              {project.learnings.map((item) => (
                <li key={item} className="flex items-start gap-3 text-zinc-600 dark:text-zinc-400">
                  <FiCheck aria-hidden="true" className="mt-1 shrink-0 text-accent" />
                  {item}
                </li>
              ))}
            </ul>
          </section>
        )}

        {/* CTA */}
        <div className="mt-16 p-8 rounded-2xl border border-zinc-200 dark:border-white/10 bg-zinc-50 dark:bg-white/[0.03] text-center">
          <h2 className="font-display text-xl font-bold text-zinc-900 dark:text-white">
            ¿Necesitas algo parecido?
          </h2>
          <p className="mt-2 text-zinc-600 dark:text-zinc-400">
            Cuéntame tu caso y te propongo una solución.
          </p>
          <Link
            href="/#contact"
            className="mt-5 inline-block px-7 py-3 text-sm font-semibold rounded-full bg-accent text-white hover:bg-accent-hover transition-colors"
          >
            Escribirme
          </Link>
        </div>
      </main>
      <Footer />
    </>
  );
}
