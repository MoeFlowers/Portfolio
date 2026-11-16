import { getCapabilities, type Locale } from "@/data/capabilities";

export default function Capabilities({ locale = "es" }: { locale?: Locale }) {
  return (
    <section id="skills" aria-labelledby="skills-title" className="site-section">
      <h2 id="skills-title">{locale === "en" ? "Technical capabilities" : "Capacidades técnicas"}</h2>
      <ul className="capability-list">
        {getCapabilities(locale).map(({ id, title, description, skills }) => (
          <li key={id}>
            <h3>{title}</h3>
            <div>
              <p>{description}</p>
              <p className="stack">{skills.join(" · ")}</p>
            </div>
          </li>
        ))}
      </ul>
    </section>
  );
}
