import Image from "next/image";
import Link from "next/link";
import { site } from "@/data/site";

const now = [
  { text: "Desarrollador de software en el sector salud, para una empresa internacional, en remoto." },
  { text: "Mostrador: caja e inventario por gramo para una carnicería con frutería.", href: "/projects/mostrador" },
  { text: "Vitrina: catálogos en línea con pedidos por WhatsApp.", href: "/projects/vitrina" },
];

export default function HeroSection() {
  return (
    <section aria-labelledby="hero-title" className="mx-auto max-w-6xl px-5 pb-20 pt-14 sm:px-8 md:pb-28 md:pt-20">
      <div className="grid items-end gap-12 md:grid-cols-12">
        <div className="md:col-span-8">
          <h1
            id="hero-title"
            className="rise wide text-[clamp(3.4rem,10.5vw,8.25rem)] font-extrabold leading-[0.88] tracking-[-0.035em] text-ink"
          >
            Moises
            <br />
            Flores
          </h1>
          <p style={{ animationDelay: "90ms" }} className="rise mt-9 max-w-[34ch] text-[clamp(1.2rem,2.1vw,1.5rem)] leading-snug text-ink">
            Ingeniero en sistemas y desarrollador full-stack. Construyo sistemas de caja, inventario y catálogos para
            comercios, aplicaciones web y móviles, y escenas 3D que corren en el navegador.
          </p>

          <div style={{ animationDelay: "180ms" }} className="rise mt-10 max-w-xl border-t border-line pt-5">
            <p className="text-[0.95rem] text-ink-3">Ahora</p>
            <ul className="mt-2 space-y-2 text-ink-2">
              {now.map((item) => (
                <li key={item.text}>
                  {item.href ? (
                    <Link href={item.href} className="link-underline">
                      {item.text}
                    </Link>
                  ) : (
                    item.text
                  )}
                </li>
              ))}
            </ul>
          </div>

          <div style={{ animationDelay: "260ms" }} className="rise mt-10 flex flex-wrap gap-3">
            <a
              href="#work"
              className="inline-flex min-h-12 items-center rounded-[6px] bg-ink px-6 text-[0.98rem] font-medium text-paper transition-opacity hover:opacity-85"
            >
              Ver proyectos
            </a>
            <a
              href="#contact"
              className="inline-flex min-h-12 items-center rounded-[6px] border border-control px-6 text-[0.98rem] font-medium text-ink transition-colors hover:border-ink"
            >
              Escribirme
            </a>
          </div>
        </div>

        <figure style={{ animationDelay: "140ms" }} className="rise md:col-span-4">
          <div className="relative mx-auto aspect-[4/5] w-full max-w-[22rem] overflow-hidden rounded-[6px] bg-sunk md:max-w-none">
            <Image
              src="/images/retrato.webp"
              alt="Retrato de Moises Flores"
              fill
              priority
              sizes="(max-width: 768px) 22rem, 33vw"
              className="object-cover"
            />
          </div>
          <figcaption className="mt-3 text-center text-[0.95rem] text-ink-3 md:text-left">{site.location}</figcaption>
        </figure>
      </div>
    </section>
  );
}
