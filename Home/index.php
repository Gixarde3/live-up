<?php session_start();
if(isset($_SESSION['recargar'])){
  if($_SESSION['recargar']){
    $_SESSION['recargar']=false;
    echo "<script type='text/javascript'>window.location='../Home/Meta/?id_meta=".
    $_SESSION['tareaRealizando']."&hash=".
    $_SESSION['hash']."';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../img/U.ico">
    <script type="text/javascript" src="js/Home.js"></script>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="css/home.css">
    <meta charset="utf-8">
    <title>Metas -
      <?php if (isset($_SESSION['usuario'])):
        echo $_SESSION['usuario'];
      ?></title>
    <?php else: ?>
      <script type="text/javascript">window.location="/";</script>
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  </head>
  <body>
    <?php
    $contadorCumplidas=0;
    include '../php/conexion.php';
    include '../php/amigos.php';
    $con=conectar();
    if(isset($_GET['idBuscado'])){
      $buscarUsuario=$_GET['idBuscado'];
      $sql="SELECT * FROM users WHERE idusuario='$buscarUsuario'";
      $consulta=mysqli_query($con,$sql);
      while($resultado=mysqli_fetch_object($consulta)){
        $user=$resultado->usuario;
        $id_usu=$resultado->idusuario;
      }
    }
    $usuarioEnLinea=$_SESSION['usuario'];
    $sql="SELECT * FROM users WHERE usuario='$usuarioEnLinea'";
    $active=33333;
    $verificada=63276;
    $consulta=mysqli_query($con,$sql);
    $count=0;
      while($obj=mysqli_fetch_object($consulta)){
        if(!isset($_GET['idBuscado'])){
          $id_usu=$obj->idusuario;
        }
        $active=$obj->estado;
        $verificada=$obj->verificada;
        $nivel=$obj->nivel;
        $porcentaje=$obj->porcentaje_nivel;
      }
    if(isset($_POST['anadir'])){
      $meta=$_POST['metaNueva'];
      $checarNoRepetida="SELECT * FROM metas WHERE texto_meta='$meta' AND id_padre='$id_usu'";
      $checarMeta=mysqli_query($con, $checarNoRepetida);
      $counter=0;
      while($obj=mysqli_fetch_object($checarMeta)){
        $counter++;
      }
      if($counter<1){
        $hash = md5( rand(0,1000) );
        $anadirMeta="INSERT INTO metas(id_padre, texto_meta, hash) VALUES ('$id_usu','$meta', '$hash')";
        mysqli_query($con, $anadirMeta);
      }
    }
    if(isset($_POST['editar'])){
      $metaEditada=$_POST['metaEditada'];
      $id_meta=$_POST['id_meta_editar'];
      $sql="UPDATE metas SET texto_meta='$metaEditada' WHERE id_meta='$id_meta'";
      mysqli_query($con, $sql);
    }
    if(isset($_POST['eliminar'])){
      $id_meta=$_POST['id_meta_eliminar'];
      $sql="DELETE FROM metas WHERE id_meta='$id_meta'";
      mysqli_query($con,$sql);
    }
    $consultarMetas="SELECT * FROM metas WHERE id_padre='$id_usu'";
    $resultadoMetas=mysqli_query($con,$consultarMetas);
    $cumplida=1;
    $por=100;
    $sql="UPDATE metas SET cumplida='$cumplida' WHERE porcentaje='$por'";
    mysqli_query($con,$sql);
    ?>
    <div class="principal">
      <img src="images/Fondo-abrido.png" alt="Fondo" class="fondo-abrido" id=fondo-abrido>
      <div class="parte-arriba">
        <div class="perfil">
          <div class="linea">
            <p><?php echo $_SESSION['usuario'] ?></p>
            <a href="../logout.php"><img src="images/salir.svg" alt="Salir" class="salir"></a>
          </div>
          <div class="linea">
            <div class="barra-porcentaje">
              <span class="porcentaje" style='width: <?php echo $porcentaje ?>%'></span>
            </div>
          </div>
          <div class="linea">
            <p>Nivel: <?php  echo $nivel;?></p>
            <p><?php echo $porcentaje; ?>%</p>
          </div>
        </div>
      </div>
      <div class="parte-abajo">
        <div class="opciones">
          <div class="opcion amigos" id=borde>
            <div class="boton-opcion">
              <img src="images/usuarios-svg.svg" alt="Amigos" class="icono">
              <p>Social</p>
            </div>
            <button class="abrir" type="button" name="abrir" onclick="abrir()"> <img src="images/proximo.svg" alt="Abrir" id=flecha> </button>
          </div>
          <div class="social-desplegado" id=desplegar>
            <form class="buscar" action="search/" method="get">
              <p>Buscar amigo: </p>
              <input type="text" name="buscar" placeholder="Escribe el usuario" class="buscar">
              <input type="submit" value="Buscar">
            </form>
            <div class="lista-amigos">
              <h1 style="width: 100%; text-align: center;">Amigos</h1>
              <?php
              listarAmigos($id_usu, $con);
              ?>
            </div>
          </div>
          <div class="opcion">
            <img src="images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </div>
          <a href="Calificar/" class="opcion">
            <img src="images/clasificacion.svg" alt="Calificar Metas">
            <p>Calificar metas</p>
          </a>
          <div class="opcion configuracion" id="conf">
            <img src="images/configuraciones.svg" alt="Configuaración">
            <p>Configuración</p>
          </div>
        </div>
        <div class="metas" id=metas style="display: block;">
          <h1 style="width: 100%; text-align:center;">Metas <?php echo isset($_POST['metas_cumplidas'])?"cumplidas ":"";
           echo isset($_GET['idBuscado'])?"de ".$user:"";?></h1>
          <?php
          $contadorMetas=0;
          while ($arreglo=mysqli_fetch_array($resultadoMetas)) {
            if($arreglo['cumplida']!=1&&!isset($_POST['metas_cumplidas'])){
              echo"<div class='meta'>";
              $extra=isset($_GET['idBuscado'])?"&idBuscado=".$_GET['idBuscado']:"";
              echo "<a href='../Home/Meta/?id_meta=".$arreglo['id_meta']."&hash=".$arreglo['hash']."".$extra."'>".$arreglo['texto_meta']." <img class='imagen-metas' src='../Home/images/portapapeles.svg' alt=''> </a>";
              echo "<div class='linea linea-meta'><div class='barra-porcentaje meta-barra'><span class='porcentaje' style='width: ".$arreglo['porcentaje']."%'></span></div><p>".$arreglo['porcentaje']."%</p>";
              if(!isset($_GET['idBuscado'])){
              echo "<button class='meta-boton' type='button' name='editar' onclick='crear(2, ".$arreglo[0].")'> <img src='images/editar.svg' alt='Editar meta'></button><button class='meta-boton' type='button' name='eliminar' onclick='crear(3, ".$arreglo[0].")''> <img src='images/eliminar.svg' alt='Eliminar meta'></button></div>";
              }
              echo "<p style='width: 100%; text-align: right;'>";if($arreglo['calificada']==1){echo $arreglo['puntos']." pts.";}else{echo "La meta aún está siendo calificada.";} echo "</p></div>";
              $contadorMetas++;
            }else{
              if($arreglo['cumplida']==1&&isset($_POST['metas_cumplidas'])){
                echo"<div class='meta'>";
                $extra=isset($_GET['idBuscado'])?"&idBuscado=".$_GET['idBuscado']:"";
                echo "<a href='../Home/Meta/?id_meta=".$arreglo['id_meta']."&hash=".$arreglo['hash']."".$extra."'>".$arreglo['texto_meta']." <img class='imagen-metas' src='../Home/images/portapapeles.svg' alt='' style='width:5%;'> </a>";
                echo "<div class='linea linea-meta'><div class='barra-porcentaje meta-barra'><span class='porcentaje' style='width: ".$arreglo['porcentaje']."%'></span></div><p>".$arreglo['porcentaje']."%</p>";
                if(!isset($_GET['idBuscado'])){
                echo "<button class='meta-boton' type='button' name='editar' onclick='crear(2, ".$arreglo[0].")'> <img src='images/editar.svg' alt='Editar meta'></button><button class='meta-boton' type='button' name='eliminar' onclick='crear(3, ".$arreglo[0].")''> <img src='images/eliminar.svg' alt='Eliminar meta'></button></div>";
                }
                echo "<p style='width: 100%; text-align: right;'>";if($arreglo['calificada']==1){echo $arreglo['puntos']." pts.";}else{echo "La meta aún está siendo calificada.";} echo "</p></div>";
                $contadorCumplidas++;
              }
            }
          }
          if ($contadorMetas==0) {
            if(!isset($_POST['metas_cumplidas'])){
              if(isset($_GET['idBuscado'])){
                echo "<h2 style='width: 100%; text-align center;'>¡Parece que ".$user." no tiene ninuna meta pendiente!</h2><h2>¡Dile que añada una!</h2>";
              }else{
                echo "<h2 style='width: 100%; text-align center;'>¡Parece que no tienes ninguna meta pendiente!</h2><h2 style='width: 100%; text-align center;'>Añade una:</h2><form action='' method='post' style='width: 100%; display: flex; flex-direction: column; justify-content: space-around'><input class='metaNueva' type='text' name='metaNueva' placeholder='Ingresa una meta' required><input class='anadirBoton' type='submit' value='Añadir' name='anadir'></form>";
              }
            }
          }
          ?>
          <?php if ($contadorMetas>=1): ?>
            <?php if (!isset($_GET['idBuscado'])): ?>
              <div class="botones">
                <button type="button" name="crear" onclick="crear(1,0)"> <img src="images/anadir.svg" alt="Crear meta"> <p>Crear</p></button>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          <?php if (isset($_GET['idBuscado'])): ?>
            <div class="botones">
              <a href="../Home" style="align-items:center; width: 20%; text-align: center;">
                <img src="images/hogar.svg" alt="Regresar a mi perfil">Regresar a mi perfil</a>
            </div>
          <?php endif; ?>
          <?php if ($contadorCumplidas<1&&isset($_POST['metas_cumplidas'])): ?>
            <?php if (isset($_GET['idBuscado'])): ?>
              <h2>¡Parece que <?php echo $user ?> no ha cumplido ninguna meta!</h2>
              <h2>Dile que añada una para completarla y obtener puntos.</h2>
            <?php else: ?>
              <h2>¡Parece que no has cumplido ninguna meta!</h2>
            <?php endif; ?>
          <?php endif; ?>
          <form action="" method="post" class="boton-abajo" style="width: 100%;">
            <?php if (!isset($_POST['metas_cumplidas'])): ?>
              <input type="submit" name="metas_cumplidas" value="Ver metas cumplidas" style="text-align: center;">
            <?php endif; ?>
            <?php if (isset($_POST['metas_cumplidas'])): ?>
              <input type="submit" name="metas_pendientes" value="Ver metas pendientes" style="text-align: center;">
            <?php endif; ?>
          </form>
        </div>
      </div>
        <div class="crear-meta" id=crear-meta>
        </div>
    </div>
    <canvas id="stars"></canvas>
    <script src="../js/fondo.js"></script>
  </body>
</html>
