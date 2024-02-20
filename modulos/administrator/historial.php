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
    <title>Historial</title>
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
    <div class="container col-md-10">
      <div class="form-row">
        <div class="form-group col-md-6">
          <h5 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/32/time-machine.png" /> Historial de actualizaciones</h5>
        </div>
        <div class="form-group col-md-6" style="text-align: center;">
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="container">
                <div class="row justify-content-center mb-3">
                  <div class="col-md-8">
                    <h6 class="text-center mb-3 text-bold">Rango de fechas a buscar</h6>
                    <div class="form-group row">
                      <label for="fechaI" class="col-md-2 col-form-label text-right">Fecha Inicio:</label>
                      <div class="col-md-3">
                        <input type="date" id="fechaI" class="form-control" value="<?= date('Y-m-d') ?>">
                      </div>
                      <label for="fechaF" class="col-md-2 col-form-label text-right">Fecha Fin:</label>
                      <div class="col-md-3">
                        <input type="date" id="fechaF" class="form-control" value="<?= date('Y-m-d') ?>">
                      </div>
                      <div class="col-md-2">
                        <button id="buscar" class="btn btn-primary">Buscar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div id="contenido-timeline" class="timeline overflow-auto" style="max-height: 500px;">
                <!-- Aquí se llenará el contenido con AJAX -->
              </div>

            </div>
          </div>
        </div>
      </section>

      <!-- /.content -->













    </div>
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
  <script src="historial.js"></script>


  </html>
<?php } else {
  echo '<meta http-equiv="refresh" content="0,url=logout.php">';
} ?>