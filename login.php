<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Incluir la librería NuSOAP
    require_once('vendor/econea/nusoap/src/nusoap.php');

    // Crear un cliente SOAP
    $client = new nusoap_client('http://localhost/webservices/Proyecto/soap_login.php?wsdl', true);

    // Llamar al método del servicio SOAP para validar el usuario

    $params = array('usuario' => $usuario, 'clave' => $clave);
    $resultado = $client->call('validarUsuario', $params);

    // Verificar si hubo errores en la llamada al servicio SOAP
    if ($client->fault) {
        $message = 'Error en la respuesta del servicio SOAP.';
    } else {
        $err = $client->getError();
        if ($err) {
            $message = 'Error en la llamada al servicio SOAP: ' . $err;
        } else {
            
            // Verificar el resultado del servicio SOAP

            if ($resultado !== 'false') {
                $data = json_decode($resultado, true);
                if ($data !== null && isset($data['id']) && isset($data['nombre']) && isset($data['rol'])) {
                    // Iniciar sesión

                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['nombre'] = $data['nombre'];
                    $_SESSION['rol'] = $data['rol']; // Asigna el rol real desde el servicio SOAP

                    // Redirección a dashboard
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $message = "Error al decodificar los datos del usuario.";
                }
            } else {
                $message = "Credenciales incorrectas.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Concesionaria</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto; 
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
            width: 100%; 
        }

        .submit-btn:hover {
            background-color: #0056b3; 
        }

        .message {
            color: red; /* Color rojo para mensajes de error */
            text-align: center; /* Centrar mensaje */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align:center;">Iniciar Sesión</h2>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div> 
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="clave">Contraseña:</label>
                <input type="password" name="clave" required>
            </div>
            <button type="submit" class="submit-btn">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
