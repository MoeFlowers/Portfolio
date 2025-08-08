import { projects } from "../data/projects";
import ProjectCard from "../components/ProjectCard";
import ContactForm from "../components/ContactForm";
import AboutSection from "../components/AboutSection";
import Navbar from "../components/Navbar";

export default function Home() {
  return (
    <main className="min-h-screen">
      <Navbar />
      {/* Hero Section */}
      <section className="text-center py-20">
        <h1 className="text-4xl font-bold mb-4">Desarrollador Full-Stack</h1>
        <p className="text-xl text-gray-600">
          Soluciones web con Python, React y automatización.
        </p>
      </section>
      <AboutSection />

      {/* Proyectos Destacados */}
      <section id="projects" className="py-20 px-4 bg-gray-50 dark:bg-gray-900/50">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
              Mis <span className="text-[#1DA1F2]">Proyectos</span> Destacados
            </h2>
            <p className="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
              Soluciones reales que he desarrollado para clientes y proyectos personales
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {projects.map((project) => (
              <ProjectCard key={project.id} project={project} />
            ))}
          </div>
        </div>
      </section>

      <ContactForm />
    </main>
  );
}