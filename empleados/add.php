<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_cargo = $_POST['id_cargo'];
    $id_dependencia = $_POST['id_dependencia'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $sueldo = $_POST['sueldo'];
    $id_eps = $_POST['id_eps'];
    $id_pension = $_POST['id_pension'];
    $id_arl = $_POST['id_arl'];

    // Consultar la cantidad de empleados existentes
    $sql_count = "SELECT COUNT(*) AS total_empleados FROM empleado";
    $result_count = mysqli_query($conn, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_empleados = $row_count['total_empleados'];

    // Calcular el código del nuevo empleado
    $codigo_empleado = 2000 + ($total_empleados + 1);

    // Insertar en la tabla Empleado con el código calculado
    $sql_empleado = "INSERT INTO Empleado (codigo, nombre_empleado, id_cargo, id_dependencia, fecha_ingreso, sueldo) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_empleado = $conn->prepare($sql_empleado);
    $stmt_empleado->bind_param("isiisd", $codigo_empleado, $nombre, $id_cargo, $id_dependencia, $fecha_ingreso, $sueldo);
    $stmt_empleado->execute();

    // Insertar en la tabla empleado_eps usando el codigo_empleado
    $sql_eps = "INSERT INTO empleado_eps (id_empleado, id_eps) VALUES (?, ?)";
    $stmt_eps = $conn->prepare($sql_eps);
    $stmt_eps->bind_param("ii", $codigo_empleado, $id_eps);
    $stmt_eps->execute();

    // Insertar en la tabla empleado_pension
    $sql_pension = "INSERT INTO empleado_pension (id_empleado, id_pension) VALUES (?, ?)";
    $stmt_pension = $conn->prepare($sql_pension);
    $stmt_pension->bind_param("ii", $codigo_empleado, $id_pension);
    $stmt_pension->execute();

    // Insertar en la tabla empleado_arl
    $sql_arl = "INSERT INTO empleado_arl (id_empleado, id_arl) VALUES (?, ?)";
    $stmt_arl = $conn->prepare($sql_arl);
    $stmt_arl->bind_param("ii", $codigo_empleado, $id_arl);
    $stmt_arl->execute();

    header("Location: list.php");
    exit();
}

// Consultar dependencias
$sql_dependencias = "SELECT id_dependencia, nombre FROM dependencia";
$result_dependencias = mysqli_query($conn, $sql_dependencias);

// Consultar cargos
$sql_cargos = "SELECT id_cargo, nombre FROM cargo";
$result_cargos = mysqli_query($conn, $sql_cargos);

// Consultar EPS
$sql_eps = "SELECT id_eps, nombre FROM eps";
$result_eps = mysqli_query($conn, $sql_eps);

// Consultar Pensión
$sql_pension = "SELECT id_pension, nombre FROM pension";
$result_pension = mysqli_query($conn, $sql_pension);

// Consultar ARL
$sql_arl = "SELECT id_arl, nombre FROM arl";
$result_arl = mysqli_query($conn, $sql_arl);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Añadir Empleado</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Añadir Empleado</h2>
<form method="post">
    Nombre: <input type="text" name="nombre" required><br>
    
    Cargo: 
    <select name="id_cargo" required>
        <option value="">Seleccione un cargo</option>
        <?php while ($row = mysqli_fetch_assoc($result_cargos)) { ?>
            <option value="<?php echo $row['id_cargo']; ?>"><?php echo $row['nombre']; ?></option>
        <?php } ?>
    </select><br>
    
    Dependencia: 
    <select name="id_dependencia" required>
        <option value="">Seleccione una dependencia</option>
        <?php while ($row = mysqli_fetch_assoc($result_dependencias)) { ?>
            <option value="<?php echo $row['id_dependencia']; ?>"><?php echo $row['nombre']; ?></option>
        <?php } ?>
    </select><br>

    Fecha de Ingreso: <input type="date" name="fecha_ingreso" required><br>

    Sueldo: <input type="number" name="sueldo" required><br>

    EPS:
    <select name="id_eps" required>
        <option value="">Seleccione una EPS</option>
        <?php while ($row = mysqli_fetch_assoc($result_eps)) { ?>
            <option value="<?php echo $row['id_eps']; ?>"><?php echo $row['nombre']; ?></option>
        <?php } ?>
    </select><br>

    Pensión:
    <select name="id_pension" required>
        <option value="">Seleccione una Institución de Pensión</option>
        <?php while ($row = mysqli_fetch_assoc($result_pension)) { ?>
            <option value="<?php echo $row['id_pension']; ?>"><?php echo $row['nombre']; ?></option>
        <?php } ?>
    </select><br>

    ARL:
    <select name="id_arl" required>
        <option value="">Seleccione una ARL</option>
        <?php while ($row = mysqli_fetch_assoc($result_arl)) { ?>
            <option value="<?php echo $row['id_arl']; ?>"><?php echo $row['nombre']; ?></option>
        <?php } ?>
    </select><br>
    
    <button type="submit">Añadir Empleado</button>
</form>
</body>
</html>
