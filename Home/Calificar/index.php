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
    }
    $calificada=0;
    $sql="SELECT * FROM metas WHERE calificada='$calificada'";
    $res=mysqli_query($con, $sql);
    function buscarUsuario($id_usuario,$con){
      $sql="SELECT * FROM users WHERE idusuario='$id_usuario'";
      $consulta=mysqli_query($con,$sql);
      while($obj=mysqli_fetch_object($consulta)){
        $nombre=$obj->usuario;
      }
      return $nombre;
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
      $id_meta=$_POST['meta_calificar'];
      $cantidad_estrellas=$_POST['valor_estrellas'];
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
      $_SESSION['calificar']=true;
      echo "<script type='text/javascript'>window.location='../';</script>";
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
      <img src="../images/Fondo-abrido.png" alt="Fondo" class="fondo-abrido" id=fondo-abrido>
      <div class="parte-arriba">
        <div class="perfil">
          <div class="linea">
            <p><?php echo $_SESSION['usuario'] ?></p>
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
          <div class="opcion amigos" id=borde>
            <div class="boton-opcion">
              <img src="../images/usuarios-svg.svg" alt="Amigos" class="icono">
              <p>Social</p>
            </div>
            <button class="abrir" type="button" name="abrir" onclick="abrir()"> <img src="../images/proximo.svg" alt="Abrir" id=flecha> </button>
          </div>
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
          <div class="opcion">
            <img src="../images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </div>
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
          <h2>Metas por calificar: </h2>
          <?php
          $contadorResultados=0;
          while($arreglo=mysqli_fetch_array($res)):
          ?>
          <div class="meta">
            <?php
            $contadorResultados++;
            $extra=isset($_GET['idBuscado'])?"&idBuscado=".$_GET['idBuscado']:"";
            ?>
            <a href="../Meta/?id_meta=<?php echo $arreglo['id_meta'] ?>&hash=<?php echo $arreglo['hash'].$extra ?>">
              <div style='width:100%;display:flex;justify-content: space-between;'>
                <?php echo  $arreglo['texto_meta']; ?>
                <p> <?php echo buscarUsuario($arreglo['id_padre'],$con); ?></p>
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
          <?php if ($contadorResultados<1): ?>
            <h3>Al parecer no hay metas que puedas calificar.</h3>
            <h3>¡Parece que tendrás que esperar un poco!.</h3>
          <?php endif; ?>
          <a href="../" style="display: flex; flex-direction:column; margin-top:50px;width: 100%;align-items: center;"> <img src="../images/hogar.svg" style="width:30%;" alt="Home"> Regresar</a>
        </div>
      </div>
    </div>
    <div id=crear-meta class=crear-meta>
    </div>
    <canvas id="stars"></canvas>
    <script src="../../js/fondo.js"></script>
  </body>
</html>
