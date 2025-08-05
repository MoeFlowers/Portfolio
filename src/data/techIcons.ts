import { IconType } from 'react-icons';
import {
  SiPython,
  SiPhp,
  SiJavascript,
  SiMysql,
  SiScikitlearn,
  SiFastapi,
  SiReact,
  SiNextdotjs,
  SiTailwindcss,
  SiFlask,
} from "react-icons/si";

type TechIconConfig = {
  icon: IconType;
  color: string;
};

export const techIcons: Record<string, TechIconConfig> = {
  Python: {
    icon: SiPython,
    color: "bg-[#3776AB] text-white",
  },
  PHP: {
    icon: SiPhp,
    color: "bg-[#777BB4] text-white",
  },
  JavaScript: {
    icon: SiJavascript,
    color: "bg-[#F7DF1E] text-black",
  },
  MySQL: {
    icon: SiMysql,
    color: "bg-[#4479A1] text-white",
  },
  "Scikit-learn": {
    icon: SiScikitlearn,
    color: "bg-[#F7931E] text-white",
  },
  FastAPI: {
    icon: SiFastapi,
    color: "bg-[#009688] text-white",
  },
  React: {
    icon: SiReact,
    color: "bg-[#61DAFB] text-[#2D2D2D]",
  },
  "Next.js": {
    icon: SiNextdotjs,
    color: "bg-black text-white",
  },
  "Tailwind CSS": {
    icon: SiTailwindcss,
    color: "bg-[#06B6D4] text-white",
  },
  "Flask":{
    icon: SiFlask,
    color: "bg-[#46A6A6] text-white",
  }
} as const;

export type TechName = keyof typeof techIcons;
