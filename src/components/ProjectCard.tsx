"use client";
import { Project } from "../data/projects";
import Link from "next/link";
import { FiGithub, FiExternalLink } from "react-icons/fi";
import { techIcons } from "../data/techIcons";

export default function ProjectCard({ project }: { project: Project }) {
  return (
    <div className="group relative bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
      {/* Contenido principal */}
      <div className="p-6">
        <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary transition-colors">
          {project.title}
        </h3>

        <p className="text-gray-600 dark:text-gray-300 mb-4">
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
                className={`${techData?.color || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'} flex items-center gap-1 text-xs px-3 py-1 rounded-full`}
              >
                {IconComponent && <IconComponent className="w-3 h-3" />}
                {tech}
              </span>
            );
          })}
        </div>
      </div>

      {/* Footer con enlaces */}
      <div className="px-6 pb-4 flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-4">
        <div className="flex space-x-3">
          {project.githubLink && (
            <Link
              href={project.githubLink}
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
              aria-label="Código en GitHub"
            >
              <FiGithub className="w-5 h-5" />
            </Link>
          )}

          {project.demoLink && (
            <Link
              href={project.demoLink}
              target="_blank"
              rel="noopener noreferrer"
              className="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
              aria-label="Ver demo"
            >
              <FiExternalLink className="w-5 h-5" />
            </Link>
          )}
        </div>

        <span className="text-sm text-gray-500 dark:text-gray-400">
          {project.duration}
        </span>
      </div>

      {/* Efecto hover sutil */}
      <div className="absolute inset-0 bg-gradient-to-br from-white/50 dark:from-gray-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none" />
    </div>
  );
}