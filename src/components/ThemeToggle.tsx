"use client";
import { useEffect, useState } from "react";
import { useTheme } from "next-themes";
import { FiSun, FiMoon } from "react-icons/fi";

export default function ThemeToggle({ locale = "es" }: { locale?: "es" | "en" }) {
  const { resolvedTheme, setTheme } = useTheme();
  const [mounted, setMounted] = useState(false);

  // Evita mismatch de hidratación: el tema solo se conoce en el cliente
  useEffect(() => setMounted(true), []);

  if (!mounted) {
    return <span className="theme-button" aria-hidden="true" />;
  }

  const isDark = resolvedTheme === "dark";

  return (
    <button
      type="button"
      onClick={() => setTheme(isDark ? "light" : "dark")}
      aria-label={locale === "en"
        ? (isDark ? "Switch to light theme" : "Switch to dark theme")
        : (isDark ? "Cambiar a tema claro" : "Cambiar a tema oscuro")}
      className="theme-button"
    >
      {isDark ? <FiSun className="w-5 h-5" /> : <FiMoon className="w-5 h-5" />}
    </button>
  );
}
