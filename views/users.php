<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users mr-3 text-blue-500"></i>Lista de Usuarios
            </h1>
            <p class="text-gray-600 mt-2">Gestiona todos los empleados registrados en el sistema</p>
        </div>
        <a href="index.php?action=create" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition duration-200 flex items-center">
            <i class="fas fa-user-plus mr-2"></i>Nuevo Usuario
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cargo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Ingreso</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Días Trabajados</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contrato</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($usuarios as $row) { ?>
                        <tr class="hover:bg-gray-50 transition duration-150">

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($row['nombre']) ?></div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($row['correo_electronico']) ?></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $row['id_rol'] == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                                    <?= $row['id_rol'] == 1 ? '👨‍💼 Desarrollador' : '👑 Lider de Area' ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?= date('d/m/Y', strtotime($row['fecha_ingreso'])) ?>
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                    <?= $row['dias_trabajados'] ?> días hábiles
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <?php if ($row['contrato']) { ?>
                                    <a href="<?= $row['contrato'] ?>" target="_blank" class="text-blue-600 hover:text-blue-800 transition duration-200 flex items-center">
                                        <i class="fas fa-file-pdf mr-2"></i>Ver PDF
                                    </a>
                                <?php } else { ?>
                                    <span class="text-gray-400 flex items-center">
                                        <i class="fas fa-times-circle mr-2"></i>No disponible
                                    </span>
                                <?php } ?>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="index.php?action=edit&id=<?= $row['id'] ?>" class="bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-yellow-600 transition duration-200 flex items-center">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </a>
                                    <button onclick="if(confirm('¿Estás seguro de eliminar a <?= htmlspecialchars($row['nombre']) ?>? ⚠️ Esta acción no se puede deshacer.')) window.location.href='controllers/usuarioController.php?action=delete&id=<?= $row['id'] ?>'"
                                        class="bg-red-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-600 transition duration-200 flex items-center">
                                        <i class="fas fa-trash mr-1"></i>Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow border border-gray-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Usuarios</p>
                    <p class="text-2xl font-bold text-gray-800"><?= count($usuarios) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>