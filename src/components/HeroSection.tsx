import Link from "next/link";

export default function HeroSection() {
    return (
        <section className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 text-center">
            {/* Efecto de fondo sutil */}
            <div className="absolute inset-0 -z-10 overflow-hidden">
                <div className="absolute left-1/3 top-1/4 w-72 h-72 bg-[#1DA1F2]/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" />
                <div className="absolute right-1/3 bottom-1/4 w-72 h-72 bg-[#1DA1F2]/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000" />
            </div>

            {/* Contenido principal */}
            <div className="max-w-3xl mx-auto">
                <h1 className="text-5xl md:text-6xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">
                    <span className="block">Moises Flores</span>
                    <span className="block bg-gradient-to-r from-[#1DA1F2] to-blue-600 bg-clip-text text-transparent">
                        Desarrollador Full-Stack
                    </span>
                </h1>

                <p className="mt-6 text-xl md:text-2xl text-gray-600 dark:text-gray-300 leading-relaxed">
                    Desarrollo soluciones <span className="font-semibold text-indigo-600 dark:text-indigo-400">end-to-end</span> con
                    <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> React</span>,
                    <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Next.js</span>,
                    <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Vue</span> en el frontend y
                    <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Node.js</span>,
                    <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Python</span> en el backend.
                    Automatizo procesos con <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]">JavaScript</span> y
                    <span className="font-semibold text-[#1DA1F2] dark:text-[#61DAFB]"> Python</span> para maximizar la eficiencia.
                </p>

                {/* CTA Buttons */}
                <div className="mt-10 flex flex-col sm:flex-row justify-center gap-4">
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

            {/* Indicador scroll */}
            <div className="mt-16 animate-bounce">
                <div className="w-6 h-10 border-2 border-[#1DA1F2] dark:border-[#1DA1F2] rounded-full flex justify-center">
                    <div className="w-1 h-2 bg-[#1DA1F2] dark:bg-[#1DA1F2] rounded-full mt-2 animate-scrollIndicator" />
                </div>
            </div>
        </section>
    );
}
