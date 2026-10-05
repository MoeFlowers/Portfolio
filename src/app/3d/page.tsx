import type { Metadata } from "next";
import StudioPage from "@/components/studio/StudioPage";

export const metadata: Metadata = {
  title: "Estudio 3D",
  robots: { index: false, follow: false },
};

export default function Page() {
  return <StudioPage />;
}
