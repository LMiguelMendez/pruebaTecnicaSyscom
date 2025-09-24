<?php
require_once __DIR__ . '/controllers/usuarioController.php';

$controller = new UsuarioController();
$usuarios = $controller->indexGetAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>

<body>
    <div>
        <form action="controllers/usuarioController.php" method="POST">
            <h1>Crear usuarios</h1>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo_electronico" placeholder="Email" required>

            <select name="id_rol" required>
                <option value="1">Empleado</option>
                <option value="2">Jefe</option>
            </select>

            <input type="date" name="fecha_ingreso" required>

            <div>
                <label for="firma">Firma (dijite su nombre):</label>
                <input type="text" name="firma" placeholder="Nombre para firma" required>
            </div>

            <button type="submit">Guardar</button>
        </form>
    </div>

    <div>
        <h2>Lista de usuarios</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Fecha Ingreso</th>
                    <th>Contrato</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $row) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['nombre']; ?></td>
                        <td><?= $row['correo_electronico']; ?></td>
                        <td><?= $row['id_rol'] == 1 ? 'Empleado' : 'Jefe'; ?></td>
                        <td><?= $row['fecha_ingreso']; ?></td>
                        <td><?= $row['dias_trabajados']; ?> días hábiles</td>
                        <td>
                            <?php if ($row['contrato']) { ?>
                                <a href="<?= $row['contrato']; ?>" target="_blank">Ver contrato</a>
                            <?php } else { ?>
                                No disponible
                            <?php } ?>
                        </td>
                        <td>
                            <a href="views/edit.php?id=<?= $row['id']; ?>">Editar</a>
                            <button onclick="eliminarUsuario(<?= $row['id']; ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<script>
function eliminarUsuario(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        window.location.href = 'controllers/usuarioController.php?action=delete&id=' + id;
    }
}
</script>