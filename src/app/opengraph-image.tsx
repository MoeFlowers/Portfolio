import { ImageResponse } from "next/og";
import { site, heroMetrics } from "@/data/site";

export const alt = `${site.name} — ${site.role}`;
export const size = { width: 1200, height: 630 };
export const contentType = "image/png";

export default function OpengraphImage() {
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
          background: "linear-gradient(135deg, #0a0a0f 0%, #12121f 100%)",
          color: "white",
          fontFamily: "sans-serif",
        }}
      >
        <div style={{ display: "flex", alignItems: "center", gap: 12, fontSize: 28, color: "#818cf8" }}>
          moisesflores.dev
        </div>
        <div style={{ display: "flex", fontSize: 72, fontWeight: 700, marginTop: 24, lineHeight: 1.1 }}>
          {site.name}
        </div>
        <div
          style={{
            display: "flex",
            fontSize: 40,
            marginTop: 12,
            background: "linear-gradient(90deg, #6366f1, #22d3ee)",
            backgroundClip: "text",
            color: "transparent",
          }}
        >
          {site.role}
        </div>
        <div style={{ display: "flex", gap: 48, marginTop: 64 }}>
          {heroMetrics.map(({ value, label }) => (
            <div key={label} style={{ display: "flex", flexDirection: "column" }}>
              <span style={{ fontSize: 44, fontWeight: 700 }}>{value}</span>
              <span style={{ fontSize: 20, color: "#a1a1aa", marginTop: 4 }}>{label}</span>
            </div>
          ))}
        </div>
      </div>
    ),
    size
  );
}
