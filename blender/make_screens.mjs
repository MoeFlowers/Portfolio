// Genera las texturas de las pantallas secundarias para la versión fotorrealista.
// Uso: node blender/make_screens.mjs  → blender/textures/{code,terminal}.png
import fs from "node:fs";
import path from "node:path";
import sharp from "sharp";

const OUT = path.join(path.dirname(new URL(import.meta.url).pathname.replace(/^\/(\w:)/, "$1")), "textures");
fs.mkdirSync(OUT, { recursive: true });

const esc = (s) => s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

// Colores estilo "One Dark"
const C = { kw: "#c678dd", fn: "#61afef", str: "#98c379", num: "#d19a66", cm: "#7f848e", tx: "#abb2bf", ty: "#e5c07b" };

const code = [
  [["cm", "// src/services/rates.ts"]],
  [["kw", "import"], ["tx", " { createClient } "], ["kw", "from"], ["str", ' "@supabase/supabase-js"'], ["tx", ";"]],
  [],
  [["kw", "export async function"], ["fn", " fetchRates"], ["tx", "() {"]],
  [["tx", "  "], ["kw", "const"], ["tx", " res = "], ["kw", "await"], ["fn", " fetch"], ["tx", "(API_URL);"]],
  [["tx", "  "], ["kw", "if"], ["tx", " (!res.ok) "], ["kw", "throw new"], ["ty", " Error"], ["tx", "("], ["str", '"BCV"'], ["tx", ");"]],
  [["tx", "  "], ["kw", "const"], ["tx", " { usd, eur } = "], ["kw", "await"], ["tx", " res."], ["fn", "json"], ["tx", "();"]],
  [["tx", "  "], ["kw", "return"], ["tx", " { usd, eur, at: "], ["ty", "Date"], ["tx", "."], ["fn", "now"], ["tx", "() };"]],
  [["tx", "}"]],
  [],
  [["kw", "export function"], ["fn", " toBs"], ["tx", "(amount: "], ["ty", "number"], ["tx", ", rate: "], ["ty", "number"], ["tx", ") {"]],
  [["tx", "  "], ["cm", "// dinero como decimal exacto"]],
  [["tx", "  "], ["kw", "return"], ["ty", " Math"], ["tx", "."], ["fn", "round"], ["tx", "(amount * rate * "], ["num", "100"], ["tx", ") / "], ["num", "100"], ["tx", ";"]],
  [["tx", "}"]],
  [],
  [["kw", "export const"], ["tx", " supabase = "], ["fn", "createClient"], ["tx", "("]],
  [["tx", "  process.env."], ["ty", "SUPABASE_URL"], ["tx", "!,"]],
  [["tx", "  process.env."], ["ty", "SUPABASE_KEY"], ["tx", "!"]],
  [["tx", ");"]],
  [],
  [["kw", "export async function"], ["fn", " saveDebt"], ["tx", "(d: "], ["ty", "Debt"], ["tx", ") {"]],
  [["tx", "  "], ["kw", "const"], ["tx", " { error } = "], ["kw", "await"], ["tx", " supabase"]],
  [["tx", "    ."], ["fn", "from"], ["tx", "("], ["str", '"debts"'], ["tx", ")"]],
  [["tx", "    ."], ["fn", "insert"], ["tx", "({ ...d, currency: "], ["str", '"USD"'], ["tx", " });"]],
  [["tx", "  "], ["kw", "if"], ["tx", " (error) "], ["kw", "throw"], ["tx", " error;"]],
  [["tx", "}"]],
  [],
  [["cm", "// TODO: recordatorios por correo"]],
  [["kw", "export const"], ["tx", " WEEKS = "], ["num", "2"], ["tx", ";"]],
];

function codeSvg(w, h) {
  const fs_ = 26, lh = 40, top = 120;
  let rows = "";
  code.forEach((line, i) => {
    const y = top + i * lh;
    rows += `<text x="70" y="${y}" fill="#495162" font-size="${fs_}" text-anchor="end">${i + 1}</text>`;
    let spans = line.map(([k, t]) => `<tspan fill="${C[k]}">${esc(t)}</tspan>`).join("");
    rows += `<text x="96" y="${y}" font-size="${fs_}" xml:space="preserve">${spans}</text>`;
  });
  return `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">
  <rect width="100%" height="100%" fill="#282c34"/>
  <rect width="100%" height="56" fill="#21252b"/>
  <rect x="0" y="0" width="300" height="56" fill="#282c34"/>
  <text x="24" y="37" fill="#d7dae0" font-size="22" font-family="Segoe UI, sans-serif">rates.ts</text>
  <rect x="0" y="56" width="100%" height="34" fill="#282c34"/>
  <text x="24" y="80" fill="#7f848e" font-size="19" font-family="Segoe UI, sans-serif">src › services › rates.ts</text>
  <rect x="${w - 14}" y="120" width="8" height="160" rx="4" fill="#4b5263"/>
  <rect x="0" y="${h - 36}" width="100%" height="36" fill="#6366f1"/>
  <text x="18" y="${h - 11}" fill="#fff" font-size="19" font-family="Segoe UI, sans-serif">⎇ main   ✓ 0 ⚠ 0     TypeScript   UTF-8</text>
  <g font-family="Consolas, 'Cascadia Mono', monospace">${rows}</g>
</svg>`;
}

const term = [
  ["#a5b4fc", "moe@studio:~/ordo$ git pull origin main"],
  ["#cbd5e1", "Already up to date."],
  ["#a5b4fc", "moe@studio:~/ordo$ npm run build"],
  ["#4ade80", "✓ Compiled successfully in 4.2s"],
  ["#a5b4fc", "moe@studio:~/ordo$ docker compose up -d"],
  ["#4ade80", "✓ Container ordo-api   Started"],
  ["#4ade80", "✓ Container ordo-db    Healthy"],
  ["#a5b4fc", "moe@studio:~/ordo$ pytest -q"],
  ["#cbd5e1", "..........................................  [100%]"],
  ["#4ade80", "42 passed in 3.18s"],
  ["#a5b4fc", "moe@studio:~/ordo$ █"],
];

function termSvg(w, h) {
  const rows = term
    .map(([c, t], i) => `<text x="28" y="${110 + i * 44}" fill="${c}" xml:space="preserve">${esc(t)}</text>`)
    .join("");
  return `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">
  <rect width="100%" height="100%" fill="#0b0e17"/>
  <rect width="100%" height="52" fill="#161b2a"/>
  <circle cx="30" cy="26" r="9" fill="#f87171"/><circle cx="60" cy="26" r="9" fill="#fbbf24"/><circle cx="90" cy="26" r="9" fill="#4ade80"/>
  <text x="${w / 2}" y="34" fill="#94a3b8" font-size="20" text-anchor="middle" font-family="Segoe UI, sans-serif">moe@studio — zsh</text>
  <g font-family="Consolas, 'Cascadia Mono', monospace" font-size="27">${rows}</g>
</svg>`;
}

await sharp(Buffer.from(codeSvg(740, 1320))).png().toFile(path.join(OUT, "code.png"));
await sharp(Buffer.from(termSvg(990, 630))).png().toFile(path.join(OUT, "terminal.png"));
console.log("ok", OUT);
