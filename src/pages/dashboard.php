<?php
session_start();
include '../../src/controllers/log_action.php';

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    log_action($id_usuario, 'Acceso', 'Entró a la sección Inicio');
} else {
    // Redirigir si no está autenticado
    header("Location: ../index.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../favicon.ico" type="image/x-icon" />
  <link href="../output.css" rel="stylesheet" />
  <title>Dashboard</title>
  <script src="./js/sweetalert2.all.min.js"></script>
</head>

<body class="font-roboto bg-gray-100 min-h-screen overflow-x-hidden">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php
    include '../components/aside.php';
    ?>
    <div class="ml-24 lg:ml-64 flex-grow flex flex-col">
      <!--Cartas de Informacion-->
      <main class="flex-grow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Card 1 -->
          <div class="w-64 lg:w-auto bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
            <?php include '../controllers/dashboard/numpersonas.php'; ?>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Habitantes</h3>
            <p class="text-2xl mt-2 text-blue-600 font-semibold">
              <?php echo $total_personas; ?>
            </p>
          </div>
          <!-- Card 2 -->
          <div class="w-64 lg:w-auto bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
            <?php include '../controllers/dashboard/numcasas.php'; ?>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Casas Censadas</h3>
            <p class="text-2xl mt-2 text-green-600 font-semibold">
              <?php echo $total_casas; ?>
            </p>
          </div>
          <!-- Card 3 -->
          <div class="w-64 lg:w-auto bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
            <?php include '../controllers/dashboard/numfamilias.php'; ?>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Familias</h3>
            <p class="text-2xl mt-2 text-yellow-600 font-semibold">
              <?php echo $total_familias; ?>
            </p>
          </div>
          <!-- Card 4 -->
          <div class="w-64 lg:w-auto bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
            <?php include '../controllers/dashboard/numbeneficios.php'; ?>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Beneficios Activos</h3>
            <p class="text-2xl mt-2 text-yellow-600 font-semibold">
              <?php echo $total_beneficios; ?>
            </p>
          </div>
        </div>
      </main>
      <!--Ultimos Habitantes-->
      <div class="container ml-4 mt-6 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
        <section class="container px-4 mx-auto">
          <div class="flex items-center justify-between border-b pb-2 mb-4">
            <h2 class="pr-2 text-xl font-bold text-gray-800 dark:text-white">Ultimos habitantes</h2>
          </div>
          <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                      <tr>
                        <th class="py-3.5 px-4 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Nombre y Apellido</th>
                        <th class="px-12 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3.5 text-sm font-normal text-left text-gray-500 dark:text-gray-400">Cedula</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                      <?php include '../controllers/dashboard/ultimoshabitantes.php'; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!--Mapa-->
      <div class="container ml-4 mt-6 bg-gray-50 dark:bg-gray-800 shadow-md rounded-lg p-4">
        <div class="flex items-center justify-between border-b pb-2 mb-4">
          <h2 class="pr-2 text-xl font-bold text-gray-800 dark:text-white">Mapa</h2>
        </div>
        <div class="relative overflow-hidden rounded-lg">
          <iframe
            class="w-full h-[600px] rounded-lg"
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2351.358949731758!2d-69.45901966046247!3d10.023483632492718!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1ses-419!2sco!4v1715788727892!5m2!1ses-419!2sco"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

      </div>
    </div>
  </div>

  <!-- Mostrar alertas con SweetAlert2 -->
  <?php
  if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{$mensaje['titulo']}',
                    text: '{$mensaje['texto']}',
                    icon: '{$mensaje['icono']}',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    unset($_SESSION['mensaje']); // Eliminar el mensaje después de mostrarlo
  }
  ?>
</body>

</html>