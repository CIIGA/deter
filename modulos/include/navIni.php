
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
    <img src="img/flor.png" width="55" height="48" alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?php if ($_SESSION['rol'] == 1) { ?>
        <a class="nav-item nav-link" href="administrator/addplz.php"><i class="fas fa-project-diagram"></i> Modulos</a>
        <a class="nav-item nav-link" href="administrator/usrs.php"><i class="fas fa-user-plus"></i> Usuarios </a>
        <a class="nav-item nav-link" href="administrator/historial.php"><i class="fas fa-history"></i> Historial</a>
      <?php } ?>


      <!--
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-lock"></i> Administrador</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">  
        <a class="dropdown-item" href="#"><i class="fas fa-users"></i> Nuevo Colaborador</a>       
        <a class="dropdown-item" href="#"><i class="fas fa-user-lock"></i> Administrador ERDM</a>
        </div>
      </li>
-->
    </ul>

    <form class="form-inline my-2 my-lg-0">
      <a class="nav-item nav-link" href="administrator/logout.php" style="color: #ffffff;"> Salir <i class="fas fa-sign-out-alt"></i></a>
    </form>

  </div>
</nav>

<br>