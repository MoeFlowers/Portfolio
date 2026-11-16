import type { MetadataRoute } from "next";
import { projects } from "@/data/projects";
import { site } from "@/data/site";

export default function sitemap(): MetadataRoute.Sitemap {
  return [
    {
      url: site.url,
      lastModified: new Date(),
      changeFrequency: "monthly",
      priority: 1,
    },
    {
      url: `${site.url}/perfil`,
      alternates: { languages: { es: `${site.url}/perfil`, en: `${site.url}/career` } },
    },
    {
      url: `${site.url}/career`,
      alternates: { languages: { es: `${site.url}/perfil`, en: `${site.url}/career` } },
    },
    { url: `${site.url}/servicios` },
    ...projects.map((project) => ({
      url: `${site.url}/projects/${project.slug}`,
      lastModified: new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.8,
    })),
  ];
}
