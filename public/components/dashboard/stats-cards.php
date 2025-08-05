<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total pacientes -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Total pacientes</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                <?php
                require_once '../../components/database/database.php';
                $conn = getDBConnection();
                $result = $conn->query("SELECT COUNT(*) AS total FROM pacientes");
                $row = $result->fetch_assoc();
                echo $row['total'];
                ?>
            </dd>
        </div>
    </div>

    <!-- Total empleados -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Total empleados</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                <?php
                $result = $conn->query("SELECT COUNT(*) AS total FROM empleados");
                $row = $result->fetch_assoc();
                echo $row['total'];
                ?>
            </dd>
        </div>
    </div>

    <!-- Citas hoy -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Citas hoy</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                <?php
                $today = date('Y-m-d');
                $result = $conn->query("SELECT COUNT(*) AS total FROM citas WHERE DATE(fecha_hora) = '$today'");
                $row = $result->fetch_assoc();
                echo $row['total'];
                ?>
            </dd>
        </div>
    </div>

    <!-- Historias clínicas -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">Historias clínicas</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                <?php
                $result = $conn->query("SELECT COUNT(*) AS total FROM historias_clinicas");
                $row = $result->fetch_assoc();
                echo $row['total'];
                ?>
            </dd>
        </div>
    </div>
</div>