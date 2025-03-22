<?php
session_start();
include 'conexion.php'; // Incluye tu archivo de conexión a la base de datos

// Verifica si 'usuario' está definida en $_SESSION
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Inicializar variable para el filtro
$filter = "";

// Verifica si se ha enviado un filtro
if (isset($_POST['filter'])) {
    $filter = $_POST['filter'];
}

// Consulta para obtener autos disponibles, aplicando el filtro si existe
$query = "SELECT * FROM autos WHERE estado = 'disponible' AND (marca LIKE ? OR modelo LIKE ?)";
$stmt = $conn->prepare($query);
$searchTerm = "%" . $filter . "%"; // Agregar comodines para LIKE
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autos Disponibles</title>
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Usa el mismo CSS del dashboard -->
    <style>
        /* Estilos adicionales para autos-disponibles.php */
        .dashboard-container {
            max-width: 960px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .filter-container {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #007bff;
            border-radius: 8px;
            background-color: #f0f8ff;
            text-align: center;
        }

        .filter-input {
            padding: 10px;
            width: 80%; /* Ancho relativo para adaptarse */
            max-width: 400px; /* Ancho máximo */
            border-radius: 5px;
            border: 1px solid #ccc;
            font-style: italic;
            color: #aaa;
            box-sizing: border-box; /* Incluir padding y border en el ancho */
        }

        /* Placeholder para el filtro */
        .filter-input::placeholder {
            font-style: italic;
            color: #aaa;
        }

        .filter-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filter-button:hover {
            background-color: #0056b3;
        }

        .dashboard-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .auto-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            width: 300px;
            transition: transform 0.3s ease;
        }

        .auto-card:hover {
            transform: translateY(-5px);
        }

        .auto-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
            max-height: 200px;
            object-fit: cover;
        }

        .detalles {
            display: none;
            margin-top: 10px;
        }

        .ver-detalles {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .ver-detalles:hover {
            background-color: #0056b3;
        }

        .regresar-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: fit-content;
        }

        .regresar-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Autos Disponibles</h1>
        </header>

        <!-- Zona de filtración -->
        <div class="filter-container">
            <form method="POST" action="">
                <input type="text" name="filter" class="filter-input" placeholder="Para buscar el modelo, ingresa 'GTR-34','GTR-35', etc" value="<?php echo htmlspecialchars($filter); ?>">
                <button type="submit" class="filter-button">Filtrar</button>
                <a href="autos-disponibles.php" class="filter-button">Limpiar Filtro</a> <!-- Opción para limpiar filtro -->
            </form>
        </div>

        <div class="dashboard-content">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="auto-card">
                    <img src="img/<?php echo $row['imagen']; ?>" alt="<?php echo $row['marca'] . ' ' . $row['modelo']; ?>" class="auto-image">
                    <h2><?php echo $row['marca'] . ' ' . $row['modelo']; ?></h2>
                    <a href="#" class="ver-detalles" onclick="toggleDetalles(event, '<?php echo $row['id']; ?>')">Ver más detalles</a>
                    
                    <!-- Detalles ocultos -->
                    <div id="detalles-<?php echo $row['id']; ?>" class="detalles">
                        <p><strong>ID:</strong> <?php echo $row['id']; ?></p>
                        <p><strong>Año:</strong> <?php echo $row['anio']; ?></p>
                        <p><strong>Precio:</strong> $<?php echo number_format($row['precio'], 2); ?></p>
                        <p><strong>Descripción:</strong> <?php echo $row['descripcion']; ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Botón para regresar al Dashboard como enlace -->
        <a href="dashboard.php" class="regresar-btn">Regresar al Inicio</a>
    </div>

    <!-- Script para manejar mostrar/ocultar -->
    <script>
        function toggleDetalles(event, id) {
            event.preventDefault(); // Evitar que el enlace recargue la página
            const detalles = document.getElementById('detalles-' + id);
            
            // Alternar visibilidad de los detalles
            if (detalles.style.display === "none" || detalles.style.display === "") {
                detalles.style.display = "block"; // Mostrar detalles
                event.target.innerText = "Ocultar detalles"; // Cambiar texto del enlace
            } else {
                detalles.style.display = "none"; // Ocultar detalles
                event.target.innerText = "Ver más detalles"; // Restaurar texto del enlace
            }
        }
    </script>
</body>
</html>
