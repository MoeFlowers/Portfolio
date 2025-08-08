"use client";
import { motion } from "framer-motion";
import { FiAward, FiCode, FiLayers } from "react-icons/fi";

export default function AboutSection() {
    return (
        <section id="about" className="py-20 px-4 bg-white dark:bg-gray-900">
            <div className="max-w-6xl mx-auto">
                {/* Encabezado mejorado */}
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    viewport={{ once: true }}
                    className="text-center mb-16"
                >
                    <span className="inline-block px-3 py-1 text-lg font-medium rounded-full bg-[#1DA1F2]/10 text-[#1DA1F2] mb-4">
                        Sobre Mí
                    </span>
                    <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Más que un desarrollador, un <span className="text-[#1DA1F2]">solucionador</span> de problemas
                    </h2>
                    <p className="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                        Transformo desafíos técnicos en oportunidades de negocio
                    </p>
                </motion.div>

                {/* Contenido principal mejorado */}
                <div className="grid md:grid-cols-2 gap-12 items-center">
                    {/* Texto descriptivo mejorado */}
                    <motion.div
                        initial={{ opacity: 0, x: -20 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.5, delay: 0.2 }}
                        viewport={{ once: true }}
                    >
                        <h3 className="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
                            <span className="text-[#1DA1F2]">Full-Stack Developer</span> con enfoque en resultados
                        </h3>
                        <div className="space-y-4">
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Especializado en construir aplicaciones web <span className="font-medium text-gray-800 dark:text-gray-200">escalables</span> y <span className="font-medium text-gray-800 dark:text-gray-200">eficientes</span>, domino todo el ciclo de desarrollo desde la arquitectura backend hasta interfaces de usuario intuitivas.
                            </p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Mi experiencia abarca desde <span className="font-medium text-gray-800 dark:text-gray-200">React/Next.js/Vue</span> en el frontend hasta <span className="font-medium text-gray-800 dark:text-gray-200">Node.js/Python</span> en el backend, con especial énfasis en automatización de procesos.
                            </p>
                            <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Valorado por mi capacidad para <span className="font-medium text-gray-800 dark:text-gray-200">comunicarme claramente</span> en español e inglés, y por mi enfoque <span className="font-medium text-gray-800 dark:text-gray-200">autodidacta</span> para mantenerme actualizado con las últimas tecnologías.
                            </p>
                        </div>
                    </motion.div>

                    {/* Estadísticas y habilidades mejoradas */}
                    <motion.div
                        initial={{ opacity: 0, x: 20 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.5, delay: 0.4 }}
                        viewport={{ once: true }}
                        className="space-y-6"
                    >
                        {/* Item 1 - Experiencia */}
                        <motion.div
                            whileHover={{ y: -5 }}
                            className="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[#1DA1F2]/30 transition-all duration-300"
                        >
                            <div className="flex items-start gap-4">
                                <div className="p-3 bg-[#1DA1F2]/10 rounded-lg">
                                    <FiCode className="w-6 h-6 text-[#1DA1F2]" />
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        +3 Años de Experiencia
                                    </h4>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        Desarrollando soluciones web completas y sistemas de automatización que han procesado millones de datos
                                    </p>
                                </div>
                            </div>
                        </motion.div>

                        {/* Item 2 - Educación */}
                        <motion.div
                            whileHover={{ y: -5 }}
                            className="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[#1DA1F2]/30 transition-all duration-300"
                        >
                            <div className="flex items-start gap-4">
                                <div className="p-3 bg-[#1DA1F2]/10 rounded-lg">
                                    <FiAward className="w-6 h-6 text-[#1DA1F2]" />
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        Formación Académica
                                    </h4>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        Ingeniería en Sistemas - UNEFA (2020-2025)<br />
                                        Cursos especializados en arquitectura de software y DevOps
                                    </p>
                                </div>
                            </div>
                        </motion.div>

                        {/* Item 3 - Enfoque */}
                        <motion.div
                            whileHover={{ y: -5 }}
                            className="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[#1DA1F2]/30 transition-all duration-300"
                        >
                            <div className="flex items-start gap-4">
                                <div className="p-3 bg-[#1DA1F2]/10 rounded-lg">
                                    <FiLayers className="w-6 h-6 text-[#1DA1F2]" />
                                </div>
                                <div>
                                    <h4 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                        Enfoque Full-Stack
                                    </h4>
                                    <p className="text-gray-600 dark:text-gray-400">
                                        Desarrollo integral desde APIs robustas hasta interfaces de usuario optimizadas, con énfasis en performance y seguridad
                                    </p>
                                </div>
                            </div>
                        </motion.div>
                    </motion.div>
                </div>
            </div>
        </section>
    );
}