import { TechName } from "@/data/techIcons";

export interface SkillCategory {
  id: string;
  title: string;
  description: string;
  skills: TechName[];
}

export const skillCategories: SkillCategory[] = [
  {
    id: "frontend",
    title: "Frontend",
    description: "Interfaces rápidas, accesibles y responsive.",
    skills: ["React", "Next.js", "Vue", "TypeScript", "JavaScript", "Tailwind CSS", "HTML", "CSS"],
  },
  {
    id: "backend",
    title: "Backend",
    description: "APIs y lógica de negocio robustas.",
    skills: ["Node.js", "Python", "PHP", "FastAPI", "Flask"],
  },
  {
    id: "data",
    title: "Datos & ML",
    description: "De datos crudos a decisiones y modelos.",
    skills: ["MySQL", "Pandas", "Scikit-learn"],
  },
  {
    id: "tools",
    title: "Herramientas & DevOps",
    description: "Flujo de trabajo, CI/CD y despliegue.",
    skills: ["Git", "GitHub Actions", "Vercel"],
  },
];
