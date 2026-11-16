const fs = require("fs");
const path = require("path");

/* Contenido */

const contact = {
  email: "moeflowers2@gmail.com",
  location: "Yaracuy, Venezuela",
  portfolio: "portfolio-eosin-theta-40.vercel.app",
  github: "github.com/MoeFlowers",
  linkedin: "in/moeflowers-dev",
};

const data = {
  es: {
    lang: "es",
    title: "Moises Flores · CV",
    name: "Moises Flores",
    role: "Desarrollador full-stack · Aplicaciones web, APIs y automatización",
    location: "Yaracuy, Venezuela",
    labels: { profile: "Perfil", experience: "Experiencia laboral", education: "Formación", skills: "Habilidades", langs: "Idiomas" },
    summary:
      "Ingeniero en Sistemas y desarrollador full-stack. Desarrollo aplicaciones web y móviles, integro servicios y automatizo procesos. Trabajo en un equipo remoto del sector salud y construyo sistemas de gestión, inventario y facturación para negocios.",
    experience: [
      {
        org: "Sector Salud · Remoto",
        dates: "Ene 2026 - Actualidad",
        title: "Desarrollador de Software",
        bullets: [
          "Diseñé y mantengo aplicaciones web y móviles para una empresa internacional del sector salud, incluidas apps móviles con Flutter.",
          "Colaboro en un equipo internacional distribuido (100% remoto) usando Git, revisión de código e integración continua para lograr despliegues frecuentes y estables.",
        ],
      },
      {
        org: "Freelance · Remoto",
        dates: "2024 - Actualidad",
        title: "Desarrollador Full-Stack",
        bullets: [
          "Desarrollo sistemas de gestión, inventario y facturación, y apps de finanzas con Supabase y Flutter, adaptados a los procesos reales de cada negocio.",
          "Implemento pruebas automatizadas, integración continua (CI) y documentación técnica para entregas confiables y fáciles de mantener.",
        ],
      },
      {
        org: "Corpoelec · Área ATIT · Yaracuy, VE",
        dates: "Ago 2025 - Ene 2026",
        title: "Pasante de TI",
        bullets: [
          "Brindé soporte técnico de hardware y software: instalación y mantenimiento preventivo y correctivo de equipos y estaciones de trabajo.",
          "Diagnostiqué y resolví errores de software de usuarios finales, asegurando la continuidad operativa del área.",
          "Administré redes y servidores, incluida la configuración de proxy y la conectividad.",
          "Apoyé la automatización de procesos internos de TI.",
        ],
      },
      {
        org: "Proyecto de Servicio Comunitario · UNEFA · Yaracuy, VE",
        dates: "Ene 2024 - Jun 2024",
        title: "Líder Técnico",
        bullets: [
          "Lideré el desarrollo de una aplicación comunitaria, desde el modelado de datos hasta el despliegue y la capacitación.",
          "Configuré CI/CD con GitHub Actions y despliegues semanales sin interrupciones del servicio.",
        ],
      },
    ],
    education: [{ org: "UNEFA · Yaracuy, VE", dates: "Ago 2026", title: "Ingeniero en Sistemas", text: "Graduado." }],
    skills: [
      ["Aplicaciones web", "React, Next.js, TypeScript, Vue, Svelte"],
      ["APIs e integraciones", "Node.js, Express, Python, FastAPI, PHP, REST, SOAP/XML"],
      ["Datos y permisos", "PostgreSQL, MySQL, Supabase, Drizzle, seguridad por fila"],
      ["Automatización", "Python, Playwright, pandas, n8n"],
      ["Pruebas y entrega", "Vitest, Playwright, pytest, GitHub Actions, Docker, Vercel"],
      ["Móvil y web 3D", "Flutter, Dart, Three.js, React Three Fiber, Blender"],
    ],
    languages: [
      ["Español", "Nativo"],
      ["Inglés", "Autodidacta. Mayor facilidad para leer y escuchar. Conversación a ritmo pausado."],
    ],
  },

  en: {
    lang: "en",
    title: "Moises Flores · CV",
    name: "Moises Flores",
    role: "Full-stack developer · Web applications, APIs and automation",
    location: "Yaracuy, Venezuela",
    labels: { profile: "Profile", experience: "Work experience", education: "Education", skills: "Skills", langs: "Languages" },
    summary:
      "Systems Engineer and full-stack developer. I build web and mobile applications, integrate services and automate workflows. I work on a remote healthcare team and build management, inventory and billing software for businesses.",
    experience: [
      {
        org: "Healthcare Sector · Remote",
        dates: "Jan 2026 - Present",
        title: "Software Developer",
        bullets: [
          "Designed and maintain web and mobile applications for an international healthcare company, including mobile apps with Flutter.",
          "Collaborate in a distributed international team (100% remote) using Git, code review and continuous integration to achieve frequent, stable deployments.",
        ],
      },
      {
        org: "Freelance · Remote",
        dates: "2024 - Present",
        title: "Full-Stack Developer",
        bullets: [
          "Build management, inventory and billing systems, and finance apps with Supabase and Flutter, tailored to each client's real processes.",
          "Implement automated tests, continuous integration (CI) and technical documentation for reliable, maintainable releases.",
        ],
      },
      {
        org: "Corpoelec · ATIT Area · Yaracuy, VE",
        dates: "Aug 2025 - Jan 2026",
        title: "IT Intern",
        bullets: [
          "Provided hardware and software support: installation and preventive and corrective maintenance of equipment and workstations.",
          "Diagnosed and resolved end-user software issues, keeping the area's operations running.",
          "Administered networks and servers, including proxy configuration and connectivity.",
          "Supported the automation of internal IT processes.",
        ],
      },
      {
        org: "Community Service Project · UNEFA · Yaracuy, VE",
        dates: "Jan 2024 - Jun 2024",
        title: "Technical Lead",
        bullets: [
          "Led development of a community application, from data modeling to deployment and administrator training.",
          "Set up CI/CD with GitHub Actions and weekly deployments with no service downtime.",
        ],
      },
    ],
    education: [{ org: "UNEFA · Yaracuy, VE", dates: "Aug 2026", title: "Systems Engineer", text: "Graduated." }],
    skills: [
      ["Web applications", "React, Next.js, TypeScript, Vue, Svelte"],
      ["APIs and integrations", "Node.js, Express, Python, FastAPI, PHP, REST, SOAP/XML"],
      ["Data and permissions", "PostgreSQL, MySQL, Supabase, Drizzle, row-level security"],
      ["Automation", "Python, Playwright, pandas, n8n"],
      ["Testing and delivery", "Vitest, Playwright, pytest, GitHub Actions, Docker, Vercel"],
      ["Mobile and 3D web", "Flutter, Dart, Three.js, React Three Fiber, Blender"],
    ],
    languages: [
      ["Spanish", "Native"],
      ["English", "Self-taught. Strongest in reading and listening. Comfortable speaking at a measured pace."],
    ],
  },
};

/* Plantilla */

const section = (label, body) => `<section class="sec">
  <h2 class="sec-h"><span class="sq"></span>${label}</h2>
  ${body}
</section>`;

// Orden del HTML = orden en que lo lee un ATS: puesto, empresa, fechas y viñetas.
// La rejilla lo coloca igual que la plantilla (empresa y fechas a la izquierda).
const entry = (e) => `<div class="row">
  <h3 class="job">${e.title}&nbsp;</h3>
  <div class="l"><div class="org">${e.org}</div><div class="dates">${e.dates}</div></div>
  <div class="body">${e.bullets ? e.bullets.map((b) => `<p class="b">• ${b}</p>`).join("") : `<p class="txt">${e.text}</p>`}</div>
</div>`;

function render(d) {
  const skills = d.skills.map(([k, v]) => `<div class="kv"><span class="k">${k}</span><span class="v">${v}</span></div>`).join("");
  const langs = d.languages.map(([k, v]) => `<div class="kv"><span class="k">${k}</span><span class="v">${v}</span></div>`).join("");

  return `<!doctype html>
<html lang="${d.lang}">
<head>
<meta charset="utf-8">
<title>${d.title}</title>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<style>
  @page{size:letter;margin:0.45in 0.6in 0.4in;}
  :root{--ink:#16181d;--body:#2f333b;--muted:#5d6370;--line:#d9dce2;--accent:#a91e2a;}
  *{margin:0;padding:0;box-sizing:border-box}
  html,body{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
  body{font-family:Arial,sans-serif;color:var(--body);font-size:9.6px;line-height:1.5;}

  .name{font-family:Arial,sans-serif;font-weight:800;font-size:27px;letter-spacing:.6px;color:var(--ink);text-transform:uppercase;line-height:1.1;}
  .role{font-size:11.5px;color:var(--body);margin-top:4px;}
  .contact{margin-top:11px;display:grid;grid-template-columns:auto auto;justify-content:start;column-gap:20px;row-gap:2px;font-size:9.4px;color:var(--body);}
  .contact b{font-weight:700;color:var(--ink);}

  .sec{margin-top:13px;}
  .sec-h{display:flex;align-items:center;gap:13px;font-size:11.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--ink);margin-bottom:7px;break-after:avoid;}
  .sq{width:16px;height:16px;background:var(--accent);flex:0 0 auto;}
  .summary{font-size:9.4px;line-height:1.52;}

  .row{display:grid;grid-template-columns:30% 70%;grid-template-areas:"l job" "l body";break-inside:avoid;}
  .row + .row .job, .row + .row .l{padding-top:8px;}
  .job{grid-area:job;font-size:11px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:var(--ink);margin-bottom:5px;}
  .l{grid-area:l;padding-right:18px;}
  .body{grid-area:body;border-bottom:1px solid var(--line);padding-bottom:8px;}
  .org{font-weight:700;color:var(--ink);font-size:9.6px;line-height:1.4;}
  .dates{font-style:italic;color:var(--muted);font-size:9.2px;margin-top:2px;}
  .b{padding-left:10px;text-indent:-8px;font-size:9.2px;line-height:1.47;margin-bottom:1px;}
  .txt{font-size:9.4px;}

  .kv{display:grid;grid-template-columns:30% 70%;font-size:9.2px;line-height:1.47;}
  .kv .k{font-weight:700;color:var(--ink);padding-right:18px;}
</style>
</head>
<body>
  <header>
    <h1 class="name">${d.name}</h1>
    <div class="role">${d.role}</div>
    <div class="contact">
      <span>${contact.email}</span><span>${d.lang === "en" ? "Remote · UTC−4" : "Remoto · UTC−4"}</span>
      <span>${d.location}</span><span>${contact.portfolio}</span>
      <span><b>GitHub:</b> ${contact.github}</span><span><b>LinkedIn:</b> ${contact.linkedin}</span>
    </div>
  </header>

  ${section(d.labels.profile, `<p class="summary">${d.summary}</p>`)}
  ${section(d.labels.experience, d.experience.map(entry).join(""))}
  ${section(d.labels.education, d.education.map(entry).join(""))}
  ${section(d.labels.skills, skills)}
  ${section(d.labels.langs, langs)}
</body>
</html>`;
}

const outDir = __dirname;
fs.writeFileSync(path.join(outDir, "cv-es.html"), render(data.es));
fs.writeFileSync(path.join(outDir, "cv-en.html"), render(data.en));
console.log("HTML generado: cv-es.html, cv-en.html");
