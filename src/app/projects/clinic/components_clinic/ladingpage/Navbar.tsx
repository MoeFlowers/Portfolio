"use client";
import Image from 'next/image'


export default function Navbar() {
    return (
        <nav className="bg-white shadow-lg">
            <div className="max-w-6xl mx-auto px-4">
                <div className="flex justify-between items-center py-4">
                    <div className="flex items-center space-x-4">
                        <Image
                            src="/projects/clinic/assets/images/logo.jpg"
                            width={45}
                            height={45}    
                            alt="Clinic Logo"
                            className="h-12"
                        />
                        <span className="text-2xl font-bold text-blue-800">
                            Clínica Odontológica
                        </span>
                    </div>
                    <div className="hidden md:flex items-center space-x-8">
                        <a
                            href="/projects/clinic/login"  // Cambia esto por tu ruta Next.js
                            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
                        >
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    );
}
