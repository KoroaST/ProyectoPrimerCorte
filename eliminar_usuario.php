<?php
session_start();
include 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header('Location: dashboard.php'); // Redirige a usuarios no administradores
    exit();
}

// Obtener el ID del usuario a eliminar
$id = $_GET['id'];

// Consulta para eliminar el usuario
$query = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

// Verificar si la eliminación fue exitosa
if ($stmt->affected_rows > 0) {
    echo '<script>alert("Usuario eliminado con éxito."); window.location.href = "usuarios.php";</script>';
} else {
    echo '<script>alert("Error al eliminar el usuario."); window.location.href = "usuarios.php";</script>';
}

$stmt->close();
$conn->close();
?>
