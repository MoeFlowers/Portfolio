import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  eslint: {
    ignoreDuringBuilds: true, // Solo para no frenar despliegue
  },
  i18n: {
    locales: ["es", "en"], // idiomas soportados
    defaultLocale: "es",   // español como base
  },
};

export default nextConfig;
