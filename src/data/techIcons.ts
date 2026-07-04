import { IconType } from "react-icons";
import {
  SiPython,
  SiPhp,
  SiJavascript,
  SiTypescript,
  SiMysql,
  SiScikitlearn,
  SiFastapi,
  SiReact,
  SiNextdotjs,
  SiVuedotjs,
  SiNodedotjs,
  SiTailwindcss,
  SiFlask,
  SiPandas,
  SiGit,
  SiGithubactions,
  SiVercel,
  SiHtml5,
  SiCss3,
} from "react-icons/si";

type TechIconConfig = {
  icon: IconType;
  color: string;
};

export const techIcons = {
  Python: { icon: SiPython, color: "bg-[#3776AB] text-white" },
  PHP: { icon: SiPhp, color: "bg-[#777BB4] text-white" },
  JavaScript: { icon: SiJavascript, color: "bg-[#F7DF1E] text-black" },
  TypeScript: { icon: SiTypescript, color: "bg-[#3178C6] text-white" },
  MySQL: { icon: SiMysql, color: "bg-[#4479A1] text-white" },
  "Scikit-learn": { icon: SiScikitlearn, color: "bg-[#F7931E] text-white" },
  FastAPI: { icon: SiFastapi, color: "bg-[#009688] text-white" },
  React: { icon: SiReact, color: "bg-[#61DAFB] text-[#2D2D2D]" },
  "Next.js": { icon: SiNextdotjs, color: "bg-black text-white" },
  Vue: { icon: SiVuedotjs, color: "bg-[#42B883] text-white" },
  "Node.js": { icon: SiNodedotjs, color: "bg-[#339933] text-white" },
  "Tailwind CSS": { icon: SiTailwindcss, color: "bg-[#06B6D4] text-white" },
  Flask: { icon: SiFlask, color: "bg-[#46A6A6] text-white" },
  Pandas: { icon: SiPandas, color: "bg-[#150458] text-white" },
  Git: { icon: SiGit, color: "bg-[#F05032] text-white" },
  "GitHub Actions": { icon: SiGithubactions, color: "bg-[#2088FF] text-white" },
  Vercel: { icon: SiVercel, color: "bg-black text-white" },
  HTML: { icon: SiHtml5, color: "bg-[#E34F26] text-white" },
  CSS: { icon: SiCss3, color: "bg-[#1572B6] text-white" },
} satisfies Record<string, TechIconConfig>;

export type TechName = keyof typeof techIcons;
