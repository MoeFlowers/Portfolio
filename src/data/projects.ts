export interface ProjectMetric {
  value: string;
  label: string;
}

export interface Shot {
  src: string;
  alt: string;
  /** "mobile" para capturas verticales de teléfono */
  device?: "desktop" | "mobile";
}

export type ProjectKind = "sistemas" | "web3d" | "ia";

/** Estado real del proyecto, no decorativo */
export type ProjectStatus =
  | "En uso"
  | "En desarrollo"
  | "Primera versión"
  | "Concepto"
  | "Entregado"
  | "Prototipo"
  | "En preparación";

export interface Project {
  slug: string;
  title: string;
  /** Una o dos líneas para el índice y las tarjetas */
  summary: string;
  /** Contexto del caso: el problema, o la idea si es un concepto */
  problem: string;
  solution: string[];
  /** Resultados verificables; nada inventado */
  results: ProjectMetric[];
  learnings?: string[];
  technologies: string[];
  role: string;
  duration: string;
  year: string;
  kind: ProjectKind;
  /** Etiqueta corta que también usa la versión 3D */
  category: string;
  status: ProjectStatus;
  githubLink?: string;
  liveLink?: string;
  liveLabel?: string;
  /** Repositorio privado por ser de un cliente */
  privateRepo?: boolean;
  shots: Shot[];
  /** Portada antigua 1200x800: solo la usa la versión 3D (/3d) */
  cover?: string;
  featured: boolean;
}

export const kindLabels: Record<ProjectKind, string> = {
  sistemas: "Sistemas de gestión",
  web3d: "Web y 3D",
  ia: "IA y automatización",
};

export const projects: Project[] = [
  {
    slug: "vitrina",
    title: "Vitrina",
    summary:
      "Catálogos en línea para empresas, con pedidos que llegan ordenados por WhatsApp e inventario que se descuenta solo.",
    problem:
      "Muchos mayoristas y comercios en Venezuela reciben los pedidos por WhatsApp en mensajes sueltos: el cliente pregunta precios que cambian con la tasa del día, pide productos que ya no hay y el pedido se pierde entre conversaciones.",
    solution: [
      "Catálogo público por empresa con precios en bolívares a la tasa BCV y referencia en dólares. El cliente arma el pedido desde el teléfono y lo envía por WhatsApp; el pedido queda en el historial de ambos.",
      "Panel de la empresa con pedidos que apartan y descuentan stock en una sola transacción, inventario con un libro de movimientos que no se edita, clientes, categorías, entrega y equipo con roles.",
      "Registro de empresas con verificación de correo y doble factor (TOTP), y una administración de la plataforma para aprobar empresas, revisar pagos de suscripción y auditar cambios.",
      "Aislamiento entre empresas desde la base de datos, con seguridad por fila en PostgreSQL, e imágenes de productos en almacenamiento compatible con S3.",
      "Antes del backend publiqué un boceto navegable en GitHub Pages para validar las pantallas.",
    ],
    results: [
      { value: "44", label: "políticas de seguridad por fila que aíslan a cada empresa" },
      { value: "26", label: "pantallas auditadas con axe-core, sin violaciones" },
      { value: "37", label: "pruebas automatizadas" },
    ],
    learnings: [
      "Diseñar un producto multiempresa desde la base de datos, no desde la interfaz.",
      "Validar las pantallas con un boceto que se puede tocar antes de escribir el backend.",
    ],
    technologies: ["Next.js", "TypeScript", "PostgreSQL", "Drizzle", "Better Auth", "Tailwind CSS", "Docker"],
    role: "Producto propio: diseño, frontend y backend",
    duration: "Desde octubre de 2026",
    year: "2026",
    kind: "sistemas",
    category: "Producto propio",
    status: "En desarrollo",
    githubLink: "https://github.com/MoeFlowers/catalogo-saas",
    liveLink: "https://moeflowers.github.io/catalogo-saas/",
    liveLabel: "Probar el boceto navegable",
    shots: [
      { src: "/images/work/vitrina/inicio.webp", alt: "Portada de Vitrina: tu catálogo en línea, tus pedidos por WhatsApp" },
      { src: "/images/work/vitrina/catalogo.webp", alt: "Catálogo público de una ferretería de ejemplo con precios en bolívares" },
      { src: "/images/work/vitrina/panel.webp", alt: "Panel de inicio de la empresa con pedidos por confirmar y stock bajo" },
      { src: "/images/work/vitrina/inventario.webp", alt: "Inventario con existencias, reservado y disponible por producto" },
      { src: "/images/work/vitrina/productos.webp", alt: "Lista de productos con categoría, precio y estado" },
      { src: "/images/work/vitrina/admin.webp", alt: "Administración de la plataforma: solicitudes, pagos y denuncias" },
      { src: "/images/work/vitrina/catalogo-movil.webp", alt: "Catálogo de Vitrina en el teléfono", device: "mobile" },
    ],
    featured: true,
  },
  {
    slug: "mostrador",
    title: "Mostrador",
    summary:
      "Caja e inventario por gramo para una carnicería con frutería. Cobra en dólares y bolívares, lleva el fiado y funciona sin internet.",
    problem:
      "Una carnicería con frutería vendía por peso con calculadora y cuaderno: precios en dólares que había que pasar a bolívares a mano, fiado anotado en papel y ninguna forma de saber cuánta carne se perdía al moler o despresar.",
    solution: [
      "Caja con teclado numérico: código, Enter, gramos, Enter. Modo monto («5 dólares de molida» se convierte en gramos) y cobro mixto en efectivo, pago móvil, punto o fiado.",
      "Inventario en gramos con kardex: entradas a costo promedio, transformaciones con merma (moler, despresar) y conteo físico a ciegas que aprueba el dueño.",
      "Tasas del BCV que se buscan solas, con validación y respaldo manual. Cada venta guarda el precio y la tasa del momento.",
      "Panel del dueño con ventas, kilos, merma, margen y fiado por cobrar, una bitácora que no se edita ni se borra y respaldos en Excel y PDF.",
      "Corre en una sola PC de la tienda, sin servidor externo, y tiene un modo táctil con botones de 48 px.",
    ],
    results: [
      { value: "37", label: "pruebas automatizadas" },
      { value: "14", label: "fallas encontradas y corregidas en la prueba de ciclo de vida" },
      { value: "Sin internet", label: "vende, cobra y descuenta inventario sin conexión" },
    ],
    learnings: [
      "Guardar el peso en gramos enteros y el dinero con la tasa congelada evita los errores de redondeo.",
      "Recorrer el sistema completo como empleado y como dueño encuentra fallas que las pruebas unitarias no ven.",
    ],
    technologies: ["Svelte", "PocketBase", "SQLite", "JavaScript"],
    role: "Desarrollador full-stack, cliente real",
    duration: "Octubre de 2026",
    year: "2026",
    kind: "sistemas",
    category: "Cliente",
    status: "Primera versión",
    privateRepo: true,
    shots: [
      { src: "/images/work/mostrador/caja.webp", alt: "Caja de Mostrador con la venta en curso y el total en dólares y bolívares" },
      { src: "/images/work/mostrador/panel.webp", alt: "Panel del dueño con ventas, merma, fiado por cobrar y lo más vendido" },
      { src: "/images/work/mostrador/cobro.webp", alt: "Cobro mixto en dólares y bolívares" },
      { src: "/images/work/mostrador/existencias.webp", alt: "Existencias de inventario en kilos por producto" },
      { src: "/images/work/mostrador/kardex.webp", alt: "Kardex con entradas, ventas y transformaciones de un producto" },
    ],
    featured: true,
  },
  {
    slug: "ordo",
    title: "Ordo",
    summary:
      "Finanzas personales en bolívares, dólares, USDT y euros: deudas, suscripciones, metas y gastos, con tasas en vivo. Web instalable y app móvil en Flutter.",
    problem:
      "Llevar las finanzas personales en Venezuela es manejar varias monedas a la vez, con tasas que cambian a diario. En una hoja de cálculo no se sabe cuánto se debe de verdad ni qué toca pagar cada quincena, y hay que poder consultarlo desde el teléfono.",
    solution: [
      "Deudas en bolívares (BCV), dólares, USDT y euros con conversión automática y tasas en vivo, con respaldo manual.",
      "Suscripciones con aviso previo, planificador de pagos por quincena, compras a crédito con calendario de cuotas, metas de ahorro y registro de gastos por categoría.",
      "Seguridad por fila en Supabase: cada usuario solo ve y edita sus datos. Recordatorios por correo con Edge Functions.",
      "Web instalable (PWA) en Vue 3 sin paso de build, publicada en Vercel.",
      "App móvil en Flutter, en desarrollo, que reutiliza el mismo backend y tiene su propio sistema de componentes (paquete ordo_ui).",
    ],
    results: [
      { value: "4 monedas", label: "Bs (BCV), USD, USDT y EUR con conversión" },
      { value: "RLS", label: "cada usuario solo ve y edita sus propios datos" },
      { value: "1 backend", label: "para la web y la app en Flutter" },
    ],
    learnings: [
      "Modelar seguridad por fila y Edge Functions para una app sin servidor propio.",
      "Llevar un diseño web a Flutter con un sistema de componentes propio.",
    ],
    technologies: ["Vue", "Supabase", "PostgreSQL", "Flutter", "Dart"],
    role: "Desarrollador full-stack",
    duration: "2026",
    year: "2026",
    kind: "sistemas",
    category: "Full-Stack",
    status: "En uso",
    githubLink: "https://github.com/MoeFlowers/ordo",
    liveLink: "https://control-deudas-nine.vercel.app",
    liveLabel: "Abrir la app",
    shots: [
      { src: "/images/work/ordo/web.webp", alt: "Pantalla de inicio de sesión de Ordo" },
      { src: "/images/work/ordo/movil.webp", alt: "Ordo en el teléfono", device: "mobile" },
    ],
    cover: "/images/projects/Ordo.png",
    featured: false,
  },
  {
    slug: "punto-de-venta-inventario",
    title: "Sistema de gestión de ventas e inventario",
    summary:
      "Punto de venta, inventario y facturación en varias monedas para comercios, con una versión ligera que funciona sin instalar nada.",
    problem:
      "Los comercios en Venezuela necesitan vender, controlar inventario y facturar en dos monedas a la vez, sobre equipos modestos y muchas veces sin conexión estable. Un error de redondeo o una tasa vieja cuestan dinero.",
    solution: [
      "Ventas y facturación en dólares, bolívares o mixto, con cálculo de dinero de precisión exacta y la tasa BCV sincronizada a diario, con historial y respaldo sin conexión.",
      "Catálogo de productos, libro de movimientos de inventario auditable, clientes con límite de crédito, permisos por rol y reportes.",
      "Pantalla de venta que se maneja solo con el teclado, pensada para cajeros sin experiencia técnica.",
      "Versión ligera de un solo archivo que funciona en el navegador sin instalación ni servidor.",
      "Pruebas, análisis estático y documentación de arquitectura en el repositorio.",
    ],
    results: [
      { value: "USD y Bs", label: "ventas y facturación con la tasa BCV del día" },
      { value: "Exacto", label: "el dinero se calcula como decimal, sin errores de redondeo" },
      { value: "Sin internet", label: "la versión ligera funciona sin conexión ni instalación" },
    ],
    learnings: [
      "Adaptar la misma solución a negocios de distinto tamaño.",
      "Tratar el dinero como decimal exacto de punta a punta.",
    ],
    technologies: ["PHP", "MySQL", "JavaScript", "Tailwind CSS"],
    role: "Desarrollador full-stack (freelance)",
    duration: "2025 a 2026",
    year: "2025",
    kind: "sistemas",
    category: "Full-Stack",
    status: "En desarrollo",
    privateRepo: true,
    shots: [],
    cover: "/images/projects/PuntoDeVentaInventario.png",
    featured: false,
  },
  {
    slug: "puerto-vivo",
    title: "Puerto Vivo",
    summary:
      "Una terminal de contenedores en 3D que se mueve en tiempo real: buque, grúas, patio y aduana, con la ficha de cada contenedor al hacer clic.",
    problem:
      "La idea: mostrar en una sola vista viva cómo opera una terminal de contenedores, algo que los paneles de logística suelen reducir a tablas. Es un concepto con datos simulados y nombres ficticios.",
    solution: [
      "Escena 3D en tiempo real: un buque que se vacía, tres grúas pórtico, carretillas que llevan cada contenedor a su ranura del patio, aduana y escáner de rayos X.",
      "Clic en un contenedor, una grúa o el buque para ver su ficha con contenido, origen, peso y seguimiento por etapas. Buscador por código o tipo de carga.",
      "El contenedor, la grúa, la carretilla y el buque los modelé por script en Blender. El agua usa un shader propio.",
      "Recorrido automático de cámara para grabar video y un modo de calidad baja para equipos con gráfica integrada.",
    ],
    results: [
      { value: "130 a 170", label: "fotogramas por segundo con gráfica dedicada" },
      { value: "4", label: "modelos 3D generados por script en Blender" },
    ],
    technologies: ["React", "Three.js", "React Three Fiber", "Blender", "Vite", "Tailwind CSS"],
    role: "Diseño y desarrollo",
    duration: "Octubre de 2026",
    year: "2026",
    kind: "web3d",
    category: "3D en tiempo real",
    status: "Concepto",
    githubLink: "https://github.com/MoeFlowers/puerto-vivo",
    shots: [
      { src: "/images/work/puerto-vivo/terminal.webp", alt: "Terminal de contenedores en 3D con buque atracado, grúas y patio" },
      { src: "/images/work/puerto-vivo/grua.webp", alt: "Grúa pórtico modelada en Blender" },
    ],
    featured: true,
  },
  {
    slug: "landings-3d",
    title: "Landings 3D",
    summary:
      "Tres páginas de venta con una pieza 3D renderizada en Blender que se mueve con el scroll o sigue el cursor.",
    problem:
      "La idea: probar cómo una pieza 3D fotorrealista puede contar un producto en una página de venta sin volverla lenta. Son conceptos con marcas ficticias.",
    solution: [
      "Hotel boutique con un vuelo de cámara por el edificio, un cat café donde el gato sigue el cursor con la cabeza y la mirada, y un anillo inteligente que se desarma con el scroll.",
      "Las tres escenas las modelé y rendericé en Blender con Cycles en la GPU, y la web las reproduce como secuencias de fotogramas.",
      "Reproductor propio: descarga comprimida, decodificación fuera del hilo principal y fundido entre fotogramas para que el scroll no tenga tirones.",
    ],
    results: [
      { value: "3", label: "landings: hotel, cat café y anillo" },
      { value: "140", label: "fotogramas por segundo medidos en Chrome, aprox., sin tirones" },
    ],
    technologies: ["Next.js", "React", "Motion", "Blender", "Tailwind CSS"],
    role: "Diseño, 3D y desarrollo",
    duration: "Octubre de 2026",
    year: "2026",
    kind: "web3d",
    category: "Render 3D y web",
    status: "Concepto",
    githubLink: "https://github.com/MoeFlowers/landings-3d",
    shots: [
      { src: "/images/work/landings-3d/hotel.webp", alt: "Landing del hotel Grand Reserve con el edificio de noche" },
      { src: "/images/work/landings-3d/gato.webp", alt: "Landing de un cat café con el gato en 3D" },
      { src: "/images/work/landings-3d/anillo.webp", alt: "Landing de un anillo inteligente sobre fondo negro" },
      { src: "/images/work/landings-3d/hotel-movil.webp", alt: "Landing del hotel en el teléfono", device: "mobile" },
    ],
    featured: true,
  },
  {
    slug: "mordisco",
    title: "Mordisco",
    summary:
      "Web de comida rápida donde armas tu hamburguesa, perro caliente o pizza en 3D y la pides por WhatsApp. Concepto con datos ficticios.",
    problem:
      "La idea: que el cliente vea lo que pide mientras lo arma, en lugar de leer una lista de ingredientes.",
    solution: [
      "Armador 3D: cada ingrediente cae, se esparce o se vierte con su animación y el precio cambia al instante.",
      "Modelos hechos en código, sin archivos 3D externos ni texturas en imagen.",
      "Pedido editable que se guarda en el navegador y sale por WhatsApp, con pago en divisas, pago móvil o punto.",
      "El texto de la página llega primero y Three.js se carga aparte.",
    ],
    results: [
      { value: "75 KB", label: "de página (gzip) antes de cargar el 3D" },
      { value: "3", label: "productos armables: hamburguesa, perro caliente y pizza" },
    ],
    technologies: ["React", "Three.js", "React Three Fiber", "Vite", "Tailwind CSS"],
    role: "Diseño y desarrollo",
    duration: "Octubre de 2026",
    year: "2026",
    kind: "web3d",
    category: "3D en tiempo real",
    status: "Concepto",
    githubLink: "https://github.com/MoeFlowers/mordisco",
    shots: [{ src: "/images/work/mordisco/armador.webp", alt: "Armador de hamburguesa de Mordisco en 3D" }],
    featured: false,
  },
  {
    slug: "stockflow",
    title: "StockFlow",
    summary:
      "Página de producto, planes y videos promocionales de un sistema de inventario que se consulta escribiendo en WhatsApp.",
    problem:
      "La idea: presentar un inventario que el equipo de una tienda pueda usar sin aprender un sistema nuevo, desde el chat que ya usa todos los días, con planes claros para negocios pequeños.",
    solution: [
      "Página de producto con tres planes: instalación local con pago único, en línea, y en línea con control por WhatsApp.",
      "Demostración del control por chat: consultar existencias, registrar salidas y recibir avisos de stock bajo en el grupo.",
      "Dos videos promocionales, uno del producto y otro del plan con WhatsApp.",
    ],
    results: [
      { value: "3", label: "planes con lo que incluye cada uno" },
      { value: "2", label: "videos promocionales" },
    ],
    technologies: ["HTML", "CSS", "JavaScript", "Video"],
    role: "Producto, diseño y video",
    duration: "2026",
    year: "2026",
    kind: "web3d",
    category: "Producto y video",
    status: "Concepto",
    githubLink: "https://github.com/MoeFlowers/stockflow",
    shots: [
      { src: "/images/work/stockflow/planes.webp", alt: "Página de StockFlow: controla tu inventario y opéralo desde WhatsApp" },
      { src: "/images/work/stockflow/planes-movil.webp", alt: "Página de StockFlow en el teléfono", device: "mobile" },
    ],
    featured: false,
  },
  {
    slug: "perfil-github",
    title: "Puerto Docker, mi perfil de GitHub",
    summary:
      "Un barco portacontenedores en el perfil de GitHub donde cada visitante carga su contenedor. Lo mueven GitHub Actions y Docker.",
    problem:
      "La idea: que el perfil de GitHub no sea solo una lista de enlaces, sino algo con lo que el visitante pueda hacer algo.",
    solution: [
      "Un barco renderizado en Blender. El visitante pulsa «Carga tu contenedor», se abre un issue y su usuario aparece pintado en un contenedor a bordo.",
      "Una GitHub Action construye la imagen Docker (Python y Pillow), dibuja el contenedor, hace el commit, comenta y cierra el issue.",
      "Secciones que se actualizan solas cada día: último push, commits públicos del año, tasa BCV y una librería con un libro por repositorio público.",
    ],
    results: [
      { value: "120", label: "contenedores a bordo como máximo, uno por visitante" },
      { value: "Diario", label: "las secciones de datos se actualizan solas" },
    ],
    technologies: ["GitHub Actions", "Docker", "Python", "Blender"],
    role: "Diseño, 3D y automatización",
    duration: "Octubre de 2026",
    year: "2026",
    kind: "ia",
    category: "Automatización",
    status: "En uso",
    githubLink: "https://github.com/MoeFlowers/MoeFlowers",
    liveLink: "https://github.com/MoeFlowers",
    liveLabel: "Ver el perfil",
    shots: [{ src: "/images/work/perfil-github/barco.webp", alt: "Barco portacontenedores del perfil de GitHub de MoeFlowers" }],
    featured: false,
  },
  {
    slug: "curso-ia-empresarios",
    title: "Recupere su tiempo",
    summary:
      "Curso de IA práctica para empresarios sin experiencia técnica: presentación interactiva, temario e investigación de base.",
    problem:
      "Muchos dueños de negocio mayores no usan la inteligencia artificial porque nadie se la explicó con sus palabras ni con tareas de su día a día.",
    solution: [
      "Presentación interactiva para la primera clase: el método para pedirle cosas a la IA, casos de antes y después y un medidor de minutos ahorrados que se exporta a CSV.",
      "Temario de 13 semanas con una clase y un laboratorio práctico por semana, y un generador de temario según la modalidad.",
      "Investigación sobre cómo aprenden los adultos, cómo adoptan la tecnología y qué dicen los estudios sobre productividad con IA.",
    ],
    results: [
      { value: "13", label: "semanas de temario" },
      { value: "2", label: "sesiones por semana: clase y laboratorio" },
    ],
    technologies: ["HTML", "CSS", "JavaScript"],
    role: "Contenido, investigación y desarrollo",
    duration: "2026",
    year: "2026",
    kind: "ia",
    category: "Formación",
    status: "En preparación",
    githubLink: "https://github.com/MoeFlowers/ia-para-empresarios",
    shots: [
      { src: "/images/work/curso-ia/portada.webp", alt: "Portada de la presentación Recupere su tiempo con un reloj" },
      { src: "/images/work/curso-ia/portada-movil.webp", alt: "Presentación del curso en el teléfono", device: "mobile" },
    ],
    featured: false,
  },
  {
    slug: "recomendador-libros-ia",
    title: "Motor de recomendación con IA",
    summary:
      "Modelo de aprendizaje automático sobre una base de conocimiento, servido por una API en tiempo real.",
    problem:
      "Encontrar la siguiente lectura entre miles de opciones es lento: las listas genéricas no consideran los gustos de cada persona.",
    solution: [
      "Modelo de filtrado colaborativo con scikit-learn entrenado sobre un catálogo de más de 10.000 libros.",
      "API REST con FastAPI y Flask que responde recomendaciones en tiempo real.",
      "Limpieza y preparación de datos reproducible.",
      "Interfaz de demostración para probar las recomendaciones.",
    ],
    results: [
      { value: "85%", label: "de precisión validada con usuarios" },
      { value: "10.000+", label: "libros en la base de conocimiento" },
    ],
    learnings: ["Evaluar un modelo de recomendación con usuarios reales, no solo con métricas en el laboratorio."],
    technologies: ["Python", "scikit-learn", "FastAPI", "Flask"],
    role: "Desarrollador de ML y backend",
    duration: "2 meses",
    year: "2025",
    kind: "ia",
    category: "Machine Learning",
    status: "Prototipo",
    githubLink: "https://github.com/MoeFlowers/Sistema-de-Recomendaci-n-de-Libros-con-IA",
    shots: [],
    cover: "/images/projects/RecomendadorLibrosIA.png",
    featured: false,
  },
  {
    slug: "bots-procesamiento-datos",
    title: "Automatización de procesamiento de Big Data",
    summary:
      "Bots en Python que consumen varias APIs, consolidan los datos y actualizan paneles cada 5 minutos, con alertas.",
    problem:
      "Recolectar y consolidar datos a mano le costaba al equipo unas 15 horas a la semana, con errores frecuentes al copiar.",
    solution: [
      "Bots en Python que consumen varias APIs y consolidan los datos solos.",
      "Paneles que se actualizan cada 5 minutos.",
      "Alertas por chat y correo cuando un valor se sale de lo normal.",
      "Tareas programadas con reintentos y manejo de errores.",
    ],
    results: [
      { value: "15 h", label: "de trabajo manual a la semana eliminadas" },
      { value: "5 min", label: "entre cada actualización de los paneles" },
    ],
    technologies: ["Python", "Pandas"],
    role: "Desarrollador de automatización",
    duration: "4 meses",
    year: "2025",
    kind: "ia",
    category: "Automatización",
    status: "Entregado",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/bot",
    shots: [],
    featured: false,
  },
  {
    slug: "servicio-comunitario",
    title: "Plataforma web de servicio comunitario",
    summary: "App web usada por más de 2.000 personas de una comunidad; el tiempo de consulta de datos bajó un 80%.",
    problem:
      "Una comunidad de más de 2.000 personas dependía de registros manuales para consultar y actualizar sus datos. Cada consulta era una espera larga y los registros se duplicaban o se perdían.",
    solution: [
      "Lideré el desarrollo de punta a punta: modelo de datos, backend y frontend.",
      "Operaciones seguras sobre PHP y MySQL, con validación y control de acceso.",
      "Interfaz responsive pensada para personas sin experiencia técnica.",
      "Despliegue con GitHub Actions y capacitación del equipo que administra la plataforma.",
    ],
    results: [
      { value: "2.000+", label: "personas usan la plataforma" },
      { value: "-80%", label: "en el tiempo de consulta de datos" },
      { value: "97%", label: "de satisfacción de los usuarios" },
    ],
    learnings: [
      "Liderar un proyecto real con usuarios y plazos, de enero a junio de 2024.",
      "Priorizar la simplicidad cuando quien usa el sistema no es técnico.",
    ],
    technologies: ["PHP", "MySQL", "JavaScript", "Tailwind CSS"],
    role: "Líder técnico y desarrollador full-stack",
    duration: "Enero a junio de 2024",
    year: "2024",
    kind: "sistemas",
    category: "Full-Stack",
    status: "Entregado",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/comunity",
    shots: [],
    cover: "/images/projects/ServicioComunitario.png",
    featured: false,
  },
  {
    slug: "clinica-odontologica",
    title: "Sistema web para clínica odontológica",
    summary: "Historias clínicas, citas y facturación para una clínica que trabajaba en papel: 6 horas semanales menos de trabajo administrativo.",
    problem:
      "La clínica llevaba historias, citas y facturas en papel y hojas de cálculo. Buscar la historia de un paciente tomaba minutos, se facturaba a mano y las citas se perdían por falta de recordatorios.",
    solution: [
      "Sistema a medida con pacientes, odontograma interactivo diente por diente, citas y facturación.",
      "Todas las historias clínicas digitalizadas, con búsqueda instantánea.",
      "Facturación con reportes y exportación a Excel.",
      "Citas con recordatorios automáticos por correo.",
    ],
    results: [
      { value: "6 h", label: "de trabajo administrativo ahorradas cada semana" },
      { value: "100%", label: "de las historias digitalizadas" },
      { value: "97%", label: "de satisfacción del personal" },
    ],
    learnings: [
      "Levantar requisitos con personas no técnicas y convertirlos en módulos concretos.",
      "Construir un odontograma interactivo reutilizable con JavaScript.",
    ],
    technologies: ["PHP", "JavaScript", "MySQL", "Tailwind CSS"],
    role: "Desarrollador full-stack (freelance)",
    duration: "6 meses",
    year: "2023",
    kind: "sistemas",
    category: "Desarrollo Web",
    status: "Entregado",
    githubLink: "https://github.com/MoeFlowers/Portfolio/tree/clinica",
    shots: [],
    cover: "/images/projects/ClinicaOdontologica.png",
    featured: false,
  },
];

export const featuredProjects = projects.filter((p) => p.featured);

/**
 * Los cinco casos que recorre la versión 3D. La V2 tiene sus pantallas de monitor
 * horneadas en Blender para estos cinco y en este orden: no cambiar sin re-hornear.
 */
export const studioProjectSlugs = [
  "ordo",
  "punto-de-venta-inventario",
  "clinica-odontologica",
  "servicio-comunitario",
  "recomendador-libros-ia",
] as const;

export function getProject(slug: string): Project | undefined {
  return projects.find((p) => p.slug === slug);
}
