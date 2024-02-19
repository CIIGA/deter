<?php
session_start();
if ((isset($_SESSION['usr'])) and (isset($_SESSION['rol']))) {
  require "../../acnxerdm/cnxAd.php";
  $pl = "SELECT * FROM modulos
    left join proveniente on proveniente.id_proveniente=modulos.id_proveniente";
  $plz = sqlsrv_query($cnx, $pl);
  $modulos = sqlsrv_fetch_array($plz);

  $pro = "SELECT * FROM proveniente";
  $prov = sqlsrv_query($cnx, $pro);
  $prove = sqlsrv_fetch_array($prov);
  //*********************************** INICIO INSERT PLZ *******************************************************
  if (isset($_POST['save'])) {
    $nombre = $_POST['nombre'];
    $origen = $_POST['prov'];
    $color = rand(1, 4);
    $val = "select * from modulos
    where nombreModulo='$nombre'";
    $vali = sqlsrv_query($cnx, $val);
    $valida = sqlsrv_fetch_array($vali);
    if ($valida) {
      echo '<script>alert("El nombre de modulos ya esta agregado. \nVerifique registro")</script>';
      echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
    } else {
      // Ubicacion de la carpeta del modulo
      $moduloPath = 'C:/inetpub/vhosts/beautiful-einstein.51-79-98-210.plesk.page/httpdocs/deter/EditorPlantilla/plantillas/' . $nombre;
      // $moduloPath = 'C:/wamp64/www/deter/EditorPlantilla/plantillas/' . $nombre;

      // Crear una carpeta del formato
      if (!mkdir($moduloPath, 0755)) {
        echo '<script>alert("modulos agregada correctamente")</script>';
        echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
      }
      $unidad = "insert into modulos (id_proveniente,nombreModulo,estado,color) values ('$origen','$nombre',1,$color)";
      sqlsrv_query($cnx, $unidad) or die('No se ejecuto la consulta isert nueva plz');

      // consultamos el id del modulo que fue insertado
      $sql_modulo_inserted = sqlsrv_query($cnx, "SELECT id_modulo from modulos where nombreModulo='$nombre'");
      $modulo_inserted = sqlsrv_fetch_array($sql_modulo_inserted);
      $id_inserted = $modulo_inserted['id_modulo'];
      //insertamos el registro ya que en esa tabla se consultara las carpetas
      $sql_insert = sqlsrv_query($cnx, "INSERT INTO nombreModulos(id_modulo,nombre) values ('$id_inserted','$nombre')");
      echo '<script>alert("modulos agregada correctamente")</script>';
      echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
    }
  }
  //************************ FIN INSERT PLZ ******************************************************************
  //****************************ACTUALIZAR DATOS DE USUARIO******************************************************
  if (isset($_POST['update'])) {
    require "../../EditorPlantilla/historial.php";
    $idplaza = $_POST['idplz'];
    $name = $_POST['nombreplz'];
    $prov = $_POST['prov'];

    $datos = "update modulos set nombreModulo='$name',id_proveniente='$prov'
    where id_modulo='$idplaza'";
    sqlsrv_query($cnx, $datos) or die('No se ejecuto la consulta update datosart');
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $insert = commit($_SESSION['usr'], $idplaza,11, '', $fecha, $hora);
    echo '<script> alert("Resgistro Actulizado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
  }
  //****************************FIN ACTUALIAR DATOS DE USUARIO***************************************************    
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modulos DCF</title>
    <link rel="icon" href="../icono/implementtaIcon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="../js/peticionAjax.js"></script>
    <style>
      body {
        background-image: url(../img/backImplementta.jpg);
        background-repeat: repeat;
        background-size: 100%;
        /*        background-attachment: fixed;*/
        overflow-x: hidden;
        /* ocultar scrolBar horizontal*/
      }

      body {
        font-family: sans-serif;
        font-style: normal;
        font-weight: normal;
        width: 100%;
        height: 100%;
        margin-top: 0%;
        padding-top: 0px;
      }

      .jumbotron {
        margin-top: 0%;
        margin-bottom: 0%;
        padding-top: 3%;
        padding-bottom: 2%;
      }

      .padding {
        padding-right: 35%;
        padding-left: 35%;
      }
    </style>
    <?php require "../include/nav.php"; ?>
  </head>

  <body>
    <div class="container">
      <h2 style="text-shadow: 0px 0px 2px #717171;"><img width="72" height="64" src="../img/flor.png" alt="flower" />Implementta DCF</h2>
      <h4 style="text-shadow: 0px 0px 2px #717171;"><i class="fas fa-project-diagram"></i> Agregar nuevo modulo</h4>
      <form action="" method="post">
        <div class="jumbotron">
          <?php if (isset($prove)) { ?>
            <div class="form-group" style="text-align:center;">
              <label for="exampleInputEmail1">Nombre del modulo: *</label>
              <input style="text-align:center;" type="text" class="form-control" name="nombre" placeholder="Nombre del nuevo modulo" required>
            </div>

            <div class="form-row">
              <div class="col-md-6">
                <div class="md-form form-group">
                  <label for="exampleInputEmail1">Origen de datos: *</label>
                  <select name="prov" class="form-control" required>
                    <option value="">Selecciona una opcion</option>
                    <?php do { ?>
                      <option value="<?php echo $prove['id_proveniente'] ?>"><?php echo utf8_encode($prove['nombreProveniente']) ?></option>
                    <?php } while ($prove = sqlsrv_fetch_array($prov)); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">

              </div>
            </div>
            <small id="e" class="form-text text-muted" style="font-size:14px;">*Todos los campos son requeridos.</small>
            <div style="text-align:right;">
              <button type="submit" class="btn btn-primary " name="save"><i class="fas fa-plus"></i> Agregar nueva modulos</button>
            </div>
          <?php } else { ?>
            <br>
            <div class="alert alert-info" role="alert">
              <i class="fas fa-info-circle"></i> Para agregar un nuevo módulo, primero inicie un origen de datos <a href="origen.php">aqui</a>.
            </div>
          <?php } ?>
        </div>
      </form>
      <br>
      <div style="text-align:left;">
        <a href="origen.php" class="btn btn-dark btn-sm"><i class="fas fa-database"></i> Nuevo origen de datos</a>
      </div>
      <hr>
      <h3 style="text-shadow: 1px 1px 2px #717171;"><i class="fas fa-wrench"></i> Editar modulos</h3>
      <hr>
    </div>

    <div class="container">
      <?php if (isset($modulos)) { ?>
        <table class="table table-sm table-hover">
          <thead>
            <tr align="center">
              <th scope="col">Modulos</th>
              <th scope="col">Origen de datos</th>
              <th scope="col">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <?php do { ?>
              <tr align="center">
                <td><?php echo $modulos['nombreModulo'] ?></td>
                <td><?php echo $modulos['nombreProveniente'] ?></td>
                <td>

                  <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#datos<?php echo $modulos['id_modulo'] ?>"><span aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Datos de modulos"><i class="far fa-edit"></i></span></a>
                  <?php if ($modulos['estado'] == 2) { ?>
                    <a href="delete.php?poneplz=1&plz=<?php echo $modulos['id_modulo'] ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar modulos" onclick="return Confirmar('¿Esta seguro de inactivar el modulo <?php echo $modulos['nombreModulo'] ?>?')">Activo</a>

                  <?php } ?>
                  <?php if ($modulos['estado'] == 1) { ?>
                    <a href="delete.php?poneplz=2&plz=<?php echo $modulos['id_modulo'] ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar modulos" onclick="return Confirmar('¿Esta seguro de activar el modulo <?php echo $modulos['nombreModulo'] ?>?')">Inactivo</a>

                  <?php } ?>
                </td>
              </tr>
          </tbody>
          <!-- *********************************MODAL PARA ACTUALIZAR UDATOS *************************************************** -->
          <form action="" method="post">
            <div class="modal fade" id="datos<?php echo $modulos['id_modulo'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" style="text-shadow: 0px 0px 2px #717171;">Editar nombre de modulos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php
                    $pro = "SELECT * FROM proveniente";
                    $prov = sqlsrv_query($cnx, $pro);
                    $prove = sqlsrv_fetch_array($prov);
                    ?>
                    <label for="exampleInputEmail1">Editar nombre de modulos: </label>
                    <input type="text" class="form-control" name="nombreplz" value="<?php echo utf8_encode($modulos['nombreModulo']) ?>" required>
                    <br>
                    <div class="md-form form-group">
                      <label for="exampleInputEmail1">Datos provenientes: *</label>
                      <select name="prov" class="form-control" required>
                        <option value="<?php echo $modulos['id_proveniente'] ?>"><?php echo utf8_encode($modulos['nombreProveniente']) ?></option>
                        <?php do { ?>
                          <option value="<?php echo $prove['id_proveniente'] ?>"><?php echo utf8_encode(ucwords(strtolower($prove['nombreProveniente']))) ?></option>
                        <?php } while ($prove = sqlsrv_fetch_array($prov)); ?>
                      </select>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <input type="hidden" class="form-control" name="idplz" value="<?php echo $modulos['id_modulo'] ?>" placeholder="Agregar marca">
                    <button type="submit" class="btn btn-primary" name="update">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                  </div>
                </div>
              </div>
            </div>
          </form>



          <!-- ***********************************FIN MODAL ACTUALIZAR DATOS *************************************************** -->
        <?php } while ($modulos = sqlsrv_fetch_array($plz)); ?>
        </table>
      <?php } else { ?>
        <div class="alert alert-info" role="alert">
          <i class="fas fa-info-circle"></i> Aun no hay módulos agregados en Implementta DCF.
        </div>
      <?php } ?>
      <br>
      <div style="text-align:center;">
        <a href="../" class="btn btn-dark btn-sm"><i class="fas fa-chevron-left"></i> Regresar</a>
      </div>
    </div>
    <br><br>
    <?php require "../include/footer.php"; ?>
  </body>
  <script src="../js/jquery-3.4.1.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.js"></script>
  <script>
    $(function() {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>
  <script>
    function Confirmar(Mensaje) {
      return (confirm(Mensaje)) ? true : false;
    }
  </script>

  </html>
<?php } else {
  echo '<meta http-equiv="refresh" content="0,url=logout.php">';
} ?>