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

// Obtener la información del auto
$query = "SELECT * FROM autos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $auto_id);
$stmt->execute();
$result = $stmt->get_result();
$auto = $result->fetch_assoc();

if (!$auto) {
    header('Location: admin.php');
    exit();
}

// Procesar el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    // Procesar la imagen si se proporciona una nueva
    if ($_FILES['imagen']['name']) {
        $imagen = $_FILES['imagen']['name'];
        $temp_imagen = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($temp_imagen, "img/" . $imagen);
    } else {
        $imagen = $auto['imagen']; // Mantener la imagen anterior
    }

    $query = "UPDATE autos SET marca = ?, modelo = ?, anio = ?, precio = ?, descripcion = ?, imagen = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssddssi", $marca, $modelo, $anio, $precio, $descripcion, $imagen, $auto_id);

    if ($stmt->execute()) {
        header("Location: admin.php"); // Redirige a la página de administración
        exit();
    } else {
        echo "Error al actualizar el auto: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Auto</title>
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
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 100px;
        }

        .form-group input[type="file"] {
            margin-top: 5px;
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
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="form-container">
            <h1>Editar Auto</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $auto_id; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="marca">Marca:</label>
                    <input type="text" name="marca" id="marca" value="<?php echo htmlspecialchars($auto['marca']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" name="modelo" id="modelo" value="<?php echo htmlspecialchars($auto['modelo']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="anio">Año:</label>
                    <input type="number" name="anio" id="anio" value="<?php echo htmlspecialchars($auto['anio']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" name="precio" id="precio" value="<?php echo htmlspecialchars($auto['precio']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required><?php echo htmlspecialchars($auto['descripcion']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" name="imagen" id="imagen">
                    <?php if ($auto['imagen']): ?>
                        <img src="img/<?php echo htmlspecialchars($auto['imagen']); ?>" alt="Imagen actual" style="max-width: 100px; margin-top: 5px;">
                    <?php endif; ?>
                </div>
                <button type="submit" class="submit-btn">Actualizar Auto</button>
            </form>
            <a href="admin.php">Regresar a la administración</a>
        </div>
    </div>
</body>
</html>
