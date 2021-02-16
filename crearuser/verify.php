<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <link rel="shortcut icon" type="image/ico" href="../img/U.ico">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Verificar cuenta - Live up</title>
  </head>
  <body>
    <header class="inicio">
      <img src="../img/logo.png" alt="logo" class="logo">
      <p>"El RPG de la vida real"</p>
    </header>
    <div class="contenedor">
      <?php
      include '../php/conexion.php';
      $mail=$_GET['email'];
      $hash=$_GET['hash'];
      $con=conectar();
      //verificar si el e-mail está en la base de datos
      $sql="SELECT * FROM users WHERE mail='$mail' AND hash='$hash'";
      $res=mysqli_query($con, $sql);
      $count=0;
      $verificada=1;
      while ($obj = mysqli_fetch_object($res)) {
       $count++;
      }
      if($count == 1)
      {
        $update="UPDATE users SET verificada='$verificada' WHERE hash='$hash'";
        $check=mysqli_query($con, $update);
        echo '<div class="Bien">Cuenta activada con éxito</div>';
      }else{
        echo '<div class="error">Error en la activación de la cuenta</div>';
      }
      ?>
      <a href="/">Inicio</a>
    </div>
    <canvas id="stars" width="100%" height="100%"></canvas>
    <script src="../js/fondo.js">
    </script>
  </body>
</html>
