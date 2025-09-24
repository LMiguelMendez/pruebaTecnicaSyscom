<?php
require_once __DIR__ . '/../controllers/usuarioController.php';

$controller = new UsuarioController();
$usuario = null;

if (isset($_GET['id'])) {
    $usuario = $controller->getUsuarioById($_GET['id']);
}

if (!$usuario) {
    header("Location: index.php?error=Usuario no encontrado");
    exit();
}
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-3"></i>Editar Usuario
            </h1>
        </div>
        
        <div class="p-6">
            <form action="controllers/usuarioController.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nombre Completo
                        </label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Correo Electrónico
                        </label>
                        <input type="email" name="correo_electronico" value="<?= htmlspecialchars($usuario['correo_electronico']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-briefcase mr-2 text-blue-500"></i>Cargo
                        </label>
                        <select name="id_rol" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                            <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>👨‍💼 Desarollador</option>
                            <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>👑 Lider de Area</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Fecha de Ingreso
                        </label>
                        <input type="date" name="fecha_ingreso" value="<?= $usuario['fecha_ingreso'] ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    </div>
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-signature mr-2 text-blue-500"></i>Firma (para el contrato)
                        </label>
                        <input type="text" name="firma" value="<?= htmlspecialchars($usuario['firma']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="index.php?action=users" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>