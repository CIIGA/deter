<?php
session_start();
// Verificar si se recibieron los datos esperados
if (isset($_POST['id'])) {
    require "cnx/cnx.php";
    require "historial.php";
    // Obtener el ID y el campo del registro a eliminar
    $id = $_POST['id'];

    // Aquí puedes incluir la lógica para eliminar el registro en tu base de datos.
    $delete= sqlsrv_query($cnx,"DELETE FROM campos where id='$id'");
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    if ($delete) {
        $insert = commit($_SESSION['usr'], $_SESSION['f'] ,10, '', $fecha, $hora);
        $response = array(
            'success' => true,
            'message' => 'El campo ha sido eliminado correctamente.'
        );
    }else{
        $response = array(
            'success' => false,
            'message' => 'Error al eliminar el campo, comuniquese con Desarrollo.'
        );
    }

 
  

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Si no se reciben los datos esperados, devolver un mensaje de error.
    $response = array(
        'success' => false,
        'message' => 'Error: No se recibieron los datos esperados.'
    );

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
