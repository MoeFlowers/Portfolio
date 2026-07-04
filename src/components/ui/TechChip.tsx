import { techIcons, TechName } from "@/data/techIcons";

export default function TechChip({ tech }: { tech: TechName }) {
  const { icon: Icon, color } = techIcons[tech];
  return (
    <span
      className={`${color} inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full`}
    >
      <Icon aria-hidden="true" className="w-3 h-3" />
      {tech}
    </span>
  );
}
