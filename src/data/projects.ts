import { TechName } from "@/data/techIcons";

interface Project {
  id: number;
  title: string;
  description: string;
  technologies: TechName[];
  githubLink?: string;
  demoLink?: string;
  duration?: string;
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
    duration: "2 months",
  },
  {
    id: 2,
    title: "Dental Clinic Web System",
    description:
      "Digitized 100% of patient records and automated billing, saving 6h/week in admin work. Built an appointment module with automated email reminders.",
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/clinica",
    demoLink: "#",
    duration: "6 months",
  },
  {
    id: 3,
    title: "Full-Stack Web Developer — Community Service Project",
    description:
      "Led end-to-end development of a web app used by 2,000+ users, reducing data query time by 80%. Implemented secure CRUD operations with PHP/MySQL and responsive UI → 97% user satisfaction",
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    demoLink: "#",
    duration: "Jan 2024 – Jun 2024",
  },
  {
    id: 4,
    title: "Data Processing Bots | Python",
    description:
      "API-powered bots updating dashboards every 5 mins with Slack alerts. Automated repetitive tasks (~15h/week saved).",
    technologies: ["Python",],
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    demoLink: "#",
    duration: "4 months",
  },
  // Others
];

export type { Project };
