import { professional } from "./professional";

type ExperienceItem = {
  period: string;
  title: string;
  org: string;
  description: string;
  highlight?: string;
  type: "trabajo" | "educacion";
};

export const experience: ExperienceItem[] = [
  ...professional.es.experience.map(({ date, title, context, summary }) => ({
    period: date,
    title,
    org: context,
    description: summary,
    type: "trabajo" as const,
  })),
  {
    period: "Ago 2026",
    title: "Ingeniero en Sistemas",
    org: "UNEFA",
    description: professional.es.education,
    type: "educacion",
  },
];
