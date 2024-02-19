<?php
session_start();
require "acnxerdm/cnxAd.php"; ?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
  <title>Implementta DCF</title>
  <link rel="icon" href="modulos/icono/implementtaIcon.png">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="modulos/css/bootstrap.css">
  <link href="modulos/fontawesome/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="modulos/css/adminlte.min.css">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css" id="theme-styles">
  <style>
    body {
      background-image: url(modulos/img/backImplementta.jpg);
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
      margin-top: -8%;
      padding-top: 0px;
    }
  </style>
</head>

<body>
  <?php
  if (isset($_POST['login'])) {
    $usuario = $_POST['correo'];
    $password = $_POST['pass'];
    $us = "select * from usuarioNuevo
        where correo='$usuario' and clave='$password'";
    $use = sqlsrv_query($cnx, $us);
    $usuario = sqlsrv_fetch_array($use);

    if (isset($usuario)) {

      $_SESSION['usr'] = $usuario['id_usuarioNuevo'];
      $_SESSION['rol'] = $usuario['id_rol'];

      $idUsr = $usuario['id_usuarioNuevo'];
      $fecha = date('Y-m-d');
      $hora = date('H:i:s');
      $dia = date('w');

      $accesos = "insert into accesos (id_usuarioNuevo,fecha,hora,dia) values ('$idUsr','$fecha','$hora',$dia)";
      sqlsrv_query($cnx, $accesos) or die('No se ejecuto la consulta isert reg accesos');

      echo "<script>
                let timerInterval
                Swal.fire({
                  title: 'Iniciando sesión ',
                  icon: 'success',
                  timer: 800,
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

      echo '<meta http-equiv="refresh" content="0.8,url=modulos/">';
    } else {
      echo "<script>
                let timerInterval
                Swal.fire({
                  title: '¡Error!',
                  html: 'Los datos no existen en Implementta DCF <br>Intenta nuevamente.',
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
      echo '<meta http-equiv="refresh" content="2,url=../deter">';
    }
  }
  ?>

  <div class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="" style="text-shadow: 0px 0px 2px #717171;"><img width="72" height="64" src="modulos/img/flor.png" alt="flower" /><b>Implementta DCF</b></a>
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Iniciar Sesion en Implementta DCF</p>

          <form method="post">
            <div class="input-group mb-3">
              <input type="email" name="correo" class="form-control" placeholder="correo@erdm.mx" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="pass" class="form-control" placeholder="Contraseña" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">

              </div>
              <!-- /.col -->
              <div class="col-6">
                <button type="submit" name="login" class="btn btn-primary btn-block" id="botones"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
              </div>
              <!-- /.col -->
            </div>
          </form>


          <!-- <div class="social-auth-links text-center mb-3">
        <hr><br>
        <a href="#" class="btn btn-block btn-dark btn-sm">
        <i class="fas fa-paper-plane"></i> Registrarme
        </a>
        <a href="#" class="btn btn-block btn-danger btn-sm">
        <i class="fas fa-user-slash"></i> Olvide mi contraseña
        </a>
      </div> -->
          <!-- /.social-auth-links -->

        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->
  </div>


  <!--*************************INICIO FOOTER***********************************************************************-->
  <nav class="navbar sticky-bottom navbar-expand-lg">
    <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
      Implementta ©<br>
      Estrategas de México <i class="far fa-registered"></i><br>
      Centro de Inteligencia Informática y Geografía Aplicada CIIGA
      <hr style="width:105%;border-color:#7a7a7a;">
      Created and designed by © <?php echo date('Y') ?> Estrategas de México<br>
    </span>
    <hr>
    <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
      Contacto:<br>
      <i class="fas fa-phone-alt"></i> Red: 187<br>
      <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
      <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
    </span>
    <ul class="navbar-nav mr-auto">

    </ul>
    <form class="form-inline my-2 my-lg-0">
      <a href="../../index.php"><img src="modulos/img/logoImplementta.png" width="155" height="150" alt=""></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="http://estrategas.mx/" target="_blank"><img src="modulos/img/logoTop.png" width="200" height="85" alt=""></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </form>
  </nav>
  <!--***********************************FIN FOOTER****************************************************************-->
</body>
<script src="modulos/js/jquery-3.4.1.min.js"></script>
<script src="modulos/js/popper.min.js"></script>
<script src="modulos/js/bootstrap.js"></script>
</html>