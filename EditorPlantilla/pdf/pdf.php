<?php
ob_start();
require "../cnx/cnx.php";
$id_formato = $_GET['f'];
// validar si el formato pertenece al modulo
$sql_formato = sqlsrv_query($cnx, "select * from formatos where id='$id_formato'");

$formatos = sqlsrv_fetch_array($sql_formato);
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
$contenidoCss = file_get_contents($css);
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


require_once "dompdf/autoload.inc.php";

use Dompdf\Dompdf;

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
$pdf->stream("nombrePdf.pdf", array("Attachment" => false));
?>