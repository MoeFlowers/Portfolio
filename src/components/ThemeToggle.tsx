"use client";
import { useEffect, useState } from "react";
import { useTheme } from "next-themes";
import { FiSun, FiMoon } from "react-icons/fi";

export default function ThemeToggle() {
  const { resolvedTheme, setTheme } = useTheme();
  const [mounted, setMounted] = useState(false);

  // El tema solo se conoce en el cliente; antes de montar se reserva el espacio
  useEffect(() => setMounted(true), []);

  if (!mounted) {
    return <span className="inline-block h-11 w-11" aria-hidden="true" />;
  }

  const isDark = resolvedTheme === "dark";

  return (
    <button
      type="button"
      onClick={() => setTheme(isDark ? "light" : "dark")}
      aria-label={isDark ? "Cambiar a tema claro" : "Cambiar a tema oscuro"}
      className="inline-flex h-11 w-11 items-center justify-center rounded-[6px] text-ink-2 transition-colors hover:bg-sunk hover:text-ink"
    >
      {isDark ? <FiSun className="h-[18px] w-[18px]" aria-hidden="true" /> : <FiMoon className="h-[18px] w-[18px]" aria-hidden="true" />}
    </button>
  );
}
