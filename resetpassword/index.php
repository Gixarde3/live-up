<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/ico" href="../img/U.ico">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>Recuperar contraseña</title>
  </head>
  <body>
    <header class="inicio">
      <img src="../img/logo.png" alt="logo" class="logo">
      <p>"El RPG de la vida real"</p>
    </header>
    <div class="contenedor">
      <p>Recuperar contraseña</p>
      <?php
        function checkMail($mail){
          include '../php/conexion.php';
          $con=conectar();
          $query="SELECT * FROM users WHERE mail='$mail'";
          $result=mysqli_query($con, $query);
          $count=0;
          while($obj=mysqli_fetch_object($result)){
              $count++;
           }
           if($count == 1)
           {
             return 1;
           }
           else
           {
             return 0;
           }
        }
        if(isset($_POST['enviar'])){
          if(isset($_POST['mail']) && !empty($_POST['mail'])){
            if(checkMail($_POST['mail'])==1){
              $db="id15290561_liveup";
              $userDB="id15290561_gixarde3";
              $pass="Ge0metry_Dash";
              $server="localhost";
              $con=mysqli_connect($server, $userDB, $pass, $db);
              echo '<div class="bueno"> Se enviará un correo con un link para recuperar tu contraseña </div>';
              $mail=$_POST['mail'];
              $query="SELECT * FROM users WHERE mail='$mail'";
              $result=mysqli_query($con, $query);
              $count=0;
              while($obj=mysqli_fetch_object($result)){
                $hash=$obj->hash;
                $count++;
               }
              $to     =  $mail;// Send email to our user

              $subject = 'Live-up | Recupera tu contraseña'; // Give the email a subject
              $message = wordwrap('

              Hemos recibido la notificación de que deseas cambiar tu contraseña o recuperarla.
              Si no eres tú solamente ignora el mensaje.

              Para cambiar o recuperar tu contraseña ingresa al siguiente link:
              https://liveupproject.000webhostapp.com/resetpassword/change/?email='.$mail.'&hash='.$hash.'

              '); // Our message above including the link

              $headers = 'From: noreply@live-up.com'.'\r\n'; // Set from headers
              $correo=@mail($to, $subject, $message, $headers);
            }
          }else{
            echo '<div class="error">Ingresa algo en e-mail </div>';
          }
        }
      ?>
      <form action="" method="post" class="login formulario">

        <div class="entrada">
          <label>Ingresa tu e-mail</label>
          <input type="text" name="mail" placeholder="Ingresa tu mail">
        </div>
        <input class="boton" type="submit" name="enviar" value="Enviar">
        <div class="registrarse">
          <p><a href="../">Regresar a la página principal</a></p>
        </div>
      </form>
    </div>

    <canvas id="stars"></canvas>
    <script src="../js/fondo.js"></script>
  </body>
</html>
