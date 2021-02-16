<?php session_start();
//Probando para ver si funciona el gihub desde atom
if(isset($_SESSION['id_user'])){
  $id_usuario=$_SESSION['id_user'];
  include 'php/conexion.php';
  $con=conectar();
  $sql="SELECT * FROM users WHERE idusuario='$id_usuario'";
  $active=33333;
  $verificada=63276;
  $consulta=mysqli_query($con,$sql);
  $count=0;
  while($obj=mysqli_fetch_object($consulta)){
    $_SESSION['id_user']=$obj->idusuario;
    $_SESSION['usuario']=$obj->usuario;
    $id_usu=$obj->idusuario;
    $active=$obj->estado;
    $verificada=$obj->verificada;
    if($active==0){
      $query="UPDATE users SET estado=1 WHERE idusuario='$id_usuario'";
      $id_usu=$_SESSION['id_user'];
      mysqli_query($con, $query);
      $count++;
    }
  }
  echo '<script type="text/javascript">window.location="Home/";</script>';
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/ico" href="../img/U.ico">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>¡Live up! El RPG de la vida real</title>
  </head>
  <body>
    <div class="ContendorGeneral">
      <header class="inicio">
        <img src="img/logo.png" alt="logo" class="logo">
        <p>"El RPG de la vida real"</p>
      </header>
      <div class="contenedor">
        <?php
        $_SESSION['id_user']=NULL;
        function checklogin($username,$password){
          include 'php/conexion.php';
          $con=conectar();
          $sql="SELECT * FROM users WHERE usuario='$username' AND password='$password'";
          $active=33333;
          $verificada=63276;
          $consulta=mysqli_query($con,$sql);
          $count=0;
          while($obj=mysqli_fetch_object($consulta)){
            $_SESSION['id_user']=$obj->idusuario;
            $_SESSION['usuario']=$obj->usuario;
            $id_usu=$obj->idusuario;
            $active=$obj->estado;
            $verificada=$obj->verificada;
            if($active==0 && $verificada==1){
              $query="UPDATE users SET estado=1 WHERE idusuario='$id_usu'";
              $id_usu=$_SESSION['id_user'];
              echo $_SESSION['id_user'];
              mysqli_query($con, $query);
              $count++;
            }
          }
           if($count == 1)
           {
            return 1;
           }
           else
           {
             if($verificada==0){
               echo '<div class="error">Tu cuenta no ha sido verificada</div>';
               return 2;
             }else{
               return 0;
             }
           }
        }
        if(isset($_SESSION['id_user'])){
          echo '<div class="error">Ya habías iniciado sesión en esta cuenta</div>';
        }else{
          if(isset($_POST['login'])){
              $Checkeo=checklogin($_POST['username'], $_POST['password']);
            if($Checkeo==1){
              echo '<div class="bueno"> Has ingresado correctamente</div>';
              echo "¿De verdad pudiste leer esto? Si es así, alo, perdón por la pizza con piña :c";
              $_SESSION['chequeo']='ingresado_login';
              echo '<script type="text/javascript">window.location="Home/";</script>';
            }else{
              if ($Checkeo!=2) {
                echo '<div class="error">Usuario o Contraseña inválidos</div>';
              }
            }
          }
        }
        ?>
        <form action="" method="post" class="login formulario">
          <!--comentarioDEPrueba-->
          <div class="entrada">
            <p>Usuario: </p>
            <input type="text" name="username" placeholder="Ingresa tu usuario">
          </div>
          <div class="entrada">
            <p>Contraseña: </p>

            <input type="password" name="password" placeholder="Ingresa tu password">
          </div>
          <input class="boton" type="submit" value="Ingresar" name="login">
          <div class="registrarse">
            <p  class="left"> <a href="crearuser/">Crear cuenta</a> </p>
            <p class="right"> <a href="resetpassword/">¿Olvidó su contraseña?</a> </p>
          </div>
        </form>
      </div>
    </div>
    <canvas id="stars"></canvas>
    <script src="js/fondo.js"></script>
  </body>
</html>
