<?php
session_start();
require "cnx/cnx.php";
if (!isset($_SESSION['usr']) || ($_SESSION['rol'] != 1)) {
    header("Location: ../modulos/administrator/logout.php");
}
if (isset($_GET['f']) and !empty($_GET['f']) and isset($_SESSION['m'])) {
    $id_formato = $_GET['f'];
} elseif (isset($_SESSION['f']) and isset($_SESSION['m'])) {
    $id_formato = $_SESSION['f'];
} else {
    header("Location: formatos.php");
    exit();
}
$_SESSION['f'] = $id_formato;
$id_modulo = $_SESSION['m'];
// validar si el formato pertenece al modulo
$sql_validar = sqlsrv_query($cnx, "select f.* from formatos as f inner join modulos as m
on f.id_modulo=m.id_modulo where m.id_modulo='$id_modulo' and f.id='$id_formato'");

if (!sqlsrv_has_rows($sql_validar)) {
    $_SESSION['error'] = 'Acceso denegado';
    header("Location: formatos.php");
    exit();
}
$formatos = sqlsrv_fetch_array($sql_validar);

// consultar los campos disponibles de este formato
$sql_campos = sqlsrv_query($cnx, "SELECT row_number() OVER (ORDER BY id desc) as fila,* FROM campos WHERE id_formato='$id_formato'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/pdf.css">
    <script src="js/alerta.js"></script>
    <title>Editor de Plantilla</title>
    <style>
        body {
            background-image: url(img/back.jpg);
            background-repeat: repeat;
            background-size: 100%;
            background-attachment: fixed;
            overflow-x: hidden;
            /* ocultar scrolBar horizontal*/
        }

        body {
            font-family: sans-serif;
            font-style: normal;
            font-weight: normal;

            margin-top: -1%;
            margin-bottom: 0%;

        }
    </style>
    <?php require "nav.php"; ?>

</head>

<body>
    <?php
    if (isset($_SESSION['snDatos'])) {
        echo "<script>
        window.onload = function() {
            mostrarSweetAlert('error', 'Fatal error', '" . htmlspecialchars($_SESSION['snDatos']) . "');
        };
    </script>";

        // Limpiar el mensaje de error en sesión
        unset($_SESSION['snDatos']);
    }
    if (isset($_SESSION['error'])) {
        echo "<script>
        window.onload = function() {
            mostrarSweetAlert('error', 'Fatal error', '" . htmlspecialchars($_SESSION['error']) . "');
        };
    </script>";

        // Limpiar el mensaje de error en sesión
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<script>
        window.onload = function() {
            mostrarSweetAlert('success', 'En hora buena!', '" . htmlspecialchars($_SESSION['success']) . "');
        };
    </script>";

        // Limpiar el mensaje de error en sesión
        unset($_SESSION['success']);
    }
    ?>


    <div class="container col-md-12">

        <div class="contenido">
            <div class="contenido">
                <div class="text-center">
                    <h4 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/38/rtf-document.png" /> Campos disponibles para el formato <?= $formatos['nombre'] ?></h4>
                </div>
                <hr>

                <?php if (sqlsrv_has_rows($sql_campos)) { ?>
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Campo</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($campos = sqlsrv_fetch_array($sql_campos)) { ?>
                                <tr>
                                    <th scope="row"><?= $campos['fila'] ?></th>
                                    <td><?= $campos['campo'] ?></td>
                                    <td><i class="fa-solid fa-align-justify"></i></i> <?= $campos['descripcion'] ?></td>
                                    <td>
                                        <button class="btn btn-danger" id="btnDelete" data-id="<?= $campos['id'] ?>" data-campo="<?= $campos['campo'] ?>"><i class="fa-solid fa-trash-can"></i> Eliminar</button>
                                    </td>


                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="alert alert-danger" role="alert">
                        Este formato aun no cuenta con ningun campo disponible, para crear uno presione el boton de Agregar campo.
                    </div>
                <?php } ?>

                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCampo"><i class="fa-solid fa-plus"></i> Agregar campo</button>

                <a href="formatos.php" class="btn btn-dark btn-sm"><i class="fas fa-angle-left"></i> Regresar</a>
            </div>
        </div>

        <nav class="navbar sticky-bottom navbar-expand-lg">
            <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
                Implementta ©<br>
                Estrategas de México <i class="far fa-registered"></i><br>
                Centro de Inteligencia Informática y Geografía Aplicada CIIGA
                <hr style="width:105%;border-color:#7a7a7a;">
                Created and designed by <i class="far fa-copyright"></i> <?php echo date('Y') ?> Estrategas de México<br>
            </span>
            <hr>
            <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
                Contacto:<br>
                <i class="fas fa-phone-alt"></i> Red: 187<br>
                <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
                <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
            </span>
            <ul class="navbar-nav mr-auto">
                <br><br><br><br><br><br><br><br>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <a href="#"><img src="img/logoImplementta.png" width="155" height="150" alt=""></a>
                <a href="http://estrategas.mx/" target="_blank"><img src="img/logoTop.png" width="200" height="85" alt=""></a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </form>
        </nav>
        <!-- Modal nueva tarea -->
        <div class="modal fade" id="modalCampo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo campo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="CrearCampo.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nombre del campo</label>
                                <input type="text" class="form-control" placeholder="Ingrese la nomenclatura que usara el usuario" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Breve descripción del campo</label>
                                <textarea class="form-control" rows="3" placeholder="Descripción del campo" name="descripcion" required></textarea>
                            </div>



                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnCrearTarea">Agregar campo</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#btnDelete').click(function() {
                    // Obtener el ID y el campo de los datos del botón
                    var id = $(this).data('id');
                    var campo = $(this).data('campo');

                    // Mostrar el mensaje de confirmación
                    Swal.fire({
                        title: '¿Estás seguro de eliminar el campo ' + campo + '?',
                        text: '¡No podrás revertir esto!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        // Si el usuario confirma
                        if (result.isConfirmed) {
                            // Enviar solicitud AJAX para eliminar el registro
                            $.ajax({
                                url: 'eliminarCampo.php', // Ruta al archivo PHP que eliminará el registro
                                method: 'POST',
                                data: {
                                    id: id // ID del registro a eliminar
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Si se eliminó correctamente, mostrar mensaje de éxito
                                        Swal.fire({
                                            title: 'Eliminado!',
                                            text: response.message,
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            // Redirigir a la vista actual después de un tiempo
                                            window.location.reload();
                                        });
                                    } else {
                                        // Si hubo un error al eliminar, mostrar mensaje de error
                                        Swal.fire({
                                            title: 'Error',
                                            text: response.message,
                                            icon: 'error',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok'
                                        });
                                    }
                                },
                                error: function() {
                                    // Si hay un error, mostrar mensaje de error
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Hubo un error al eliminar el campo.',
                                        icon: 'error',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
</body>

</html>