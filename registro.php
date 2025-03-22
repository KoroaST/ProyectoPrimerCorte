<?php
session_start();
include 'conexion.php'; // Incluye tu archivo de conexión a la base de datos

$message = ""; // Variable para almacenar mensajes

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $clave = password_hash($_POST['clave'], PASSWORD_ARGON2ID); // Hashear la contraseña usando Argon2

    $query = "INSERT INTO usuarios (nombre, usuario, clave, rol, fecha_creacion) VALUES (?, ?, ?, 'usuario', NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombre, $usuario, $clave);

    if ($stmt->execute()) {
        // Éxito al agregar el usuario
        $message = "Usuario registrado correctamente.";
    } else {
        $message = "Error al registrar el usuario: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="form-container">
        <h1>Registrar Nuevo Usuario</h1>
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p> <!-- Mostrar mensajes -->
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="clave">Contraseña:</label>
                <input type="password" name="clave" required>
            </div>
            <button type="submit">Registrar Usuario</button>
        </form>
        <a href="login.php">Volver al Login</a>
    </div>
</body>
</html>
