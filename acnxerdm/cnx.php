
<?php

    session_start();

    $serverName = "51.79.98.210";
    $connectionInfoa = array( 'Database'=>'determinaciones', 'UID'=>'sa', 'PWD'=>'=JeFGm[jFd%J?7j');
    $cnxConect = sqlsrv_connect($serverName, $connectionInfoa);
    date_default_timezone_set('America/Mexico_City');

    //$plz=$_GET['idpl']; //cambiar por session
    $plz = $_SESSION['m'];
    
    $pro="SELECT * FROM modulos
    inner join proveniente on proveniente.id_proveniente=modulos.id_proveniente
    where modulos.id_modulo=$plz";
    $prov=sqlsrv_query($cnxConect,$pro);
    $proveniente=sqlsrv_fetch_array($prov);

if(isset($proveniente)){
    $connectionInfo = array( 'Database'=>$proveniente['data'], 'UID'=>'sa', 'PWD'=>'=JeFGm[jFd%J?7j');
    $cnxa = sqlsrv_connect($serverName, $connectionInfo) or die('No se realizo la conexion ' . print_r(sqlsrv_errors(), true));;
} else{
    echo 'No se puede establecer conexion';
}




?>

 
 



 