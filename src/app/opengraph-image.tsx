import { ImageResponse } from "next/og";
import { site } from "@/data/site";

export const alt = `${site.name} · ${site.role}`;
export const size = { width: 1200, height: 630 };
export const contentType = "image/png";

// Colores de DESIGN.md (tema claro)
export default function OpengraphImage() {
  return new ImageResponse(
    (
      <div
        style={{
          width: "100%",
          height: "100%",
          display: "flex",
          flexDirection: "column",
          justifyContent: "space-between",
          padding: "84px",
          background: "#f7f6f2",
          color: "#1b1a17",
          fontFamily: "sans-serif",
        }}
      >
        <div style={{ display: "flex", flexDirection: "column" }}>
          <div style={{ display: "flex", fontSize: 128, fontWeight: 800, lineHeight: 0.9, letterSpacing: -4 }}>Moises</div>
          <div style={{ display: "flex", fontSize: 128, fontWeight: 800, lineHeight: 0.9, letterSpacing: -4 }}>Flores</div>
          <div style={{ display: "flex", width: 120, height: 6, background: "#b3202b", marginTop: 36 }} />
        </div>
        <div style={{ display: "flex", flexDirection: "column", fontSize: 34, color: "#57534b", lineHeight: 1.35 }}>
          <div style={{ display: "flex" }}>Desarrollador full-stack e ingeniero en sistemas.</div>
          <div style={{ display: "flex" }}>Sistemas para comercios, apps web y móviles, y 3D en el navegador.</div>
        </div>
      </div>
    ),
    size
  );
}
