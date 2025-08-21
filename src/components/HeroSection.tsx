"use client";
import Link from "next/link";
import Image from "next/image";
import { useTheme } from "next-themes";
import { FaReact, FaNodeJs, FaPython, FaHtml5, FaCss3Alt, FaJs } from "react-icons/fa";

export default function HeroSection() {
    const { theme } = useTheme();

    return (
        <section className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div className="flex flex-col md:flex-row items-center gap-12">
                {/* Lado Izquierdo: Foto + Iconos Orbitando */}
                <div className="relative w-64 h-64 flex items-center justify-center">
                    {/* Imagen Circular */}
                    <div
                        className="relative w-48 h-48 rounded-full overflow-hidden border-4 border-blue-500 transition-colors duration-300"
                    >

                        <Image
                            src="/images/profilephoto.png"
                            alt="Moises Flores"
                            fill
                            className="object-cover"
                        />
                    </div>

                    {/* Iconos Orbitando */}
                    <div className="absolute w-full h-full animate-spin-slow">
                        <div className="absolute -top-6 left-1/2 -translate-x-1/2">
                            <FaReact size={32} className="text-[#61DAFB]" />
                        </div>
                        <div className="absolute top-1/2 -right-6 -translate-y-1/2">
                            <FaNodeJs size={32} className="text-green-500" />
                        </div>
                        <div className="absolute -bottom-6 left-1/2 -translate-x-1/2">
                            <FaPython size={32} className="text-yellow-400" />
                        </div>
                        <div className="absolute top-1/2 -left-6 -translate-y-1/2">
                            <FaJs size={32} className="text-yellow-300" />
                        </div>
                        <div className="absolute top-10 -right-4">
                            <FaHtml5 size={32} className="text-orange-500" />
                        </div>
                        <div className="absolute -top-4 right-10">
                            <FaCss3Alt size={32} className="text-blue-400" />
                        </div>
                    </div>
                </div>

                {/* Lado Derecho: Texto y Botones */}
                <div className="text-center md:text-left max-w-lg">
                    <h1 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
                        Moises Flores
                        <span className="block bg-gradient-to-r from-[#1DA1F2] to-blue-600 bg-clip-text text-transparent">
                            Desarrollador Full-Stack
                        </span>
                    </h1>
                    <p className="mt-6 text-lg md:text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
                        Desarrollo soluciones <span className="font-semibold text-indigo-600 dark:text-indigo-400">end-to-end</span> con
                        <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> React</span>,
                        <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Next.js</span>,
                        <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Vue</span> en el frontend y
                        <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Node.js</span>,
                        <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Python</span> en el backend.
                    </p>

                    {/* Botones */}
                    <div className="mt-8 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <Link
                            href="#projects"
                            className="px-8 py-3.5 text-base font-semibold rounded-lg bg-[#1DA1F2] text-white hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-[#1DA1F2]/30"
                        >
                            Ver mis proyectos
                        </Link>
                        <Link
                            href="#contact"
                            className="px-8 py-3.5 text-base font-semibold rounded-lg border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
                        >
                            Contactarme
                        </Link>
                    </div>
                </div>
            </div>

            <style jsx>{`
                .animate-spin-slow {
                    animation: spin 12s linear infinite;
                }
                @keyframes spin {
                    from {
                        transform: rotate(0deg);
                    }
                    to {
                        transform: rotate(360deg);
                    }
                }
            `}</style>
        </section>
    );
}
