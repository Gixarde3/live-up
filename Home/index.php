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
      </title> <script type="text/javascript">window.location="/";</script>
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
        $actualizado=$obj->actualizado;
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
    if(isset($_POST['privatizar'])){
      $uno=1;
      $dos=2;
      $id_meta=$_POST['id_meta_privatizar'];
      if($admin!=1){
        $sql="UPDATE metas SET privada='$uno' WHERE id_meta='$id_meta'";
        mysqli_query($con,$sql);
      }else{
        $sql="UPDATE metas SET privada='$dos' WHERE id_meta='$id_meta'";
        mysqli_query($con,$sql);
      }
    }
    if(isset($_POST['publicar'])){
      $uno=0;
      $id_meta=$_POST['id_meta_publicar'];
      $sql="UPDATE metas SET privada='$uno' WHERE id_meta='$id_meta'";
      mysqli_query($con,$sql);
    }
    $consultarMetas="SELECT * FROM metas WHERE id_padre='$id_usu'";
    $resultadoMetas=mysqli_query($con,$consultarMetas);
    $cumplida=1;
    $por=100;
    $sql="UPDATE metas SET cumplida='$cumplida' WHERE porcentaje='$por'";
    mysqli_query($con,$sql);
    $sql="SELECT * From metas WHERE cumplida='$cumplida' AND calificada='$cumplida'";
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
    $sql="SELECT * From logros WHERE calificada='$cumplida'";
    $consulta=mysqli_query($con,$sql);
    while($res=mysqli_fetch_object($consulta)){
      $subida=$res->puntos_subidos;
      $padre=$res->id_padre;
      $puntosDara=$res->promedio_puntos;
      $id_meta=$res->id_logro;
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
        $sql="UPDATE logros SET puntos_subidos='$subidos' WHERE id_logro='$id_meta'";
        mysqli_query($con,$sql);
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
          <?php if(isset($_GET['idBuscado'])){$iconoVerficacion=$adminBuscado==1?"<img class='verificado' src='https://liveupproject.000webhostapp.com/Home/images/cheque.svg' alt='Verificado'>":"";} ?>
          <h1 style="width: 100%; text-align:center;">Metas <?php echo isset($_POST['metas_cumplidas'])?"cumplidas ":"";
           echo isset($_GET['idBuscado'])?"de ".$user.$iconoVerficacion:"";?></h1>
           <?php if (!isset($_GET['idBuscado'])): ?>
             <div class="boton-logro">
               <a href="Logros/"> <button type="button" name="button" class="logros">¿Has hecho algo importante para tí hoy? ¡Agrégalo como un logro!</button> </a>
             </div>
           <?php else: ?>
             <div class="boton-logro">
               <a href="Logros/?idBuscado=<?php echo $_GET['idBuscado'];?>"> <button type="button" name="button" class="logros">¡Mira esos pequeños logros que hicieron a <?php echo $user ?> sentirse mejor en el día!</button> </a>
             </div>
           <?php endif; ?>
          <?php $contadorMetas=0; ?>
          <?php while($arreglo=mysqli_fetch_array($resultadoMetas)): ?>
            <?php $display=true; ?>
            <?php if (isset($_GET['idBuscado'])) {
              $display=false;
              if($arreglo['privada']==0){
                $display=true;
              }
              if($admin==1){
                if($arreglo['privada']==1){
                  $display=true;
                }
              }
            }
            if($display){
              $display=false;
              if ($arreglo['cumplida']==1){
                if (isset($_POST['metas_cumplidas'])){
                  $display=true;
                }
              }else{
                if (!isset($_POST['metas_cumplidas'])){
                  $display=true;
                }
              }
            }
            ?>
            <?php if ($display): ?>
              <div class="meta">
                <?php $extra=isset($_GET['idBuscado'])?"&idBuscado=".$_GET['idBuscado']:""; ?>
                <div class="linea linea-meta">
                  <a href="../Home/Meta/?id_meta=<?php echo $arreglo['id_meta']."&hash=".$arreglo['hash'].$extra ?>">
                    <?php echo $arreglo['texto_meta'] ?>
                  </a>
                <?php if (!isset($_GET['idBuscado'])): ?>
                  <button class="meta-boton" type="button" name="editar" onclick="crear(2,<?php echo $arreglo[0]; ?>)">
                    <img class="imagen-metas" src="../Home/images/editar.svg" alt="">
                  </button>
                <?php endif; ?>
                </div>
                <div class="linea linea-meta">
                  <div class="barra-porcentaje meta-barra">
                    <span class="porcentaje" style="width: <?php echo $arreglo['porcentaje']; ?>%"></span>
                  </div>
                  <p><?php echo $arreglo['porcentaje']; ?>%</p>
                  <a href="../Home/Meta/?id_meta=<?php echo $arreglo['id_meta']."&hash=".$arreglo['hash'].$extra; ?>">
                  <button class="meta-boton" type="button" name="editar">
                    <img src='../Home/images/portapapeles.svg' alt='Editar meta'>
                  </button>
                  </a>
                  <button class="meta-boton" type="button" name="privatizar" <?php if (!isset($_GET['idBuscado'])): ?>onclick="crear(<?php echo $arreglo['privada']==0?"13":"14" ?>,<?php echo $arreglo[0] ?>)<?php endif; ?> ">
                    <img src='images/candado<?php echo $arreglo['privada']==0?"_vacio.svg":".svg"; ?>' alt='Privatizar meta'>
                  </button>
                  <?php if (!isset($_GET['idBuscado'])||$admin==1): ?>
                    <button class="meta-boton" type="button" name="eliminar" onclick="crear(3,<?php echo $arreglo[0] ?>)">
                      <img src='images/eliminar.svg' alt='Eliminar meta'>
                    </button>
                  <?php endif; ?>
                 </div>
                 <p style="width: 100%; text-align: right;"><?php echo $arreglo['calificada']==1?$arreglo['promedio_puntos']."pts":"La meta está siendo calificada"; ?></p>
               </div>
            <?php $contadorMetas++;?>
            <?php endif; ?>
          <?php endwhile; ?>
        <?php if ($contadorMetas==0): ?>
          <?php if (!isset($_POST['metas_cumplidas'])): ?>
            <?php if (isset($_GET['idBuscado'])): ?>
              <h2 style="width:100%; text-align: center;">¡Parece que <?php echo $user; ?> no tiene ninguna meta pendiente!</h2>
              <h2 style="width:100%; display: flex; justify-content: space-around">¡Dile que cree una!</h2>
            <?php else: ?>
              <h2 style="width:100%; text-align: center;">¡Parece que no tienes ninguna meta pendiente!</h2>
              <h2 style="width:100%; display: flex; justify-content: space-around">¡Añade una!</h2>
            <?php endif; ?>
          <?php endif; ?>
          <?php if (isset($_POST['metas_cumplidas'])): ?>
            <?php if (isset($_GET['idBuscado'])): ?>
              <h2 style="width:100%; text-align: center;">¡Parece que <?php echo $user; ?> no tiene ha cumplido ninguna meta</h2>
              <h2 style="width:100%; display: flex; justify-content: space-around">¡Dile que cree una!</h2>
            <?php else: ?>
              <h2 style="width:100%; text-align: center;">¡Parece que no tienes ninguna meta cumplida!</h2>
              <h2 style="width:100%; display: flex; justify-content: space-around">¡Añade una!</h2>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (!isset($_GET['idBuscado'])): ?>
          <div class="botones">
            <button type="button" name="crear" onclick="crear(1,0)"> <img src="images/anadir.svg" alt="Crear meta"><p>Crear</p></button>
          </div>
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
    if($actualizado==0){
      $uno=1;
      $usuarioEnLinea=$_SESSION['usuario'];
      $sql="UPDATE users SET actualizado='$uno' WHERE usuario='$usuarioEnLinea'";
      if(mysqli_query($con, $sql)===false){
        echo "no se pudo banda";
      }

      echo '<script type="text/javascript">'.
      'crear(15,0);'.
      '</script>';
    }
      ?>

    <canvas id="stars"></canvas>
    <script src="../js/fondo.js"></script>
  </body>
</html>
