<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="../js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../../img/U.ico">
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../css/home.css">
    <meta charset="utf-8">
    <title>Leaderboards -
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
      $admin=$obj->admin;
    }
    $calificada=0;
    $sql="SELECT * FROM users ORDER BY puntaje DESC";
    $res=mysqli_query($con, $sql);
    function buscarUsuario($id_usuario,$con){
      $sql="SELECT * FROM users WHERE idusuario='$id_usuario'";
      $consulta=mysqli_query($con,$sql);
      while($obj=mysqli_fetch_object($consulta)){
        $nombre=$obj->usuario;
      }
      return $nombre;
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
    function checarSiEsTop3($rank){
      switch ($rank) {
        case 1:
          return "numero_1";
        break;
        case 2:
          return "numero_2";
        break;
        case 3:
          return "numero_3";
        break;
        default:
          return "";
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
          <a href="" class="opcion">
            <img src="../images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </a>
          <a href="/" class="opcion">
            <img src="../images/hogar.svg" alt="Principal">
            <p>Principal</p>
          </a>
          <a href="../Calificar" class="opcion">
            <img src="../images/clasificacion.svg" alt="Calificar Metas">
            <p>Calificar metas</p>
          </a>
          <div class="opcion configuracion" id="conf">
            <img src="../images/configuraciones.svg" alt="Configuaración">
            <p>Configuración</p>

          </div>

        </div>
        <div class="metas" id=metas style="display:block;">
          <h2 style="width: 100%; text-align: center; display: block ruby;">Top 10 mejores personas cumpliendo sus metas</h2>
          <?php
          $contadorResultados=0;
          while($arreglo=mysqli_fetch_array($res)):
          ?>
          <div class="meta alinecion">
            <?php
            $contadorResultados++;
            ?>
            <div class="perfil ranking">
              <div class="linea">
                <a href="../?idBuscado=<?php echo $arreglo['idusuario']; ?>"> <p><?php echo $arreglo['usuario'] ?><?php echo $arreglo['admin']==1?"<img class='verificado' src='https://liveupproject.000webhostapp.com/Home/images/cheque.svg' alt='Verificado'>":""; ?></p></a>
                <p class="numero_rank <?php echo checarSiEsTop3($contadorResultados); ?>">#<?php echo $contadorResultados; ?></p>
                <p class="puntaje_destacando"><?php echo $arreglo['puntaje'] ?> puntos</p>
              </div>
              <div class="linea">
                <div class="barra-porcentaje">
                  <?php
                  $puntosRanking=$arreglo['puntaje'];
                  $nivelRanking=$arreglo['nivel'];
                  $nivelesUsuariosRanking = array(10,50,100,200,500,1000,2000,5000,10000);
                  for ($i=0; $i <sizeof($nivelesUsuariosRanking); $i++) {
                    if($puntosRanking>$nivelesUsuariosRanking[$i]){
                      $nivelRanking=$i+2;
                    }else{
                      $porcentajeRanking=$puntosRanking*100/$nivelesUsuariosRanking[$i];
                      break;
                    }
                  }
                   ?>
                  <span class="porcentaje" style="width: <?php echo $porcentajeRanking; ?>%"></span>
                </div>
              </div>
              <div class="linea">
                <p>Nivel: <?php  echo $nivelRanking;?></p>
                <p><?php echo $porcentajeRanking ?>%</p>
              </div>
            </div>
          </div>
          <?php
          if($contadorResultados>=10){
            break;
          }
          endwhile;
           ?>
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
  </body>
</html>
