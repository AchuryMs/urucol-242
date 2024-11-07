<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<?php
include 'db.php';

// Consulta para contar el número de empleados
$sql = "SELECT COUNT(*) AS total_empleados FROM empleado";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_empleados = $row['total_empleados'];
?>

<body>
    <div class="container">


        <div class="sidebar">
            <!-- Contador de empleados -->
            <div class="employee-count">
                <span><?php echo $total_empleados; ?></span> <!-- Número de empleados -->
            </div>

            <!-- Título Empleados debajo del contador -->
            <h3>Empleados</h3>

            <ul>
                <li><a href="empleados/add.php">Añadir Empleado</a></li>
                <li><a href="novedades/add_novedad.php">Añadir Novedades</a></li>
                <li><a href="empleados/list.php">CRUD de Empleados</a></li>
                <li><a href="reportes/reporte_nomina.php">Reporte de Nómina</a></li>
                <li><a href="reportes/reporte_individual.php">Reporte de Información Individual</a></li>
                <li><a href="reportes/reporte_salud.php">Reporte de Salud y Pensión</a></li>
                <li><a href="reportes/reporte_novedades.php">Reporte de Novedades</a></li>
            </ul>
        </div>
        <div class="content">
            <h1 class="main-title">UruCol-242</h1>

            <?php
            include 'db.php';

            // Consulta corregida basándose en la estructura de las tablas
            $sql = "
                SELECT 
                    E.codigo,
                    E.nombre_empleado,
                    D.id_dependencia,
                    C.id_cargo,
                    E.fecha_ingreso,
                    E.sueldo
                FROM 
                    empleado E
                LEFT JOIN 
                    dependencia D ON E.id_dependencia = D.id_dependencia
                LEFT JOIN 
                    cargo C ON E.id_cargo = C.id_cargo
            ";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1' class='employee-table'>";
                echo "<tr>
                        <th>Código</th>
                        <th>Nombre del Empleado</th>
                        <th>Dependencia</th>
                        <th>Cargo</th>
                        <th>Fecha de Ingreso</th>
                        <th>Sueldo</th>
                        <th>Acciones</th>
                     </tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["codigo"] . "</td>
                            <td>" . $row["nombre_empleado"] . "</td>
                            <td>" . $row["id_dependencia"] . "</td>
                            <td>" . $row["id_cargo"] . "</td>
                            <td>" . $row["fecha_ingreso"] . "</td>
                            <td>" . $row["sueldo"] . "</td>
                            <td>
                                <a href='delete.php?id=" . $row["codigo"] . "' onclick='return confirm(\"¿Estás seguro de eliminar este empleado?\")'>Eliminar</a>
                            </td>
                         </tr>";
                }

                echo "</table>";
            } else {
                echo "No se encontraron empleados.";
            }

            $conn->close();
            ?>

        </div>
    </div>
</body>

</html>