export const site = {
  name: "Moises Flores",
  role: "Desarrollador Full-Stack",
  // Cambiar por el dominio propio cuando esté conectado en Vercel
  url: "https://portfolio-eosin-theta-40.vercel.app",
  email: "moeflowers2@gmail.com",
  github: "https://github.com/MoeFlowers",
  linkedin: "https://www.linkedin.com/in/moeflowers-dev/",
  location: "Venezuela · Remoto",
  description:
    "Desarrollador Full-Stack especializado en React, Next.js y Python. Construyo aplicaciones web, bots y automatizaciones que ahorran horas de trabajo y sirven a miles de usuarios.",
  keywords: [
    "Desarrollador Full-Stack",
    "React",
    "Next.js",
    "TypeScript",
    "Python",
    "Automatización",
    "Bots",
    "Machine Learning",
    "Supabase",
    "Docker",
    "Moises Flores",
  ],
} as const;

// Cada cifra enlaza al proyecto o la experiencia que la respalda
export const heroMetrics = [
  { value: "+3", label: "años de experiencia", href: "/#experience" },
  { value: "2.000+", label: "usuarios en la plataforma comunitaria", href: "/projects/servicio-comunitario" },
  { value: "21 h/sem", label: "de trabajo automatizado para clientes", href: "/#experience" },
  { value: "85%", label: "precisión del recomendador ML", href: "/projects/recomendador-libros-ia" },
] as const;
