<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="../js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../../img/U.ico">
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../css/home.css">
    <meta charset="utf-8">
    <title>Buscar -
      <?php if (isset($_SESSION['usuario'])):
        echo $_SESSION['usuario'];
      ?>
      </title>
    <?php else: ?>
        </title> <script type="text/javascript">window.location="/";</script>
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
    $busqueda=$_GET['buscar'];
    $sql="SELECT * FROM users WHERE usuario LIKE '%".$busqueda."%'";
    $res=mysqli_query($con, $sql);
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
          <h2>Buscar usuarios:</h2>
          <form class="buscar-page" action="" method="get">
            <input type="text" name="buscar" value="<?php echo $_GET['buscar'] ?>" placeholder="Buscar un usuario">
            <input type="submit" name="" value="Buscar">
          </form>
          <h2>Resultados: </h2>
          <?php
          $contadorResultados=0;
          while ($usuario=mysqli_fetch_array($res)) {
            $iconoVerficacion=$usuario['admin']==1?"<img class='verificado' src='https://liveupproject.000webhostapp.com/Home/images/cheque.svg' alt='Verificado'>":"";
            if($usuario[0]!=$id_usu){
              $nivelUsuario=1;
              $puntosUsuario=$usuario['puntaje'];
              $nivelesUsuario = array(10,50,100,200,500,1000,2000,5000,10000);
              for ($i=0; $i <sizeof($nivelesUsuario); $i++) {
                if($puntosUsuario>=$nivelesUsuario[$i]){
                  $nivelUsuario=$i+2;
                }else{
                  $porcentajeUsuario=$puntosUsuario*100/$nivelesUsuario[$i];
                  break;
                }
              }
              echo "<div class='perfil-encontrado'><div class='linea' style='margin-bottom:20px;'>".
              "<a style='width: 30%;display:flex;align-items:center;flex-direction:column;justify-content:space-around;' href='../?idBuscado=".$usuario[0]."'>".
              $usuario[1].$iconoVerficacion."</a>".
              "<div style='display: flex;width: 70%; flex-direction: column;justify-content: space-around; align-items:center'>".
                "<div class='barra-porcentaje meta-barra'>".
                  "<span class='porcentaje' style='width: ".$porcentajeUsuario."%'></span>".
                "</div>".
                "<div class='linea'>".
                  "<p>Nv: ".$nivelUsuario."</p>".
                  "<p>".$porcentajeUsuario."%</p>".
                "</div>".
              "</div>".
              "<form action='' method='post' style='width: 10%;'>".
                "<input type='text' name='amigo_anadir' value='".$usuario[0]."' style='display:none;'>".
                "<input class='".checarSiAmigo($id_usu, $usuario[0], $con)." amigos-anadir' type='image' name='anadirAmigo' value='simon' src=../images/".checarSiAmigo($id_usu,$usuario[0],$con).".svg>".
              "</form>".
              "</div>
            </div>";
            $contadorResultados++;
            }
          }
          ?>
          <?php if ($contadorResultados<1): ?>
            <h3>No hay ningun usuario que contenga en su nombre "<?php echo $_GET['buscar']; ?>".</h3>
            <h3>Intente con otra búsqueda.</h3>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div id=crear-meta class=crear-meta>
    </div>
    <canvas id="stars"></canvas>
    <script src="../../js/fondo.js"></script>
    <?php
    if(isset($_POST['amigo_anadir'])){
      $usuarioAnadir=$_POST['amigo_anadir'];
      if(checarSiAmigo($id_usu,$usuarioAnadir,$con)=="a"){
        $sql="INSERT INTO amigos_".$id_usu." (id_amigo) VALUES ('$usuarioAnadir');";
        mysqli_query($con,$sql);
        echo '<script type="text/javascript">window.location="../";</script>';
      }
    }
    ?>
  </body>
</html>
