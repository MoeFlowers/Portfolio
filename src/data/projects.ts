import { TechName } from "@/data/techIcons";

export interface ProjectMetric {
  value: string;
  label: string;
}

export interface Project {
  slug: string;
  title: string;
  /** Resumen corto para la tarjeta (1-2 líneas). */
  summary: string;
  /** Contexto del caso de estudio. */
  problem: string;
  /** Qué se construyó, punto por punto. */
  solution: string[];
  /** Resultados medibles. */
  results: ProjectMetric[];
  learnings?: string[];
  technologies: TechName[];
  role: string;
  duration: string;
  githubLink?: string;
  liveLink?: string;
  image: string;
  category: string;
  featured: boolean;
}

export const projects: Project[] = [
  {
    slug: "clinica-odontologica",
    title: "Sistema Web para Clínica Odontológica",
    summary:
      "Digitalización completa de historiales y facturación de una clínica real: 6 h/semana menos de trabajo administrativo.",
    problem:
      "La clínica gestionaba historiales médicos, citas y facturación en papel y hojas de cálculo. Buscar el historial de un paciente tomaba minutos, la facturación se hacía a mano y las citas se perdían por falta de recordatorios.",
    solution: [
      "Sistema web a medida con módulos de pacientes, odontograma interactivo diente por diente, citas y facturación.",
      "Digitalización del 100% de los historiales clínicos con búsqueda instantánea.",
      "Facturación automática con exportación a Excel y reportes con gráficos.",
      "Módulo de citas con recordatorios automáticos por correo electrónico.",
    ],
    results: [
      { value: "6 h/sem", label: "de trabajo administrativo ahorradas" },
      { value: "100%", label: "de historiales digitalizados" },
      { value: "97%", label: "de satisfacción del personal" },
    ],
    learnings: [
      "Levantar requisitos con usuarios no técnicos y traducirlos a módulos concretos.",
      "Diseñar un odontograma interactivo reutilizable con JavaScript puro.",
    ],
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    role: "Desarrollador Full-Stack (freelance)",
    duration: "6 meses",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/clinica",
    liveLink: "https://dental-clinic-web-system.vercel.app/",
    image: "/images/projects/DentalClinicProject.jpeg",
    category: "Desarrollo Web",
    featured: true,
  },
  {
    slug: "servicio-comunitario",
    title: "Plataforma Web de Servicio Comunitario",
    summary:
      "App web end-to-end usada por más de 2.000 personas: el tiempo de consulta de datos bajó un 80%.",
    problem:
      "Una comunidad de más de 2.000 personas dependía de registros manuales para consultar y actualizar sus datos. Cada consulta implicaba esperas largas y los registros se duplicaban o perdían.",
    solution: [
      "Lideré el desarrollo end-to-end de la aplicación: modelado de datos, backend y frontend.",
      "Operaciones CRUD seguras con PHP y MySQL, con validación y control de acceso.",
      "Interfaz responsive pensada para usuarios sin experiencia técnica.",
      "Despliegue y capacitación al equipo que hoy administra la plataforma.",
    ],
    results: [
      { value: "2.000+", label: "usuarios activos" },
      { value: "-80%", label: "en tiempo de consulta de datos" },
      { value: "97%", label: "de satisfacción de usuarios" },
    ],
    learnings: [
      "Liderar un proyecto real con usuarios finales y plazos: enero a junio de 2024.",
      "Priorizar accesibilidad y simplicidad cuando la audiencia no es técnica.",
    ],
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    role: "Líder técnico y desarrollador Full-Stack",
    duration: "Ene 2024 – Jun 2024",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    liveLink: "https://comunity.vercel.app/",
    image: "/images/projects/ComunityServiceProject.jpeg",
    category: "Full-Stack",
    featured: true,
  },
  {
    slug: "recomendador-libros-ia",
    title: "Sistema de Recomendación de Libros con IA",
    summary:
      "Motor de recomendaciones con filtrado colaborativo sobre 10.000+ libros, servido por API REST en tiempo real.",
    problem:
      "Encontrar el próximo libro adecuado entre miles de opciones es lento: los lectores pierden tiempo revisando listas genéricas que no consideran sus gustos.",
    solution: [
      "Modelo de filtrado colaborativo con Scikit-learn entrenado sobre un dataset de más de 10.000 libros.",
      "API REST con FastAPI y Flask que sirve recomendaciones en tiempo real.",
      "Pipeline de limpieza y preparación de datos reproducible.",
      "Frontend de demostración para probar recomendaciones al instante.",
    ],
    results: [
      { value: "85%", label: "de precisión validada con usuarios" },
      { value: "10.000+", label: "libros procesados" },
      { value: "-70%", label: "en tiempo de búsqueda de lecturas" },
    ],
    learnings: [
      "Evaluar modelos de recomendación con feedback real de usuarios, no solo métricas offline.",
    ],
    technologies: ["Python", "Scikit-learn", "FastAPI", "Flask"],
    role: "Desarrollador ML / Backend",
    duration: "2 meses",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/book",
    liveLink: "https://book-recommendation-ai.vercel.app/",
    image: "/images/projects/AIPoweredBookRecommendation.jpeg",
    category: "Machine Learning",
    featured: true,
  },
  {
    slug: "bots-procesamiento-datos",
    title: "Bots de Procesamiento de Datos",
    summary:
      "Flota de bots en Python que consume APIs, actualiza dashboards cada 5 minutos y alerta por Slack. ~15 h/semana ahorradas.",
    problem:
      "Tareas repetitivas de recolección y consolidación de datos consumían unas 15 horas semanales del equipo, con errores frecuentes de copiado manual.",
    solution: [
      "Bots en Python que consumen múltiples APIs y consolidan los datos automáticamente.",
      "Actualización de dashboards cada 5 minutos con datos frescos.",
      "Alertas automáticas por Slack y correo cuando se detectan anomalías.",
      "Programación de tareas y manejo de errores con reintentos.",
    ],
    results: [
      { value: "~15 h/sem", label: "de trabajo manual eliminadas" },
      { value: "5 min", label: "de frecuencia de actualización" },
      { value: "0", label: "errores de copiado manual desde el despliegue" },
    ],
    technologies: ["Python", "Pandas"],
    role: "Desarrollador de automatización",
    duration: "4 meses",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/bot",
    liveLink: "https://data-processing-bots.vercel.app/",
    image: "/images/projects/DataProcessingBots.jpeg",
    category: "Automatización",
    featured: false,
  },
];

export const featuredProjects = projects.filter((p) => p.featured);

export function getProject(slug: string): Project | undefined {
  return projects.find((p) => p.slug === slug);
}
