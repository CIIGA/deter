<?php

session_start();
if ( isset($_SESSION['usr']) !== true and isset($_SESSION['rol']) !== true ) {
    echo '<meta http-equiv="refresh" content="1,url=../index.php">';
    exit();
}

require "../acnxerdm/cnx.php";
//session_start();
// $_SESSION['plz'] = 1027;
// unset($_SESSION['plz']);
// unset($_SESSION['plazaBD']);
//$_SESSION['plazaBD'] = "implementtaTijuanaA";


$bd = '';
$nombrePlaza = 'Sin conexion';
// if( isset($_SESSION['plz']) ){
// if( isset($_SESSION['plazaBD']) ){
//     $bd = $_SESSION['plazaBD'];

//     $serverName = "51.222.44.135";
//     $connectionInfo = array( 'Database' => 'implementtaAdministrator', 'UID'=>'sa', 'PWD'=>'vrSxHH3TdC');
//     $cnxa = sqlsrv_connect($serverName, $connectionInfo);
//     date_default_timezone_set('America/Mexico_City');

//     $sqlBD = sqlsrv_query($cnxa, "select pro.nombreProveniente, pro.data from plaza p join proveniente pro
//     on p.id_proveniente = pro.id_proveniente
//     where pro.data = ? ", array( $bd ) );

//     $rowDB = sqlsrv_fetch_array($sqlBD);
//     $nombrePlaza = $rowDB['nombreProveniente'];
//     // $bd = $rowDB['data'];
// }else{
//     echo '<meta http-equiv="refresh" content="0,url=https://implementta.net/"';
// }

function generarValues($num){
    $values = 'VALUES(?,?,?)';
    
    for($i = 1;$i < $num/3; $i++){
        $values = $values.', (?,?,?)';
    }
    
    return $values;
}

if(isset($_POST['upload'])){

    $ext = '';
    if(isset($_FILES['peticionFile']) and $_FILES['peticionFile']['error'] <> 4){
        $archivo = $_FILES['peticionFile']['name'];
        $archivotemp = $_FILES['peticionFile']['tmp_name'];
        $ext = pathinfo($archivo,PATHINFO_EXTENSION);
    }

    if($ext=='xls' or $ext=='xlsx' ){

        //require_once('../PhpSpreadsheet/vendor/autoload.php');
        require_once('pdf/generacionPDF/PhpSpreadsheet/vendor/autoload.php');

        $tablaGlobal = "peticionPDF";
        $tokenInsert = date('YmdHis').rand(2000, 9999).rand(3000, 6000);

        $exitoUpload = 0;
        $fileName = $archivotemp;
        if(file_exists($fileName)){

            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($fileName);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($fileName);
            $dataExcel = $spreadsheet->getSheet(0)->toArray(null,false,false,false); //Revisar por si necesita configurar diferente.
            //$dataExcel = $spreadsheet->getActiveSheet()->toArray(null,false,false,false); //Revisar por si necesita configurar diferente.
            $primerFila = $dataExcel[0];

            $token = date('dmyhis').rand(1000,9999);
            $insertados = 0;

            $filaExcel1 = array();
            $insertConsulta = "insert into ".$tablaGlobal."(cuenta, nomeclatura, token)";
                
            for($x=1;$x<count($dataExcel);$x++){
                if(trim($dataExcel[$x][0]) == ''){
                    break;
                }

                $filaExcel1[] = trim($dataExcel[$x][0]);
                $filaExcel1[] = trim($dataExcel[$x][1]);
                $filaExcel1[] = $token;

                if(count($filaExcel1) == 300){//Deben ser multiplos de 3

                    if(sqlsrv_query($cnxa,$insertConsulta.generarValues(count($filaExcel1)),$filaExcel1) == false ){
                        // print_r(sqlsrv_errors() );
                        // exit();
                        sqlsrv_query($cnxa,"DELETE FROM peticionPDF WHERE token = ?",array($token) );
                        echo '<meta http-equiv="refresh" content="0,url=cargaPeticion.php?f='.$_GET['f'].'&errorIns"';
                    }
                    $filaExcel1 = array();
                }
            }

            if(count($filaExcel1) > 0){
                if(sqlsrv_query($cnxa,$insertConsulta.generarValues(count($filaExcel1)),$filaExcel1) == false ){
                    // print_r(sqlsrv_errors() );
                    // exit();
                    sqlsrv_query($cnxa,"DELETE FROM peticionPDF WHERE token = ?",array($token) );
                    echo '<meta http-equiv="refresh" content="0,url=cargaPeticion.php?f='.$_GET['f'].'&errorIns"';
                }
                $filaExcel1 = array();
            }

            $sqlCount = sqlsrv_query($cnxa, "SELECT COUNT(*) FROM $tablaGlobal where token = ?", array($token) );
            if( ($rowCount = sqlsrv_fetch_array($sqlCount)) != null){
                $insertados = $rowCount[0];
            }

            echo '<meta http-equiv="refresh" content="0,url=cargaPeticion.php?f='.$_GET['f'].'&ok='.$insertados.'&tkn='.$token.'"';
        }
    }else{
        echo '<script>alert("El archivo seleccionado debe estar en formato XLS o XLSX");</script>';
        echo '<meta http-equiv="refresh" content="0,url=cargaPeticion.php?f='.$_GET['f'].'"';
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title >Carga - Guadalajara</title>
        <link rel="icon" href="img/implementtaIcon.png">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <style>
            body {
                background-image: url(img/back.jpg);
                background-repeat: repeat;
                background-size: 100%;
                background-attachment: fixed;
                min-height: 100vh;

                display: flex;
                flex-direction: column;
            }

            body {
                font-family: sans-serif;
                font-style: normal;
                font-weight: normal;

                margin-top: -1%;
                margin-bottom: 0%;

            }

            .jumboC{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-left: 15px;
                margin-right: 15px;
            }

            .footer {
              margin-top: auto;
              width: 100%;
            }
        </style>
        <?php require "nav.php"; ?>
    </head>
    <body>
<div class="jumboC">
    <?php if( isset($_GET['ok']) ){ ?>
        <div class="alert alert-success">Se subieron correctamente <?php echo $_GET['ok']; ?> registros.</div>
    <?php }else if( isset($_GET['error']) ){ ?>
        <div class="alert alert-danger">Hubo un error al subir los registros, intentelo de nuevo</div>
    <?php } ?>
    <div class="text-center">
        <h4 style="text-shadow: 0px 0px 2px #717171;"><img src="https://img.icons8.com/fluency/38/rtf-document.png" /> Carga de cuentas GUADALAJARA para PDF Masivo</h4>
        <!-- <h4 style="text-shadow: 0px 0px 2px #717171;"> Conectado a: <?php //echo $nombrePlaza; ?></h4> -->
    </div>
    <?php if( isset($_GET['tkn']) ){ ?>
    <br/>
    <div class="d-flex w-100 justify-content-center">
        <a class="btn btn-success" href="pdf/generacionPDF/pdfDownloadZapopan.php?tkn=<?= $_GET['tkn'] ?>&f=<?= $_GET['f'] ?>" onclick="esperaPDF()">Generar PDF´s</a>
    </div>
    <?php }else{ ?>
        <form class="d-flex w-100 justify-content-center" method="post" enctype="multipart/form-data">
        <div class="d-flex flex-column w-50 mt-2 justify-content-around align-items-center">
                <label>Para ingresar cuentas: </label>
                <br/>
                <input type="file" name="peticionFile">
                <br/>
                <div>
                    <button class="btn btn-primary btn-sm" onclick="espera()" name="upload"> Subir cuentas </button>
                    <a class="btn btn-dark btn-sm" href="formatos.php">Volver atrás</a>
                </div>
                <br/>
            </div>
        </form>
    <?php } ?>
</div>
<script>
    function espera(){
        Swal.fire({
        icon: 'info',
        title: 'Cargando datos',
        text: 'Se estan subiendo sus registros espere. . .',
        showCancelButton: false, // Mostrar el botón de cancelar
        showConfirmButton: false,
        allowOutsideClick: false, // Evitar que se cierre haciendo clic afuera
        allowEscapeKey: false, // Evitar que se cierre presionando Esc
        didOpen: () => {
            Swal.showLoading();
        }
        });
    }

    function esperaPDF(){
        Swal.fire({
        icon: 'info',
        title: 'Generando archivos PDF',
        text: 'Se estan generando sus archivos, este proceso puede tardar varios minutos . . .',
        showCancelButton: false, // Mostrar el botón de cancelar
        showConfirmButton: true,
        allowOutsideClick: false, // Evitar que se cierre haciendo clic afuera
        allowEscapeKey: false, // Evitar que se cierre presionando Esc
        confirmButtonText: "Nueva peticion"
        }).then((result) => {
            if (result.isConfirmed) {
                location.href = "cargaPeticion.php?f=" + <?= $_GET['f']; ?>;
            }
        });
    }
</script>
<!--*************************INICIO FOOTER***********************************************************************-->
    <footer class="d-flex text-center footer">
        <div class="container">
            <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
                Implementta ©<br>
                Estrategas de México <i class="far fa-registered"></i><br>
                Centro de Inteligencia Informática y Geografía Aplicada CIIGA
                <hr style="width:105%;border-color:#7a7a7a;">
                Created and designed by <i class="far fa-copyright"></i> <?php echo date('Y') ?> Estrategas de México<br>
            </span>
        </div>
        <div class="container">
            <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
                Contacto:<br>
                <i class="fas fa-phone-alt"></i> Red: 187<br>
                <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
                <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
            </span>
            <ul class="navbar-nav mr-auto">
                <br><br>
            </ul>
        </div>
        <div class="container">
            <form class="form-inline my-2 my-lg-0">
                <a href="#"><img src="img/logoImplementta.png" width="155" height="150" alt=""></a>
                <a href="http://estrategas.mx/" target="_blank"><img src="img/logoTop.png" width="200" height="85" alt=""></a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </form>
        </div>
    </footer>
<!--***********************************FIN FOOTER****************************************************************-->
    </body>
</html>