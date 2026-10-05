import { CanvasTexture, SRGBColorSpace } from "three";

const PALETTE = ["#a5b4fc", "#67e8f9", "#f0abfc", "#fde68a", "#86efac", "#94a3b8"];

function makeCanvas(w: number, h: number) {
  const canvas = document.createElement("canvas");
  canvas.width = w;
  canvas.height = h;
  const tex = new CanvasTexture(canvas);
  tex.flipY = false; // UV de glTF
  tex.colorSpace = SRGBColorSpace;
  return { canvas, ctx: canvas.getContext("2d")!, tex };
}

/** Monitor vertical: "código" abstracto que se desplaza. */
export function createCodeScreen() {
  const { canvas, ctx, tex } = makeCanvas(256, 456);
  const lines = Array.from({ length: 80 }, (_, i) => {
    const indent = [0, 1, 2, 2, 3, 1][i % 6] * 14;
    const tokens = Array.from({ length: 1 + ((i * 7) % 4) }, (_, j) => ({
      w: 18 + ((i * 13 + j * 29) % 60),
      c: PALETTE[(i + j * 3) % PALETTE.length],
    }));
    return { indent, tokens };
  });
  let offset = 0;
  return {
    tex,
    draw(dt: number) {
      offset = (offset + dt * 22) % (lines.length * 14);
      ctx.fillStyle = "#0d1020";
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = "#151a33";
      ctx.fillRect(0, 0, 26, canvas.height);
      for (let k = 0; k < 36; k++) {
        const idx = Math.floor(offset / 14) + k;
        const line = lines[idx % lines.length];
        const y = k * 14 - (offset % 14) + 10;
        ctx.fillStyle = "#3b4270";
        ctx.fillRect(6, y, 12, 5);
        let x = 34 + line.indent;
        for (const t of line.tokens) {
          ctx.fillStyle = t.c;
          ctx.globalAlpha = 0.85;
          ctx.fillRect(x, y, t.w, 6);
          x += t.w + 6;
        }
        ctx.globalAlpha = 1;
      }
      tex.needsUpdate = true;
    },
  };
}

const COMMANDS = [
  "$ git pull origin main",
  "Already up to date.",
  "$ npm run build",
  "✓ Compiled successfully",
  "$ docker compose up -d",
  "✓ Container api  Started",
  "$ pytest -q",
  "42 passed in 3.1s",
  "$ python bot.py --run",
  "→ 128 registros procesados",
];

/** Portátil: terminal que va escribiendo comandos. */
export function createTerminalScreen() {
  const { canvas, ctx, tex } = makeCanvas(512, 326);
  let chars = 0;
  return {
    tex,
    draw(dt: number) {
      chars += dt * 28;
      const total = COMMANDS.join("\n").length + 40;
      if (chars > total) chars = 0;
      ctx.fillStyle = "#0a0d1a";
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = "#151a33";
      ctx.fillRect(0, 0, canvas.width, 26);
      ["#f87171", "#fbbf24", "#4ade80"].forEach((c, i) => {
        ctx.fillStyle = c;
        ctx.beginPath();
        ctx.arc(16 + i * 18, 13, 5, 0, Math.PI * 2);
        ctx.fill();
      });
      ctx.font = "18px monospace";
      let left = Math.floor(chars);
      let y = 52;
      for (const cmd of COMMANDS) {
        if (left <= 0) break;
        const text = cmd.slice(0, left);
        ctx.fillStyle = cmd.startsWith("$") ? "#a5b4fc" : cmd.startsWith("✓") ? "#4ade80" : "#cbd5e1";
        ctx.fillText(text, 14, y);
        left -= cmd.length + 1;
        y += 27;
      }
      if (Math.floor(performance.now() / 500) % 2) {
        ctx.fillStyle = "#e2e8f0";
        ctx.fillRect(14, y - 18, 10, 20);
      }
      tex.needsUpdate = true;
    },
  };
}
