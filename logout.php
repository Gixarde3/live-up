<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <link rel="shortcut icon" type="image/ico" href="../img/U.ico">
    <meta charset="utf-8">
    <title>Logout</title>
  </head>
  <body>
    <?php
    include 'php/conexion.php';
    $con=conectar();
        $iduser=$_SESSION['id_user'];
        $sql="UPDATE users SET estado=0 WHERE idusuario='$iduser'";
        mysqli_query($con, $sql);
        session_destroy();
        echo 'Redireccionando';
        echo '<script type="text/javascript">window.location="/live-up";</script>';
    ?>
  </body>
</html>
