// components_clinic/BackgroundImage.tsx
import Image from 'next/image';

export default function BackgroundImage() {
  return (
    <div className="fixed inset-0 -z-10">
      <Image
        src="/projects/clinic/assets/images/bg.jpg"
        alt="Fondo odontológico"
        fill
        className="object-cover"
        quality={100}
        priority
      />
      <div className="absolute inset-0 bg-black/30"></div> {/* Overlay para mejor legibilidad */}
    </div>
  );
}