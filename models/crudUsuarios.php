<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Usuario
{
    public static function getAll()
    {
        $pdo = connection();
        $sql = "SELECT u.*, r.nombre_cargo 
            FROM usuarios u 
            LEFT JOIN roles r ON u.id_rol = r.id 
            WHERE u.fecha_eliminacion IS NULL 
            ORDER BY u.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nombre, $correo_electronico, $id_rol, $fecha_ingreso, $firma)
    {
        $pdo = connection();

        if (self::emailExists($correo_electronico)) {
            throw new Exception("El correo electrónico ya está registrado");
        }

        $sql = "INSERT INTO usuarios (nombre, correo_electronico, id_rol, fecha_ingreso, firma) VALUES (:nombre, :correo_electronico, :id_rol, :fecha_ingreso, :firma)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo_electronico' => $correo_electronico,
            ':id_rol' => $id_rol,
            ':fecha_ingreso' => $fecha_ingreso,
            ':firma' => $firma
        ]);

        $id = $pdo->lastInsertId();

        $rutaContrato = self::generarContratoPDF($id, $nombre, $correo_electronico, $id_rol, $fecha_ingreso, $firma);

        $sql = "UPDATE usuarios SET contrato = :contrato WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':contrato' => $rutaContrato, ':id' => $id]);

        return $id;
    }

    public static function update($id, $nombre, $correo_electronico, $id_rol, $fecha_ingreso, $firma)
    {
        $pdo = connection();

        if (self::emailExists($correo_electronico, $id)) {
            throw new Exception("El correo electrónico ya está registrado por otro usuario");
        }

        $sql = "UPDATE usuarios 
            SET nombre = :nombre, 
                correo_electronico = :correo_electronico, 
                id_rol = :id_rol, 
                fecha_ingreso = :fecha_ingreso, 
                firma = :firma 
            WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':nombre' => $nombre,
            ':correo_electronico' => $correo_electronico,
            ':id_rol' => $id_rol,
            ':fecha_ingreso' => $fecha_ingreso,
            ':firma' => $firma,
            ':id' => $id
        ]);

        if ($result) {
            $usuario = self::getById($id);
            $rutaContrato = self::generarContratoPDF(
                $usuario['id'],
                $usuario['nombre'],
                $usuario['correo_electronico'],
                $usuario['id_rol'],
                $usuario['fecha_ingreso'],
                $usuario['firma']
            );

            $sql = "UPDATE usuarios SET contrato = :contrato WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':contrato' => $rutaContrato, ':id' => $id]);
        }

        return $result;
    }

    public static function emailExists($correo, $excludeId = null)
    {
        $pdo = connection();
        $sql = "SELECT COUNT(*) as count FROM usuarios WHERE correo_electronico = :correo";
        $params = [':correo' => $correo];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    public static function generarContratoPDF($id, $nombre, $email, $id_rol, $fecha_ingreso, $firma)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $cargo = ($id_rol == 1) ? 'Desarrollador' : 'Lider de Area';

        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Contrato de Trabajo</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; }
            .content { margin: 30px 0; line-height: 1.6; }
            .firma-section { margin-top: 50px; }
            .firma { border-top: 1px solid #333; width: 300px; padding-top: 10px; }
            .footer { margin-top: 50px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>CONTRATO DE TRABAJO</h1>
            <h2>Syscom Colombia</h2>
        </div>
        
        <div class='content'>
            <p>Por medio del presente documento se formaliza el contrato de trabajo entre:</p>
            
            <p><strong>EMPRESA:</strong> Syscom Colombia</p>
            <p><strong>TRABAJADOR:</strong> $nombre</p>
            <p><strong>CARGO:</strong> $cargo</p>
            <p><strong>EMAIL:</strong> $email</p>
            <p><strong>FECHA DE INGRESO:</strong> $fecha_ingreso</p>
            
            <p>El trabajador se compromete a prestar sus servicios bajo los términos y condiciones establecidos 
            en el reglamento interno de la empresa y la legislación laboral colombiana.</p>
            
            <div class='firma-section'>
                <p>Firma del Trabajador:</p>
                <div class='firma'>
                    <strong>$firma</strong>
                </div>
                
                <p style='margin-top: 30px;'>Firma del Representante Legal:</p>
                <div class='firma'>
                    <strong>Syscom Colombia</strong>
                </div>
            </div>
        </div>
        
        <div class='footer'>
            <p>Contrato generado automáticamente el " . date('d/m/Y') . "</p>
            <p>ID de contrato: CONTRACT-$id-" . date('Ymd') . "</p>
        </div>
    </body>
    </html>
    ";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $directorio = __DIR__ . '/../employeContracts/';
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = 'contrato_' . $id . '_' . date('Ymd_His') . '.pdf';
        $rutaCompleta = $directorio . $nombreArchivo;

        file_put_contents($rutaCompleta, $dompdf->output());

        return 'employeContracts/' . $nombreArchivo;
    }

    public static function getById($id)
    {
        $pdo = connection();
        $sql = "SELECT u.*, r.nombre_cargo 
            FROM usuarios u 
            LEFT JOIN roles r ON u.id_rol = r.id 
            WHERE u.id = :id AND u.fecha_eliminacion IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function delete($id)
    {
        $pdo = connection();

        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
