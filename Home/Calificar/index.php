<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="../js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../../img/U.ico">
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../css/home.css">
    <meta charset="utf-8">
    <title>Calificar metas -
      <?php if (isset($_SESSION['usuario'])):
        echo $_SESSION['usuario'];
      ?></title>
    <?php else: ?>
      </title><script type="text/javascript">window.location="/";</script>
    <?php endif; ?>
    <script type="text/javascript" src="../js/Home.js">
    </script>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  </head>
  <body>
    <?php
    $validacion_de_meta_calificada_una_sola_vez=true;
    include '../../php/conexion.php';
    include '../../php/amigos.php';
    $con=conectar();
    $user=$_SESSION['usuario'];
    $sql="SELECT * FROM users WHERE usuario='$user'";
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
      $nivel=$obj->nivel;
      $puntos=$obj->puntaje;
      $porcentaje=$obj->porcentaje_nivel;
      $admin=$obj->admin;
    }
    if(isset($_POST['calificar_logros'])){
      $calificada=0;
      $sql="SELECT * FROM logros WHERE calificada='$calificada'";
      $res=mysqli_query($con, $sql);
    }else{
      $calificada=0;
      $sql="SELECT * FROM metas WHERE calificada='$calificada'";
      $res=mysqli_query($con, $sql);
    }
    function buscarUsuario($id_usuario,$con){
      $sql="SELECT * FROM users WHERE idusuario='$id_usuario'";
      $consulta=mysqli_query($con,$sql);
      while($obj=mysqli_fetch_object($consulta)){
        $nombre=$obj->usuario;
        $adminBuscado=$obj->admin;
        $datos = array('nombre' => $nombre, 'admin'=>$adminBuscado );
      }
      return $datos;
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
    if(isset($_POST['enviar_estrellas'])){

      $validacion_de_meta_calificada_una_sola_vez=true;
      $id_meta=$_POST['meta_calificar'];
      $sql="SELECT * FROM calificacion_usuario WHERE id_usuario='$id_usu' AND id_meta_calificada='$id_meta'";
      $consulta=mysqli_query($con,$sql);
      while(mysqli_fetch_object($consulta)){
        $validacion_de_meta_calificada_una_sola_vez=false;
      }
      if($validacion_de_meta_calificada_una_sola_vez||$admin==1){
        if(isset($_POST['valor_estrellas'])){
          $cantidad_estrellas=$_POST['valor_estrellas'];
        }else{
          $cantidad_estrellas=0;
        }
        $calificada=1;
        $sql="SELECT * From metas WHERE id_meta='$id_meta'";
        $consulta=mysqli_query($con,$sql);
        while($obj=mysqli_fetch_object($consulta)){
          $puntosActuales=$obj->puntos;
          $cantidad_calificada=$obj->cantidad_calificacion;
        }
        $puntosNuevos=$puntosActuales+($cantidad_estrellas*10);
        $cantidad_calificada++;
        if($cantidad_calificada<=10){
          $sql="UPDATE metas SET puntos='$puntosNuevos' WHERE id_meta='$id_meta'";
          mysqli_query($con, $sql);
          $sql="UPDATE metas SET cantidad_calificacion='$cantidad_calificada' WHERE id_meta='$id_meta'";
          mysqli_query($con, $sql);
        }
        if($cantidad_calificada>=10){
          $sql="UPDATE metas SET calificada='$calificada' WHERE id_meta='$id_meta'";
          mysqli_query($con, $sql);
        }
        $promedio_puntos=$puntosNuevos/$cantidad_calificada;
        $sql="UPDATE metas SET promedio_puntos='$promedio_puntos' WHERE id_meta='$id_meta'";
        mysqli_query($con, $sql);
        $sql="INSERT INTO calificacion_usuario (id_usuario,id_meta_calificada) VALUES ('$id_usu','$id_meta')";
        mysqli_query($con,$sql);
        $_SESSION['calificar']=true;
        echo "<script type='text/javascript'>window.location='../';</script>";
      }
    }
      if(isset($_POST['enviar_estrellas_logro'])){
        $validacion_de_meta_calificada_una_sola_vez=true;
        $id_meta=$_POST['logro_calificar'];
        $sql="SELECT * FROM logros_calificados WHERE id_usuario='$id_usu' AND id_logro='$id_meta'";
        $consulta=mysqli_query($con,$sql);
        while(mysqli_fetch_object($consulta)){
          $validacion_de_meta_calificada_una_sola_vez=false;
        }
        if($validacion_de_meta_calificada_una_sola_vez||$admin==1){
          if(isset($_POST['valor_estrellas'])){
            $cantidad_estrellas=$_POST['valor_estrellas'];
          }else{
            $cantidad_estrellas=0;
          }
          $calificada=1;
          $sql="SELECT * From logros WHERE id_logro='$id_meta'";
          $consulta=mysqli_query($con,$sql);
          while($obj=mysqli_fetch_object($consulta)){
            $puntosActuales=$obj->puntos;
            $cantidad_calificada=$obj->cantidad_calificacion;
          }
          $puntosNuevos=$puntosActuales+($cantidad_estrellas*10);
          $cantidad_calificada++;
          if($cantidad_calificada<=10){
            $sql="UPDATE logros SET puntos='$puntosNuevos' WHERE id_logro='$id_meta'";
            mysqli_query($con, $sql);
            $sql="UPDATE logros SET cantidad_calificacion='$cantidad_calificada' WHERE id_logro='$id_meta'";
            mysqli_query($con, $sql);
          }
          if($cantidad_calificada>=10){
            $sql="UPDATE logros SET calificada='$calificada' WHERE id_logro='$id_meta'";
            mysqli_query($con, $sql);
          }
          $promedio_puntos=$puntosNuevos/$cantidad_calificada;
          $sql="UPDATE logros SET promedio_puntos='$promedio_puntos' WHERE id_logro='$id_meta'";
          mysqli_query($con, $sql);
          $sql="INSERT INTO logros_calificados(id_usuario,id_logro) VALUES ('$id_usu','$id_meta')";
          mysqli_query($con,$sql);
          $_SESSION['calificar']=true;
          echo "<script type='text/javascript'>window.location='../';</script>";
        }
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
    ?>
    <div class="principal">
      <button type="button" name="button" id="cerrarDesplegado" onclick="abrir()" class="fondo-abrido"></button>
      <img src="../images/Fondo-abrido.png" alt="Fondo" class="fondo-abrido" id=fondo-abrido>
      <div class="parte-arriba">
        <div class="perfil">
          <div class="linea">
            <p><?php echo $_SESSION['usuario'] ?><?php echo $admin==1?"<img class='verificado' src='../images/cheque.svg' alt='Verificado'>":""; ?></p>
            <a href="../../logout.php"><img src="../images/salir.svg" alt="Salir" class="salir"></a>
          </div>
          <div class="linea">
            <div class="barra-porcentaje">
              <span class="porcentaje" style='width: <?php echo $porcentaje ?>%'></span>
            </div>
          </div>
          <div class="linea">
            <p>Nivel: <?php  echo $nivel;?></p>
            <p>Puntos: <?php echo $puntos; ?></p>
            <p><?php echo $porcentaje ?>%</p>
          </div>
        </div>
      </div>
      <div class="parte-abajo">
        <div class="opciones">
          <button class="opcion amigos" id=borde onclick="abrir()">
            <div class="boton-opcion">
              <img src="../images/usuarios-svg.svg" alt="Amigos" class="icono">
              <p>Social</p>
              <img src="../images/proximo.svg" alt="Abrir" id=flecha>
            </div>
          </button>
          <div class="social-desplegado" id=desplegar>
            <form class="buscar" action="../search" method="get">
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
          <a href="../Leaderboard" class="opcion">
            <img src="../images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </a>
          <a href="/" class="opcion">
            <img src="../images/hogar.svg" alt="Principal">
            <p>Principal</p>
          </a>
          <a href="" class="opcion">
            <img src="../images/clasificacion.svg" alt="Calificar Metas">
            <p>Calificar metas</p>
          </a>
          <div class="opcion configuracion" id="conf">
            <img src="../images/configuraciones.svg" alt="Configuaración">
            <p>Configuración</p>

          </div>

        </div>
        <div class="metas" id=metas style="display:block;">
          <div class="titulo_calificar">
            <?php if (!isset($_POST['calificar_logros'])): ?>
              <h2>Metas por calificar: </h2>
              <form action="" method="post">
                <input type="submit" name="calificar_logros" value="Calificar logros" style="text-align: center;">
              </form>
            <?php else: ?>
              <h2>Logros por calificar: </h2>
              <form action="" method="post">
                <input type="submit" name="calificar_metas" value="Calificar metas" style="text-align: center;">
              </form>
            <?php endif; ?>
          </div>
          <?php if (!isset($_POST['calificar_logros'])): ?>
            <?php
            $contadorResultados=0;
            while($arreglo=mysqli_fetch_array($res)):
            ?>
            <div class="meta">
              <?php
              $contadorResultados++;
              $arregloUsu=buscarUsuario($arreglo['id_padre'],$con);
              ?>
              <a href="../Meta/?id_meta=<?php echo $arreglo['id_meta'] ?>&hash=<?php echo $arreglo['hash']?> <?php echo $arreglo['id_padre']!=$id_usu?"&idBuscado=".$arreglo['id_padre']: ""; ?>">
                <div style='width:100%;display:flex;justify-content: space-between;'>
                  <?php echo  $arreglo['texto_meta']; ?>
                  <?php if (($arreglo['privada']!=1||$admin==1)&&$arreglo['privada']!=2): ?>
                    <p> <?php echo $arregloUsu['nombre']  ?> <?php echo $arregloUsu['admin']==1?"<img class='verificado' src='../images/cheque.svg' alt='Verificado'>":""; ?></p>
                  <?php else: ?>
                    <p>Usuario privado</p>
                  <?php endif; ?>
                </div>
              </a>
              <div class='linea linea-meta'>
                <div class='barra-porcentaje meta-barra'>
                  <span class='porcentaje' style="width: <?php echo $arreglo['porcentaje']; ?>%"></span>
                </div>
                <p><?php echo $arreglo['porcentaje'] ?>%</p>
                <button class="boton_calificar_incorporado" type="button" name="button" onclick="crear(11,<?php echo $arreglo['id_meta']; ?>)"> <img src="../images/clasificacion.svg" alt="Calificar esta meta"> </button>
              </div>
                <div style='width:100%; display:flex; justify-content:space-between;'>
                  <p style='width: 50%; text-align: center;'>Calificado:<?php  echo $arreglo['cantidad_calificacion'];?>  veces</p>
                  <p style='width:50%; text-align:center;'>Puntos promedio: <?php echo $arreglo['promedio_puntos']; ?></p>
                </div>

              </div>
            <?php
              endwhile;
             ?>
          <?php else: ?>
            <?php
            $contadorResultados=0;
            while($arreglo=mysqli_fetch_array($res)):
            ?>
            <div class="meta">
              <?php
              $contadorResultados++;
              $arregloUsu=buscarUsuario($arreglo['id_padre'],$con);
              ?>
              <p>
                <div style='width:100%;display:flex;justify-content: space-between;'>
                  <?php echo  $arreglo['texto_logro']; ?>
                  <?php if (($arreglo['privada']!=1||$admin==1)&&$arreglo['privada']!=2): ?>
                    <p> <?php echo $arregloUsu['nombre']  ?> <?php echo $arregloUsu['admin']==1?"<img class='verificado' src='../images/cheque.svg' alt='Verificado'>":""; ?></p>
                  <?php else: ?>
                    <p>Usuario privado</p>
                  <?php endif; ?>
                  <button class="boton_calificar_incorporado" type="button" name="button" onclick="crear(22,<?php echo $arreglo['id_logro']; ?>)"> <img src="../images/clasificacion.svg" alt="Calificar este logro"> </button>
                </div>
              </p>
                <div style='width:100%; display:flex; justify-content:space-between;'>
                  <p style='width: 50%; text-align: center;'>Calificado:<?php  echo $arreglo['cantidad_calificacion'];?>  veces</p>
                  <p style='width:50%; text-align:center;'>Puntos promedio: <?php echo $arreglo['promedio_puntos']; ?></p>
                </div>
              </div>
            <?php
              endwhile;
             ?>
          <?php endif; ?>
          <?php if ($contadorResultados<1): ?>
            <h3>Al parecer no hay metas que puedas calificar.</h3>
            <h3>¡Parece que tendrás que esperar un poco!.</h3>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div id=crear-meta class=crear-meta>
    </div>
    <canvas id="stars"></canvas>
    <script src="../../js/fondo.js"></script>
    <?php
    if(!$validacion_de_meta_calificada_una_sola_vez){
      echo "<script type='text/javascript'>crear(12,0)</script>";
    }
     ?>
  </body>
</html>
