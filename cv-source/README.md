# CV source (generador de los PDF del portfolio)

Los currículums que se descargan desde el portfolio (`public/cv-moises-flores-es.pdf` y
`public/cv-moises-flores-en.pdf`) se generan desde aquí. No edites los PDF a mano: edita el
contenido en `build.js` y vuelve a renderizar.

## Editar

Todo el contenido (español e inglés) está en el objeto `data` de [`build.js`](build.js):
habilidades, experiencia y proyectos. El diseño (dos columnas, colores, tipografía) está en
el `<style>` de la plantilla.

## Regenerar los PDF

Requiere Node y Google Chrome instalados.

```bash
# 1) genera cv-es.html y cv-en.html
node build.js

# 2) renderiza cada uno a PDF (Chrome headless)
CHROME="/c/Program Files/Google/Chrome/Application/chrome.exe"
for L in es en; do
  "$CHROME" --headless=new --disable-gpu --no-pdf-header-footer \
    --print-to-pdf="$PWD/cv-$L.pdf" --virtual-time-budget=10000 "file:///$PWD/cv-$L.html"
done

# 3) copia los PDF a public/ con el nombre que usa el portfolio
cp cv-es.pdf ../public/cv-moises-flores-es.pdf
cp cv-en.pdf ../public/cv-moises-flores-en.pdf
```

La fuente (Inter) se carga desde Google Fonts al renderizar, así que necesitas conexión.
