<?php session_start();?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="../js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../../img/U.ico">
    <script type="text/javascript" src="../js/Home.js"></script>
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../css/home.css">
    <meta charset="utf-8">
    <!-- Verifica si se está cargando la página con un usuario logeado-->
    <title>Logros -
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
    include '../../php/conexion.php';
    include '../../php/amigos.php';
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
        $introducido=$obj->introducido_logros;
      }
    if(isset($_POST['anadir'])){
      $logro=$_POST['logroNuevo'];
      $checarNoRepetida="SELECT * FROM logros WHERE texto_logro='$logro' AND id_padre='$id_usu'";
      $checarMeta=mysqli_query($con, $checarNoRepetida);
      $existeMeta=false;
      while($obj=mysqli_fetch_object($checarMeta)){
        $existeMeta=true;
      }
      if(!$existeMeta){
        $hash = md5( rand(0,1000) );
        $anadirMeta="INSERT INTO logros(id_padre, texto_logro, hash) VALUES ('$id_usu','$logro', '$hash')";
        mysqli_query($con, $anadirMeta);
      }
    }
    if(isset($_POST['editar'])){
      $metaEditada=$_POST['logroEditado'];
      $id_meta=$_POST['id_logro_editar'];
      $sql="UPDATE logros SET texto_logro='$metaEditada' WHERE id_logro='$id_meta'";
      mysqli_query($con, $sql);
    }
    if(isset($_POST['eliminar'])){
      $id_meta=$_POST['id_logro_eliminar'];
      $sql="DELETE FROM logros WHERE id_logro='$id_meta'";
      mysqli_query($con,$sql);
    }
    if(isset($_POST['privatizar'])){
      $uno=1;
      $dos=2;
      $id_meta=$_POST['id_logro_privatizar'];
      if($admin!=1){
        $sql="UPDATE logros SET privada='$uno' WHERE id_logro='$id_meta'";
        mysqli_query($con,$sql);
      }else{
        $sql="UPDATE logros SET privada='$dos' WHERE id_logro='$id_meta'";
        mysqli_query($con,$sql);
      }
    }
    if(isset($_POST['publicar'])){
      $uno=0;
      $id_meta=$_POST['id_logro_publicar'];
      $sql="UPDATE logros SET privada='$uno' WHERE id_logro='$id_meta'";
      mysqli_query($con,$sql);
    }
    $consultarMetas="SELECT * FROM logros WHERE id_padre='$id_usu'";
    $resultadoMetas=mysqli_query($con,$consultarMetas);
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
            <p><?php echo $porcentaje; ?>%</p>
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
            <form class="buscar" action="../search/" method="get">
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
          <a href="../Leaderboard/" class="opcion">
            <img src="../images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </a>
          <a href="<?php echo isset($_GET['idBuscado'])?"/":"../"; ?>" class="opcion">
            <img src="../images/hogar.svg" alt="Principal">
            <p>Principal</p>
          </a>
          <a href="../Calificar/" class="opcion">
            <img src="../images/clasificacion.svg" alt="Calificar Metas">
            <p>Calificar metas</p>
          </a>
          <div class="opcion configuracion" id="conf">
            <img src="../images/configuraciones.svg" alt="Configuaración">
            <p>Configuración</p>
          </div>
        </div>
        <div class="metas" id=metas style="display: block;">
          <?php if(isset($_GET['idBuscado'])){$iconoVerficacion=$adminBuscado==1?"<img class='verificado' src='https://liveupproject.000webhostapp.com/Home/images/cheque.svg' alt='Verificado'>":"";} ?>
          <h1 style="width: 100%; text-align:center;">Logros <?php echo isset($_POST['metas_cumplidas'])?"cumplidas ":"";
           echo isset($_GET['idBuscado'])?"de ".$user.$iconoVerficacion:"";?></h1>
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
            ?>
            <?php if ($display): ?>
              <div class="meta">
                <?php $extra=isset($_GET['idBuscado'])?"&idBuscado=".$_GET['idBuscado']:""; ?>
                <div class="linea linea-meta">
                  <p><?php echo $arreglo['texto_logro'] ?></p>
                <?php if (!isset($_GET['idBuscado'])): ?>
                  <button class="meta-boton" type="button" name="editar" onclick="crear(17,<?php echo $arreglo[0]; ?>)">
                    <img class="imagen-metas" src="../images/editar.svg" alt="">
                  </button>
                <?php endif; ?>
                  <button class="meta-boton" type="button" name="privatizar" <?php if (!isset($_GET['idBuscado'])): ?>onclick="crear(<?php echo $arreglo['privada']==0?"19":"20" ?>,<?php echo $arreglo[0] ?>)<?php endif; ?> ">
                    <img src='../images/candado<?php echo $arreglo['privada']==0?"_vacio.svg":".svg"; ?>' alt='Privatizar meta'>
                  </button>
                  <?php if (!isset($_GET['idBuscado'])): ?>
                    <button class="meta-boton" type="button" name="eliminar" onclick="crear(18,<?php echo $arreglo[0] ?>)">
                      <img src='../images/eliminar.svg' alt='Eliminar meta'>
                    </button>
                  <?php endif; ?>
                 </div>
                 <p style="width: 100%; text-align: right;"><?php echo $arreglo['calificada']==1?$arreglo['promedio_puntos']."pts":"El logro está siendo calificado"; ?></p>
               </div>
            <?php $contadorMetas++;?>
            <?php endif; ?>
          <?php endwhile; ?>
        <?php if ($contadorMetas==0): ?>
          <?php if (isset($_GET['idBuscado'])): ?>
            <h2 style="width:100%; display: flex; justify-content: space-around">¡Parece que <?php echo $user; ?> no tiene ninguna logro realizado!</h2>
            <h2 style="width:100%; display: flex; justify-content: space-around">¡Espera a que haga uno, el o hará 😉!</h2>
          <?php else: ?>
            <h2 style="width:100%; display: flex; justify-content: space-around">¡Parece que no tienes ningun logro!</h2>
            <h2 style="width:100%; display: flex; justify-content: space-around">¡Si haz hecho algo genial que no está en tus metas, añádelo!</h2>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (!isset($_GET['idBuscado'])): ?>
          <div class="botones">
            <button type="button" name="crear" onclick="crear(16,0)"> <img src="../images/anadir.svg" alt="Crear meta"><p>Crear</p></button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
      <div class="crear-meta" id=crear-meta>
      </div>
    <?php
    if(isset($existeMeta)){
      if($existeMeta){
        echo "  <script type='text/javascript'>".
                  "crear(21,0);".
                "</script>";
      }
    }
    if($introducido==0){
      $uno=1;
      $usuarioEnLinea=$_SESSION['usuario'];
      $sql="UPDATE users SET introducido_logros='$uno' WHERE usuario='$usuarioEnLinea'";
      if(mysqli_query($con, $sql)===false){
        echo "no se pudo banda";
      }

      echo '<script type="text/javascript">'.
      'crear(23,0);'.
      '</script>';
    }
      ?>
    <canvas id="stars"></canvas>
    <script src="../../js/fondo.js"></script>
  </body>
</html>
