# Portafolio de Moisés Flores

Portafolio de desarrollo full-stack con un perfil profesional bilingüe, una página de servicios y seis casos de estudio.

**En vivo:** https://portfolio-eosin-theta-40.vercel.app/

## Stack

- [Next.js 15](https://nextjs.org/) (App Router) + React 19 + TypeScript
- [Tailwind CSS v4](https://tailwindcss.com/) (tokens en `src/app/globals.css`)
- [Framer Motion](https://www.framer.com/motion/) para el estudio 3D, con alternativa estática
- [next-themes](https://github.com/pacocoursey/next-themes) para el modo claro y oscuro
- [Resend](https://resend.com/) para el formulario de contacto
- [Vercel Analytics](https://vercel.com/analytics) para métricas de visitas
- GitHub Actions ejecuta lint, pruebas y build en los cambios sobre main

## Desarrollo

```bash
npm install
npm run dev      # http://localhost:3000
npm run lint
npm test
npm run build
```

## Variables de entorno

Usa Node.js 22 y configura las variables necesarias en `.env.local`.

| Variable | Requerida | Descripción |
|---|---|---|
| `RESEND_API_KEY` | Para el formulario | API key de Resend. Sin ella, el formulario muestra el email directo como alternativa. |
| `CONTACT_FROM_EMAIL` | No | Remitente verificado en Resend (default: `onboarding@resend.dev`). |
| `CONTACT_TO_EMAIL` | No | Destinatario de los mensajes (default: email personal). |
| `NEXT_PUBLIC_PORTFOLIO_CUSTOM_EVENTS` | No | Establece `true` solo si el plan de Vercel incluye eventos personalizados. |

En producción, configúralas en **Vercel → Project → Settings → Environment Variables**.

## Estructura

```
src/
├── app/                  # Páginas ES/EN, casos, API de contacto, sitemap, robots y Open Graph
├── components/           # Secciones de la página + ui/ (primitivos reutilizables)
└── data/                 # Contenido: proyectos (case studies), skills, experiencia, config del sitio
```

Los casos, su índice y el sitemap leen `src/data/projects.ts`. El [plan de captación](docs/captacion.md) reúne enlaces y borradores para compartir el perfil.

## Proyectos incluidos (ramas de este repo)

| Rama | Proyecto | Demo |
|---|---|---|
| `clinica` | Sistema web para clínica odontológica | [Ver](https://dental-clinic-web-system.vercel.app/) |
| `comunity` | Plataforma de servicio comunitario (2.000+ usuarios) | [Ver](https://comunity.vercel.app/) |
| `book` | Recomendador de libros con IA | [Ver](https://book-recommendation-ai.vercel.app/) |
| `bot` | Bots de procesamiento de datos | [Ver](https://data-processing-bots.vercel.app/) |
