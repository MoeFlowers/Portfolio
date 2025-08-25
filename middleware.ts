import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";

export function middleware(req: NextRequest) {
  const { pathname } = req.nextUrl;

  // Si ya tiene prefijo de idioma, no hacemos nada
  if (pathname.startsWith("/es") || pathname.startsWith("/en") || pathname.startsWith("/_next")) {
    return NextResponse.next();
  }

  // Detectar idioma del navegador
  const langHeader = req.headers.get("accept-language") || "es";
  const lang = langHeader.split(",")[0].split("-")[0]; // ej: "en-US" → "en"

  const locale = lang === "en" ? "en" : "es";

  // Redirigir automáticamente
  return NextResponse.redirect(new URL(`/${locale}${pathname}`, req.url));
}
