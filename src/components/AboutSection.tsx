"use client";
import { motion } from "framer-motion";
import { FiAward, FiCode, FiClock } from "react-icons/fi";

export default function AboutSection() {
    return (
        <section id="about" className="py-20 px-4 bg-white dark:bg-gray-900">
            <div className="max-w-6xl mx-auto">
                {/* Encabezado */}
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    viewport={{ once: true }}
                    className="text-center mb-16"
                >
                    <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Sobre Mí
                    </h2>
                    <div className="w-20 h-1 bg-gray-200 dark:bg-gray-700 mx-auto"></div>
                </motion.div>

                {/* Contenido principal */}
                <div className="grid md:grid-cols-2 gap-12 items-center">
                    {/* Texto descriptivo */}
                    <motion.div
                        initial={{ opacity: 0, x: -20 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.5, delay: 0.2 }}
                        viewport={{ once: true }}
                    >
                        <h3 className="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
                            Desarrollador Full-Stack orientado a resultados
                        </h3>
                        <p className="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Convierto datos en productos útiles, optimizo procesos y mejoro la UX.
                            Domino el ciclo completo de desarrollo (backend → frontend) y disfruto
                            el trabajo asíncrono en equipos distribuidos ágiles.
                        </p>
                        <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
                            Comunicación clara en español e inglés. Autodidacta por naturaleza
                            con 3+ años creando soluciones web y bots en Python.
                        </p>
                    </motion.div>

                    {/* Estadísticas y habilidades */}
                    <motion.div
                        initial={{ opacity: 0, x: 20 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.5, delay: 0.4 }}
                        viewport={{ once: true }}
                        className="space-y-6"
                    >
                        {/* Item 1 */}
                        <div className="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div className="flex items-start gap-4">
                                <div className="p-3 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200">
                                    <FiCode className="w-6 h-6 text-green-500 dark:text-green-400" />
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        Experiencia
                                    </h4>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        3+ años desarrollando soluciones web y bots de automatización
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Item 2 */}
                        <div className="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div className="flex items-start gap-4">
                                <div className="p-3 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200">
                                    <FiAward className="w-6 h-6 text-yellow-500 dark:text-yellow-400" />
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        Educación
                                    </h4>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        Ingeniería en Sistemas - UNEFA (2020-2025)
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Item 3 */}
                        <div className="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div className="flex items-start gap-4">
                                <div className="p-3 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-800 dark:text-gray-200">
                                    <FiClock className="w-6 h- text-blue-500 dark:text-blue-400" />
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        Disponibilidad
                                    </h4>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        Tiempo completo, parcial o por proyecto. Preferencia por contratos remotos/híbridos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </motion.div>
                </div>
            </div>
        </section>
    );
}