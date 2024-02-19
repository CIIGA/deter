<?php
session_start();
$_SESSION['usr']=NULL;
$_SESSION['rol']=NULL;
session_destroy();
echo '<meta http-equiv="refresh" content="0,url=../../">';
?>