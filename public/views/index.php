<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPSFANB - Historial Clínico Dental</title>
    <link rel="icon" href="/src/assets/images/favicon.ico" type="image/x-icon">
    <link href="/src/assets/css/styles.css" rel="stylesheet">
    <link href="output.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-50 to-cyan-50 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="/src/assets/images/logo.jpg" alt="IPSFANB Logo" class="h-12">
                    <span class="text-2xl font-bold text-blue-800">IPSFANB Dental</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-blue-700 font-medium">Inicio</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Servicios</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Nosotros</a>
                    <a href="/login.html"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">Iniciar
                        Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-blue-900 leading-tight mb-6">
                    Gestión Moderna de <span class="text-cyan-600">Historiales Dentales</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8">
                    Solución digital integral para el manejo de historiales clínicos dentales del IPSFANB.
                    Seguro, eficiente y diseñado para profesionales de la salud dental.
                </p>
                <div class="flex space-x-4">
                    <a href="/login.html"
                        class="bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition duration-300 font-medium">
                        Acceder al Sistema
                    </a>
                    <a href="#"
                        class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition duration-300 font-medium">
                        Más Información
                    </a>
                </div>
            </div>
            <div class="relative">
                <div class="bg-blue-600 rounded-2xl h-80 w-full absolute -z-10 top-6 left-6"></div>
                <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
                    alt="Dentista trabajando" class="rounded-2xl h-80 w-full object-cover shadow-xl">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-blue-900 mb-12">Características Principales</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-blue-50 p-6 rounded-xl">
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-800 mb-2">Historiales Digitales</h3>
                    <p class="text-gray-600">Acceso rápido y seguro a los historiales clínicos dentales de los
                        pacientes.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl">
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-800 mb-2">Seguridad Garantizada</h3>
                    <p class="text-gray-600">Protección de datos con cifrado avanzado y controles de acceso.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl">
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-800 mb-2">Agenda Integrada</h3>
                    <p class="text-gray-600">Gestión de citas y recordatorios automáticos para pacientes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <img src="/src/assets/images/logo-removebg-preview.png" alt="IPSFANB Logo" class="h-12 mb-4">
                    <p class="text-blue-200">Sistema de gestión de historiales clínicos dentales para el IPSFANB.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white">Inicio</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white">Servicios</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white">Nosotros</a></li>
                        <li><a href="/login.html" class="text-blue-200 hover:text-white">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-blue-200">
                        <li>Caracas, Venezuela</li>
                        <li>info@ipsfanb.com</li>
                        <li>+58 212 123 4567</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-blue-200 hover:text-white">Términos y Condiciones</a></li>
                        <li><a href="#" class="text-blue-200 hover:text-white">Política de Privacidad</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-8 pt-8 text-center text-blue-300">
                <p>© 2023 IPSFANB Dental. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>