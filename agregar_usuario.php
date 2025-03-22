<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Incluir la librería NuSOAP
require_once 'vendor/autoload.php';
require_once('vendor/econea/nusoap/src/nusoap.php');

// Recibir los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $rol = 'usuario'; // Asignar rol usuario por defecto

    // Crear un cliente SOAP
    $client = new nusoap_client('http://localhost/webservices/Proyecto/soap_usuarios.php?wsdl', false);
    $client->soap_defencoding = 'UTF-8';
    $client->decode_utf8 = false;

    // Llamar al método del servicio SOAP para crear el usuario
    $params = array('nombre' => $nombre, 'usuario' => $usuario, 'clave' => $clave, 'rol' => $rol);
    $resultado = $client->call('agregarUsuario', $params);

    // Verificar si hubo errores en la llamada al servicio SOAP
    if ($client->fault) {
        $message = 'Error en la respuesta del servicio SOAP.';
    } else {
        $err = $client->getError();
        if ($err) {
            $message = 'Error en la llamada al servicio SOAP: ' . $err;
        } else {
            // Usuario creado con exito, redirigir a usuarios.php
            header('Location: usuarios.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario - Concesionaria</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto; 
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
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

        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 48%;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
        }

        .submit-btn {
            background-color: #28a745; /* Verde */
            color: white;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        .back-btn {
            background-color: #ffc107; /* Amarillo */
            color: black;
        }

        .back-btn:hover {
            background-color: #e0a800;
        }

        .message {
            color: green; /* Color verde para mensajes de éxito */
            text-align: center; /* Centrar mensaje */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Agregar Usuario</h2>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div> 
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
            <div class="button-container">
                <button type="submit" class="btn submit-btn">Agregar Usuario</button>
                <a href="usuarios.php" class="btn back-btn">Volver a la lista de usuarios</a>
            </div>
        </form>
    </div>
</body>
</html>