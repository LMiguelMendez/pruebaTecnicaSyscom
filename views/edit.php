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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    
    <form action="../controllers/usuarioController.php" method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= $usuario['id']; ?>">
        
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        
        <div>
            <label for="correo_electronico">Correo Electrónico:</label>
            <input type="email" name="correo_electronico" value="<?= htmlspecialchars($usuario['correo_electronico']); ?>" required>
        </div>
        
        <div>
            <label for="id_rol">Cargo:</label>
            <select name="id_rol" required>
                <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : ''; ?>>Empleado</option>
                <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : ''; ?>>Jefe</option>
            </select>
        </div>
        
        <div>
            <label for="fecha_ingreso">Fecha de Ingreso:</label>
            <input type="date" name="fecha_ingreso" value="<?= $usuario['fecha_ingreso']; ?>" required>
        </div>
        
        <div>
            <label for="firma">Firma:</label>
            <input type="text" name="firma" value="<?= htmlspecialchars($usuario['firma']); ?>" required>
        </div>
        
        <div>
            <button type="submit">Actualizar Usuario</button>
            <a href="index.php">Cancelar</a>
        </div>
    </form>
</body>
</html>