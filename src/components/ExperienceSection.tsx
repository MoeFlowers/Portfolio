import { FiDownload } from "react-icons/fi";
import { experience } from "@/data/experience";
import { languages, toolGroups } from "@/data/tools";
import { cvFiles } from "@/data/site";
import SectionHeading from "./ui/SectionHeading";

export default function ExperienceSection() {
  return (
    <section id="experience" aria-labelledby="experience-title" className="mx-auto max-w-6xl px-5 py-24 sm:px-8 md:py-32">
      <SectionHeading
        id="experience-title"
        title="Experiencia"
        note={
          <span className="flex flex-wrap items-center gap-x-5 gap-y-2">
            <span>Currículum en PDF:</span>
            {cvFiles.map((cv) => (
              <a key={cv.href} href={cv.href} download className="link-underline inline-flex items-center gap-1.5 text-ink">
                <FiDownload aria-hidden="true" className="h-4 w-4" />
                {cv.label}
              </a>
            ))}
          </span>
        }
      />

      <ol className="mt-4">
        {experience.map((item) => (
          <li key={`${item.period}-${item.title}`} className="border-b border-line py-8">
            <div className="grid gap-x-10 gap-y-2 md:grid-cols-12">
              <p className="tabular text-[0.95rem] text-ink-3 md:col-span-3 md:pt-1">{item.period}</p>
              <div className="md:col-span-9">
                <h3 className="text-[1.2rem] font-semibold text-ink">
                  {item.title}
                  {item.type === "educacion" && <span className="sr-only"> (formación)</span>}
                </h3>
                <p className="text-ink-2">{item.org}</p>
                {item.points.length > 0 && (
                  <ul className="mt-4 max-w-[62ch] list-disc space-y-1.5 pl-5 text-[0.98rem] text-ink-2 marker:text-ink-3">
                    {item.points.map((point) => (
                      <li key={point}>{point}</li>
                    ))}
                  </ul>
                )}
              </div>
            </div>
          </li>
        ))}
      </ol>

      <div className="mt-24 grid gap-x-10 gap-y-8 md:grid-cols-12">
        <h2 id="tools-title" className="wide text-[1.6rem] font-bold leading-none text-ink md:col-span-3">
          Herramientas
        </h2>
        <dl aria-labelledby="tools-title" className="md:col-span-9">
          {toolGroups.map((g) => (
            <div key={g.area} className="grid gap-x-8 gap-y-1 border-b border-line py-4 first:pt-0 sm:grid-cols-[11rem_1fr]">
              <dt className="text-[0.95rem] text-ink-3">{g.area}</dt>
              <dd className="text-ink">{g.items}</dd>
            </div>
          ))}
          <div className="grid gap-x-8 gap-y-1 py-4 sm:grid-cols-[11rem_1fr]">
            <dt className="text-[0.95rem] text-ink-3">Idiomas</dt>
            <dd className="text-ink">{languages.map((l) => `${l.name} (${l.level})`).join(" · ")}</dd>
          </div>
        </dl>
      </div>
    </section>
  );
}
