export const site = {
  name: "Moises Flores",
  role: "Desarrollador Full-Stack",
  // Cambiar por el dominio propio cuando esté conectado en Vercel
  url: "https://portfolio-eosin-theta-40.vercel.app",
  email: "moeflowers2@gmail.com",
  whatsapp: "https://wa.me/584225594556",
  whatsappLabel: "+58 422-559-45-56",
  github: "https://github.com/MoeFlowers",
  linkedin: "https://www.linkedin.com/in/moeflowers-dev/",
  location: "Yaracuy, Venezuela · Remoto",
  description:
    "Desarrollador full-stack e ingeniero en sistemas. Construyo sistemas de caja, inventario y catálogos para comercios, aplicaciones web y móviles, y escenas 3D que corren en el navegador.",
  keywords: [
    "Desarrollador Full-Stack",
    "Ingeniero en Sistemas",
    "React",
    "Next.js",
    "TypeScript",
    "Python",
    "Flutter",
    "PostgreSQL",
    "Supabase",
    "Three.js",
    "Blender",
    "Automatización",
    "Moises Flores",
  ],
} as const;

export const cvFiles = [
  { href: "/cv-moises-flores-es.pdf", label: "Español" },
  { href: "/cv-moises-flores-en.pdf", label: "English" },
] as const;

// Las usa la versión 3D (/3d). Cada cifra enlaza a lo que la respalda.
export const heroMetrics = [
  { value: "+3", label: "años de experiencia", href: "/#experience" },
  { value: "2.000+", label: "usuarios en la plataforma comunitaria", href: "/projects/servicio-comunitario" },
  { value: "21 h/sem", label: "de trabajo automatizado para clientes", href: "/#experience" },
  { value: "85%", label: "precisión del recomendador ML", href: "/projects/recomendador-libros-ia" },
] as const;
