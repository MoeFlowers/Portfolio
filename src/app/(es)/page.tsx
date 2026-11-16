import Image from "next/image";
import type { Metadata } from "next";
import Capabilities from "@/components/Capabilities";
import FeaturedCases from "@/components/FeaturedCases";
import RouteContact from "@/components/RouteContact";
import SiteFooter from "@/components/SiteFooter";
import SiteHeader from "@/components/SiteHeader";
import TrackedLink from "@/components/TrackedLink";

export const metadata: Metadata = { alternates: { canonical: "/" } };

export default function Home() {
  return (
    <>
      <SiteHeader />
      <main id="main-content" className="site-wrap">
        <section className="site-hero">
          <div>
            <p className="intro-line">Ingeniero en sistemas · Venezuela (UTC−4)</p>
            <h1>Software de negocio y automatización</h1>
            <p className="lede">Desarrollo aplicaciones web, conecto servicios y automatizo los procesos que hoy haces a mano.</p>
            <div className="hero-actions" id="experience">
              <TrackedLink href="/perfil" event="portfolio_route" targetName="career" className="site-button">Ver perfil profesional</TrackedLink>
              <TrackedLink href="/servicios" event="portfolio_route" targetName="project" className="site-button secondary">Ver servicios</TrackedLink>
            </div>
          </div>
          <figure className="portrait">
            <Image src="/images/profilephoto.jpg" alt="Retrato de Moisés Flores" fill sizes="(min-width: 860px) 320px, 144px" priority />
          </figure>
        </section>
        <Capabilities />
        <FeaturedCases />
        <RouteContact />
      </main>
      <SiteFooter />
    </>
  );
}
