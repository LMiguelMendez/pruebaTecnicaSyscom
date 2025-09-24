<?php
session_start();
require_once __DIR__ . '/controllers/usuarioController.php';

$controller = new UsuarioController();
$content = 'views/home.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'create':
            $content = 'views/create.php';
            break;
        case 'users':
            $usuarios = $controller->indexGetAll();
            $content = 'views/users.php';
            break;
        case 'edit':
            $content = 'views/edit.php';
            break;
        default:
            $content = 'views/home.php';
            break;
    }
}

include 'views/layout.php';
?>