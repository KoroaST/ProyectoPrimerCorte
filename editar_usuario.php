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
    header('Location: usuarios.php'); // Redirige si no hay ID
    exit();
}

$id = $_GET['id'];

// Obtener información del usuario
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: usuarios.php'); // Redirige si no se encuentra el usuario
    exit();
}

$message = ""; // Variable para almacenar mensajes

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'] ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : $user['clave']; // Mantener clave anterior si está vacía

    if (empty($nombre) || empty($usuario)) {
        $message = "Todos los campos son obligatorios.";
    } else {
        try {
            $query = "UPDATE usuarios SET nombre = ?, usuario = ?, clave = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $nombre, $usuario, $clave, $id);

            if ($stmt->execute()) {
                // Éxito al actualizar el usuario
                $message = "Usuario actualizado correctamente.";
            } else {
                // Error al actualizar el usuario
                $message = "Error al actualizar el usuario: " . $stmt->error;
            }
        } catch (Exception $e) {
            $message = "Ocurrió un error inesperado: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .action-buttons a {
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none; /* Sin subrayado */
        }

        .action-buttons .back-btn {
            background-color: #6c757d; /* Color gris */
        }

        .action-buttons .back-btn:hover {
            background-color: #5a6268; /* Color gris oscuro */
        }

        .action-buttons .dashboard-btn {
            background-color: #007bff; /* Color azul */
        }

        .action-buttons .dashboard-btn:hover {
            background-color: #0056b3; /* Azul oscuro */
        }

        .message {
            color: red;
            text-align: center;
        }
    </style>
    <script>
        function confirmarActualizar() {
            return confirm("¿Estás seguro que deseas actualizar la información del usuario?");
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <div class="form-container">
            <h1>Editar Usuario</h1>
            <?php if ($message): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post" onsubmit="return confirmarActualizar()">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" name="usuario" id="usuario" value="<?php echo htmlspecialchars($user['usuario']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="clave">Contraseña:</label>
                    <input type="password" name="clave" id="clave" placeholder="Dejar vacío para mantener la contraseña actual">
                </div>
                <button type="submit" class="submit-btn">Actualizar Usuario</button>
            </form>

            <div class="action-buttons">
                <a href="javascript:history.back()" class="back-btn">Atrás</a>
                <a href="usuarios.php" class="dashboard-btn">Regresar a la gestión de usuarios</a>
            </div>
        </div>
    </div>
</body>
</html>
