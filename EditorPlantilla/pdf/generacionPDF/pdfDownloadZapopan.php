<?php

session_start();

if ( isset($_SESSION['usr']) !== true and isset($_SESSION['rol']) !== true ) {
    echo '<meta http-equiv="refresh" content="1,url=../../../index.php">';
    exit();
}

require "../../../acnxerdm/cnx.php";
require_once "../dompdf/autoload.inc.php";
use Dompdf\Dompdf;


$id_formato = $_GET['f'];
$token = $_GET['tkn'];

/**** Creacion de archivo ZIP ***/
$zipFichas = new ZipArchive();

$nombreZip = "Generacion PDF_$token.zip";

$rutaZip = "archivosPDF/";
 
$exitoZip = $zipFichas->open($rutaZip.$nombreZip, ZIPARCHIVE::CREATE);
$pdfEliminar = array();
$i = 1;
if( $exitoZip !== true ){
    exit();
}
//*** ****************************/

$sql_formato = sqlsrv_query($cnxConect, "select * from formatos where id='$id_formato'");

$formatos = sqlsrv_fetch_array($sql_formato);

$sql_peticion = sqlsrv_query($cnxa, "select * from peticionPDF where token = ?", array($token) );
$peticionPDF = sqlsrv_fetch_array($sql_peticion);

if($peticionPDF != null and $formatos != null){

        do{

            $sql_datos = sqlsrv_query($cnxa, "select top 1 * from datosCuentaGuadalajara where claveSIAPA = ?", array($peticionPDF['cuenta']) );
            $datos = sqlsrv_fetch_array($sql_datos);
            $sql_datosCalles = sqlsrv_query($cnxa, "select CALLE1, CALLE2 from [dbo].[DEPURADO_base21022024$] where CVE#SIAPA = ?", array($peticionPDF['cuenta']) );
            $datosCalles = sqlsrv_fetch_array($sql_datosCalles);

            $sql_conceptos = sqlsrv_query($cnxa, "select * from conceptosCuentaGuadalajara where tokenDatosCuenta = ? ", array($datos['tokenRegistro']) );
            $datosConceptos = sqlsrv_fetch_array($sql_conceptos);

            if($datosConceptos != null){
                $faturacion = ($datosConceptos['facturacion'] == null or $datosConceptos['facturacion']  == 0 ) ? "$-" : "$ ".$datosConceptos['facturacion'];
                $recargos = ($datosConceptos['recargos'] == null or $datosConceptos['recargos']  == 0 ) ? "$-" : "$ ".$datosConceptos['recargos'];
                $factibilidad = ($datosConceptos['factibilidad'] == null or $datosConceptos['factibilidad']  == 0 ) ? "$-" : "$ ".$datosConceptos['factibilidad'];
                $reconexion = ($datosConceptos['reconexion'] == null or $datosConceptos['reconexion']  == 0 ) ? "$-" : "$ ".$datosConceptos['reconexion'];
                $infraccion = ($datosConceptos['infraccion'] == null or $datosConceptos['infraccion']  == 0 ) ? "$-" : "$ ".$datosConceptos['infraccion'];
                $cambioPro = ($datosConceptos['cambioDePropietario'] == null or $datosConceptos['cambioDePropietario']  == 0 ) ? "$-" : "$ ".$datosConceptos['cambioDePropietario'];
                $garantiaM = ($datosConceptos['garantiaDeMedidor'] == null or $datosConceptos['garantiaDeMedidor']  == 0 ) ? "$-" : "$ ".$datosConceptos['garantiaDeMedidor'];
                $ivaAdd = ($datosConceptos['ivaAdicional'] == null or $datosConceptos['ivaAdicional']  == 0 ) ? "$-" : "$ ".$datosConceptos['ivaAdicional'];
                $mantenimientoIn = ($datosConceptos['mantenimientoInfraestructura'] == null or $datosConceptos['mantenimientoInfraestructura']  == 0 ) ? "$-" : "$ ".$datosConceptos['mantenimientoInfraestructura'];
                $cuotaUni = ($datosConceptos['cuotaUnica'] == null or $datosConceptos['cuotaUnica']  == 0 ) ? "$-" : "$ ".$datosConceptos['cuotaUnica'];
                $usoInfra = ($datosConceptos['usoInfraestructura'] == null or $datosConceptos['usoInfraestructura']  == 0 ) ? "$-" : "$ ".$datosConceptos['usoInfraestructura'];
                $contribucionP = ($datosConceptos['contribucionPlantas'] == null or $datosConceptos['contribucionPlantas']  == 0 ) ? "$-" : "$ ".$datosConceptos['contribucionPlantas'];
                $reparacionM = ($datosConceptos['reparacionMedidor'] == null or $datosConceptos['reparacionMedidor']  == 0 ) ? "$-" : "$ ".$datosConceptos['reparacionMedidor'];
                $notificacionReque = ($datosConceptos['notificacionRequerimiento'] == null or $datosConceptos['notificacionRequerimiento']  == 0 ) ? "$-" : "$ ".$datosConceptos['notificacionRequerimiento'];
            }else{
                $faturacion = "$-";
                $recargos = "$-";
                $factibilidad = "$-";
                $reconexion = "$-";
                $infraccion = "$-";
                $cambioPro = "$-";
                $garantiaM = "$-";
                $ivaAdd = "$-";
                $mantenimientoIn = "$-";
                $cuotaUni = "$-";
                $usoInfra = "$-";
                $contribucionP = "$-";
                $reparacionM = "$-";
                $notificacionReque = "$-";
            }

            if( $datos != null and $datosCalles != null ){

                $html = $formatos['html'];
                $css = $formatos['css'];
                $header = $formatos['header'];
                $footer = $formatos['footer'];
                $tamanio = $formatos['tamanio'];
                $nombre = $formatos['nombre'];
                $top = $formatos['top'];
                $bottom = $formatos['bottom'];
                $left = $formatos['left'];
                $right = $formatos['right'];

                $html = str_replace(' ', '%20', $html);
                $css = str_replace(' ', '%20', $css);
                $header = str_replace(' ', '%20', $header);
                $footer = str_replace(' ', '%20', $footer);

                $contenido = file_get_contents($html);
                $contenido = str_replace('CBD_NOMECLATURA', $peticionPDF['nomeclatura'] , $contenido );
                $contenido = str_replace('CBD_CUENTA', $datos['ctaContrato'] , $contenido );
                $contenido = str_replace('CBD_CLAVESIAPA', $datos['claveSIAPA'] , $contenido );
                $contenido = str_replace('CBD_T_TARIFA', $datos['tipoTarifa'] , $contenido );
                $contenido = str_replace('CBD_T_USO', $datos['tipoUsoTarifa'] , $contenido );
                $contenido = str_replace('CBD_NOMBRE', $datos['nombre'] , $contenido );
                $contenido = str_replace('CBD_DOMICILIO_C', $datos['domicilioCalle'] , $contenido );
                $contenido = str_replace('CBD_NUMEXT', $datos['numExterior'] , $contenido );
                $contenido = str_replace('CBD_CALLE1', $datosCalles['CALLE1'] , $contenido );
                $contenido = str_replace('CBD_CALLE2', $datosCalles['CALLE2'] , $contenido );
                $contenido = str_replace('CBD_COLONIA', $datos['colonia'] , $contenido );
                $contenido = str_replace('CBD_CODIGO_P', $datos['codigoPostal'] , $contenido );
                $contenido = str_replace('CBD_MUNICIPIO', $datos['municipio'] , $contenido );

                $contenido = str_replace('IBD_FACTURACION', $faturacion , $contenido );
                $contenido = str_replace('IBD_RECARGOS', $recargos , $contenido );
                $contenido = str_replace('IBD_FACTIBILIDAD', $factibilidad , $contenido );
                $contenido = str_replace('IBD_RECONEXION', $reconexion , $contenido );
                $contenido = str_replace('IBD_INFRACCION', $infraccion , $contenido );
                $contenido = str_replace('IBD_CAMBIO_PRO', $cambioPro , $contenido );
                $contenido = str_replace('IBD_GARATIA_M', $garantiaM , $contenido );
                $contenido = str_replace('IBD_IVA_AD', $ivaAdd , $contenido );
                $contenido = str_replace('IBD_MANTENI_INFRA', $mantenimientoIn , $contenido );
                $contenido = str_replace('IBD_CUOTA_U', $cuotaUni , $contenido );
                $contenido = str_replace('IBD_USO_INFRA', $usoInfra , $contenido );
                $contenido = str_replace('IBD_CONTRIBUCION_P', $contribucionP , $contenido );
                $contenido = str_replace('IBD_REPARACION_M', $reparacionM , $contenido );
                $contenido = str_replace('IBD_NOTIFICACION_R', $notificacionReque , $contenido );

                $contenidoCss = file_get_contents($css);

                ob_start();
                ?>
                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title> <?= $formatos['nombre'] ?></title>
                    <style>
                        @page {
                            margin: 0px;
                        }

                        #header,
                        #footer {
                            position: fixed;
                            left: 0;
                            right: 0;
                        }

                        #header {
                            top: 0px;
                        }

                        #footer {
                            bottom: 0px;
                        }

                        body {
                            margin: <?= $top.'cm' ?> <?= $right.'cm' ?> <?= $bottom.'cm' ?> <?= $left.'cm' ?>;
                        }

                        <?= $contenidoCss ?>
                    </style>


                </head>


                <body>

                    <div id="header">
                        <img style="width: 21cm; " src="<?= $header ?>" alt="">
                    </div>
                    <div id="footer">
                        <img style="width: 21cm; " src="<?= $footer ?>" alt="">
                    </div>

                    <?php echo $contenido ?>


                </body>

                </html>
                <?php
                $html = ob_get_clean();

                $pdf = new Dompdf();

                $options = $pdf->getOptions();
                $options->set(array("isRemoteEnabled" => true));
                $pdf->set_option("isPhpEnabled", true);
                $pdf->setOptions($options);

                $pdf->loadHtml($html);
                $tipo = 'letter';
                if ($tamanio == 'oficio') {
                    $tipo = 'legal';
                }
                $pdf->setPaper($tipo, 'portrait');
                $pdf->render();
                $nombrePDF = 'Nombre del pdf masivo_'.$i.'.pdf';

                $output = $pdf->output();
                file_put_contents($rutaZip.$nombrePDF, $output);

                $zipFichas->addFile($rutaZip.$nombrePDF, $nombrePDF);
                $pdfEliminar[] = $rutaZip.$nombrePDF;

                $i++;

            }

        }while($peticionPDF = sqlsrv_fetch_array($sql_peticion));

    
}

if(isset($zipFichas) and $exitoZip === true){
    $zipFichas->close();

    if( file_exists($rutaZip.$nombreZip) ){
        header("Content-Type: application/zip");
        header('Content-Disposition: attachment; filename="'.$nombreZip.'"');
        readfile($rutaZip.$nombreZip);
    }

}

foreach ($pdfEliminar as $file) {
    unlink($file);
}

?>