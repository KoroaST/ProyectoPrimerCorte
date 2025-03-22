<?php
session_start();
include 'conexion.php';

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    header('Location: dashboard.php'); // Redirige a usuarios no administradores
    exit();
}

// Conectar con el servicio SOAP
require_once('vendor/econea/nusoap/src/nusoap.php');
$client = new nusoap_client('http://localhost/webservices/Proyecto/soap_usuarios.php?wsdl', true);

// Si no se ha buscado nada, obtener todos los usuarios
$criterio = isset($_GET['buscar']) ? $_GET['buscar'] : '';
if ($criterio) {
    $params = array('criterio' => $criterio);
    $resultado = $client->call('buscarUsuario', $params);
} else {
    $resultado = $client->call('obtenerUsuarios');
}

if ($client->fault || $client->getError()) {
    $usuarios = array();
} else {
    $usuarios = json_decode($resultado, true);
    if (!is_array($usuarios)) {
        $usuarios = array();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios Existentes - Concesionaria</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <style>
        .dashboard-container {
            max-width: 960px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-form {
            margin-bottom: 15px;
        }

        .search-form input {
            padding: 8px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-form button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .action-buttons a {
            padding: 8px 12px;
            margin: 5px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }

        .edit-btn {
            background-color: #ffc107; /* Amarillo */
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .dashboard-btn {
            background-color: #6c757d; /* Gris */
        }

        .dashboard-btn:hover {
            background-color: #5a6268;
        }

        .add-user-btn {
            background-color: #28a745; /* Verde */
        }

        .add-user-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Gestionar Usuarios Existentes</h1>
            <form class="search-form" method="GET" action="usuarios.php">
                <input type="text" name="buscar" placeholder="Buscar usuario..." value="<?php echo htmlspecialchars($criterio); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($usuarios) && !isset($usuarios['error'])): ?>
                    <?php foreach ($usuarios as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['rol'])); ?></td>
                            <td class="action-buttons">
                                <?php if ($row['rol'] === 'admin'): ?>
                                    No permitido
                                <?php else: ?>
                                    <a href="editar_usuario.php?id=<?php echo $row['id']; ?>" class="edit-btn">Editar</a>
                                    <a href="eliminar_usuario.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirmarEliminar()">Eliminar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay usuarios encontrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="action-buttons">
            <a href="dashboard.php" class="dashboard-btn">Regresar al Dashboard</a>
            <a href="agregar_usuario.php" class="add-user-btn">Agregar Nuevo Usuario</a>
        </div>
    </div>
</body>
</html>