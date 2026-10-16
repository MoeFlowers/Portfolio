"use client";
import { useState } from "react";
import { FiArrowUpRight } from "react-icons/fi";
import { site } from "@/data/site";
import SectionHeading from "./ui/SectionHeading";

type Status = "idle" | "submitting" | "success" | "error";

const channels = [
  { label: "WhatsApp", value: site.whatsappLabel, href: site.whatsapp, external: true },
  { label: "LinkedIn", value: "moeflowers-dev", href: site.linkedin, external: true },
  { label: "GitHub", value: "MoeFlowers", href: site.github, external: true },
];

export default function ContactForm() {
  const [formData, setFormData] = useState({ name: "", email: "", message: "", company: "" });
  const [status, setStatus] = useState<Status>("idle");
  const [errorMessage, setErrorMessage] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setStatus("submitting");
    setErrorMessage("");

    try {
      const res = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      if (!res.ok) {
        const data = (await res.json().catch(() => null)) as { error?: string } | null;
        throw new Error(data?.error ?? "No se pudo enviar el mensaje.");
      }

      setStatus("success");
      setFormData({ name: "", email: "", message: "", company: "" });
    } catch (err) {
      setStatus("error");
      setErrorMessage(err instanceof Error ? err.message : "No se pudo enviar el mensaje.");
    }
  };

  const field =
    "mt-2 block w-full rounded-[6px] border border-control bg-paper px-4 py-3 text-ink placeholder:text-ink-3 transition-colors focus:border-ink focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent";

  return (
    <section id="contact" aria-labelledby="contact-title" className="mx-auto max-w-6xl px-5 py-24 sm:px-8 md:py-32">
      <SectionHeading id="contact-title" title="Contacto" note="Trabajo en remoto desde Venezuela (UTC-4)." />

      <div className="mt-12">
        <p className="text-ink-2">Escríbeme al correo</p>
        <a
          href={`mailto:${site.email}`}
          className="wide mt-2 inline-block break-all text-[clamp(1.6rem,5.2vw,3.6rem)] font-bold leading-tight text-ink underline decoration-control decoration-2 underline-offset-[0.18em] transition-colors hover:decoration-accent"
        >
          {site.email}
        </a>
      </div>

      <ul className="mt-12 grid border-t border-line sm:grid-cols-3">
        {channels.map((c) => (
          <li key={c.label} className="border-b border-line sm:border-b-0 sm:border-r sm:last:border-r-0">
            <a
              href={c.href}
              target="_blank"
              rel="noopener noreferrer"
              className="group flex min-h-11 items-center justify-between gap-3 py-5 sm:px-5 sm:first:pl-0"
            >
              <span>
                <span className="block text-[0.92rem] text-ink-3">{c.label}</span>
                <span className="text-ink group-hover:text-accent transition-colors">{c.value}</span>
              </span>
              <FiArrowUpRight aria-hidden="true" className="h-5 w-5 text-ink-3" />
              <span className="sr-only">(se abre en otra pestaña)</span>
            </a>
          </li>
        ))}
      </ul>

      <div className="mt-20 grid gap-10 md:grid-cols-12">
        <div className="md:col-span-4">
          <h3 className="text-[1.2rem] font-semibold text-ink">O déjame un mensaje</h3>
          <p className="mt-2 text-ink-2">Te respondo al correo que escribas.</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-5 md:col-span-8">
          {/* Campo trampa para bots: oculto para personas */}
          <div className="absolute -m-px h-px w-px overflow-hidden" aria-hidden="true">
            <label htmlFor="company">No completar este campo</label>
            <input
              type="text"
              id="company"
              name="company"
              tabIndex={-1}
              autoComplete="off"
              value={formData.company}
              onChange={(e) => setFormData({ ...formData, company: e.target.value })}
            />
          </div>

          <div className="grid gap-5 sm:grid-cols-2">
            <label className="block text-[0.95rem] text-ink-2">
              Nombre
              <input
                type="text"
                name="name"
                autoComplete="name"
                minLength={2}
                maxLength={100}
                required
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className={field}
              />
            </label>
            <label className="block text-[0.95rem] text-ink-2">
              Correo
              <input
                type="email"
                name="email"
                autoComplete="email"
                required
                placeholder="tu@correo.com"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className={field}
              />
            </label>
          </div>
          <label className="block text-[0.95rem] text-ink-2">
            Mensaje
            <textarea
              name="message"
              rows={5}
              minLength={10}
              maxLength={5000}
              required
              placeholder="Qué necesitas y para cuándo"
              value={formData.message}
              onChange={(e) => setFormData({ ...formData, message: e.target.value })}
              className={field}
            />
          </label>

          <div className="flex flex-wrap items-center gap-5">
            <button
              type="submit"
              disabled={status === "submitting"}
              className="inline-flex min-h-12 items-center rounded-[6px] bg-ink px-6 font-medium text-paper transition-opacity hover:opacity-85 disabled:cursor-not-allowed disabled:opacity-60"
            >
              {status === "submitting" ? "Enviando…" : "Enviar mensaje"}
            </button>

            <div aria-live="polite" role="status" className="text-[0.95rem]">
              {status === "success" && <p className="text-ink">Mensaje enviado. Te respondo pronto al correo que dejaste.</p>}
              {status === "error" && (
                <p className="text-ink">
                  <span className="font-semibold">No se envió.</span> {errorMessage} Escríbeme directo a{" "}
                  <a href={`mailto:${site.email}`} className="link-underline">
                    {site.email}
                  </a>
                  .
                </p>
              )}
            </div>
          </div>
        </form>
      </div>
    </section>
  );
}
