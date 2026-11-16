"use client";

import { useState, type FormEvent } from "react";
import { trackPortfolioEvent } from "@/lib/analytics";
import { site } from "@/data/site";
import type { Locale } from "@/data/capabilities";
import type { ContactError, ContactIntent } from "@/lib/contact";
import TrackedLink from "./TrackedLink";

const copy = {
  es: {
    title: "Contacto", name: "Nombre", email: "Correo", intent: "Motivo",
    general: "Consulta general", career: "Oportunidad laboral", project: "Proyecto",
    role: "Puesto o equipo", scope: "Negocio o tipo de proyecto", optional: "opcional",
    message: "Mensaje", send: "Enviar mensaje", sending: "Enviando…",
    sent: "Mensaje enviado. Te responderé al correo que escribiste.", emailLink: "Escribirme por correo",
    noScript: "Para contactar sin JavaScript, usa el enlace de correo.",
    subjects: { general: "Consulta desde el portafolio", career: "Oportunidad laboral", project: "Consulta de proyecto" },
    errors: {
      body: "Revisa los campos e inténtalo de nuevo.", name: "Escribe entre 2 y 100 caracteres.",
      email: "Escribe un correo válido.", intent: "Elige un motivo de contacto.",
      context: "Escribe hasta 200 caracteres.", message: "Escribe entre 10 y 5000 caracteres.",
      unavailable: "El formulario no está disponible. Puedes escribirme por correo.",
      delivery: "El mensaje no se pudo enviar. Intenta de nuevo o escríbeme por correo.",
    },
  },
  en: {
    title: "Contact", name: "Name", email: "Email", intent: "Reason",
    general: "General inquiry", career: "Job opportunity", project: "Project inquiry",
    role: "Role or team", scope: "Business or project type", optional: "optional",
    message: "Message", send: "Send message", sending: "Sending…",
    sent: "Message sent. I’ll reply to the email address you provided.", emailLink: "Email me directly",
    noScript: "To contact me without JavaScript, use the email link.",
    subjects: { general: "Portfolio inquiry", career: "Job opportunity", project: "Project inquiry" },
    errors: {
      body: "Check the fields and try again.", name: "Enter 2 to 100 characters.",
      email: "Enter a valid email address.", intent: "Choose a reason for contacting me.",
      context: "Enter up to 200 characters.", message: "Enter 10 to 5000 characters.",
      unavailable: "The form is unavailable. You can email me directly.",
      delivery: "The message couldn’t be sent. Try again or email me directly.",
    },
  },
};

export default function RouteContact({ locale = "es", initialIntent = "general" }: {
  locale?: Locale;
  initialIntent?: ContactIntent;
}) {
  const t = copy[locale];
  const [intent, setIntent] = useState(initialIntent);
  const [status, setStatus] = useState<"idle" | "sending" | "sent">("idle");
  const [issue, setIssue] = useState<ContactError | null>(null);

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = event.currentTarget;
    const fields = Object.fromEntries(new FormData(form));
    setStatus("sending");
    setIssue(null);
    try {
      const response = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...fields, intent }),
      });
      if (!response.ok) {
        const data = await response.json().catch(() => null) as { code?: string } | null;
        const code = data?.code;
        const errorCode = code && Object.hasOwn(t.errors, code) ? code as ContactError : "delivery";
        setIssue(errorCode);
        setStatus("idle");
        const field = form.elements.namedItem(errorCode);
        if (field instanceof HTMLElement) field.focus();
        return;
      }
      form.reset();
      setIntent(initialIntent);
      setStatus("sent");
      trackPortfolioEvent("portfolio_contact_sent", intent);
    } catch {
      setIssue("delivery");
      setStatus("idle");
    }
  }

  function invalid(field: ContactError) {
    return issue === field ? { "aria-invalid": true, "aria-describedby": "contact-error" } : {};
  }

  return (
    <section id="contact" aria-labelledby="contact-title" className="site-section contact-section">
      <div>
        <h2 id="contact-title">{t.title}</h2>
        <TrackedLink href={`mailto:${site.email}?subject=${encodeURIComponent(t.subjects[intent])}`} event="portfolio_contact" targetName={intent} className="text-action">{t.emailLink}</TrackedLink>
        <noscript><p>{t.noScript}</p></noscript>
      </div>
      <form method="post" action="/api/contact" className="site-form" onSubmit={submit} aria-busy={status === "sending"}>
        <label>{t.intent}
          <select name="intent" value={intent} onChange={(e) => setIntent(e.target.value as ContactIntent)} {...invalid("intent")}>
            <option value="general">{t.general}</option>
            <option value="career">{t.career}</option>
            <option value="project">{t.project}</option>
          </select>
        </label>
        <div className="form-columns">
          <label>{t.name}<input autoComplete="name" name="name" required minLength={2} maxLength={100} {...invalid("name")} /></label>
          <label>{t.email}<input autoComplete="email" type="email" name="email" required maxLength={254} {...invalid("email")} /></label>
        </div>
        {intent !== "general" && (
          <label>{intent === "career" ? t.role : t.scope} <span>({t.optional})</span>
            <input key={intent} name="context" maxLength={200} {...invalid("context")} />
          </label>
        )}
        <label>{t.message}<textarea name="message" rows={5} required minLength={10} maxLength={5000} {...invalid("message")} /></label>
        <input name="company" type="text" hidden tabIndex={-1} autoComplete="off" />
        <button type="submit" className="site-button" disabled={status === "sending"}>{status === "sending" ? t.sending : t.send}</button>
        {issue && <p id="contact-error" role="alert">{t.errors[issue]}</p>}
        <p role="status">{status === "sent" ? t.sent : ""}</p>
      </form>
    </section>
  );
}
