<?php
require_once __DIR__ . '/../models/crudUsuarios.php';
require_once __DIR__ . '/../helpers/timeCalculator.php';

class UsuarioController
{
    public function indexGetAll()
    {
        $usuarios = Usuario::getAll();

        foreach ($usuarios as &$usuario) {
            $usuario['dias_trabajados'] = DiasHabilesCalculator::calcularDiasHabiles(
                $usuario['fecha_ingreso']
            );
        }

        return $usuarios;
    }

    public function getUsuarioById($id)
    {
        return Usuario::getById($id);
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'create';

            try {
                switch ($action) {
                    case 'create':
                        $this->createUsuario();
                        break;
                    case 'update':
                        $this->updateUsuario();
                        break;
                    default:
                        throw new Exception("Acción no válida");
                }
            } catch (Exception $e) {
                header("Location: ../index.php?error=" . urlencode($e->getMessage()));
                exit();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
                $this->deleteUsuario();
            }
        }
    }

    private function createUsuario()
    {
        try {
            $nombre = $_POST['nombre'];
            $correo_electronico = $_POST['correo_electronico'];
            $id_rol = $_POST['id_rol'];
            $fecha_ingreso = $_POST['fecha_ingreso'];
            $firma = $_POST['firma'];

            if (empty($nombre) || empty($correo_electronico) || empty($id_rol) || empty($fecha_ingreso) || empty($firma)) {
                throw new Exception("Todos los campos son obligatorios");
            }

            if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del correo electrónico no es válido");
            }

            $id = Usuario::create($nombre, $correo_electronico, $id_rol, $fecha_ingreso, $firma);

            header("Location: ../index.php?action=users&success=Usuario creado correctamente");
            exit();
        } catch (Exception $e) {
            $_SESSION['form_data'] = $_POST;
            header("Location: ../index.php?action=create&error=" . urlencode($e->getMessage()));
            exit();
        }
    }

    private function updateUsuario()
    {
        try {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $correo_electronico = $_POST['correo_electronico'];
            $id_rol = $_POST['id_rol'];
            $fecha_ingreso = $_POST['fecha_ingreso'];
            $firma = $_POST['firma'];

            if (empty($id) || empty($nombre) || empty($correo_electronico) || empty($id_rol) || empty($fecha_ingreso) || empty($firma)) {
                throw new Exception("Todos los campos son obligatorios");
            }

            if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del correo electrónico no es válido");
            }

            $result = Usuario::update($id, $nombre, $correo_electronico, $id_rol, $fecha_ingreso, $firma);

            if ($result) {
                header("Location: ../index.php?action=users&success=Usuario actualizado correctamente");
            } else {
                throw new Exception("Error al actualizar el usuario");
            }
            exit();
        } catch (Exception $e) {
            header("Location: ../index.php?action=edit&id=" . $_POST['id'] . "&error=" . urlencode($e->getMessage()));
            exit();
        }
    }


    private function deleteUsuario()
    {
        $id = $_GET['id'];

        if (empty($id)) {
            throw new Exception("ID de usuario no válido");
        }

        $result = Usuario::delete($id);

        if ($result) {
            header("Location: ../index.php?success=Usuario eliminado correctamente");
        } else {
            throw new Exception("Error al eliminar el usuario");
        }
        exit();
    }
}

if (isset($_SERVER['REQUEST_METHOD'])) {
    $controller = new UsuarioController();
    $controller->handleRequest();
}
