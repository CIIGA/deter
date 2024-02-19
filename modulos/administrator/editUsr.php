<?php
session_start();
if ((isset($_SESSION['usr'])) and (isset($_SESSION['rol']))) {
    require "../../acnxerdm/cnxAd.php";
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" value="notranslate">
        <title>Actualizar Usuario</title>
        <link rel="icon" href="../icono/implementtaIcon.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link href="../fontawesome/css/all.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css" id="theme-styles">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
        <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
        <style>
            body {
                background-image: url(../img/backImplementta.jpg);
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
                width: 100%;
                height: 100%;
                margin-top: 3%;
                padding-top: 0px;
            }
        </style>

    </head>

    <body>
        <?php
        if ((isset($_GET['reUsr'])) and (isset($_GET['tkn'])) and (isset($_GET['id'])) and (isset($_GET['usr']))) {

            $idUsrNew = $_GET['tkn'];
            $va = "select * from usuarioNuevo
            where id_usuarioNuevo='$idUsrNew'";
            $val = sqlsrv_query($cnx, $va);
            $valida = sqlsrv_fetch_array($val);

            if (isset($valida)) {

                if (isset($_POST['save'])) {
                    
                    $nombre = $_POST['nombre'];
                    $correo = $_POST['correo'];
                    $clave = $_POST['clave'];

                    $va2 = "select id_usuarioNuevo from usuarioNuevo
                    where ((correo='$correo') and (id_usuarioNuevo <> $idUsrNew))";
                    $val2 = sqlsrv_query($cnx, $va2);
                    $valida2 = sqlsrv_fetch_array($val2);

                    if (isset($valida2)) {
                        echo "<script>
                        let timerInterval
                        Swal.fire({
                            title: '¡Error!',
                            html: 'El usuario ya existe <br>Ingrese un usuario nuevo',
                            icon: 'error',
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                                b.textContent = Swal.getTimerLeft()
                            }, 100)
                            },
                            willClose: () => {
                            clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                            console.log('I was closed by the timer')
                            }
                        })
                        </script>";
                        echo '<meta http-equiv="refresh" content="2,url=usrs.php">';
                    } else {

                        $tarjeta = "UPDATE usuarioNuevo SET nombreUsr='$nombre', correo='$correo', clave='$clave' WHERE id_usuarioNuevo='$idUsrNew'";
                        sqlsrv_query($cnx, $tarjeta) or die('No se actualizo la consulta perfilesTarjeta ' . print_r(sqlsrv_errors(), true));

                        require "../../EditorPlantilla/historial.php";
                    $fecha = date('Y-m-d');
                    $hora = date('H:i:s');
                    $insert = commit($_SESSION['usr'], $idUsrNew,14, '', $fecha, $hora);

                        echo "<script>
                                Swal.fire({
                                    title: 'Finalizado',
                                    //text: 'Nuevo usuario agregado correctamente',
                                    icon: 'success',
                                    allowOutsideClick: false, // Evitar que se cierre haciendo clic afuera
                                    allowEscapeKey: false, // Evitar que se cierre presionando Esc
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                    location.href ='usrs.php';
                                    }
                                });
                                </script>";
                    }
                }
        ?>



                <div class="container">
                    <h2 style="text-shadow: 0px 0px 2px #717171;"><img width="72" height="64" src="../img/flor.png" alt="flower" />Implementta DCF</h2>
                    <!-- Main content -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Actualizar Usuario Implementta DCF</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="" method="post">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Nombre de Usuario: *</label>
                                            <input type="text" name="nombre" class="form-control form-control-sm" minlength="5" maxlength="40" value="<?php echo $valida['nombreUsr'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Correo: *</label>
                                            <input type="email" name="correo" class="form-control form-control-sm" autocomplete="new-password" minlength="5" maxlength="300" value="<?php echo $valida['correo'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Contraseña: *</label>
                                            <input type="text" name="clave" class="form-control form-control-sm" autocomplete="new-password" minlength="6" maxlength="20" value="<?php echo $valida['clave'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Rol de usuario: *</label>
                                            <select class="form-control form-control-sm" name="rol" required>
                                                <option value="3">General</option>
                                                <option value="2">Administrador</option>
                                                <!-- <option>Root</option> -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">* Todos los campos son requeridos.</small>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">

                                <div class="form-row">
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-2">
                                        <a href="usrs.php" class="btn btn-dark btn-sm btn-block"><i class="fas fa-chevron-left"></i> Cancelar</a>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-warning btn-sm btn-block" name="save"><i class="fas fa-check"></i> Actualizar Usuario</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
        <?php
            } else {
                echo '<meta http-equiv="refresh" content="0,url=usrs.php">';
            }
        } else {
            echo '<meta http-equiv="refresh" content="0,url=usrs.php">';
        } ?>
        <br><br><br>
        <!--*************************INICIO FOOTER***********************************************************************-->
        <nav class="navbar sticky-bottom navbar-expand-lg">
            <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
                Implementta Web <i class="far fa-registered"></i><br>
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
                <a href=""><img src="../img/logoImplementta.png" width="155" height="150" alt=""></a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="http://estrategas.mx/" target="_blank"><img src="../img/logoTop.png" width="200" height="85" alt=""></a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </form>
        </nav>
        <!--***********************************FIN FOOTER****************************************************************-->
    </body>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <script src="../plugins/sparklines/sparkline.js"></script>
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    </html>
<?php } else {
    echo '<meta http-equiv="refresh" content="1,url=logout.php">';
} ?>