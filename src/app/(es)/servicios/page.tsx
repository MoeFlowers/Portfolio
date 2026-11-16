import type { Metadata } from "next";
import Link from "next/link";
import RouteContact from "@/components/RouteContact";
import SiteFooter from "@/components/SiteFooter";
import SiteHeader from "@/components/SiteHeader";
import TrackedLink from "@/components/TrackedLink";

const description = "Sistemas de gestión, automatización y sitios web para comercios y equipos en Venezuela.";

export const metadata: Metadata = {
  title: "Servicios web para negocios",
  description,
  alternates: { canonical: "/servicios" },
  openGraph: { title: "Servicios web para negocios · Moisés Flores", description, url: "/servicios", locale: "es_VE", images: ["/social-card"] },
};

const services = [
  { title: "Sistemas para operar tu negocio", text: "Ventas, inventario y facturación en dólares y bolívares, adaptados a cómo trabaja tu equipo.", href: "/projects/punto-de-venta-inventario", link: "Ver caso de punto de venta" },
  { title: "Automatización e integraciones", text: "Conecta sistemas y automatiza tareas como consolidar reportes, actualizar datos o generar avisos.", href: "/projects/bots-procesamiento-datos", link: "Ver caso de automatización" },
  { title: "Webs para presentar y vender", text: "Páginas para explicar tu oferta desde el celular. Si el producto lo necesita, podemos mostrarlo con una experiencia 3D.", href: "/3d", link: "Ver estudio 3D" },
];

export default function ServicesPage() {
  return (
    <>
      <SiteHeader current="services" contactHref="#contact" />
      <main id="main-content" className="site-wrap">
        <section className="site-hero route-hero">
          <div>
            <p className="intro-line">Servicios para negocios</p>
            <h1>Software adaptado a tu operación</h1>
            <p className="lede">Si llevas el inventario en varias hojas o dedicas tiempo a copiar datos, podemos empezar por ese proceso.</p>
            <div className="hero-actions">
              <TrackedLink href="#contact" event="portfolio_contact" targetName="project" className="site-button">Cuéntame tu proyecto</TrackedLink>
            </div>
          </div>
        </section>
        <section className="site-section" aria-labelledby="services-title">
          <h2 id="services-title">En qué puedo ayudarte</h2>
          <ul className="capability-list service-list">
            {services.map(({ title, text, href, link }) => (
              <li key={title}><h3>{title}</h3><div><p>{text}</p><Link href={href} className="text-action">{link}</Link></div></li>
            ))}
          </ul>
        </section>
        <section className="site-section" aria-labelledby="process-title">
          <h2 id="process-title">Cómo trabajamos</h2>
          <p className="lede">Primero revisamos tu proceso y qué necesitas resolver. Definimos el alcance y te muestro una primera versión para probarla con tu equipo.</p>
        </section>
        <RouteContact initialIntent="project" />
      </main>
      <SiteFooter />
    </>
  );
}
