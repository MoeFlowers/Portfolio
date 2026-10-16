import Link from "next/link";

export default function NotFound() {
  return (
    <main className="mx-auto flex min-h-screen max-w-6xl flex-col justify-center px-5 sm:px-8">
      <p className="tabular text-ink-3">Error 404</p>
      <h1 className="wide mt-3 text-[clamp(2.4rem,7vw,5rem)] font-extrabold leading-[0.92] text-ink">
        Esta página no existe
      </h1>
      <p className="mt-6 max-w-md text-lg text-ink-2">El enlace está roto o la página cambió de dirección.</p>
      <div className="mt-8 flex flex-wrap gap-x-6 gap-y-3">
        <Link href="/" className="link-underline text-ink">
          Ir al inicio
        </Link>
        <Link href="/#projects" className="link-underline text-ink">
          Ver todos los proyectos
        </Link>
      </div>
    </main>
  );
}
