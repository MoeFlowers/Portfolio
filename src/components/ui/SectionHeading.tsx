interface SectionHeadingProps {
  id: string;
  title: string;
  note?: React.ReactNode;
}

/** Título de sección en Mona Sans ancha, con una nota opcional alineada a la derecha. */
export default function SectionHeading({ id, title, note }: SectionHeadingProps) {
  return (
    <div className="flex flex-wrap items-end justify-between gap-x-8 gap-y-3 border-b border-line pb-5">
      <h2 id={id} className="wide text-[clamp(1.9rem,4vw,2.9rem)] font-bold leading-none text-ink">
        {title}
      </h2>
      {note && <div className="text-[0.95rem] text-ink-3">{note}</div>}
    </div>
  );
}
