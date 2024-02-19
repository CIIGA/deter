<?php
session_start();
if ((isset($_SESSION['usr'])) and (isset($_SESSION['rol']))) {
    require "../../acnxerdm/cnxAd.php";
    //***************************************************/
    $us = "select usuarioNuevo.id_usuarioNuevo,usuarioNuevo.id_rol,usuarioNuevo.nombreUsr,usuarioNuevo.correo,
    usuarioNuevo.insertDate,usuarioNuevo.estado,roles.nombreRol from usuarioNuevo
    inner join roles on roles.id_rol=usuarioNuevo.id_rol where roles.id_rol not in ('1')";
    $use = sqlsrv_query($cnx, $us);
    $usuario = sqlsrv_fetch_array($use);
    //***************************************************/
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" value="notranslate">
        <title>Nuevo usuario</title>
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
                padding-top: 0px;
            }
        </style>
        <?php require "../include/nav.php"; ?>
    </head>

    <body>
        <div class="container">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <h2 style="text-shadow: 0px 0px 2px #717171;"><img width="72" height="64" src="../img/flor.png" alt="flower" />Implementta DCF</h2>
                </div>
                <div class="form-group col-md-6" style="text-align: center;">
                    <a href="../" class="btn btn-app bg-gradient-dark">
                        <i class="fas fa-chevron-left"></i> Regresar
                    </a>
                    <a href="addUsr.php" class="btn btn-app bg-success">
                        <span class="badge bg-yellow">Nuevo</span>
                        <i class="fas fa-users"></i> Nuevo Usuario
                    </a>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">

                </div>
                <div class="form-group col-md-6" style="text-align: center;">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="simple-results.html">
                                        <div class="input-group">
                                            <input type="search" class="form-control form-control-sm" placeholder="Buscar nombre de usuario o correo" required autofocus>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-lg btn-sm btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <hr>

            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th> </th>
                        <th>Nombre de usuario</th>
                        <th style="text-align: center;">Fecha Alta</th>
                        <th style="text-align: center;">Estatus</th>
                        <th>Usuario</th>
                        <th style="text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php do {
                        $idUsrNew = $usuario['id_usuarioNuevo'];
                        $ac = "select acceso.* from usuarioNuevo
                        inner join acceso on acceso.id_usuarioNuevo=usuarioNuevo.id_usuarioNuevo
                        where usuarioNuevo.id_usuarioNuevo=$idUsrNew";
                        $acc = sqlsrv_query($cnx, $ac);
                        $accesos = sqlsrv_fetch_array($acc);
                    ?>
                        <tr data-widget="expandable-table" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top">
                            <td style="text-align: center;">
                                <?php if ($usuario['id_rol'] == 1) { ?>
                                    <span class="badge badge-danger"><i class="fas fa-terminal"></i></span>
                                    <small id="emailHelp" class="form-text text-muted"><?php echo $usuario['nombreRol'] ?></small>
                                <?php } else if ($usuario['id_rol'] == 2) { ?>
                                    <span class="badge badge-warning"><i class="fas fa-user-shield"></i></span>
                                    <small id="emailHelp" class="form-text text-muted"><?php echo $usuario['nombreRol'] ?></small>
                                <?php } else if ($usuario['id_rol'] == 3) { ?>
                                    <span class="badge badge-dark"><i class="fas fa-user"></i></span>
                                    <small id="emailHelp" class="form-text text-muted"><?php echo $usuario['nombreRol'] ?></small>
                                <?php } ?>
                            </td>
                            <td style="vertical-align:middle;" >
                                <?php echo $usuario['nombreUsr'] ?>
                            </td>
                            <td style="text-align: center;vertical-align:middle;">
                                <small id="emailHelp" class="form-text text-muted"><?php echo $usuario['insertDate'] ?></small>
                            </td>
                            <td style="text-align: center;vertical-align:middle;">
                                <?php if ($usuario['estado'] == 1) { ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
                                <?php } else { ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Inactivo</span>
                                <?php } ?>
                                <?php if (!isset($accesos)) { ?>
                                    <small id="emailHelp" class="form-text text-muted">*Sin permisos de acceso</small>
                                <?php } ?>
                            </td>
                            <td style="vertical-align:middle;">
                                <small id="emailHelp" class="form-text text-muted"><?php echo $usuario['correo'] ?></small>
                            </td>
                            <td style="text-align: center;"><span class="badge badge-warning"><i class="fas fa-chevron-down"></i></span></td>
                        </tr>
                        <tr class="expandable-body">
                            <td colspan="5">
                                <div class="form-row">
                                    <div class="form-group col-md-6">

                                    </div>
                                    <div class="form-group col-md-2">
                                        <a href="permisoPlz.php?usr=<?php echo $usuario['id_usuarioNuevo'] ?>" class="btn btn-dark btn-lg btn-block btn-sm"><i class="fas fa-user-shield"></i> Permisos</a>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <a href="editUsr.php?reUsr=1&tkn=<?php echo $usuario['id_usuarioNuevo'] . '&id=' . date('HisYmd') . '&usr=' . rand(1, 99999999) ?>" class="btn btn-primary btn-lg btn-block btn-sm"><i class="fas fa-pen"></i> Actualizar</a>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <?php if ($usuario['estado'] == 1) { ?>
                                            <button type="button" class="btn btn-danger btn-lg btn-block btn-sm" data-toggle="tooltip" data-placement="right" title="Desactivar Acceso de Usuario" onclick="return Confirmar('delete.php?poneUsr=1&tkn=<?php echo $usuario['id_usuarioNuevo'] . '&id=' . date('HisYmd') . '&usr=' . rand(1, 99999999) ?>')"><i class="fas fa-lock"></i> Desactivar</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-warning btn-lg btn-block btn-sm" data-toggle="tooltip" data-placement="right" title="Rectivar Acceso de Usuario" onclick="return ConfirmarRe('delete.php?reUsr=1&tkn=<?php echo $usuario['id_usuarioNuevo'] . '&id=' . date('HisYmd') . '&usr=' . rand(1, 99999999) ?>')"><i class="fas fa-lock-open"></i> Reactivar</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } while ($usuario = sqlsrv_fetch_array($use)); ?>
                </tbody>
            </table>
        </div>
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
    <script src="../dist/js/adminlte.min.js"></script>
    <script>
        function Confirmar(key) {
            Swal.fire({
                title: 'Desactivar Usuario',
                text: '¿Estas seguro que quieres desactivar el acceso a este usuario? Ya no podrá acceder, pero podrás reactivarlo en cualquier momento.',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Si, desactivar Usuario',
                denyButtonText: `Cancelar`
            }).then((result) => {
                if (result.isConfirmed) {
                    //console.log('SI confirma');
                    location.href = key;
                }
            });
        }
    </script>
    <script>
        function ConfirmarRe(keyRe) {
            Swal.fire({
                title: 'Reactivar Usuario',
                text: '¿Estas seguro que quieres Reactivar el acceso a este usuario?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Si, reactivar Usuario',
                denyButtonText: `Cancelar`
            }).then((result) => {
                if (result.isConfirmed) {
                    //console.log('SI confirma');
                    location.href = keyRe;
                }
            });
        }
    </script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    </html>
<?php } else {
    echo '<meta http-equiv="refresh" content="0,url=logout.php">';
} ?>