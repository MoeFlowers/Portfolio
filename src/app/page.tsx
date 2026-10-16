import Navbar from "@/components/Navbar";
import HeroSection from "@/components/HeroSection";
import FeaturedWork from "@/components/work/FeaturedWork";
import ProjectIndex from "@/components/work/ProjectIndex";
import ExperienceSection from "@/components/ExperienceSection";
import ContactForm from "@/components/ContactForm";
import Footer from "@/components/Footer";
import SectionHeading from "@/components/ui/SectionHeading";
import { projects } from "@/data/projects";

export default function Home() {
  return (
    <>
      <a href="#main-content" className="skip-link">
        Saltar al contenido
      </a>
      <Navbar />
      <main id="main-content">
        <HeroSection />

        <section id="work" aria-labelledby="work-title" className="mx-auto max-w-6xl px-5 py-20 sm:px-8 md:py-24">
          <SectionHeading id="work-title" title="Trabajos seleccionados" />
          <FeaturedWork />
        </section>

        <section id="projects" aria-labelledby="projects-title" className="mx-auto max-w-6xl px-5 py-20 sm:px-8 md:py-28">
          <SectionHeading
            id="projects-title"
            title="Todos los proyectos"
            note={<span className="tabular">{projects.length} proyectos, del más reciente al más antiguo</span>}
          />
          <ProjectIndex />
        </section>

        <ExperienceSection />
        <ContactForm />
      </main>
      <Footer />
    </>
  );
}
