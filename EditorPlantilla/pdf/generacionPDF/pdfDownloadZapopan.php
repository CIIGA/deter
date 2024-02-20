<?php

require "../../../acnxerdm/cnx.php";
require_once "../dompdf/autoload.inc.php";
use Dompdf\Dompdf;

$id_formato = $_GET['f'];
$token = $_GET['tkn'];

/**** Creacion de archivo ZIP ***/
$zipFichas = new ZipArchive();

$nombreZip = "Generacion PDF_$token.zip";
$rutaZip = "archivosPDF/";
 
//$pathPadre = 'C:/inetpub/vhosts/beautiful-einstein.51-79-98-210.plesk.page/httpdocs/EditorPlantilla/pdf/generacionPDF/';
$exitoZip = $zipFichas->open($rutaZip.$nombreZip, ZIPARCHIVE::CREATE);
$pdfEliminar = array();
$i = 1;
if( $exitoZip !== true ){
    //Exito creado el ZIP
    // -------------------------- Hacer algo para cuando falla la creacion del ZIP.
    exit();
}
//*** ****************************/

// validar si el formato pertenece al modulo
$sql_formato = sqlsrv_query($cnxConect, "select * from formatos where id='$id_formato'");

$formatos = sqlsrv_fetch_array($sql_formato);

$sql_peticion = sqlsrv_query($cnxa, "select top 5 * from peticionPDF where token = ?", array($token) );
$peticionPDF = sqlsrv_fetch_array($sql_peticion);

if($peticionPDF != null and $formatos != null){

    $sql_datos = sqlsrv_query($cnxa, "select top 1 * from datosCuentaGuadalajara where claveSIAPA = ?", array($peticionPDF['cuenta']) );
    $datos = sqlsrv_fetch_array($sql_datos);

    do{

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

        $contenido = str_replace('CVESIAPA', $datos['claveSIAPA'] , file_get_contents($html) );
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
                    /* margin: Top, Right, Bottom, Left*/
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
        //guardar tod0 el buher en una variable
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
        // horizontal
        // $dompdf->setPaper('A4', 'landscape'); 
        $pdf->render();
        // true para que habra el pdf
        // false para que se descargue
        //$pdf->stream($formatos['nombre'].".pdf", array("Attachment" => false));
        $nombrePDF = 'Nombre del pdf masivo_'.$i.'.pdf';

        $output = $pdf->output();
        file_put_contents($rutaZip.$nombrePDF, $output);

        $zipFichas->addFile($rutaZip.$nombrePDF, $nombrePDF);
        $pdfEliminar[] = $rutaZip.$nombrePDF;

        $i++;

    }while($peticionPDF = sqlsrv_fetch_array($sql_peticion));

}

if(isset($zipFichas) and $exitoZip === true){
    $zipFichas->close();

    if( file_exists($rutaZip.$nombreZip) ){
        //echo '<script>window.open("'.$rutaZip.$nombreZip.'");</script>';
        header('Content-Description: File Transfer');
        header("Content-Type: application/octet-stream");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Disposition: attachment; filename="'.$nombreZip.'"');
        readfile($rutaZip.$nombreZip);
    }

}

foreach ($pdfEliminar as $file) {
    unlink($file);
}

?>