import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import { FiArrowUpRight } from "react-icons/fi";
import { projects, getProject, kindLabels } from "@/data/projects";
import { site } from "@/data/site";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import ShotFrame from "@/components/ui/ShotFrame";
import { ResultsList } from "@/components/work/parts";

interface CaseStudyProps {
  params: Promise<{ slug: string }>;
}

// Mismo orden que el índice de la home: más reciente primero
const ordered = [...projects].sort((a, b) => Number(b.year) - Number(a.year));

export function generateStaticParams() {
  return projects.map(({ slug }) => ({ slug }));
}

export async function generateMetadata({ params }: CaseStudyProps): Promise<Metadata> {
  const { slug } = await params;
  const project = getProject(slug);
  if (!project) return {};
  const image = project.shots.find((s) => s.device !== "mobile");

  return {
    title: project.title,
    description: project.summary,
    alternates: { canonical: `/projects/${project.slug}` },
    openGraph: {
      title: project.title,
      description: project.summary,
      url: `${site.url}/projects/${project.slug}`,
      ...(image && { images: [{ url: image.src }] }),
    },
  };
}

export default async function CaseStudyPage({ params }: CaseStudyProps) {
  const { slug } = await params;
  const project = getProject(slug);
  if (!project) notFound();

  const desktop = project.shots.filter((s) => s.device !== "mobile");
  const mobile = project.shots.filter((s) => s.device === "mobile");
  const [hero, ...rest] = desktop;
  const isConcept = project.status === "Concepto";
  const index = ordered.findIndex((p) => p.slug === project.slug);
  const next = ordered[(index + 1) % ordered.length];

  return (
    <>
      <a href="#main-content" className="skip-link">
        Saltar al contenido
      </a>
      <Navbar />
      <main id="main-content" className="mx-auto max-w-6xl px-5 pb-24 pt-10 sm:px-8 md:pt-14">
        <Link href="/#projects" className="link-underline text-[0.95rem] text-ink-2">
          Volver a todos los proyectos
        </Link>

        <header className="mt-10 grid gap-10 lg:grid-cols-12">
          <div className="lg:col-span-8">
            <p className="tabular text-[0.95rem] text-ink-3">
              {project.year} · {kindLabels[project.kind]} · <span className="text-ink-2">{project.status}</span>
            </p>
            <h1 className="wide mt-4 text-[clamp(2.6rem,7vw,5.4rem)] font-extrabold leading-[0.9] tracking-[-0.03em] text-ink">
              {project.title}
            </h1>
            <p className="mt-7 max-w-[46ch] text-[clamp(1.1rem,1.8vw,1.35rem)] leading-snug text-ink">{project.summary}</p>
          </div>

          <dl className="self-end text-[0.95rem] lg:col-span-4">
            <div className="border-t border-line py-3">
              <dt className="text-ink-3">Rol</dt>
              <dd className="text-ink">{project.role}</dd>
            </div>
            <div className="border-t border-line py-3">
              <dt className="text-ink-3">Duración</dt>
              <dd className="text-ink">{project.duration}</dd>
            </div>
            <div className="border-t border-line py-3">
              <dt className="text-ink-3">Tecnologías</dt>
              <dd className="text-ink">{project.technologies.join(" · ")}</dd>
            </div>
            <div className="border-y border-line py-3">
              <dt className="sr-only">Enlaces</dt>
              <dd className="flex flex-wrap gap-x-5 gap-y-2">
                {project.liveLink && (
                  <a href={project.liveLink} target="_blank" rel="noopener noreferrer" className="link-underline inline-flex items-center gap-1 text-ink">
                    {project.liveLabel ?? "Ver en vivo"}
                    <FiArrowUpRight aria-hidden="true" className="h-4 w-4" />
                    <span className="sr-only">(se abre en otra pestaña)</span>
                  </a>
                )}
                {project.githubLink && (
                  <a href={project.githubLink} target="_blank" rel="noopener noreferrer" className="link-underline inline-flex items-center gap-1 text-ink">
                    Código
                    <FiArrowUpRight aria-hidden="true" className="h-4 w-4" />
                    <span className="sr-only">(se abre en otra pestaña)</span>
                  </a>
                )}
                {project.privateRepo && <span className="text-ink-3">Código privado: es de un cliente.</span>}
              </dd>
            </div>
          </dl>
        </header>

        {hero && <ShotFrame shot={hero} priority sizes="(max-width: 1200px) 100vw, 1150px" className="mt-14" />}

        <section aria-labelledby="results-title" className="mt-16">
          <h2 id="results-title" className="sr-only">
            Resultados
          </h2>
          <ResultsList project={project} />
        </section>

        <div className="mt-20 space-y-16">
          <Block title={isConcept ? "La idea" : "El problema"}>
            <p className="max-w-[62ch] text-[1.05rem] text-ink-2">{project.problem}</p>
          </Block>

          <Block title="Qué construí">
            <ul className="max-w-[62ch] list-disc space-y-3 pl-5 text-[1.05rem] text-ink-2 marker:text-ink-3">
              {project.solution.map((item) => (
                <li key={item}>{item}</li>
              ))}
            </ul>
          </Block>

          {project.learnings && project.learnings.length > 0 && (
            <Block title="Qué aprendí">
              <ul className="max-w-[62ch] list-disc space-y-3 pl-5 text-[1.05rem] text-ink-2 marker:text-ink-3">
                {project.learnings.map((item) => (
                  <li key={item}>{item}</li>
                ))}
              </ul>
            </Block>
          )}
        </div>

        {(rest.length > 0 || mobile.length > 0) && (
          <section aria-labelledby="gallery-title" className="mt-24">
            <h2 id="gallery-title" className="wide border-b border-line pb-4 text-[1.6rem] font-bold text-ink">
              Más pantallas
            </h2>
            <div className="mt-8 grid gap-4 sm:grid-cols-2">
              {rest.map((shot) => (
                <figure key={shot.src}>
                  <ShotFrame shot={shot} sizes="(max-width: 640px) 100vw, 560px" />
                  <figcaption className="mt-2 text-[0.92rem] text-ink-3">{shot.alt}</figcaption>
                </figure>
              ))}
            </div>
            {mobile.length > 0 && (
              <div className="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
                {mobile.map((shot) => (
                  <figure key={shot.src}>
                    <ShotFrame shot={shot} sizes="(max-width: 640px) 50vw, 270px" />
                    <figcaption className="mt-2 text-[0.92rem] text-ink-3">{shot.alt}</figcaption>
                  </figure>
                ))}
              </div>
            )}
          </section>
        )}

        <nav aria-label="Más proyectos" className="mt-24 grid gap-8 border-t border-line pt-10 md:grid-cols-2">
          <div>
            <p className="text-[0.95rem] text-ink-3">Siguiente proyecto</p>
            <Link href={`/projects/${next.slug}`} className="wide mt-2 inline-block text-[clamp(1.6rem,3.4vw,2.4rem)] font-bold leading-tight text-ink transition-colors hover:text-accent">
              {next.title}
            </Link>
          </div>
          <div className="md:text-right">
            <p className="text-[0.95rem] text-ink-3">¿Necesitas algo parecido?</p>
            <Link href="/#contact" className="link-underline mt-2 inline-block text-[1.1rem] text-ink">
              Escríbeme y lo conversamos
            </Link>
          </div>
        </nav>
      </main>
      <Footer />
    </>
  );
}

function Block({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <section className="grid gap-x-10 gap-y-4 border-t border-line pt-6 md:grid-cols-12">
      <h2 className="text-[1.15rem] font-semibold text-ink md:col-span-3">{title}</h2>
      <div className="md:col-span-9">{children}</div>
    </section>
  );
}
