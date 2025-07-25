<?php
require 'conexion.php';

$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$direccion = trim($_POST['direccion']);
$telefono = trim($_POST['telefono']);

try {
    $stmt = $pdo->prepare("INSERT INTO DONANTE (nombre, email, direccion, telefono)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $direccion, $telefono]);
    echo 'Donante registrado correctamente.';
} catch (PDOException $e) {
    echo 'Error al guardar donante: ' . $e->getMessage();
}