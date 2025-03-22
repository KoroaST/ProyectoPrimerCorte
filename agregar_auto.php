<?php
session_start();
include 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header('Location: dashboard.php'); // Redirige a usuarios no administradores
    exit();
}

$message = ""; // Variable para almacenar mensajes

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    // Procesar la imagen
    $imagen = $_FILES['imagen']['name'];
    $temp_imagen = $_FILES['imagen']['tmp_name'];
    move_uploaded_file($temp_imagen, "img/" . $imagen);

    $query = "INSERT INTO autos (marca, modelo, anio, precio, descripcion, imagen, estado) 
              VALUES (?, ?, ?, ?, ?, ?, 'disponible')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssddss", $marca, $modelo, $anio, $precio, $descripcion, $imagen);

    if ($stmt->execute()) {
        // Éxito al agregar el auto
        $message = "Vehículo Agregado a la base de datos.";
    } else {
        $message = "Error al agregar el auto: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Auto</title>
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

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .action-buttons a {
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .action-buttons .back-btn {
            background-color: #6c757d;
        }

        .action-buttons .back-btn:hover {
            background-color: #5a6268;
        }

        .action-buttons .dashboard-btn {
            background-color: #007bff;
        }

        .action-buttons .dashboard-btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function confirmarAgregar() {
            if (confirm("¿Estás seguro que terminaste con la información del vehículo?")) {
                // El formulario se enviará automáticamente si el usuario hace clic en "Aceptar"
                return true;
            } else {
                // El formulario no se enviará si el usuario hace clic en "Cancelar"
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <div class="form-container">
            <h1>Información Nuevo Vehículo</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return confirmarAgregar()">
                <div class="form-group">
                    <label for="marca">Marca:</label>
                    <input type="text" name="marca" id="marca" required>
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" name="modelo" id="modelo" required>
                </div>
                <div class="form-group">
                    <label for="anio">Año:</label>
                    <input type="number" name="anio" id="anio" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" name="precio" id="precio" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" name="imagen" id="imagen" required>
                </div>
                <button type="submit" class="submit-btn">Guardar Auto</button>
                <?php if ($message): ?>
                    <script>
                        alert("<?php echo $message; ?>");
                        window.location.href = "gestionar-autos.php"; // Redirige automáticamente
                    </script>
                <?php endif; ?>
            </form>

            <div class="action-buttons">
                <a href="javascript:history.back()" class="back-btn">Atrás</a>
                <a href="gestionar-autos.php" class="dashboard-btn">Regresar a la administración</a>
            </div>
        </div>
    </div>
</body>
</html>
