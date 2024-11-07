<?php
include '../db.php';

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
    echo "<table border='1'>";
    echo "<tr>
            <th>Código</th>
            <th>Nombre del Empleado</th>
            <th>Dependencia</th>
            <th>Cargo</th>
            <th>Fecha de Ingreso</th>
            <th>Sueldo</th>
            <th>Acciones</th>
         </tr>";

    while($row = $result->fetch_assoc()) {
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

    echo "</table>
    <a href='../index.php'>Volver al inicio</a>";
} else {
    echo "No se encontraron empleados.";
}

$conn->close();
?>
