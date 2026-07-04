import { projects } from "@/data/projects";
import ProjectCard from "./ProjectCard";
import SectionHeading from "./ui/SectionHeading";
import Reveal from "./ui/Reveal";

export default function ProjectsSection() {
  return (
    <section id="projects" className="py-24 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <SectionHeading
          eyebrow="Proyectos"
          title={
            <>
              Casos de estudio con <span className="text-accent">impacto real</span>
            </>
          }
          description="Cada proyecto resolvió un problema concreto para usuarios reales. Haz clic para ver el problema, la solución y los resultados."
        />

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {projects.map((project, i) => (
            <Reveal key={project.slug} delay={(i % 3) * 0.08} className="h-full">
              <ProjectCard project={project} />
            </Reveal>
          ))}
        </div>
      </div>
    </section>
  );
}
