<?php
session_start();

// Verifica si 'usuario' está definida en $_SESSION
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Verificar si 'rol' está definido en la sesión, si no, asignar 'usuario' por defecto
if (!isset($_SESSION['rol'])) {
    $_SESSION['rol'] = 'usuario';
}

// Obtener el rol del usuario desde la sesión
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Concesionaria</title>
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Incluye tu CSS -->
    <style>
        .dashboard-container {
            max-width: 960px; /* Ancho máximo del contenedor */
            margin: 20px auto; /* Centrar el contenedor */
            background-color: #fff; /* Fondo blanco para el contenedor */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Sombra suave */
            padding: 20px; /* Espacio interno */
        }

        header h1 {
            text-align: center; /* Centrar el título */
        }

        .dashboard-content {
            display: flex; /* Usar flexbox para organizar las tarjetas */
            flex-wrap: wrap; /* Permitir que las tarjetas se envuelvan a la siguiente línea */
            gap: 20px; /* Espacio entre las tarjetas */
            justify-content: center; /* Centrar las tarjetas horizontalmente */
        }

        .card {
            background-color: #f9f9f9; /* Fondo gris claro para las tarjetas */
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
            padding: 15px;
            text-align: center;
            width: 300px; /* Ancho fijo para las tarjetas */
        }

        .card h2 {
            color: #007bff; /* Color azul para el título de la tarjeta */
        }

        .card img.icon {
            width: 30px; /* Ajusta el tamaño del icono según sea necesario */
            height: auto; /* Mantiene la proporción del icono */
            margin-right: 10px; /* Espacio entre el icono y el texto */
            vertical-align: middle; /* Alineación vertical del icono con el texto */
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Dashboard de la Concesionaria</h1>
            <p>Ahora te encuentras en la sección de Inicio.</p>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>.</p>
        </header>

        <div class="dashboard-content">
            <?php if ($rol === 'admin'): ?>
                <!-- Opciones para administradores -->
                <div class="card">
                    <h2><img src="img/gestionarcar.png" alt="Gestionar Autos" class="icon"> Gestionar Autos</h2>
                    <p>Administra los vehículos en tu inventario.</p>
                    <a href="gestionar-autos.php">Ir a Gestionar Autos</a>
                </div>

                <div class="card">
                    <h2><img src="img/usergestionar.png" alt="Gestionar Usuarios" class="icon"> Gestionar Usuarios</h2>
                    <p>Administra las cuentas de usuario del sistema.</p>
                    <a href="usuarios.php">Ir a Gestionar Usuarios</a>
                </div>
            <?php else: ?>
                <!-- Opciones para usuarios normales -->
                <div class="card">
                    <h2><img src="img/gestionarcar.png" alt="Autos Disponibles" class="icon"> Autos Disponibles</h2>
                    <p>Explora los autos disponibles para la venta.</p>
                    <a href="autos-disponibles.php">Ver Autos Disponibles</a>
                </div>
            <?php endif; ?>

            <div class="card">
                <h2><img src="img/logout.png" alt="Cerrar Sesión" class="icon"> Cerrar Sesión</h2>
                <p>Finaliza tu sesión de forma segura.</p>
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
