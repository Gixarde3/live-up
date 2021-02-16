<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../../css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>Cambiar contraseña</title>
  </head>
  <body>
    <header class="inicio">
      <img src="../../img/logo.png" alt="logo" class="logo">
      <p>"El RPG de la vida real"</p>
    </header>
    <div class="contenedor">
      <?php
      function cambiarContraseña($user, $newPass, $valido){
        include '../php/conexion.php';
        $con=conectar();
        $contraseña=$newPass;
        $query="UPDATE users SET password='$contraseña' WHERE usuario='$user'";
        $count=0;
        if($valido){
          $sql=mysqli_query($con, $query);
          if($sql!==NULL){
            $count++;
          }
        }
        return ($count==1);
      }
      if(isset($_POST['cambiar'])){
        $db="id15290561_liveup";
        $userDB="id15290561_gixarde3";
        $pass="Ge0metry_Dash";
        $server="localhost";
        $con=mysqli_connect($server, $userDB, $pass, $db);
        $passNuevo=$_POST['passwordNuevo'];
        $passConfirmada=$_POST['confirmarPassword'];
        $count=0;
        if($passNuevo==$passConfirmada){
          if(isset($_GET['email'])){
            $mail=$_GET['email'];
            $query="SELECT * FROM users WHERE mail='$mail'";
            $sql=mysqli_query($con, $query);
            while($obj=mysqli_fetch_object($sql)){
              $count++;
              $user=$obj->usuario;
            }
          }else{
            $user=$_POST['usuario'];
            $count++;
          }
          if(!isset($_GET['hash'])){
            $passwordActual=$_POST['password'];
            $checarPass="SELECT * FROM users WHERE password='$passwordActual'";
            $resultadoCheckeo=mysqli_query($con,$checarPass);
            while($obj=mysqli_fetch_object($resultadoCheckeo)){
              $count++;
            }
            $valido=($count>=1);
            if(cambiarContraseña($user,$passNuevo,$valido)){
              echo '<div class="bueno"> Contraseña cambiada con éxito </div>';
          	}else{
              echo '<div class="error"> Error al cambiar la contraseña </div>';
            }
          }else{
            $valido=TRUE;
            if(cambiarContraseña($user,$passNuevo,$valido)){
              echo '<div class="bueno"> Contraseña cambiada con éxito </div>';
          	}else{
              echo '<div class="error"> Error al cambiar la contraseña </div>';
            }
          }
        }else{
          echo '<div class="error"> Contraseñas no son iguales </div>';
        }
      }
      ?>
      <form class="login formulario" action="" method="post">
        <?php
        if(!isset($_GET['email'])){
          echo '<div class="entrada"><p>Ingresa tu usuario</p><input type="text" name="usuario" placeholder="Usuario"></div>';
        }
        if(!isset($_GET['hash'])){
          echo '<div class="entrada"><p>Ingresa la contraseña actual</p><input type="password" name="password" placeholder="Contraseña actual"></div>';
        }
        echo '<div class="entrada"><p>Ingresa tu nueva contraseña</p><input type="password" name="passwordNuevo" placeholder="Contraseña"></div>';
        echo '<div class="entrada"><p>Confirma tu contraseña</p><input type="password" name="confirmarPassword" placeholder="Confirma tu contraseña"></div>';
        echo '<input type="submit" name="cambiar" class="boton" value="Enviar">';
        ?>
      </form>

    </div>
    <canvas id="stars" width="100%" height="100%"></canvas>
    <script src="../../js/fondo.js"></script>
  </body>
</html>
