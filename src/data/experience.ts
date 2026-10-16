// Mismo contenido que el CV (cv-source/build.js); si cambia uno, cambiar el otro.
export interface ExperienceItem {
  period: string;
  title: string;
  org: string;
  points: string[];
  type: "trabajo" | "educacion";
}

export const experience: ExperienceItem[] = [
  {
    period: "Ene 2026 a hoy",
    title: "Desarrollador de software",
    org: "Sector salud, empresa internacional · Remoto",
    points: [
      "Diseño y mantengo aplicaciones web y móviles, incluidas apps en Flutter.",
      "Digitalicé flujos de trabajo clave, reduciendo cerca de un 80% el tiempo de procesos que antes eran manuales y repetitivos.",
      "Trabajo en un equipo internacional 100% remoto con Git, revisión de código e integración continua.",
    ],
    type: "trabajo",
  },
  {
    period: "2024 a hoy",
    title: "Desarrollador full-stack",
    org: "Freelance · Remoto",
    points: [
      "Desarrollo sistemas de gestión, inventario y facturación, y apps de finanzas con Supabase y Flutter, adaptados a los procesos de cada negocio.",
      "Automaticé procesamiento de datos y tareas repetitivas con Python y APIs REST: unas 21 horas semanales de trabajo manual menos entre clientes.",
      "Entrego con pruebas automatizadas, integración continua y documentación técnica.",
    ],
    type: "trabajo",
  },
  {
    period: "Ago 2025 a ene 2026",
    title: "Pasante de TI",
    org: "Corpoelec · Área ATIT · Yaracuy",
    points: [
      "Soporte de hardware y software: instalación y mantenimiento preventivo y correctivo de equipos.",
      "Diagnóstico y solución de errores de software de usuarios finales.",
      "Administración de redes y servidores, incluida la configuración de proxy.",
    ],
    type: "trabajo",
  },
  {
    period: "Ene a jun 2024",
    title: "Líder técnico",
    org: "Proyecto de servicio comunitario · UNEFA",
    points: [
      "Lideré una app web usada por más de 2.000 personas que redujo un 80% el tiempo de consulta de datos.",
      "Despliegues semanales con GitHub Actions, sin interrupciones del servicio.",
    ],
    type: "trabajo",
  },
  {
    period: "2020 a 2026",
    title: "Ingeniero en Sistemas",
    org: "UNEFA · Yaracuy · Título oficial",
    points: [],
    type: "educacion",
  },
];
