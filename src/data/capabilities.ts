export type Locale = "es" | "en";

const capabilities = [
  {
    id: "web",
    title: { es: "Aplicaciones web", en: "Web applications" },
    detail: { es: "Interfaces y sistemas de gestión para operar desde el navegador.", en: "Interfaces and management systems that run in the browser." },
    skills: ["React", "Next.js", "TypeScript", "Vue", "Svelte", "Tailwind CSS"],
  },
  {
    id: "apis",
    title: { es: "APIs e integraciones", en: "APIs and integrations" },
    detail: { es: "Conecto aplicaciones, valido entradas y documento los contratos de API.", en: "I connect applications, validate inputs and document API contracts." },
    skills: ["Node.js", "Express", "Python", "FastAPI", "PHP", "REST", "SOAP / XML", "OpenAPI"],
  },
  {
    id: "data",
    title: { es: "Datos y permisos", en: "Data and permissions" },
    detail: { es: "Diseño modelos relacionales y reglas que limitan el acceso por usuario o empresa.", en: "I design relational models and access rules that isolate data by user or business." },
    skills: ["PostgreSQL", "Supabase", "RLS", "Drizzle", "MySQL", "PocketBase"],
  },
  {
    id: "automation",
    title: { es: "Automatización", en: "Automation" },
    detail: { es: "Automatizo navegación, reportes y tareas que requieren mover datos entre sistemas.", en: "I automate browser workflows, reporting and tasks that move data between systems." },
    skills: ["Python", "Playwright", "pandas", "n8n"],
  },
  {
    id: "delivery",
    title: { es: "Pruebas y entrega", en: "Testing and delivery" },
    detail: { es: "Pruebo componentes, integraciones y flujos completos antes de desplegar.", en: "I test components, integrations and complete workflows before deployment." },
    skills: ["Vitest", "Playwright", "pytest", "GitHub Actions", "Docker", "Vercel"],
  },
  {
    id: "mobile",
    title: { es: "Móvil", en: "Mobile" },
    detail: { es: "Desarrollo aplicaciones Flutter que consumen APIs y comparten datos con la web.", en: "I build Flutter applications that use APIs and share data with web applications." },
    skills: ["Flutter", "Dart", "Riverpod", "BLoC"],
  },
  {
    id: "3d",
    title: { es: "Web 3D", en: "3D on the web" },
    detail: { es: "Modelo escenas en Blender y las llevo al navegador con interacción en tiempo real.", en: "I model scenes in Blender and bring them to the browser with real-time interaction." },
    skills: ["Blender", "Three.js", "React Three Fiber"],
  },
];

export function getCapabilities(locale: Locale) {
  return capabilities.map(({ id, title, detail, skills }) => ({
    id,
    title: title[locale],
    description: detail[locale],
    skills,
  }));
}
