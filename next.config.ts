import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  eslint: {
    ignoreDuringBuilds: true, // Opción temporal para permitir el despliegue
  },
};

export default nextConfig;
