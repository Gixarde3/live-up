<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <link rel="shortcut icon" type="image/ico" href="../img/U.ico">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Crear cuenta - Live up</title>
  </head>
  <body>
    <header class="inicio">
      <img src="../img/logo.png" alt="logo" class="logo">
      <p>"El RPG de la vida real"</p>
    </header>
    <div class="contenedor">
      <?php
      include '../php/conexion.php';
      function crearCuenta($user, $password, $mail){
        if(isset($_POST['userNuevo']) && !empty($_POST['userNuevo']) AND isset($_POST['mailNuevo']) && !empty($_POST['mailNuevo'])){
          $usuario=$_POST['userNuevo'];
          $correo=$_POST['mailNuevo'];
          $con=conectar();
          $sql = "SELECT * FROM users WHERE usuario='$usuario' OR mail='$correo'";
          $conexion=mysqli_query($con,$sql);
          $valido=0;
          while ($usado = mysqli_fetch_object($conexion)) {
           $valido++;
          }
          if($valido==1){
            return 0;
          }else{
            $res=mysqli_query($con, $sql);
            $count=0;
            $nivel=1;
            $hash = md5( rand(0,1000) );
            $registro="INSERT INTO users (usuario,password,mail,hash,nivel) VALUES ('$usuario','$password','$correo','$hash','$nivel');";
            $to     = $mail; // Send email to our user

            $subject = 'Signup | Verification'; // Give the email a subject
            $message = wordwrap('

            ¡Gracias por registrarte!
            Tu cuenta ya fué creada, pero es necesario que la confirmes para poder iniciar sesión
            ------------------------
            Username: '.$user.'
            Password: '.$password.'
            ------------------------

            Haz click en este link o cópialo y pégalo en tu navegador para activar tu cuenta:
            https://liveupproject.000webhostapp.com/crearuser/verify.php?email='.$mail.'&hash='.$hash.'

            ');
            $headers = 'From: noreply@live-up.com'.'\r\n'; // Set from headers
            $correo=@mail($to, $subject, $message, $headers);
            mysqli_query($con, $registro);
            $obtenerRegistro="SELECT * FROM users WHERE usuario='$user' AND password='$password'";
            $consulta=mysqli_query($con, $obtenerRegistro);
            while($obj=mysqli_fetch_object($consulta)){
              $_SESSION['id_user']=$obj->idusuario;
              $_SESSION['usuario']=$obj->usuario;
              $id_usu=$obj->idusuario;
              echo "hecho";
            }
            $generarAmigos="CREATE TABLE amigos_"."$id_usu"."(id_amigo int(5) NOT NULL, PRIMARY KEY(id_amigo));";
            mysqli_query($con, $generarAmigos);
            while ($obj = mysqli_fetch_object($res)) {
             $count++;
            }
            if($count == 1)
            {
              return 0;
            }
            else
            {
              return 1;
            }
     			}
        }
      }

      if(isset($_POST['create'])){
        $letras="abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
        $car=str_split($letras);
        $pas=str_split($_POST['passwordNuevo']);
        $valido=FALSE;
        for($i=0; $i<sizeof($pas); $i++){
          for($e=0; $e<sizeof($car); $e++){
            if($pas[$i]==$car[$e]){
              $valido=TRUE;
              break;
            }
          }
          if($valido){
            break;
          }
        }
        if($valido){
          if(isset($_POST['terminos'])){
            if(crearCuenta($_POST['userNuevo'], $_POST['passwordNuevo'],$_POST['mailNuevo'])==1){
              echo '<script type="text/javascript">window.location="../";</script>';
            }
            else{
              echo '<div class="error">Usuario o e-mail en uso</div>';
            }
          }else{
            echo '<div class="error">No has aceptado los términos y condiciones</div>';
          }
        }else{
          echo '<div class="error">La contraeña debe incluir letras</div>';
        }
      }
      ?>
      <form class="formulario crear" action="" method="post">
        <div class="entrada">
          <label>Usuario: </label>
          <input type="text" name="userNuevo" placeholder="Ingresa el usario a crear" required>
        </div>
        <div class="entrada">
          <label>Correo electrónico: </label>
          <input type="text" name="mailNuevo" placeholder="Ingresa tu e-mail" required>
        </div>
        <div class="entrada">
          <label>Contraseña: </label>
          <input type="password" name="passwordNuevo" placeholder="ingresa tu nueva contraseña" required>
        </div>
        <input class="boton" type="submit" name="create" value="Crear cuenta">
        <div class="registrarse">
          <p class="left"><a href="../">Regresar al inicio</a></p>
          <p class="terminos-condiciones right"><input type="checkbox" name="terminos" value="aceptado" required><a href="../terminos">Acepto los términos y condiciones</a></p>
        </div>
      </form>
    </div>
    <canvas id="stars" width="100%" height="100%"></canvas>
    <script src="../js/fondo.js">
    </script>
  </body>
</html>
