import type { Metadata } from "next";
import Link from "next/link";
import { professional } from "@/data/professional";
import { site } from "@/data/site";
import type { Locale } from "@/data/capabilities";
import Capabilities from "./Capabilities";
import RouteContact from "./RouteContact";
import SiteFooter from "./SiteFooter";
import SiteHeader from "./SiteHeader";
import TrackedLink from "./TrackedLink";

export function professionalMetadata(locale: Locale): Metadata {
  const t = professional[locale];
  const path = locale === "en" ? "/career" : "/perfil";
  return {
    title: t.title,
    description: t.description,
    alternates: { canonical: path, languages: { es: "/perfil", en: "/career", "x-default": "/perfil" } },
    openGraph: { title: `${t.title} · ${site.name}`, description: t.description, url: path, locale: locale === "en" ? "en_US" : "es_VE", images: ["/social-card"] },
  };
}

export default function ProfessionalProfile({ locale }: { locale: Locale }) {
  const en = locale === "en";
  const t = professional[locale];
  return (
    <div lang={locale}>
      <SiteHeader locale={locale} contactHref="#contact" />
      <main id="main-content" className="site-wrap">
        <section className="site-hero route-hero">
          <div>
            <Link className="text-action" href={en ? "/perfil" : "/career"} hrefLang={en ? "es" : "en"} lang={en ? "es" : "en"}>{en ? "Ver en español" : "Read in English"}</Link>
            <h1>{t.title}</h1>
            <p className="lede">{t.description}</p>
            <div className="hero-actions">
              <TrackedLink href={en ? "/cv-moises-flores-en.pdf" : "/cv-moises-flores-es.pdf"} event="portfolio_cv" targetName={en ? "cv-en" : "cv-es"} className="site-button" download>
                {en ? "Download résumé (PDF)" : "Descargar CV (PDF)"}
              </TrackedLink>
              <TrackedLink href="#contact" event="portfolio_contact" targetName="career" className="site-button secondary">{en ? "Discuss a role" : "Hablemos de una oportunidad"}</TrackedLink>
            </div>
          </div>
        </section>
        <section className="site-section" aria-labelledby="experience-title" id="experience">
          <h2 id="experience-title">{en ? "Experience" : "Experiencia"}</h2>
          <ol className="experience-list">
            {t.experience.map((role) => (
              <li key={role.title}>
                <p className="stack">{role.date}</p>
                <div><h3>{role.title}</h3><p className="stack">{role.context}</p><p>{role.summary}</p></div>
              </li>
            ))}
          </ol>
          <p className="profile-note">{t.education}</p>
          <p className="profile-note">{t.languages}</p>
        </section>
        <Capabilities locale={locale} />
        <RouteContact locale={locale} initialIntent="career" />
      </main>
      <SiteFooter locale={locale} />
    </div>
  );
}
