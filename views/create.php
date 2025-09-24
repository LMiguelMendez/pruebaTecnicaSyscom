<?php
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-user-plus mr-3"></i>Crear Nuevo Usuario
            </h1>
        </div>
        
        <div class="p-6">
            <!-- Mostrar error específico si existe -->
            <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <div>
                        <p class="text-red-700 font-semibold">Error de validación</p>
                        <p class="text-red-600"><?= htmlspecialchars($_GET['error']) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <form action="controllers/usuarioController.php" method="POST" class="space-y-6" onsubmit="return validateForm()">
                <input type="hidden" name="action" value="create">
                
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nombre Completo *
                        </label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Ej: Juan Carlos Pérez García" required
                               id="nombreInput">
                        <div id="nombreError" class="text-red-500 text-sm hidden"></div>
                    </div>
                    
                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>Correo Electrónico *
                        </label>
                        <input type="email" name="correo_electronico" value="<?= htmlspecialchars($formData['correo_electronico'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="ejemplo@syscom.com.co" required
                               id="emailInput">
                        <div id="emailError" class="text-red-500 text-sm hidden"></div>
                    </div>
                    
                    <!-- Cargo -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-briefcase mr-2 text-blue-500"></i>Cargo *
                        </label>
                        <select name="id_rol" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                            <option value="">Seleccione un cargo</option>
                            <option value="1" <?= (isset($formData['id_rol']) && $formData['id_rol'] == '1') ? 'selected' : '' ?>>👨‍💼 Desarrollador</option>
                            <option value="2" <?= (isset($formData['id_rol']) && $formData['id_rol'] == '2') ? 'selected' : '' ?>>👑 Lider de Area</option>
                        </select>
                    </div>
                    
                    <!-- Fecha Ingreso -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Fecha de Ingreso *
                        </label>
                        <input type="date" name="fecha_ingreso" value="<?= $formData['fecha_ingreso'] ?? '' ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                    </div>
                    
                    <!-- Firma -->
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-signature mr-2 text-blue-500"></i>Firma (para el contrato) *
                        </label>
                        <input type="text" name="firma" value="<?= htmlspecialchars($formData['firma'] ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Ej: Juan Carlos Pérez García" required>
                        <p class="text-sm text-gray-500">Este nombre aparecerá como firma en el contrato PDF</p>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="index.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-600 transition duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    let isValid = true;
    
    document.querySelectorAll('[id$="Error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');
    const email = emailInput.value.trim();
    
    if (!email) {
        emailError.textContent = 'El correo electrónico es obligatorio';
        emailError.classList.remove('hidden');
        emailInput.classList.add('border-red-500');
        isValid = false;
    } else if (!isValidEmail(email)) {
        emailError.textContent = 'El formato del correo electrónico no es válido';
        emailError.classList.remove('hidden');
        emailInput.classList.add('border-red-500');
        isValid = false;
    } else {
        emailInput.classList.remove('border-red-500');
    }
    
    const nombreInput = document.getElementById('nombreInput');
    const nombreError = document.getElementById('nombreError');
    const nombre = nombreInput.value.trim();
    
    if (!nombre) {
        nombreError.textContent = 'El nombre es obligatorio';
        nombreError.classList.remove('hidden');
        nombreInput.classList.add('border-red-500');
        isValid = false;
    } else {
        nombreInput.classList.remove('border-red-500');
    }
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

document.getElementById('emailInput').addEventListener('blur', function() {
    validateForm();
});
</script>