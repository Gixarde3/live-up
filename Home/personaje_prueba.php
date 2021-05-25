<?php session_start();
//Verifica si no estás recargando para alguna meta.
//Esto porque la recarga se hacía demasiado rápido y no se mostraban los datos actualizados.
if(isset($_SESSION['recargar'])){
  if($_SESSION['recargar']){
    $_SESSION['recargar']=false;
    echo "<script type='text/javascript'>window.location='../Home/Meta/?id_meta=".
    $_SESSION['tareaRealizando']."&hash=".
    $_SESSION['hash']."';</script>";
  }
}
if(isset($_SESSION['calificar'])){
  if($_SESSION['calificar']){
    $_SESSION['calificar']=false;
    echo "<script type='text/javascript'>window.location='Calificar/'</script>";
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
    <!-- Verifica si se está cargando la página con un usuario logeado-->
    <title>Metas -
      <?php if (isset($_SESSION['usuario'])):
        echo $_SESSION['usuario'];
      ?></title>
    <?php else: ?>
    </title>  <script type="text/javascript">window.location="/";</script>
    <?php endif; ?>
    <!-- De lo contrario, simplemente te regrsa a la página inicial. -->
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
        $adminBuscado=$resultado->admin;
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
        $puntos=$obj->puntaje;
        $admin=$obj->admin;
      }
    if(isset($_POST['anadir'])){
      $meta=$_POST['metaNueva'];
      $checarNoRepetida="SELECT * FROM metas WHERE texto_meta='$meta' AND id_padre='$id_usu'";
      $checarMeta=mysqli_query($con, $checarNoRepetida);
      $existeMeta=false;
      while($obj=mysqli_fetch_object($checarMeta)){
        $existeMeta=true;
      }
      if(!$existeMeta){
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
      $sql="DELETE FROM minimetas WHERE meta_madre='$id_meta'";
      mysqli_query($con,$sql);
    }
    $consultarMetas="SELECT * FROM metas WHERE id_padre='$id_usu'";
    $resultadoMetas=mysqli_query($con,$consultarMetas);
    $cumplida=1;
    $por=100;
    $sql="UPDATE metas SET cumplida='$cumplida' WHERE porcentaje='$por'";
    mysqli_query($con,$sql);
    $sql="SELECT * From metas WHERE cumplida='$cumplida'";
    $consulta=mysqli_query($con,$sql);
    while($res=mysqli_fetch_object($consulta)){
      $subida=$res->puntos_subidos;
      $padre=$res->id_padre;
      $puntosDara=$res->promedio_puntos;
      $id_meta=$res->id_meta;
      $subidos=1;
      if($subida==0){
        $sql="SELECT * FROM users WHERE idusuario='$padre'";
        $consultaUsuarioParaSubirPuntos=mysqli_query($con,$sql);
        while ($usuarioASubirPuntos=mysqli_fetch_object($consultaUsuarioParaSubirPuntos)) {
          $puntosActualesDelUsuario=$usuarioASubirPuntos->puntaje;
        }
        $puntosActualesDelUsuario+=$puntosDara;
        echo $puntosActualesDelUsuario;
        $sql="UPDATE users SET puntaje='$puntosActualesDelUsuario' WHERE idusuario='$padre'";
        mysqli_query($con,$sql);
        $sql="UPDATE metas SET puntos_subidos='$subidos' WHERE id_meta='$id_meta'";
        mysqli_query($con,$sql);
      }
      $niveles = array(10,50,100,200,500,1000,2000,5000,10000);
      for ($i=0; $i <sizeof($niveles); $i++) {
        if($puntos>$niveles[$i]){
          $nivel=$i+2;
        }else{
          $porcentaje=$puntos*100/$niveles[$i];
          break;
        }
      }
    }
    ?>
    <div class="principal">
      <button type="button" name="button" id="cerrarDesplegado" onclick="abrir()" class="fondo-abrido"></button>
      <img src="images/Fondo-abrido.png" alt="Fondo" class="fondo-abrido" id=fondo-abrido>
      <div class="parte-arriba">
        <div class="perfil">
          <div class="linea">
            <p><?php echo $_SESSION['usuario'] ?><?php echo $admin==1?"<img class='verificado' src='images/cheque.svg' alt='Verificado'>":""; ?></p>
            <a href="../logout.php"><img src="images/salir.svg" alt="Salir" class="salir"></a>
          </div>
          <div class="linea">
            <div class="barra-porcentaje">
              <span class="porcentaje" style='width: <?php echo $porcentaje ?>%'></span>
            </div>
          </div>
          <div class="linea">
            <p>Nivel: <?php  echo $nivel;?></p>
            <p>Puntos: <?php echo $puntos; ?></p>
            <p><?php echo $porcentaje; ?>%</p>
          </div>
        </div>
      </div>
      <div class="parte-abajo">
        <div class="opciones">
          <button class="opcion amigos" id=borde onclick="abrir()">
            <div class="boton-opcion">
              <img src="images/usuarios-svg.svg" alt="Amigos" class="icono">
              <p>Social</p>
              <img src="images/proximo.svg" alt="Abrir" id=flecha>
            </div>
          </button>
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
          <a href="Leaderboard/" class="opcion">
            <img src="images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </a>
          <a href="<?php echo isset($_GET['idBuscado'])?"/":""; ?>" class="opcion">
            <img src="images/hogar.svg" alt="Principal">
            <p>Principal</p>
          </a>
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
          <div class="personaje">
            <img src="images/partes_cuerpo/body_character.png" alt="Cuerpo" class="torzo">
            <img src="images/partes_cuerpo/face_happy.png" alt="Rostro" class="cara">
            <img src="images/partes_cuerpo/feets_character.png" alt="Pies" class="pies">
          </div>
          <br>
          <div class="personaje">
            <img src="images/partes_cuerpo/Cat_face.png" alt="Cuerpo" class="torzo">
            <img src="images/partes_cuerpo/lenny_face.gif" alt="Rostro" class="cara">
            <img src="images/partes_cuerpo/feets_character.png" alt="Pies" class="pies">
          </div>
        </div>
      </div>
        <div class="crear-meta" id=crear-meta>
        </div>
    <?php
    if(isset($existeMeta)){
      if($existeMeta){
        echo "  <script type='text/javascript'>".
                  "crear(10,0);".
                "</script>";
      }
    }
      ?>
    <canvas id="stars"></canvas>
    <script src="../js/fondo.js"></script>
  </body>
</html>
