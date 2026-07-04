import { NextResponse } from "next/server";
import { Resend } from "resend";
import { site } from "@/data/site";

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

interface ContactPayload {
  name?: string;
  email?: string;
  message?: string;
  /** Honeypot: los humanos no ven este campo; si llega con valor, es un bot. */
  company?: string;
}

export async function POST(request: Request) {
  let body: ContactPayload;
  try {
    body = await request.json();
  } catch {
    return NextResponse.json({ error: "Cuerpo de la petición inválido." }, { status: 400 });
  }

  const name = body.name?.trim() ?? "";
  const email = body.email?.trim() ?? "";
  const message = body.message?.trim() ?? "";

  // Honeypot: responder como éxito para no dar pistas al bot
  if (body.company) {
    return NextResponse.json({ ok: true });
  }

  if (name.length < 2 || name.length > 100) {
    return NextResponse.json({ error: "El nombre debe tener entre 2 y 100 caracteres." }, { status: 400 });
  }
  if (!EMAIL_REGEX.test(email)) {
    return NextResponse.json({ error: "El correo electrónico no es válido." }, { status: 400 });
  }
  if (message.length < 10 || message.length > 5000) {
    return NextResponse.json({ error: "El mensaje debe tener entre 10 y 5000 caracteres." }, { status: 400 });
  }

  const apiKey = process.env.RESEND_API_KEY;
  if (!apiKey) {
    // Sin clave configurada el formulario no puede enviar: se informa al cliente
    // para que muestre el fallback de email directo.
    return NextResponse.json(
      { error: "El formulario no está disponible en este momento." },
      { status: 503 }
    );
  }

  const resend = new Resend(apiKey);
  const { error } = await resend.emails.send({
    from: process.env.CONTACT_FROM_EMAIL ?? "Portfolio <onboarding@resend.dev>",
    to: process.env.CONTACT_TO_EMAIL ?? site.email,
    replyTo: email,
    subject: `Nuevo mensaje de ${name} — portfolio`,
    text: `Nombre: ${name}\nEmail: ${email}\n\n${message}`,
  });

  if (error) {
    console.error("Error enviando email de contacto:", error);
    return NextResponse.json(
      { error: "No se pudo enviar el mensaje. Intenta de nuevo." },
      { status: 502 }
    );
  }

  return NextResponse.json({ ok: true });
}
