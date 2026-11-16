export type ContactIntent = "general" | "career" | "project";

type ContactDetails = {
  intent: ContactIntent;
  name: string;
  email: string;
  context: string;
  message: string;
};

export type ContactError = "body" | "name" | "email" | "message" | "intent" | "context" | "unavailable" | "delivery";

type ParsedContact =
  | { ok: true; value: ContactDetails | null }
  | { ok: false; code: ContactError };

export function parseContact(input: unknown): ParsedContact {
  if (!input || typeof input !== "object" || Array.isArray(input)) {
    return { ok: false, code: "body" };
  }
  const fields = input as Record<string, unknown>;
  if (fields.company) return { ok: true, value: null };
  const trimmed = (value: unknown) => typeof value === "string" ? value.trim() : "";
  const name = trimmed(fields.name);
  const email = trimmed(fields.email);
  const context = trimmed(fields.context);
  const message = trimmed(fields.message);
  const intent = fields.intent === undefined ? "general" : fields.intent;
  if (intent !== "general" && intent !== "project" && intent !== "career") return { ok: false, code: "intent" };
  if (name.length < 2 || name.length > 100) return { ok: false, code: "name" };
  if (email.length > 254 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return { ok: false, code: "email" };
  if (message.length < 10 || message.length > 5000) return { ok: false, code: "message" };
  if ((fields.context !== undefined && typeof fields.context !== "string") || context.length > 200) {
    return { ok: false, code: "context" };
  }
  return { ok: true, value: { name, email, context, message, intent } };
}

const subjects = {
  general: "Portafolio: consulta general",
  career: "Portafolio: oportunidad laboral",
  project: "Portafolio: consulta de proyecto",
} satisfies Record<ContactIntent, string>;

export function contactSubject(intent: ContactIntent) {
  return subjects[intent];
}
