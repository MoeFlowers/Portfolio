"use client";
import { Project } from "../data/projects";
import Link from "next/link";
import { FiGithub, FiExternalLink, FiArrowRight } from "react-icons/fi";
import { techIcons } from "../data/techIcons";
import Image from "next/image";

interface ProjectCardProps {
  project: Project;
}

export default function ProjectCard({ project }: ProjectCardProps): React.ReactElement {
  return (
    <div className="group relative bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 h-full flex flex-col">
      {/* Imagen destacada */}
      <div className="relative h-48 w-full overflow-hidden">
        <Image
          src={project.image}
          alt={project.title}
          fill
          className="object-cover transition-transform duration-500 group-hover:scale-105"
          sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-gray-900/30 to-transparent" />
      </div>

      {/* Contenido principal */}
      <div className="p-6 flex-grow">
        {project.category && (
          <span className="inline-block px-3 py-1 text-xs font-medium rounded-full bg-[#1DA1F2]/10 text-[#1DA1F2] mb-3">
            {project.category}
          </span>
        )}

        <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-[#1DA1F2] transition-colors">
          {project.title}
        </h3>

        <p className="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
          {project.description}
        </p>

        {/* Tecnologías con iconos */}
        <div className="flex flex-wrap gap-2 mb-6">
          {project.technologies.map((tech) => {
            const techData = techIcons[tech];
            const IconComponent = techData?.icon;

            return (
              <span
                key={tech}
                className={`${techData?.color || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'} flex items-center gap-1 text-xs px-3 py-1 rounded-full transition-colors duration-200 hover:scale-105`}
              >
                {IconComponent && <IconComponent className="w-3 h-3" />}
                {tech}
              </span>
            );
          })}
        </div>
      </div>

      {/* Footer con enlaces */}
      <div className="px-6 pb-6 flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-4">
        <div className="flex space-x-3">
          {project.githubLink && project.githubLink !== "#" && (
            <Link
              href={project.githubLink}
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-[#1DA1F2] hover:text-white transition-colors duration-200"
              aria-label="Código en GitHub"
            >
              <FiGithub className="w-5 h-5" />
            </Link>
          )}

          {project.externalLink && project.externalLink !== "#" && (
            <Link
              href="#"
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-[#1DA1F2] hover:text-white transition-colors duration-200"
              aria-label="Ver demo"
            >
              <FiExternalLink className="w-5 h-5" />
            </Link>
          )}
        </div>

        <Link
          href="#"
          className="flex items-center text-sm font-medium text-[#1DA1F2] hover:text-[#1a8cd8] transition-colors"
        >
          Ver detalles
          <FiArrowRight className="ml-1" />
        </Link>
      </div>

      {/* Efecto hover sutil */}
      <div className="absolute inset-0 bg-gradient-to-br from-white/50 dark:from-gray-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" />
    </div>
  );
}