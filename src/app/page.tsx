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
      <section id="projects" className="py-12 px-4">
        <h2 className="text-3xl font-bold mb-8 text-center">Mis Proyectos</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {projects.map((project) => (
            <ProjectCard key={project.id} project={project} />
          ))}
        </div>
      </section>
      <ContactForm />
    </main>
  );
}