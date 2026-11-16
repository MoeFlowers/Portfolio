import { createShareImage } from "@/components/ShareImage";

export const dynamic = "force-static";

export function GET() {
  return createShareImage();
}
