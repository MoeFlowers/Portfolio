"use client";
import { useState } from "react";
import { FiSend, FiUser, FiMail, FiMessageSquare, FiCheckCircle, FiAlertCircle } from "react-icons/fi";
import { site } from "@/data/site";
import SectionHeading from "./ui/SectionHeading";
import Reveal from "./ui/Reveal";

type Status = "idle" | "submitting" | "success" | "error";

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

  return (
    <section id="contact" className="py-24 px-4 sm:px-6 lg:px-8 bg-zinc-50 dark:bg-white/[0.02]">
      <div className="max-w-7xl mx-auto">
        <SectionHeading
          eyebrow="Contacto"
          title={
            <>
              ¿Tienes un proyecto en mente? <span className="text-accent">Hablemos</span>
            </>
          }
          description="Cuéntame qué necesitas y te respondo en menos de 24 horas."
        />

        <Reveal className="max-w-2xl mx-auto">
          <div className="p-8 md:p-10 rounded-2xl border border-zinc-200 dark:border-white/10 bg-white/70 dark:bg-white/[0.03] backdrop-blur-sm">
            <form onSubmit={handleSubmit} className="space-y-6" noValidate={false}>
              {/* Honeypot anti-spam: oculto para humanos */}
              <div className="absolute w-px h-px overflow-hidden -m-px" aria-hidden="true">
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

              <Field
                id="name"
                label="Nombre completo"
                icon={<FiUser aria-hidden="true" className="h-5 w-5" />}
              >
                <input
                  type="text"
                  id="name"
                  name="name"
                  autoComplete="name"
                  minLength={2}
                  maxLength={100}
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className={inputClasses}
                  placeholder="Ej: Juan Pérez"
                  required
                />
              </Field>

              <Field
                id="email"
                label="Correo electrónico"
                icon={<FiMail aria-hidden="true" className="h-5 w-5" />}
              >
                <input
                  type="email"
                  id="email"
                  name="email"
                  autoComplete="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className={inputClasses}
                  placeholder="Ej: juan@ejemplo.com"
                  required
                />
              </Field>

              <Field
                id="message"
                label="Mensaje"
                icon={<FiMessageSquare aria-hidden="true" className="h-5 w-5" />}
                iconTop
              >
                <textarea
                  id="message"
                  name="message"
                  rows={5}
                  minLength={10}
                  maxLength={5000}
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className={inputClasses}
                  placeholder="Cuéntame sobre tu proyecto..."
                  required
                />
              </Field>

              <button
                type="submit"
                disabled={status === "submitting"}
                className="flex items-center justify-center gap-2 w-full px-6 py-3.5 rounded-full font-semibold text-white bg-accent hover:bg-accent-hover disabled:opacity-60 disabled:cursor-not-allowed transition-colors duration-200 shadow-lg shadow-accent/25"
              >
                {status === "submitting" ? (
                  "Enviando..."
                ) : (
                  <>
                    <FiSend aria-hidden="true" className="w-4.5 h-4.5" />
                    Enviar mensaje
                  </>
                )}
              </button>

              {/* Estado del envío, anunciado a lectores de pantalla */}
              <div aria-live="polite" role="status">
                {status === "success" && (
                  <p className="flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400">
                    <FiCheckCircle aria-hidden="true" className="w-4.5 h-4.5 shrink-0" />
                    Mensaje enviado. Te responderé en menos de 24 horas.
                  </p>
                )}
                {status === "error" && (
                  <p className="flex items-start gap-2 text-sm text-red-600 dark:text-red-400">
                    <FiAlertCircle aria-hidden="true" className="w-4.5 h-4.5 shrink-0 mt-0.5" />
                    <span>
                      {errorMessage} Escríbeme directamente a{" "}
                      <a href={`mailto:${site.email}`} className="underline font-medium">
                        {site.email}
                      </a>
                      .
                    </span>
                  </p>
                )}
              </div>
            </form>

            <p className="mt-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
              También puedes escribirme directamente a{" "}
              <a href={`mailto:${site.email}`} className="text-accent dark:text-accent-soft hover:underline">
                {site.email}
              </a>
            </p>
          </div>
        </Reveal>
      </div>
    </section>
  );
}

const inputClasses =
  "block w-full pl-10 pr-4 py-3 rounded-xl border border-zinc-300 dark:border-white/10 bg-white dark:bg-white/[0.04] text-zinc-900 dark:text-white placeholder:text-zinc-400 dark:placeholder:text-zinc-500 focus:ring-2 focus:ring-accent focus:border-transparent outline-none transition-all";

function Field({
  id,
  label,
  icon,
  iconTop,
  children,
}: {
  id: string;
  label: string;
  icon: React.ReactNode;
  iconTop?: boolean;
  children: React.ReactNode;
}) {
  return (
    <div className="group">
      <label
        htmlFor={id}
        className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2"
      >
        {label}
      </label>
      <div className="relative">
        <div
          className={`absolute left-0 pl-3 flex items-center pointer-events-none text-zinc-400 dark:text-zinc-500 group-focus-within:text-accent ${
            iconTop ? "top-3" : "inset-y-0"
          }`}
        >
          {icon}
        </div>
        {children}
      </div>
    </div>
  );
}
