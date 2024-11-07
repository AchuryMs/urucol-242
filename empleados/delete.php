<?php
include '../db.php';

$id_empleado = $_GET['id'];

// Iniciar una transacción para asegurar que todas las eliminaciones ocurran juntas
$conn->begin_transaction();

try {
    // Eliminar registros relacionados en la tabla novedades
    $sql_novedades = "DELETE FROM novedades WHERE codigo = ?";
    $stmt_novedades = $conn->prepare($sql_novedades);
    $stmt_novedades->bind_param("i", $id_empleado);
    $stmt_novedades->execute();

    // Eliminar registros en la tabla empleado_eps
    $sql_eps = "DELETE FROM empleado_eps WHERE id_empleado = ?";
    $stmt_eps = $conn->prepare($sql_eps);
    $stmt_eps->bind_param("i", $id_empleado);
    $stmt_eps->execute();

    // Eliminar registros en la tabla empleado_pension
    $sql_pension = "DELETE FROM empleado_pension WHERE id_empleado = ?";
    $stmt_pension = $conn->prepare($sql_pension);
    $stmt_pension->bind_param("i", $id_empleado);
    $stmt_pension->execute();

    // Eliminar registros en la tabla empleado_arl
    $sql_arl = "DELETE FROM empleado_arl WHERE id_empleado = ?";
    $stmt_arl = $conn->prepare($sql_arl);
    $stmt_arl->bind_param("i", $id_empleado);
    $stmt_arl->execute();

    // Finalmente, eliminar el empleado de la tabla Empleado
    $sql_empleado = "DELETE FROM empleado WHERE codigo = ?";
    $stmt_empleado = $conn->prepare($sql_empleado);
    $stmt_empleado->bind_param("i", $id_empleado);
    $stmt_empleado->execute();

    // Confirmar la transacción si todas las consultas se ejecutaron correctamente
    $conn->commit();
    
    // Redireccionar después de la eliminación
    header("Location: list.php");
    exit();

} catch (Exception $e) {
    // Si ocurre algún error, revertir la transacción
    $conn->rollback();
    echo "Error al eliminar el empleado: " . $e->getMessage();
}
?>
