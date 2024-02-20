<?php
require "../../EditorPlantilla/cnx/cnx.php";

// Obtener la fecha seleccionada del POST
$fechaI = $_POST['fechaI'];
$fechaF = $_POST['fechaF'];

$sql_historial = sqlsrv_query($cnx, "select nombreUsr,correo,id_edicion,comentario,fecha,hora,id_campo from historial inner join usuarioNuevo
on historial.id_usuarioNuevo=usuarioNuevo.id_usuarioNuevo
where fecha between '$fechaI' and '$fechaF' order by fecha desc, hora desc");

// Inicializar una variable para almacenar el HTML del timeline
$htmlTimeline = '';
if (sqlsrv_has_rows($sql_historial)) {
  // Iterar sobre los resultados de la consulta SQL
  while ($row = sqlsrv_fetch_array($sql_historial, SQLSRV_FETCH_ASSOC)) {
    $fecha = date('d M. Y', strtotime($row['fecha']));
    $color = 'danger';
    if ($row['fecha'] == date('Y-m-d')) {
      $fecha = 'Hoy ' . date('d M. Y', strtotime($row['fecha']));
      $color = 'success';
    }
    $id_campo = $row['id_campo'];
    $comentarioH = $row['comentario'];

    $id_edicion = $row['id_edicion'];
    switch ($id_edicion) {
      case 1:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario Edito el formato '.$Nformato.' del modulo '.$Nmodulo.' comentando lo siguiente: '.$comentarioH.'';
        break;
      case 2:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario reemplazo el archivo html del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 3:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario reemplazo el archivo css del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 4:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario reemplazo el archivo del header del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 5:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario reemplazo el archivo del footer del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 6:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario edito los margenes del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 7:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario edito el tamaño del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 8:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario edito el estado del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 9:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario regreso una version anterior del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 10:
        $sql = sqlsrv_query($cnx, "select nombre,nombreModulo from formatos as f inner join modulos as m on f.id_modulo = m.id_modulo where f.id='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nformato=$datos['nombre'];
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario elimino un campo del formato '.$Nformato.' del modulo '.$Nmodulo.'';
        break;
      case 11:
        $sql = sqlsrv_query($cnx, "select nombreModulo from modulos as m  where m.id_modulo='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario edito el modulo '.$Nmodulo.'';
        break;
      case 12:
        $sql = sqlsrv_query($cnx, "select nombreModulo from modulos as m  where m.id_modulo='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nmodulo=$datos['nombreModulo'];
        $comentario = 'El usuario edito el estado del modulo '.$Nmodulo.'';
        break;
      case 13:
        $sql = sqlsrv_query($cnx, "select nombreUsr from usuarioNuevo as u  where u.id_usuarioNuevo='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nuser=$datos['nombreModulo'];
        $comentario = 'El usuario edito el estado del usuario '.$Nuser.'';
        break;
      case 14:
        $sql = sqlsrv_query($cnx, "select nombreUsr from usuarioNuevo as u  where u.id_usuarioNuevo='$id_campo'");
        $datos = sqlsrv_fetch_array($sql);
        $Nuser=$datos['nombreModulo'];
        $comentario = 'El usuario actualizo los datos del usuario '.$Nuser.'';
        break;
      
      default:
        $comentario = 'Error al obtener esta informacion';
    }




    // Generar el HTML para el registro actual
    $htmlTimeline .= '<div class="time-label">
    <span class="bg-' . $color . '">' . $fecha . '</span>
    </div>
    <div>
    <i class="fas fa-envelope bg-blue"></i>
    <div class="timeline-item">
      <span class="time"><i class="fas fa-clock"></i> ' . $row['hora'] . '</span>
      <h3 class="timeline-header"><span class="text-bold">' . $row['nombreUsr'] . '</span> <span class="text-primary">' . $row['correo'] . '</span></h3>
    
      <div class="timeline-body">
      ' . $comentario . '
      </div>
      <div class="timeline-footer">
      </div>
    </div>
    </div>';
  }
} else {
  $htmlTimeline = '<div class="alert alert-danger" role="alert">
  No se encontraron datos en este rango de fechas.
</div>';
}



// Devolver el HTML generado
echo $htmlTimeline;
