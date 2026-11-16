import ProfessionalProfile, { professionalMetadata } from "@/components/ProfessionalProfile";

export const metadata = professionalMetadata("es");

export default function ProfilePage() {
  return <ProfessionalProfile locale="es" />;
}
