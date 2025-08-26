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
    title: "Sistema de Recomendación de Peliculas con IA",
    description:
      "Desarrollé un modelo de filtrado colaborativo (Scikitlearn) procesando 10,000+ libros. Integré una API REST (Flask/FastAPI) para recomendaciones en tiempo real → 85% de precisión según usuarios.",
    technologies: ["Python", "Scikit-learn", "FastAPI", "Flask"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/book",
    demoLink: "#",
    externalLink: "https://book-recommendation-ai.vercel.app/",
    duration: "2 meses",
    image: "/images/projects/AIPoweredBookRecommendation.jpeg",
    category: "Machine Learning",
  },
  {
    id: 2,
    title: "Sistema Web para Clínica Odontológica",
    description:
      "Digitalicé 100% de los historiales y automatizó la facturación, ahorrando 6h/sem en trabajo administrativo. Construí un módulo de citas con recordatorios automáticos por correo.",
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/clinica",
    demoLink: "/projects/clinic",
    externalLink: "https://dental-clinic-web-system.vercel.app/",
    duration: "6 meses",
    image: "/images/projects/DentalClinicProject.jpeg",
    category: "Desarrollo Web",
  },
  {
    id: 3,
    title: "Desarrollador Web Full‑Stack — Proyecto de Servicio Comunitario",
    description:
      "Lideré el desarrollo end-to-end de una app web usada por 2,000+ usuarios, reduciendo el tiempo de consulta de datos un 80%. Implementé operaciones CRUD seguras con PHP/MySQL y una UI responsive → 97% de satisfacción del usuario.",
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    demoLink: "#",
    externalLink: "https://comunity.vercel.app/",
    duration: "Ene 2024 – Jun 2024",
    image: "/images/projects/ComunityServiceProject.jpeg",
    category: "Full Stack",
  },
  {
    id: 4,
    title: "Bots de Procesamiento de Datos | Python",
    description:
      "Bots que consumen APIs y actualizan dashboards cada 5 min con alertas por Slack. Automatizó tareas repetitivas (~15h/sem ahorradas).",
    technologies: ["Python"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    demoLink: "#",
    externalLink: "https://data-processing-bots.vercel.app/",
    duration: "4 meses",
    image: "/images/projects/DataProcessingBots.jpeg",
    category: "Automatización",
  },
  // Puedes agregar más proyectos aquí...
];
