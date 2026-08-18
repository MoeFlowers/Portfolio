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
    slug: "punto-de-venta-inventario",
    title: "Punto de Venta e Inventario para Comercios",
    summary:
      "Sistemas de ventas, inventario y facturación en doble moneda (USD/VES con tasa BCV) para comercios venezolanos: desde un sistema robusto con ingeniería completa hasta una app ligera 100% offline.",
    problem:
      "Los comercios en Venezuela necesitan vender, controlar inventario y facturar en dos monedas a la vez (USD y bolívares a tasa BCV), sobre hardware modesto y, muchas veces, sin conexión estable. Los errores de redondeo en dinero y una tasa desactualizada no son aceptables.",
    solution: [
      "Ventas y facturación en USD, VES o mixto con cálculo monetario de precisión exacta (bcmath) y tasa BCV con sincronización diaria, historial, override manual y fallback offline.",
      "Catálogo de productos, libro auditable de movimientos de inventario, clientes con límites de crédito, permisos por rol y reportes (sistema robusto en PHP 8.2).",
      "Pantalla de venta operable 100% por teclado, pensada para cajeros no técnicos.",
      "Variante ligera de un solo archivo que funciona offline en el navegador —sin instalación ni servidor— con compresión LZ-String para superar el límite de localStorage.",
      "Calidad de ingeniería: PSR-4, PHPUnit, PHPStan y php-cs-fixer en CI, sobre documentación técnica y ADRs (spec-driven).",
    ],
    results: [
      { value: "USD/VES", label: "ventas y facturación en doble moneda con tasa BCV" },
      { value: "bcmath", label: "cálculo de dinero de precisión exacta" },
      { value: "Offline", label: "opera sin conexión ni instalación" },
    ],
    learnings: [
      "Adaptar la misma solución a distintos tamaños de negocio: desde un sistema con ingeniería completa hasta una app ligera de un solo archivo.",
      "Diseñar dinero como decimal exacto de extremo a extremo para evitar errores de redondeo.",
    ],
    technologies: ["PHP", "MySQL", "JavaScript", "Tailwind CSS"],
    role: "Desarrollador Full-Stack (freelance)",
    duration: "2025 – 2026",
    image: "/images/projects/PuntoDeVentaInventario.png",
    category: "Full-Stack",
    featured: true,
  },
  {
    slug: "control-deudas-multimoneda",
    title: "Control de Deudas — Tablero Multi-moneda",
    summary:
      "App de finanzas para llevar deudas por pagar y por cobrar en Bs (BCV), USD y USDT, con planificador de pagos por quincena y datos aislados por usuario.",
    problem:
      "Llevar deudas en un país con varias monedas en paralelo (bolívares a tasa BCV, dólares y USDT) es confuso en una hoja de cálculo: no se sabe cuánto se debe realmente ni qué toca pagar cada quincena, y los datos deben poder consultarse desde el teléfono.",
    solution: [
      "Tablero único que consolida deudas por pagar y por cobrar en Bs (BCV), USD y USDT.",
      "Planificador de pagos por quincena y tasas de cambio actualizadas automáticamente.",
      "Login por usuario con seguridad por fila (RLS) en Supabase: cada persona ve y gestiona solo sus propias deudas.",
      "Frontend en Vue 3 sin build, 100% estático, desplegado en Vercel; Postgres y Auth gestionados por Supabase, sin servidor propio.",
    ],
    results: [
      { value: "3 monedas", label: "Bs (BCV), USD y USDT en un tablero" },
      { value: "RLS", label: "seguridad por fila: cada usuario ve solo lo suyo" },
      { value: "0", label: "servidores que mantener (Supabase + Vercel)" },
    ],
    learnings: [
      "Modelar seguridad por fila (RLS) en Postgres para aislar datos por usuario sin backend propio.",
      "Enviar una app útil con Vue estático + Supabase, minimizando costo y mantenimiento.",
    ],
    technologies: ["Vue", "Supabase", "PostgreSQL", "Vercel"],
    role: "Desarrollador Full-Stack",
    duration: "2026",
    image: "/images/projects/ControlDeudasProject.png",
    category: "Full-Stack",
    featured: true,
  },
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
    image: "/images/projects/ClinicaOdontologica.png",
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
    image: "/images/projects/ServicioComunitario.png",
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
    image: "/images/projects/RecomendadorLibrosIA.png",
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
    image: "/images/projects/BotsProcesamiento.png",
    category: "Automatización",
    featured: false,
  },
];

export const featuredProjects = projects.filter((p) => p.featured);

export function getProject(slug: string): Project | undefined {
  return projects.find((p) => p.slug === slug);
}
