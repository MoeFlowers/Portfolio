<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">
                Bienvenido,
                <?php
                if ($_SESSION['tipo_usuario'] === 'Empleado') {
                    echo $_SESSION['empleado']['primer_nombre'] . ' ' . $_SESSION['empleado']['primer_apellido'];
                } else {
                    echo $_SESSION['paciente']['primer_nombre'] . ' ' . $_SESSION['paciente']['primer_apellido'];
                }
                ?>
            </h1>

            <p class="mt-2">Aquí tienes un resumen de tu actividad reciente</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-tooth text-white opacity-20 text-6xl"></i>
        </div>
    </div>
</div>