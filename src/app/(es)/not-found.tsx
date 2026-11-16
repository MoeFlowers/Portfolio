import Link from "next/link";

export default function NotFound() {
  return (
    <main className="min-h-screen flex flex-col items-center justify-center px-4 text-center">
      <p className="font-mono text-sm uppercase tracking-[0.25em] text-accent mb-4">Error 404</p>
      <h1 className="font-display text-4xl sm:text-5xl font-bold text-zinc-900 dark:text-white mb-4">
        Esta página no existe
      </h1>
      <p className="text-lg text-zinc-600 dark:text-zinc-400 mb-8 max-w-md">
        El enlace puede estar roto o la página fue movida.
      </p>
      <Link
        href="/"
        className="px-7 py-3 text-sm font-semibold rounded-full bg-accent text-white hover:bg-accent-hover transition-colors duration-200"
      >
        Volver al inicio
      </Link>
    </main>
  );
}
