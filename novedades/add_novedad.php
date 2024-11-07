<?php
include '../db.php';

// Consultar los empleados
$sql_empleados = "SELECT codigo, nombre_empleado FROM empleado";
$result_empleados = mysqli_query($conn, $sql_empleados);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_empleado = $_POST['id_empleado'];
    $novedad_incapacidad = isset($_POST['novedad_incapacidad']) ? 1 : 0;
    $novedad_vacaciones = isset($_POST['novedad_vacaciones']) ? 1 : 0;
    $dias_trabajados = $_POST['dias_trabajados'];
    $dias_incapacidad = $_POST['dias_incapacidad'] ?? 0;
    $dias_vacaciones = $_POST['dias_vacaciones'] ?? 0;
    $fecha_inicio_incapacidad = $_POST['fecha_inicio_incapacidad'] ?? null;
    $fecha_fin_incapacidad = $_POST['fecha_fin_incapacidad'] ?? null;
    $fecha_inicio_vacaciones = $_POST['fecha_inicio_vacaciones'] ?? null;
    $fecha_fin_vacaciones = $_POST['fecha_fin_vacaciones'] ?? null;
    $bonificacion = $_POST['bonificacion'];
    $transporte = $_POST['transporte'];

    // Insertar novedad
    $sql = "INSERT INTO novedades (codigo, novedad_incapacidad, novedad_vacaciones, dias_trabajados, 
            dias_incapacidad, dias_vacaciones, fecha_inicio_incapacidad, fecha_fin_incapacidad, 
            fecha_inicio_vacaciones, fecha_fin_vacaciones, bonificacion, transporte)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiisssdd", $id_empleado, $novedad_incapacidad, $novedad_vacaciones, 
                      $dias_trabajados, $dias_incapacidad, $dias_vacaciones, $fecha_inicio_incapacidad, 
                      $fecha_fin_incapacidad, $fecha_inicio_vacaciones, $fecha_fin_vacaciones, 
                      $bonificacion, $transporte);
    $stmt->execute();
    header("Location: ../reportes/reporte_novedades.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Añadir Novedad</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Añadir Novedad</h2>
<form method="post">
    Empleado: 
    <select name="id_empleado" required>
        <option value="">Seleccione un empleado</option>
        <?php while ($row = mysqli_fetch_assoc($result_empleados)) { ?>
            <option value="<?php echo $row['codigo']; ?>"><?php echo $row['nombre_empleado']; ?></option>
        <?php } ?>
    </select><br>
    
    <label>
        <input type="checkbox" name="novedad_incapacidad"> Novedad Incapacidad
    </label><br>
    
    <label>
        <input type="checkbox" name="novedad_vacaciones"> Novedad Vacaciones
    </label><br>
    
    Días Trabajados en el Mes: 
    <input type="number" name="dias_trabajados" required><br>
    
    Días de Incapacidad: 
    <input type="number" name="dias_incapacidad"><br>
    
    Días de Vacaciones: 
    <input type="number" name="dias_vacaciones"><br>

    Fecha de Inicio de Incapacidad: 
    <input type="date" name="fecha_inicio_incapacidad"><br>
    
    Fecha de Fin de Incapacidad: 
    <input type="date" name="fecha_fin_incapacidad"><br>
    
    Fecha de Inicio de Vacaciones: 
    <input type="date" name="fecha_inicio_vacaciones"><br>
    
    Fecha de Fin de Vacaciones: 
    <input type="date" name="fecha_fin_vacaciones"><br>
    
    Bonificación: 
    <input type="number" step="0.01" name="bonificacion"><br>

    Transporte: 
    <input type="number" step="0.01" name="transporte"><br>

    <button type="submit">Añadir Novedad</button>
     <a href="../index.php">Volver</a> 
</form>
</body>
</html>
