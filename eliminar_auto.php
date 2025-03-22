<?php
session_start();
include 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header('Location: dashboard.php'); // Redirige a usuarios no administradores
    exit();
}

// Verificar si se proporciona un ID
if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit();
}

$auto_id = $_GET['id'];

// Eliminar el auto
$query = "DELETE FROM autos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $auto_id);

if ($stmt->execute()) {
    header("Location: admin.php"); // Redirige a la página de administración
    exit();
} else {
    echo "Error al eliminar el auto: " . $stmt->error;
}
?>
