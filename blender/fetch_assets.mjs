// Descarga los modelos y texturas CC0 de Poly Haven que usa build_studio_real.py.
// Uso: node blender/fetch_assets.mjs   → blender/assets/ (ignorado por git; se puede volver a bajar)
import fs from "node:fs";
import path from "node:path";

const ROOT = path.join(path.dirname(new URL(import.meta.url).pathname.replace(/^\/(\w:)/, "$1")), "assets");

const MODELS = {
  potted_plant_01: "2k",
  potted_plant_04: "1k",
  modern_arm_chair_01: "2k",
  decorative_book_set_01: "1k",
  book_encyclopedia_set_01: "1k",
  ceramic_vase_01: "1k",
  standing_picture_frame_01: "1k",
};
const TEXTURES = {
  wood_floor: ["Diffuse", "nor_gl", "Rough"],
  american_walnut_veneer: ["Diffuse", "nor_gl", "Rough"],
  white_plaster_02: ["Diffuse", "nor_gl", "Rough"],
  wool_boucle: ["Diffuse", "nor_gl", "Rough"],
};
const TEX_RES = "2k";

async function get(url, dest) {
  if (fs.existsSync(dest)) return 0;
  fs.mkdirSync(path.dirname(dest), { recursive: true });
  const res = await fetch(url);
  if (!res.ok) throw new Error(`${res.status} ${url}`);
  const buf = Buffer.from(await res.arrayBuffer());
  fs.writeFileSync(dest, buf);
  return buf.length;
}

const files = async (id) => (await fetch(`https://api.polyhaven.com/files/${id}`)).json();
let total = 0;

for (const [id, res] of Object.entries(MODELS)) {
  // glTF si existe; si no (p. ej. decorative_book_set_01), el .blend original
  const j = await files(id);
  const file = j.gltf ? j.gltf[res].gltf : j.blend[res].blend;
  const dir = path.join(ROOT, "models", id);
  total += await get(file.url, path.join(dir, path.basename(file.url)));
  for (const [rel, f] of Object.entries(file.include ?? {})) total += await get(f.url, path.join(dir, rel));
  console.log("modelo", id, res);
}
for (const [id, maps] of Object.entries(TEXTURES)) {
  const j = await files(id);
  for (const m of maps) {
    const f = j[m][TEX_RES].jpg ?? j[m][TEX_RES].png;
    total += await get(f.url, path.join(ROOT, "textures", id, `${m}.jpg`));
  }
  console.log("textura", id);
}
console.log(`listo · ${(total / 1024 / 1024).toFixed(1)} MB descargados → ${ROOT}`);
