import { NextResponse } from "next/server";
import { Resend } from "resend";
import { site } from "@/data/site";
import { contactSubject, parseContact, type ContactError } from "@/lib/contact";

function failure(code: ContactError, status: number) {
  return NextResponse.json({ code }, { status });
}

export async function POST(request: Request) {
  let input: unknown;
  try {
    input = await request.json();
  } catch {
    return failure("body", 400);
  }
  const parsed = parseContact(input);
  if (!parsed.ok) return failure(parsed.code, 400);
  if (!parsed.value) return NextResponse.json({ ok: true });

  const apiKey = process.env.RESEND_API_KEY;
  if (!apiKey) return failure("unavailable", 503);

  const { name, email, message, context, intent } = parsed.value;
  const resend = new Resend(apiKey);
  try {
    const { error } = await resend.emails.send({
      from: process.env.CONTACT_FROM_EMAIL ?? "Portfolio <onboarding@resend.dev>",
      to: process.env.CONTACT_TO_EMAIL ?? site.email,
      replyTo: email,
      subject: contactSubject(intent),
      text: [`Nombre: ${name}`, `Correo: ${email}`, ...(context ? [`Contexto: ${context}`] : []), "", message].join("\n"),
    });
    if (error) return failure("delivery", 502);
  } catch {
    return failure("delivery", 502);
  }
  return NextResponse.json({ ok: true });
}
