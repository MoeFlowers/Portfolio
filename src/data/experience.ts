export interface ExperienceItem {
  period: string;
  title: string;
  org: string;
  description: string;
  highlight?: string;
  type: "trabajo" | "educacion";
}

export const experience: ExperienceItem[] = [
  {
    period: "2024 — Hoy",
    title: "Desarrollador Full-Stack Freelance",
    org: "Proyectos independientes",
    description:
      "Desarrollo de aplicaciones web a medida y automatizaciones para clientes: sistema de gestión para clínica odontológica, bots de procesamiento de datos y motores de recomendación con ML.",
    highlight: "21 h/semana de trabajo manual automatizadas entre clientes",
    type: "trabajo",
  },
  {
    period: "Ene — Jun 2024",
    title: "Líder técnico — Proyecto de Servicio Comunitario",
    org: "Comunidad local · UNEFA",
    description:
      "Lideré el desarrollo end-to-end de una plataforma web usada por más de 2.000 personas, desde el modelado de datos hasta el despliegue y la capacitación del equipo administrador.",
    highlight: "2.000+ usuarios · -80% tiempo de consulta",
    type: "trabajo",
  },
  {
    period: "2023 — 2024",
    title: "Sistema Web para Clínica Odontológica",
    org: "Cliente privado (freelance)",
    description:
      "Digitalización completa de historiales, odontograma interactivo, citas con recordatorios automáticos y facturación con reportes.",
    highlight: "6 h/semana ahorradas · 100% historiales digitales",
    type: "trabajo",
  },
  {
    period: "2020 — 2025",
    title: "Ingeniería en Sistemas",
    org: "UNEFA",
    description:
      "Formación en ingeniería de software, bases de datos y arquitectura de sistemas, complementada con cursos de arquitectura de software y DevOps.",
    type: "educacion",
  },
];
