<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syscom Colombia - Gestión de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-building text-2xl"></i>
                    <span class="text-xl font-bold">Syscom Colombia</span>
                </div>
                <div class="flex space-x-2">
                    <a href="index.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-blue-500 transition duration-200 <?= (!isset($_GET['action']) || $_GET['action'] == '') ? 'bg-blue-500' : '' ?>">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>
                    <a href="index.php?action=create" class="flex items-center px-3 py-2 rounded-lg hover:bg-blue-500 transition duration-200 <?= (isset($_GET['action']) && $_GET['action'] == 'create') ? 'bg-blue-500' : '' ?>">
                        <i class="fas fa-user-plus mr-2"></i>Crear Usuario
                    </a>
                    <a href="index.php?action=users" class="flex items-center px-3 py-2 rounded-lg hover:bg-blue-500 transition duration-200 <?= (isset($_GET['action']) && $_GET['action'] == 'users') ? 'bg-blue-500' : '' ?>">
                        <i class="fas fa-users mr-2"></i>Lista de Usuarios
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong class="font-bold">Éxito! </strong>
                        <span class="ml-2">' . htmlspecialchars($_GET['success']) . '</span>
                    </div>
                  </div>';
        }
        if (isset($_GET['error'])) {
            echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong class="font-bold">Error! </strong>
                        <span class="ml-2">' . htmlspecialchars($_GET['error']) . '</span>
                    </div>
                  </div>';
        }
        ?>
        
        <?php include $content; ?>
    </main>

    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 Syscom Colombia. Prueba Tecnica.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>