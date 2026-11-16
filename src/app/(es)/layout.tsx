import DocumentLayout, { baseMetadata, baseViewport } from "@/components/DocumentLayout";

export const metadata = baseMetadata;
export const viewport = baseViewport;

export default function Layout({ children }: { children: React.ReactNode }) {
  return <DocumentLayout locale="es">{children}</DocumentLayout>;
}
