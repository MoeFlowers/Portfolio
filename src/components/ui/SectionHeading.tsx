import Reveal from "./Reveal";

interface SectionHeadingProps {
  eyebrow: string;
  title: React.ReactNode;
  description?: string;
  align?: "center" | "left";
}

export default function SectionHeading({
  eyebrow,
  title,
  description,
  align = "center",
}: SectionHeadingProps) {
  const alignment = align === "center" ? "text-center mx-auto" : "text-left";

  return (
    <Reveal className={`max-w-2xl mb-14 ${align === "center" ? "mx-auto" : ""}`}>
      <div className={alignment}>
        <p className="font-mono text-xs uppercase tracking-[0.25em] text-accent mb-3">
          {eyebrow}
        </p>
        <h2 className="font-display text-3xl sm:text-4xl font-bold tracking-tight text-zinc-900 dark:text-white text-balance">
          {title}
        </h2>
        {description && (
          <p className="mt-4 text-lg text-zinc-600 dark:text-zinc-400">
            {description}
          </p>
        )}
      </div>
    </Reveal>
  );
}
