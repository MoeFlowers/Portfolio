# Portfolio — Moises Flores

Portfolio personal de desarrollador Full-Stack, con proyectos presentados como casos de estudio (problema → solución → resultados medibles).

**En vivo:** https://portfolio-eosin-theta-40.vercel.app/

## Stack

- [Next.js 15](https://nextjs.org/) (App Router) + React 19 + TypeScript
- [Tailwind CSS v4](https://tailwindcss.com/) (tokens en `src/app/globals.css`)
- [Framer Motion](https://www.framer.com/motion/) — animaciones que respetan `prefers-reduced-motion`
- [next-themes](https://github.com/pacocoursey/next-themes) — modo claro/oscuro con toggle
- [Resend](https://resend.com/) — envío real del formulario de contacto
- [Vercel Analytics](https://vercel.com/analytics) — métricas de visitas
- CI con GitHub Actions (lint + build en cada push/PR)

## Desarrollo

```bash
npm install
npm run dev      # http://localhost:3000
npm run lint
npm run build
```

## Variables de entorno

Copia `.env.example` a `.env.local`:

| Variable | Requerida | Descripción |
|---|---|---|
| `RESEND_API_KEY` | Para el formulario | API key de Resend. Sin ella, el formulario muestra el email directo como alternativa. |
| `CONTACT_FROM_EMAIL` | No | Remitente verificado en Resend (default: `onboarding@resend.dev`). |
| `CONTACT_TO_EMAIL` | No | Destinatario de los mensajes (default: email personal). |

En producción, configúralas en **Vercel → Project → Settings → Environment Variables**.

## Estructura

```
src/
├── app/                  # Rutas: home, /projects/[slug], API contacto, SEO (sitemap, robots, OG)
├── components/           # Secciones de la página + ui/ (primitivos reutilizables)
└── data/                 # Contenido: proyectos (case studies), skills, experiencia, config del sitio
```

Para añadir un proyecto: editar `src/data/projects.ts` (el case study, el sitemap y el grid se generan solos).

## Proyectos incluidos (ramas de este repo)

| Rama | Proyecto | Demo |
|---|---|---|
| `clinica` | Sistema web para clínica odontológica | [Ver](https://dental-clinic-web-system.vercel.app/) |
| `comunity` | Plataforma de servicio comunitario (2.000+ usuarios) | [Ver](https://comunity.vercel.app/) |
| `book` | Recomendador de libros con IA | [Ver](https://book-recommendation-ai.vercel.app/) |
| `bot` | Bots de procesamiento de datos | [Ver](https://data-processing-bots.vercel.app/) |
