import ProfessionalProfile, { professionalMetadata } from "@/components/ProfessionalProfile";

export const metadata = professionalMetadata("en");

export default function CareerPage() {
  return <ProfessionalProfile locale="en" />;
}
