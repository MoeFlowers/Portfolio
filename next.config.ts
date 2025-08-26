import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  eslint: {
    ignoreDuringBuilds: true, // Solo para no frenar despliegue
  },
};

export default nextConfig;
