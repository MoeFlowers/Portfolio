const fs = require("fs");
const path = require("path");

/* Contenido */

const contact = {
  email: "moeflowers2@gmail.com",
  phone: "+58 422-559-45-56",
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
    role: "Desarrollador Full-Stack · Automatización & IA",
    location: "Yaracuy, Venezuela",
    labels: { profile: "Perfil", experience: "Experiencia laboral", education: "Formación", skills: "Habilidades", langs: "Idiomas" },
    summary:
      "Desarrollador full-stack orientado a resultados con 3+ años creando aplicaciones web y móviles, bots y automatizaciones que ahorran horas de trabajo y escalan a miles de usuarios. Experiencia en el sector salud (remoto, internacional) y en sistemas de gestión, facturación e inventario para empresas. Domino el ciclo completo de backend y frontend, apps móviles con Flutter y buenas prácticas de ingeniería (pruebas y CI). Comunicación clara en español e inglés.",
    experience: [
      {
        org: "Sector Salud · Remoto",
        dates: "Ene 2026 - Actualidad",
        title: "Desarrollador de Software",
        bullets: [
          "Diseñé y mantengo aplicaciones web y móviles para una empresa internacional del sector salud, incluidas apps móviles con Flutter.",
          "Digitalicé flujos clave de trabajo, reduciendo en ~80% el tiempo dedicado a procesos antes manuales y repetitivos.",
          "Colaboro en un equipo internacional distribuido (100% remoto) usando Git, revisión de código e integración continua para lograr despliegues frecuentes y estables.",
        ],
      },
      {
        org: "Freelance · Remoto",
        dates: "2024 - Actualidad",
        title: "Desarrollador Full-Stack",
        bullets: [
          "Desarrollo sistemas de gestión, inventario y facturación, y apps de finanzas con Supabase y Flutter, adaptados a los procesos reales de cada negocio.",
          "Automaticé el procesamiento de grandes volúmenes de datos y flujos repetitivos con Python y APIs REST, eliminando unas 21 h/semana de trabajo manual entre clientes.",
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
          "Lideré el desarrollo end-to-end de una app web usada por más de 2.000 habitantes, reduciendo un 80% el tiempo de consulta de datos.",
          "Desarrollé un CRUD seguro en PHP/MySQL con interfaz responsive, que obtuvo un 97% de satisfacción de los usuarios.",
          "Configuré CI/CD con GitHub Actions y despliegues semanales sin interrupciones del servicio.",
        ],
      },
    ],
    education: [{ org: "UNEFA · Yaracuy, VE", dates: "2020 - 2026", title: "Ingeniero en Sistemas", text: "Graduado (título oficial)." }],
    skills: [
      ["Lenguajes", "Python, PHP, JavaScript, TypeScript, HTML5, CSS3"],
      ["Frontend", "React, Next.js, Vue, Tailwind CSS"],
      ["Móvil", "Flutter (Dart)"],
      ["Backend y APIs", "FastAPI, Flask, Node.js, REST y GraphQL"],
      ["Bases de datos", "MySQL, PostgreSQL, Supabase, MongoDB, Firebase"],
      ["DevOps y herramientas", "Git/GitHub, CI/CD (GitHub Actions), Docker, Vercel"],
      ["Automatización e IA", "Bots en Python, automatización de WhatsApp, ML (Scikit-learn, Pandas)"],
    ],
    languages: [
      ["Español", "Nativo"],
      ["Inglés", "B2 / C1"],
    ],
  },

  en: {
    lang: "en",
    title: "Moises Flores · CV",
    name: "Moises Flores",
    role: "Full-Stack Developer · Automation & AI",
    location: "Yaracuy, Venezuela",
    labels: { profile: "Profile", experience: "Work experience", education: "Education", skills: "Skills", langs: "Languages" },
    summary:
      "Results-driven full-stack developer with 3+ years building web and mobile applications, bots and automations that save hours of work and scale to thousands of users. Experience in the healthcare sector (remote, international) and in management, billing and inventory systems for businesses. I own the full backend and frontend cycle, build mobile apps with Flutter, and follow solid engineering practices (tests and CI). Clear communication in Spanish and English.",
    experience: [
      {
        org: "Healthcare Sector · Remote",
        dates: "Jan 2026 - Present",
        title: "Software Developer",
        bullets: [
          "Designed and maintain web and mobile applications for an international healthcare company, including mobile apps with Flutter.",
          "Digitized key workflows, cutting the time spent on formerly manual, repetitive processes by ~80%.",
          "Collaborate in a distributed international team (100% remote) using Git, code review and continuous integration to achieve frequent, stable deployments.",
        ],
      },
      {
        org: "Freelance · Remote",
        dates: "2024 - Present",
        title: "Full-Stack Developer",
        bullets: [
          "Build management, inventory and billing systems, and finance apps with Supabase and Flutter, tailored to each client's real processes.",
          "Automated the processing of large data volumes and repetitive workflows with Python and REST APIs, removing about 21 h/week of manual work across clients.",
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
          "Led end-to-end development of a web app used by more than 2,000 residents, cutting data-lookup time by 80%.",
          "Built a secure CRUD in PHP/MySQL with a responsive UI that reached 97% user satisfaction.",
          "Set up CI/CD with GitHub Actions and weekly deployments with no service downtime.",
        ],
      },
    ],
    education: [{ org: "UNEFA · Yaracuy, VE", dates: "2020 - 2026", title: "Systems Engineer", text: "Graduated (official degree)." }],
    skills: [
      ["Languages", "Python, PHP, JavaScript, TypeScript, HTML5, CSS3"],
      ["Frontend", "React, Next.js, Vue, Tailwind CSS"],
      ["Mobile", "Flutter (Dart)"],
      ["Backend & APIs", "FastAPI, Flask, Node.js, REST & GraphQL"],
      ["Databases", "MySQL, PostgreSQL, Supabase, MongoDB, Firebase"],
      ["DevOps & tools", "Git/GitHub, CI/CD (GitHub Actions), Docker, Vercel"],
      ["Automation & AI", "Python bots, WhatsApp automation, ML (Scikit-learn, Pandas)"],
    ],
    languages: [
      ["Spanish", "Native"],
      ["English", "B2 / C1"],
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Rubik:wght@700;800&display=swap" rel="stylesheet">
<style>
  @page{size:letter;margin:0.45in 0.6in 0.4in;}
  :root{--ink:#16181d;--body:#2f333b;--muted:#5d6370;--line:#d9dce2;--accent:#1f3fbf;}
  *{margin:0;padding:0;box-sizing:border-box}
  html,body{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
  body{font-family:'IBM Plex Sans',Arial,sans-serif;color:var(--body);font-size:9.6px;line-height:1.5;}

  .name{font-family:'Rubik',Arial,sans-serif;font-weight:800;font-size:27px;letter-spacing:.6px;color:var(--ink);text-transform:uppercase;line-height:1.1;}
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
      <span>${contact.email}</span><span>${contact.phone}</span>
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
