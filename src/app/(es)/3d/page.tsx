import type { Metadata } from "next";
import StudioExperience from "@/components/studio/StudioExperience";

export const metadata: Metadata = {
  title: "Estudio 3D",
  robots: { index: false, follow: false },
};

export default function Page() {
  return <StudioExperience />;
}
