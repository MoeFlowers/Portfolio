import { ImageResponse } from "next/og";
import { site } from "@/data/site";

const size = { width: 1200, height: 630 };

export function createShareImage() {
  return new ImageResponse(
    (
      <div
        style={{
          width: "100%",
          height: "100%",
          display: "flex",
          flexDirection: "column",
          justifyContent: "center",
          padding: "80px",
          background: "#121110",
          color: "#f3ebdd",
          fontFamily: "sans-serif",
        }}
      >
        <div style={{ display: "flex", alignItems: "center", gap: 12, fontSize: 28, color: "#ff7781" }}>
          Venezuela · Remoto
        </div>
        <div style={{ display: "flex", fontSize: 72, fontWeight: 700, marginTop: 24, lineHeight: 1.1 }}>
          {site.name}
        </div>
        <div
          style={{
            display: "flex",
            fontSize: 40,
            marginTop: 12,
            color: "#c9c1b3",
          }}
        >
          {site.role}
        </div>
        <div style={{ display: "flex", gap: 48, marginTop: 64, fontSize: 27, borderTop: "2px solid #ff7781", paddingTop: 24 }}>
          Aplicaciones web · APIs · Automatización
        </div>
      </div>
    ),
    size
  );
}
