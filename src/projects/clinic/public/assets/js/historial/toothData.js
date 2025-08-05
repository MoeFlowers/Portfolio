// Datos de posición y tipos de dientes
export const toothTypes = {
    // Dientes superiores
    '1': { name: "Tercer Molar Superior Izquierdo", type: "molar" },
    '2': { name: "Segundo Molar Superior Izquierdo", type: "molar" },
    '3': { name: "Primer Molar Superior Izquierdo", type: "molar" },
    '4': { name: "Segundo Premolar Superior Izquierdo", type: "premolar" },
    '5': { name: "Primer Premolar Superior Izquierdo", type: "premolar" },
    '6': { name: "Canino Superior Izquierdo", type: "canino" },
    '7': { name: "Incisivo Lateral Superior Izquierdo", type: "incisivo" },
    '8': { name: "Incisivo Central Superior Izquierdo", type: "incisivo" },
    '9': { name: "Incisivo Central Superior Derecho", type: "incisivo" },
    '10': { name: "Incisivo Lateral Superior Derecho", type: "incisivo" },
    '11': { name: "Canino Superior Derecho", type: "canino" },
    '12': { name: "Primer Premolar Superior Derecho", type: "premolar" },
    '13': { name: "Segundo Premolar Superior Derecho", type: "premolar" },
    '14': { name: "Primer Molar Superior Derecho", type: "molar" },
    '15': { name: "Segundo Molar Superior Derecho", type: "molar" },
    '16': { name: "Tercer Molar Superior Derecho", type: "molar" },

    // Dientes inferiores
    '17': { name: "Tercer Molar Inferior Izquierdo", type: "molar" },
    '18': { name: "Segundo Molar Inferior Izquierdo", type: "molar" },
    '19': { name: "Primer Molar Inferior Izquierdo", type: "molar" },
    '20': { name: "Segundo Premolar Inferior Izquierdo", type: "premolar" },
    '21': { name: "Primer Premolar Inferior Izquierdo", type: "premolar" },
    '22': { name: "Canino Inferior Izquierdo", type: "canino" },
    '23': { name: "Incisivo Lateral Inferior Izquierdo", type: "incisivo" },
    '24': { name: "Incisivo Central Inferior Izquierdo", type: "incisivo" },
    '25': { name: "Incisivo Central Inferior Derecho", type: "incisivo" },
    '26': { name: "Incisivo Lateral Inferior Derecho", type: "incisivo" },
    '27': { name: "Canino Inferior Derecho", type: "canino" },
    '28': { name: "Primer Premolar Inferior Derecho", type: "premolar" },
    '29': { name: "Segundo Premolar Inferior Derecho", type: "premolar" },
    '30': { name: "Primer Molar Inferior Derecho", type: "molar" },
    '31': { name: "Segundo Molar Inferior Derecho", type: "molar" },
    '32': { name: "Tercer Molar Inferior Derecho", type: "molar" }
};

export const teethPositions = [
    // Superiores izquierdos (1-8) - Espejo de los derechos pero en lado izquierdo
    { number: 1, x: 478, y: 148, width: 25, height: 25 },  // Tercer molar superior izquierdo
    { number: 2, x: 475, y: 125, width: 25, height: 25 },  // Segundo molar superior izquierdo
    { number: 3, x: 480, y: 102, width: 25, height: 25 },  // Primer molar superior izquierdo
    { number: 4, x: 485, y: 85, width: 25, height: 25 },  // Segundo premolar superior izquierdo
    { number: 5, x: 495, y: 65, width: 25, height: 25 },  // Primer premolar superior izquierdo
    { number: 6, x: 505, y: 48, width: 25, height: 25 }, // Canino superior izquierdo
    { number: 7, x: 515, y: 35, width: 27, height: 27 }, // Incisivo lateral superior izquierdo
    { number: 8, x: 538, y: 29, width: 27, height: 27 },  // Incisivo central superior izquierdo
    // Superiores derechos (9-16)
    { number: 9, x: 560, y: 29, width: 25, height: 25 },
    { number: 10, x: 580, y: 35, width: 25, height: 25 },
    { number: 11, x: 592, y: 50, width: 25, height: 25 },
    { number: 12, x: 601, y: 68, width: 25, height: 25 },
    { number: 13, x: 612, y: 85, width: 25, height: 25 },
    { number: 14, x: 618, y: 105, width: 25, height: 25 },
    { number: 15, x: 620, y: 125, width: 27, height: 27 },
    { number: 16, x: 619, y: 150, width: 27, height: 27 },

    // Inferiores izquierdos (17-24) - Con ajuste para el 24
    { number: 17, x: 622, y: 208, width: 30, height: 30 },  // Tercer molar inferior izquierdo
    { number: 18, x: 622, y: 230, width: 30, height: 30 },  // Segundo molar inferior izquierdo
    { number: 19, x: 618, y: 255, width: 30, height: 30 },  // Primer molar inferior izquierdo
    { number: 20, x: 608, y: 275, width: 30, height: 30 },  // Segundo premolar inferior izquierdo
    { number: 21, x: 595, y: 300, width: 25, height: 25 },  // Primer premolar inferior izquierdo
    { number: 22, x: 585, y: 315, width: 25, height: 25 },  // Canino inferior izquierdo
    { number: 23, x: 575, y: 320, width: 25, height: 25 },  // Incisivo lateral inferior izquierdo
    { number: 24, x: 560, y: 325, width: 25, height: 25 },  // Incisivo central inferior izquierdo (ajustado)

    // Inferiores derechos (25-32) - Simétricos y alineados con la curva
    { number: 25, x: 540, y: 325, width: 25, height: 25 },  // Incisivo central inferior derecho
    { number: 26, x: 525, y: 320, width: 25, height: 25 },  // Incisivo lateral inferior derecho
    { number: 27, x: 515, y: 315, width: 25, height: 25 },  // Canino inferior derecho
    { number: 28, x: 505, y: 300, width: 25, height: 25 },  // Primer premolar inferior derecho
    { number: 29, x: 492, y: 275, width: 30, height: 30 },  // Segundo premolar inferior derecho
    { number: 30, x: 482, y: 255, width: 30, height: 30 },  // Primer molar inferior derecho
    { number: 31, x: 478, y: 230, width: 30, height: 30 },  // Segundo molar inferior derecho
    { number: 32, x: 478, y: 208, width: 30, height: 30 }   // Tercer molar inferior derecho
];