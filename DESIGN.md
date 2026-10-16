# Dirección de diseño del portafolio

## Pedido del dueño (2026-10-16)

"Mejora el diseño. Quitemos esos colores azul típicos, hagamos algo más agradable a la vista del usuario. Algo minimalista profesional que combine con mi perfil." Además: añadir todos los proyectos de la carpeta Moises.

Esta dirección la propuso el agente a partir de ese pedido y de la identidad que Moises ya usa (marfil y rojo en la versión 3D, el video de TikTok y su perfil de GitHub). Si algo no le representa, se cambia aquí primero.

## Lectura

Portafolio personal para reclutadores de trabajo remoto y para dueños de comercios en Venezuela, en un lenguaje minimalista y cálido donde el color lo ponen las capturas reales de los proyectos.

Dial: ENERGY 2 / RHYTHM 2 / MOTION 2

## Paleta

Cuatro tonos y un acento. El rojo es la firma que Moises ya usa; aparece solo en momentos clave.

| Token | Claro | Oscuro | Uso |
|---|---|---|---|
| paper | #F7F6F2 | #121110 | Fondo |
| sunk | #EEECE6 | #1B1A18 | Marco de capturas, bloques hundidos |
| ink | #1B1A17 | #F3EBDD | Texto principal (en oscuro es el marfil de su marca) |
| ink-2 | #57534B | #C9C1B3 | Texto de apoyo |
| ink-3 | #6B665C | #A39C8F | Metadatos: año, stack, estado |
| line | #DEDAD2 | #2A2826 | Divisores decorativos |
| control | #8F8A80 | #6F6A61 | Bordes de controles (3:1) |
| red | #B3202B | #FF6670 | Acento: subrayado activo, foco, selección |

Contraste verificado con `contrast-check.py` de antislop: el par más justo es ink-3 sobre sunk (4,83:1).

## Tipografía

Una sola familia: **Mona Sans** (la tipografía abierta de GitHub, con eje de ancho 75 a 125).

- Títulos: ancho 125, peso 700 a 800, interletrado negativo. Da carácter sin una segunda familia.
- Texto: ancho 100, peso 400 y 500, 17 px.
- Datos (años, stack, estado): la misma familia con cifras tabulares, sin monoespaciada.

Por qué: su perfil de GitHub es pieza central de su marca personal, y una sola familia con anchos distintos mantiene la página sobria.

## Composición

- Inicio: nombre muy ancho, una frase de lo que construye, qué hace ahora y su foto.
- Trabajos destacados: cuatro proyectos con capturas reales, cada uno con una composición distinta (imagen a la derecha, a la izquierda, a todo el ancho).
- Índice de proyectos: todos los proyectos en filas con año, tipo, stack y estado, filtrables por categoría. Es la firma de la página: al pasar el cursor o el foco por una fila, la captura aparece en un panel fijo a la derecha.
- Experiencia, herramientas y contacto en texto, sin tarjetas.

## Reglas

- Radio 6 px en botones, campos y marcos; nada de píldoras.
- Sin sombras; la elevación se marca con borde o con el fondo `sunk`.
- Sin íconos decorativos; los únicos íconos son de acción (descargar, enlace externo, menú, tema).
- Movimiento: entrada escalonada del inicio al cargar (CSS), fundido corto en la vista previa del índice y estados hover. Nada de apariciones al hacer scroll: el contenido nunca depende de un observador para verse. Respeta `prefers-reduced-motion`.
- Los datos que se muestran salen de los proyectos reales; nada inventado.
