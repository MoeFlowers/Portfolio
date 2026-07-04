import { FiGithub, FiLinkedin, FiMail } from "react-icons/fi";
import { site } from "@/data/site";

const navLinks = [
  { href: "#skills", label: "Habilidades" },
  { href: "#experience", label: "Experiencia" },
  { href: "#projects", label: "Proyectos" },
  { href: "#contact", label: "Contacto" },
];

export default function Footer() {
  return (
    <footer className="border-t border-zinc-200 dark:border-white/10">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="flex flex-col md:flex-row items-center justify-between gap-6">
          <div className="text-center md:text-left">
            <p className="font-display text-lg font-bold text-zinc-900 dark:text-white">
              moisesflores<span className="text-accent">.dev</span>
            </p>
            <p className="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              {site.role} · {site.location}
            </p>
          </div>

          <nav aria-label="Pie de página">
            <ul className="flex flex-wrap justify-center gap-x-6 gap-y-2">
              {navLinks.map(({ href, label }) => (
                <li key={href}>
                  <a
                    href={href}
                    className="text-sm text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft transition-colors"
                  >
                    {label}
                  </a>
                </li>
              ))}
            </ul>
          </nav>

          <div className="flex items-center gap-2">
            <a
              href={site.github}
              target="_blank"
              rel="noopener noreferrer"
              aria-label="GitHub"
              className="p-2 rounded-full text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
            >
              <FiGithub className="w-5 h-5" />
            </a>
            <a
              href={site.linkedin}
              target="_blank"
              rel="noopener noreferrer"
              aria-label="LinkedIn"
              className="p-2 rounded-full text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
            >
              <FiLinkedin className="w-5 h-5" />
            </a>
            <a
              href={`mailto:${site.email}`}
              aria-label="Enviar email"
              className="p-2 rounded-full text-zinc-600 dark:text-zinc-400 hover:text-accent dark:hover:text-accent-soft hover:bg-zinc-100 dark:hover:bg-white/10 transition-colors"
            >
              <FiMail className="w-5 h-5" />
            </a>
          </div>
        </div>

        <div className="mt-10 pt-6 border-t border-zinc-200 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-2">
          <p className="text-xs text-zinc-500 dark:text-zinc-500">
            © {new Date().getFullYear()} {site.name}. Todos los derechos reservados.
          </p>
          <p className="font-mono text-xs text-zinc-500 dark:text-zinc-500">
            Next.js · TypeScript · Tailwind CSS · Vercel
          </p>
        </div>
      </div>
    </footer>
  );
}
