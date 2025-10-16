<?php

require_once 'conexion.php';

// 1) Recoger y sanitizar los datos del formulario
// Usamos htmlspecialchars para prevenir XSS al mostrar los datos (aunque aquí no se muestran)
// y trim para eliminar espacios en blanco al inicio/fin.
$nombre    = isset($_POST['nombre'])    ? htmlspecialchars(trim($_POST['nombre'])) : '';
$email     = isset($_POST['email'])     ? htmlspecialchars(trim($_POST['email']))  : '';
$telefono  = isset($_POST['telefono'])  ? htmlspecialchars(trim($_POST['telefono'])) : '';
$intereses = isset($_POST['intereses']) ? htmlspecialchars(trim($_POST['intereses'])) : '';

// 2) Validaciones de Datos en el Servidor
$errores = [];

if (empty($nombre) || strlen($nombre) < 3) {
    $errores[] = 'El nombre es obligatorio y debe tener al menos 3 caracteres.';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email es obligatorio y debe ser un formato válido.';
}
// Validaciones para el teléfono si es obligatorio
if (!empty($telefono) && !preg_match('/^\+?[0-9]{7,15}$/', $telefono)) {
    $errores[] = 'El teléfono no tiene un formato válido.';
}

if (!empty($errores)) {
    // Aquí podríamos redirigir al usuario de vuelta al formulario con los errores
    // Para una implementación sencilla, solo mostraremos los errores y saldremos.
    echo '❌ Errores en la postulación: <br>';
    foreach ($errores as $error) {
        echo '- ' . $error . '<br>';
    }
    echo '<br><a href="index.html">Volver al formulario</a>';
    exit();
}

try {
    // 3) Preparar la consulta SQL para insertar en la tabla de VOLUNTARIOS
    $sql = '
        INSERT INTO VOLUNTARIOS (nombre, email, telefono, intereses)
        VALUES (:nombre, :email, :telefono, :intereses)
    ';
    $stmt = $pdo->prepare($sql);

    // 4) Ejecutar la consulta con los datos validados y sanitizados
    $stmt->execute([
        ':nombre'    => $nombre,
        ':email'     => $email,
        ':telefono'  => $telefono,
        ':intereses' => $intereses
    ]);

    // 5) Feedback al usuario y redirección (mejor práctica que solo un echo)
    header('Location: index.html?status=success_voluntario');
    exit();
}
?>