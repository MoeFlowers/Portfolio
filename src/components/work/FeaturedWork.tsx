import Link from "next/link";
import { getProject, type Project } from "@/data/projects";
import ShotFrame from "@/components/ui/ShotFrame";
import { ProjectLinks, ProjectMeta, ResultsList, Stack } from "./parts";

function need(slug: string): Project {
  const p = getProject(slug);
  if (!p) throw new Error(`Falta el proyecto ${slug}`);
  return p;
}

function Title({ project }: { project: Project }) {
  return (
    <h3 className="wide mt-3 text-[clamp(2rem,4.2vw,3.1rem)] font-bold leading-[0.95] text-ink">
      <Link href={`/projects/${project.slug}`} className="hover:text-accent transition-colors">
        {project.title}
      </Link>
    </h3>
  );
}

/** La imagen principal también lleva al caso; se oculta del teclado porque el título ya es el enlace. */
function ShotLink({ project, index, sizes, priority }: { project: Project; index: number; sizes: string; priority?: boolean }) {
  return (
    <Link href={`/projects/${project.slug}`} tabIndex={-1} aria-hidden="true" className="group block">
      <ShotFrame
        shot={project.shots[index]}
        sizes={sizes}
        priority={priority}
        className="transition-[border-color] duration-200 group-hover:border-control"
      />
    </Link>
  );
}

export default function FeaturedWork() {
  const vitrina = need("vitrina");
  const mostrador = need("mostrador");
  const puerto = need("puerto-vivo");
  const landings = need("landings-3d");

  return (
    <div className="mt-14 space-y-28 md:space-y-36">
      {/* Vitrina: el proyecto más completo, con galería de pantallas */}
        <article aria-labelledby="f-vitrina" className="grid gap-10 lg:grid-cols-12 lg:gap-12">
          <div className="lg:col-span-5 lg:pt-4">
            <ProjectMeta project={vitrina} />
            <div id="f-vitrina">
              <Title project={vitrina} />
            </div>
            <p className="mt-5 text-[1.08rem] text-ink-2">{vitrina.summary}</p>
            <ResultsList project={vitrina} layout="rows" className="mt-8" />
            <Stack project={vitrina} className="mt-8" />
            <ProjectLinks project={vitrina} className="mt-5" />
          </div>
          <div className="lg:col-span-7">
            <ShotLink project={vitrina} index={0} sizes="(max-width: 1024px) 100vw, 680px" />
            <div className="mt-3 grid grid-cols-3 gap-3">
              {[1, 2, 3].map((i) => (
                <ShotFrame key={i} shot={vitrina.shots[i]} sizes="(max-width: 1024px) 33vw, 220px" />
              ))}
            </div>
          </div>
        </article>

      {/* Mostrador: espejado, la caja manda */}
        <article aria-labelledby="f-mostrador" className="grid gap-10 lg:grid-cols-12 lg:gap-12">
          <div className="lg:order-2 lg:col-span-5 lg:pt-4">
            <ProjectMeta project={mostrador} />
            <div id="f-mostrador">
              <Title project={mostrador} />
            </div>
            <p className="mt-5 text-[1.08rem] text-ink-2">{mostrador.summary}</p>
            <ResultsList project={mostrador} layout="rows" className="mt-8" />
            <Stack project={mostrador} className="mt-8" />
            <ProjectLinks project={mostrador} className="mt-5" />
            <p className="mt-3 text-[0.92rem] text-ink-3">Código privado: es el sistema de un cliente.</p>
          </div>
          <div className="lg:order-1 lg:col-span-7">
            <ShotLink project={mostrador} index={0} sizes="(max-width: 1024px) 100vw, 680px" />
            <div className="mt-3 grid grid-cols-2 gap-3">
              <ShotFrame shot={mostrador.shots[1]} sizes="(max-width: 1024px) 50vw, 330px" />
              <ShotFrame shot={mostrador.shots[3]} sizes="(max-width: 1024px) 50vw, 330px" />
            </div>
          </div>
        </article>

      {/* Puerto Vivo: la escena necesita todo el ancho */}
        <article aria-labelledby="f-puerto">
          <ShotLink project={puerto} index={0} sizes="(max-width: 1200px) 100vw, 1150px" />
          <div className="mt-8 grid gap-8 lg:grid-cols-12 lg:gap-12">
            <div className="lg:col-span-6">
              <ProjectMeta project={puerto} />
              <div id="f-puerto">
                <Title project={puerto} />
              </div>
            </div>
            <div className="lg:col-span-6">
              <p className="text-[1.08rem] text-ink-2">{puerto.summary}</p>
              <ResultsList project={puerto} layout="rows" className="mt-7" />
              <Stack project={puerto} className="mt-7" />
              <ProjectLinks project={puerto} className="mt-5" />
            </div>
          </div>
        </article>

      {/* Landings 3D: tres piezas, tres capturas lado a lado */}
        <article aria-labelledby="f-landings">
          <div className="grid gap-8 lg:grid-cols-12 lg:gap-12">
            <div className="lg:col-span-6">
              <ProjectMeta project={landings} />
              <div id="f-landings">
                <Title project={landings} />
              </div>
            </div>
            <div className="lg:col-span-6 lg:pt-8">
              <p className="text-[1.08rem] text-ink-2">{landings.summary}</p>
              <Stack project={landings} className="mt-6" />
              <ProjectLinks project={landings} className="mt-5" />
            </div>
          </div>
          <div className="mt-10 grid gap-3 sm:grid-cols-3">
            {[0, 1, 2].map((i) => (
              <figure key={i}>
                <ShotFrame shot={landings.shots[i]} sizes="(max-width: 640px) 100vw, 380px" />
                <figcaption className="mt-2 text-[0.92rem] text-ink-3">
                  {["Hotel boutique", "Cat café", "Anillo inteligente"][i]}
                </figcaption>
              </figure>
            ))}
          </div>
        </article>
    </div>
  );
}
