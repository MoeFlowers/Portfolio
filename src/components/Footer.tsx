import { site } from "@/data/site";

export default function Footer() {
  return (
    <footer className="border-t border-line">
      <div className="mx-auto flex max-w-6xl flex-col gap-3 px-5 py-10 text-[0.92rem] text-ink-3 sm:flex-row sm:items-center sm:justify-between sm:px-8">
        <p>
          © {new Date().getFullYear()} {site.name}
        </p>
        <p>Hecho con Next.js y Tailwind CSS, tipografía Mona Sans</p>
      </div>
    </footer>
  );
}
