// Convierte los fotogramas de build_studio_real.py a WebP para la web.
// Uso: node blender/frames_to_webp.mjs [entrada=blender/out_real] [salida=public/3d/v2]
import fs from "node:fs";
import path from "node:path";
import sharp from "sharp";

const src = process.argv[2] ?? "blender/out_real";
const dst = process.argv[3] ?? "public/3d/v2";
fs.mkdirSync(dst, { recursive: true });

const files = fs.readdirSync(src).filter((f) => /^(f_\d{3}|p_\d)\.png$/.test(f));
let bytes = 0;
for (const f of files) {
  const out = path.join(dst, f.replace(/\.png$/, ".webp"));
  await sharp(path.join(src, f)).webp({ quality: 82, effort: 5 }).toFile(out);
  bytes += fs.statSync(out).size;
}
fs.copyFileSync(path.join(src, "manifest.json"), path.join(dst, "manifest.json"));
console.log(`${files.length} imágenes · ${(bytes / 1024 / 1024).toFixed(1)} MB → ${dst}`);
