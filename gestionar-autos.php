<?php
session_start();
include 'conexion.php'; // 

// 

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Verificar si el usuario tiene el rol de administrador

if ($_SESSION['rol'] !== 'admin') {
    header('Location: dashboard.php'); // Redirige a usuarios no administradores
    exit();
}

// Consulta para obtener todos los autos
$query = "SELECT * FROM autos";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Autos</title>
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Usa el mismo CSS del dashboard -->
    <style>
        /* Estilos adicionales para gestionar-autos.php */
        .dashboard-container {
            max-width: 960px;
            margin: 40px auto; /* Aumentar margen superior del contenedor */
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
            margin-top: 20px; /* Aumentar margen superior del encabezado */
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .add-auto-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-auto-btn:hover {
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

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons a {
            padding: 5px 10px;
            margin-right: 5px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .action-buttons .edit-btn {
            background-color: #28a745;
        }

        .action-buttons .edit-btn:hover {
            background-color: #218838;
        }

        .action-buttons .delete-btn {
            background-color: #dc3545;
        }

        .action-buttons .delete-btn:hover {
            background-color: #c82333;
        }

        .regresar-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .regresar-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Encabezado -->
        <div class="dashboard-header">
            <h1>Gestionar Autos</h1>
            <a href="agregar_auto.php" class="add-auto-btn">Agregar Nuevo Auto</a>
        </div>

        <!-- Tabla de Autos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['marca']; ?></td>
                        <td><?php echo $row['modelo']; ?></td>
                        <td><?php echo $row['anio']; ?></td>
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        <td><?php echo ucfirst($row['estado']); ?></td> <!-- Capitaliza la primera letra -->
                        <td class="action-buttons">
                            <a href="editar_auto.php?id=<?php echo $row['id']; ?>" class="edit-btn">Editar</a>
                            <a href="eliminar_auto.php?id=<?php echo $row['id']; ?>" class="delete-btn">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="7">No hay autos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Botón para regresar al Dashboard -->
        <a href="dashboard.php" class="regresar-btn">Regresar al Dashboard</a>
    </div>
</body>
</html>
