import { TechName } from "@/data/techIcons";

export interface Project {
  id: number;
  title: string;
  description: string;
  technologies: TechName[];
  githubLink?: string;
  demoLink?: string;
  externalLink?: string;
  duration: string;
  image: string; // Obligatorio
  category?: string; // Opcional
}

export const projects: Project[] = [
  {
    id: 1,
    title: "AI-Powered Book Recommendation",
    description:
      "Developed a collaborative filtering model (Scikitlearn) processing 10,000+ books. Integrated REST API (Flask/FastAPI) for real-time recommendations → 85% user-rated accuracy.",
    technologies: ["Python", "Scikit-learn", "FastAPI", "Flask"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/book",
    demoLink: "#",
    externalLink: "https://book-recommendation-ai.vercel.app/",
    duration: "2 months",
    image: "/images/projects/AIPoweredBookRecommendation.jpeg",
    category: "Machine Learning"
  },
  {
    id: 2,
    title: "Dental Clinic Web System",
    description:
      "Digitized 100% of patient records and automated billing, saving 6h/week in admin work. Built an appointment module with automated email reminders.",
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/clinica",
    demoLink: "/projects/clinic",
    externalLink: "https://dental-clinic-web-system.vercel.app/",
    duration: "6 months",
    image: "/images/projects/DentalClinicProject.jpeg",
    category: "Web Development"
  },
  {
    id: 3,
    title: "Full-Stack Web Developer — Community Service Project",
    description:
      "Led end-to-end development of a web app used by 2,000+ users, reducing data query time by 80%. Implemented secure CRUD operations with PHP/MySQL and responsive UI → 97% user satisfaction",
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    demoLink: "#",
    externalLink: "https://comunity.vercel.app/",
    duration: "Jan 2024 – Jun 2024",
    image: "/images/projects/ComunityServiceProject.jpeg",
    category: "Full Stack"
  },
  {
    id: 4,
    title: "Data Processing Bots | Python",
    description:
      "API-powered bots updating dashboards every 5 mins with Slack alerts. Automated repetitive tasks (~15h/week saved).",
    technologies: ["Python"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    demoLink: "#",
    externalLink: "https://data-processing-bots.vercel.app/",
    duration: "4 months",
    image: "/images/projects/DataProcessingBots.jpeg",
    category: "Automation"
  },
  // Puedes agregar más proyectos aquí...
];
