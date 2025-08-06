export default function HeroSection() {
    return (
        <section className="py-20 px-4 bg-gradient-to-r from-blue-50 to-cyan-50">
            <div className="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 className="text-4xl md:text-5xl font-bold text-blue-900 leading-tight mb-6">
                        Excelencia en <span className="text-cyan-600">Salud Bucal Militar</span>
                    </h1>
                    <p className="text-lg text-gray-600 mb-8">
                        Atención odontológica especializada para el personal militar activo,
                        reserva activa y sus familias en IPSFA Barquisimeto.
                    </p>
                    <div className="col p-2 gap-4">
                        <a
                            href="/projects/clinic/login"
                            className="bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition duration-300 font-medium text-center"
                        >
                            Acceder al Sistema
                        </a>
                    </div>
                    <div className="mt-6">
                        <a
                            href="https://maps.app.goo.gl/frhG5SV8378uAauf9"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center text-blue-600 hover:text-blue-800 transition duration-300"
                        >
                            {/* Icono de ubicación */}
                            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 32 32">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Visítanos en Barquisimeto
                        </a>
                    </div>
                </div>
                <div className="relative">
                    <div className="bg-blue-600 rounded-2xl h-80 w-full absolute -z-10 top-6 left-6"></div>
                    <img
                        src="/projects/clinic/assets/images/img1.jpg"
                        alt="Equipo odontológico IPSFA"
                        className="rounded-2xl h-80 w-full object-cover shadow-xl"
                    />
                </div>
            </div>
        </section>
    );
}