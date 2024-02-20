<?php
session_start();
unset($_SESSION['f']);
require "cnx/cnx.php";

if (isset($_GET['m']) and !empty($_GET['m']) and isset($_SESSION['usr']) and (isset($_SESSION['rol']))) {
    $id_modulo = $_GET['m'];
} elseif (isset($_SESSION['m']) and isset($_SESSION['usr']) and (isset($_SESSION['rol']))) {
    $id_modulo = $_SESSION['m'];
} else {
    header("Location: ../modulos/index.php");
    exit();
}
$id_rol = $_SESSION['rol'];
$_SESSION['m'] = $id_modulo;
if ($id_rol == 1) {
    $sql_formatos = sqlsrv_query($cnx, "SELECT row_number() OVER (ORDER BY id desc) as fila,* FROM formatos where id_modulo='$id_modulo'");
} else {
    $sql_formatos = sqlsrv_query($cnx, "SELECT row_number() OVER (ORDER BY id desc) as fila,* FROM formatos where id_modulo='$id_modulo' and estado='2'");
}


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

        .contenido {
            align-items: center;
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
            <div class="text-center">
                <h4 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/38/rtf-document.png" /> Formatos de Guadalajara</h4>
            </div>
            <hr>

            <?php if (sqlsrv_has_rows($sql_formatos)) { ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Formato</th>
                            <?php if ($id_rol == 1) { ?>

                                <th scope="col">Fecha de creación</th>
                                <th scope="col">Ultima modificación</th>
                                <th scope="col" class="text-center" colspan="4">Archivos</th>
                                <th scope="col">Estado</th>
                            <?php } ?>
                            <?php if ($id_rol == 1) { ?>
                                <th scope="col" colspan="4" class="text-center">Acciones</th>
                            <?php } elseif ($id_rol == 2) { ?>
                                <th scope="col" colspan="3" class="text-center">Acciones</th>
                            <?php } elseif ($id_rol == 3) { ?>
                                <th scope="col" class="text-center">Acciones</th>
                            <?php } ?>


                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 0; // Inicializamos un índice para hacer los ID únicos

                        // Iterar sobre los formatos
                        while ($formatos = sqlsrv_fetch_array($sql_formatos)) {
                            $id_formato = $formatos['id'];
                            $sql_commit = sqlsrv_query($cnx, "select top 1 fecha from commits where id_formato='$id_formato' order by id desc");
                            $fecha_commit = '---------------';
                            if (sqlsrv_has_rows($sql_commit)) {
                                $commit = sqlsrv_fetch_array($sql_commit);
                                $fecha_commit = $commit['fecha'];
                            }
                        ?>
                            <tr>
                                <th scope="row"><?= $formatos['fila'] ?></th>
                                <td><i class="fa-solid fa-file-pdf"></i> <?= $formatos['nombre'] ?></td>
                                <?php if ($id_rol == 1) { ?>
                                    <td><i class="fa-solid fa-calendar-days"></i> <?= $formatos['fechaC'] ?></td>
                                    <td><i class="fa-solid fa-calendar-days"></i> <?= $fecha_commit ?></td>
                                    <td>
                                        <button class="btn btn-info btnArchivo" data-tipo="css" data-id="<?= $formatos['id'] ?>" data-archivo="<?= $formatos['css'] ?>"><i class="fa-brands fa-css3-alt"></i> css</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btnArchivo" data-tipo="html" data-id="<?= $formatos['id'] ?>" data-archivo="<?= $formatos['html'] ?>"><i class="fa-brands fa-html5"></i> html</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btnArchivo" data-tipo="header" data-id="<?= $formatos['id'] ?>" data-archivo="<?= $formatos['header'] ?>"><i class="fa-regular fa-image"></i> header</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-secondary btnArchivo" data-tipo="footer" data-id="<?= $formatos['id'] ?>" data-archivo="<?= $formatos['footer'] ?>"><i class="fa-regular fa-image"></i> footer</button>
                                    </td>
                                    <td>
                                        <form id="estadoForm<?= $index ?>" action="actualizar_estado.php" method="POST">
                                            <?php if ($formatos['estado'] == '1') { ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="customSwitch<?= $index ?>" name="estado" value="2">
                                                    <input type="hidden" name="estado" value="2">
                                                    <label class="custom-control-label badge bg-danger rounded-pill" for="customSwitch<?= $index ?>">Inactivo</label>
                                                </div>
                                            <?php }
                                            if ($formatos['estado'] == '2') { ?>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="customSwitch<?= $index ?>" name="estado" value="1" checked>
                                                    <input type="hidden" name="estado" value="1">
                                                    <label class="custom-control-label badge bg-success rounded-pill" for="customSwitch<?= $index ?>">Activo</label>
                                                </div>
                                            <?php } ?>
                                            <input type="hidden" name="id_formato" value="<?= $formatos['id']; ?>">
                                            <button type="submit" style="display: none;"></button>
                                        </form>
                                    </td>
                                <?php } ?>
                                <?php if ($id_rol == 1 || $id_rol == 2) { ?>
                                    <td class="text-center">

                                    <button class="btn btn-warning btnConfigurar" data-id="<?= $formatos['id'] ?>" data-top="<?= $formatos['top'] ?>" data-bottom="<?= $formatos['bottom'] ?>" 
                                    data-left="<?= $formatos['left'] ?>" data-right="<?= $formatos['right'] ?>" data-tamanio="<?= $formatos['tamanio'] ?>">Configurar</button>

                                    </td>
                                <?php } ?>
                                <?php if ($id_rol == 1 || ($id_rol == 2 and $formatos['estado'] == 2)) { ?>
                                    <td class="text-center">

                                        <a href="editor.php?f=<?= $formatos['id'] ?>" class="btn btn-primary">Editar</a>

                                    </td>
                                <?php } ?>
                                <?php if ($id_rol == 1) { ?>
                                    <td class="text-center">

                                        <a href="campos.php?f=<?= $formatos['id'] ?>" class="btn btn-success">Campos</a>

                                    </td>
                                <?php } ?>
                                <td class="text-center">
                                    <button data-id_formato="<?= $formatos['id'] ?>" class="btn btn-info btnImprimir">Imprimir</button>
                                </td>
                            </tr>
                        <?php
                            $index++; // Incrementamos el índice para hacer los ID únicos
                        }
                        ?>

                    </tbody>
                </table>
            <?php } else { ?>
                <?php if ($id_rol == 1) { ?>
                    <div class="alert alert-danger" role="alert">
                        Este modulo aun no cuenta con ningun formato, para crear uno presione el boton de crear nuevo formato.
                    </div>
                <?php } ?>
                <?php if ($id_rol == 2 || $id_rol == 3) { ?>
                    <div class="alert alert-danger" role="alert">
                        Este modulo no tiene ningun formato disponible, para solicitar uno comuniquese con soporte.
                    </div>
                <?php } ?>
            <?php } ?>
            <?php if ($id_rol == 1) { ?>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalFormato"><i class="fa-solid fa-file"></i> Crear nuevo formato</button>
            <?php } ?>
            <a href="../modulos/index.php" class="btn btn-dark btn-sm"><i class="fas fa-angle-left"></i> Regresar</a>
        </div>
    </div>

    <!-- Modal nueva tarea -->
    <div class="modal fade" id="modalFormato" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nuevo formato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="CrearFormato.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del formato</label>
                            <input type="text" class="form-control" placeholder="Ingrese el nombre" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="html" class="form-label">Carga tu archivo txt del html</label>
                            <input type="file" class="form-control" id="html" accept=".txt" name="html" required>
                        </div>

                        <div class="mb-3">
                            <label for="css" class="form-label">Carga tu archivo txt de los estilos css</label>
                            <input type="file" class="form-control" id="css" accept=".txt" name="css" required>
                        </div>
                        <div class="mb-3">
                            <label for="encabezado" class="form-label">Carga tu imagen del header</label>
                            <input type="file" class="form-control" id="encabezado" accept=".jpg" name="header" required>
                        </div>
                        <div class="mb-3">
                            <label for="pie" class="form-label">Carga tu imagen del footer</label>
                            <input type="file" class="form-control" id="pie" accept=".jpg" name="footer" required>
                        </div>

                        <div class="input-group mb-3 text-center">
                            <label class="form-label">Define el margen que tendrá el formato</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Superior</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" name="top" aria-describedby="basic-addon1" required>
                                        <span class="input-group-text" id="basic-addon1">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Inferior</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" name="bottom" aria-describedby="basic-addon2" required>
                                        <span class="input-group-text" id="basic-addon2">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Izquierda</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" name="left" aria-describedby="basic-addon3" required>
                                        <span class="input-group-text" id="basic-addon3">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Derecha</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" name="right" aria-describedby="basic-addon4" required>
                                        <span class="input-group-text" id="basic-addon4">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tamanio" class="form-label">Selecciona el tamaño que tendra las hojas del pdf</label>
                            <select class="custom-select custom-select-sm mb-3" name="tamanio" required>
                                <option value="">--Seleccion una opción--</option>
                                <option value="carta">Carta</option>
                                <option value="oficio">Oficio</option>
                            </select>
                        </div>




                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnCrearTarea">Crear formato</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- modal configurar -->
    <div class="modal fade" id="modalConfigurar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Configurar parametros del formato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="configurar.php" method="post">
                        <input type="hidden" id="c_id_formato" name="id_formato">
                        <div class="input-group mb-3 text-center">
                            <label class="form-label">Define el margen al que desea actualizar el formato</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Superior</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" id="mtop" name="top" aria-describedby="basic-addon1" required>
                                        <span class="input-group-text" id="basic-addon1">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Inferior</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" id="mbottom" name="bottom" aria-describedby="basic-addon2" required>
                                        <span class="input-group-text" id="basic-addon2">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Izquierda</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" id="mleft" name="left" aria-describedby="basic-addon3" required>
                                        <span class="input-group-text" id="basic-addon3">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Derecha</label>
                                    <div class="d-flex align-items-center">
                                        <input type="number" class="form-control" id="mright" name="right" aria-describedby="basic-addon4" required>
                                        <span class="input-group-text" id="basic-addon4">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tamanio" class="form-label">Tamaño del formato</label>
                            <select class="custom-select custom-select-sm mb-3" name="tamanio" id="tamanio" required>
                            </select>
                        </div>




                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnCrearTarea">Actualizar formato</button>
                    </form>
                </div>

            </div>
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
    <!-- modal para el replace archivo -->
    <div class="modal fade" id="modalArchivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Archivo <span class="tipoArchivo"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="replaceArchivo.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="css" class="form-label">Si deseas reemplazar el archivo actual carga el nuevo archivo</label>
                            <input type="file" class="form-control" id="archivo" accept=".txt" name="archivo" required>
                            <input type="hidden" class="id_formato" id="" name="id_formato">
                            <input type="hidden" class="tipo" name="tipo">
                        </div>
                        <div class="mb-3">

                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <a id="" href="" download class="btn btn-success btnDescarga">Descargar archivo actual</a>
                        <button type="submit" class="btn btn-primary">Reemplazar archivo</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- modal para el replace de header y footer-->
    <div class="modal fade" id="modalArchivoFondo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Archivo <span class="tipoArchivo"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="replaceArchivo.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="css" class="form-label">Si deseas reemplazar el archivo actual carga el nuevo archivo</label>
                            <input type="file" class="form-control" id="archivoFondo" accept=".jpg" name="archivo" required>
                            <input type="hidden" class="id_formato" name="id_formato">
                            <input type="hidden" class="tipo" name="tipo">
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <a id="" href="" download class="btn btn-success btnDescarga">Descargar archivo actual</a>
                        <button type="submit" class="btn btn-primary">Reemplazar archivo</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
   <script src="js/formatos.js"></script>
    
</body>

</html>