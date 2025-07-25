<?php
// Habilito errores en pantalla para depurar
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'conexion.php';  // Asegúrate de que está en el mismo directorio

// 1) Recoger y sanitizar los datos
$nombre      = isset($_POST['nombre'])      ? trim($_POST['nombre'])      : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$presupuesto = isset($_POST['presupuesto']) ? floatval($_POST['presupuesto']) : 0;
$fi          = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$ff          = !empty($_POST['fecha_fin'])    ? $_POST['fecha_fin']    : null;

// 2) Validaciones básicas de servidor
if (strlen($nombre) < 3) {
    die('❌ El nombre del proyecto debe tener al menos 3 caracteres.');
}
if ($presupuesto <= 0) {
    die('❌ El presupuesto debe ser un número mayor que cero.');
}

try {
    // 3) Preparo la consulta con parámetros nombrados
    $sql = '
        INSERT INTO PROYECTO
            (nombre, descripcion, presupuesto, fecha_inicio, fecha_fin)
        VALUES
            (:nombre, :descripcion, :presupuesto, :fecha_inicio, :fecha_fin)
    ';
    $stmt = $pdo->prepare($sql);

    // 4) Ejecuto pasando un array asociativo
    $stmt->execute([
        ':nombre'       => $nombre,
        ':descripcion'  => $descripcion,
        ':presupuesto'  => $presupuesto,
        ':fecha_inicio' => $fi,
        ':fecha_fin'    => $ff
    ]);

    echo '✅ Proyecto registrado correctamente.';
} catch (PDOException $e) {
    // 5) Si hay error en la consulta, lo muestro (¡no dejar en producción!)
    echo '❌ Error al guardar proyecto: ' . $e->getMessage();
    exit;
}