import Image from "next/image";
import Link from "next/link";
import { projects } from "@/data/projects";

export default function FeaturedCases() {
  return (
    <section id="projects" aria-labelledby="projects-title" className="site-section">
      <h2 id="projects-title">Proyectos publicados</h2>
      <ul className="case-list">
        {projects.map((project) => (
          <li key={project.slug}>
            <Link href={`/projects/${project.slug}`} className="case-link">
              <div className="case-image">
                <Image src={project.image} alt="" fill sizes="(min-width: 1050px) 33vw, (min-width: 680px) 50vw, 100vw" />
              </div>
              <p className="stack">{project.category}</p>
              <h3>{project.title}</h3>
            </Link>
            <p>{project.summary}</p>
          </li>
        ))}
      </ul>
    </section>
  );
}
