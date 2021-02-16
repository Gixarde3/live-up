<?php
function conectar(){
define('DB_SERVER','localhost');
define('DB_NAME','live-up');
define('DB_USER','root');
define('DB_PASS','');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
return $con;
}
?>
