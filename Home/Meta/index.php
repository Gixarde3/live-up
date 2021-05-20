<?php session_start();?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="../js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../../img/U.ico">
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../css/home.css">
    <meta charset="utf-8">
    <title>Tareas -
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
        $puntos=$obj->puntaje;
        $nivel=$obj->nivel;
        $porcentaje=$obj->porcentaje_nivel;
        $admin=$obj->admin;
      }
    $id_meta=$_GET['id_meta'];
    $hash=$_GET['hash'];
    $sql="SELECT * FROM metas WHERE id_meta='$id_meta' AND hash='$hash'";
    $resultadoDeLaMeta=mysqli_query($con, $sql);
    while($obj=mysqli_fetch_object($resultadoDeLaMeta)){
      $texto_meta=$obj->texto_meta;
      $porcentaje_meta=$obj->porcentaje;
    }
    $sql="SELECT * FROM minimetas WHERE meta_madre='$id_meta' AND usuario='$id_usu'";
    $resultado=mysqli_query($con,$sql);

    if(isset($_POST['eliminar'])){
      $id_tarea=$_POST['id_tarea_eliminar'];
      $sql="DELETE FROM minimetas WHERE id_tarea='$id_tarea'";
      mysqli_query($con,$sql);
      $_SESSION['recargar']=true;
      $_SESSION['tareaRealizando']=$_GET['id_meta'];
      $_SESSION['hash']=$_GET['hash'];
      echo "<script type='text/javascript'>window.location='../';</script>";
    }
    if(isset($_POST['cumplir'])){
      $id_tarea=$_POST['id_tarea_cumplir'];
      $completada=1;
      $sql="UPDATE minimetas SET completada='$completada' WHERE id_tarea='$id_tarea'";
      mysqli_query($con, $sql);
      $sql="SELECT * FROM minimetas WHERE id_tarea='$id_tarea'";
      $result=mysqli_query($con, $sql);
      while ($obj=mysqli_fetch_object($result)) {
        $metaMadre=$obj->meta_madre;
        $porcentajeAnadir=$obj->porcentaje;
      }
      $sql="SELECT * FROM metas WHERE id_meta='$metaMadre'";
      $result=mysqli_query($con, $sql);
      while ($obj=mysqli_fetch_object($result)) {
        $porcentaje=$obj->porcentaje;
      }
      $porcentajeFinal=$porcentaje+$porcentajeAnadir;
      $sql="UPDATE metas SET porcentaje='$porcentajeFinal' WHERE id_meta='$metaMadre'";
      $result=mysqli_query($con, $sql);
      $_SESSION['recargar']=true;
      $_SESSION['tareaRealizando']=$_GET['id_meta'];
      $_SESSION['hash']=$_GET['hash'];
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
          <?php if(isset($_GET['idBuscado'])){
            $iconoVerficacion=$adminBuscado==1?"<img class='verificado' src='https://liveupproject.000webhostapp.com/Home/images/cheque.svg' alt='Verificado'>":"";
            } ?>
          <a class="hiper" href="../<?php echo isset($_GET['idBuscado'])?"?idBuscado=".$_GET['idBuscado']:"" ?>"><h2>Tareas de: <?php echo $texto_meta ?><?php echo isset($_GET['idBuscado'])?" de ".$user.$iconoVerficacion:""; ?></h2><img src="../images/hogar.svg" alt=""></a>
          <?php
          $porcentaje_ocupado=0;
          $porcentaje_cumplido=0;
          $contadorTareas=0;
          ?>
          <?php while($tareas=mysqli_fetch_array($resultado)): ?>
            <div class="meta tarea">
              <?php if ($tareas[3]==1): ?>
                <div class="linea linea-tarea moviles" style="justify-content: space-around">
                  <div class="espacio">
                    <p style="color: #43df30"><?php echo $tareas[4] ?></p>
                    <p style="color: #43df30">Esta tarea otorgó <input type="hidden" value=<?php echo $tareas[5]; ?> id=porcentajeDeLaMeta_<?php echo $tareas[0]; ?>></input><?php echo $tareas[5]; ?>% a la meta</p>
                    <?php $porcentaje_cumplido+=$tareas[5]; ?>
                  </div>
                </div>
                <?php if (!isset($_GET['idBuscado'])): ?>
                  <div class="botones-tarea">
                    <button class='meta-boton' type='button' name='cumplir' onclick='crear(5, <?php echo $tareas[0]; ?>)'><img src='../images/seleccione.svg' alt='Cumplir meta' class='cumplir'></button>
                    <button class='meta-boton' type='button' name='editar' onclick='crear(8, <?php echo $tareas[0]; ?>)'><img src='../images/editar.svg' alt='Editar meta'></button>
                    <button class='meta-boton' type='button' name='eliminar' onclick='crear(9, <?php echo $tareas[0]; ?>)'><img src='../images/eliminar.svg' alt='Eliminar meta'></button>
                  </div>
                <?php else: ?>
                  <p class=tarea_estado style="color: #43df30; text-align:center;">Esta tarea ya ha sido cumplida</p>
                <?php endif; ?>
            <?php else: ?>
              <div class="linea linea-tarea moviles" style="justify-content: space-around">
                <div class="espacio">
                  <p><?php echo $tareas[4] ?></p>
                  <p>Esta tarea otorgará <input type="hidden" value=<?php echo $tareas[5]; ?> id=porcentajeDeLaMeta_<?php echo $tareas[0]; ?>></input><?php echo $tareas[5]; ?>% a la meta</p>
                </div>
              </div>
              <?php if (!isset($_GET['idBuscado'])): ?>
                <div class="botones-tarea">
                  <button class='meta-boton' type='button' name='cumplir' onclick='crear(4,<?php echo $tareas[0]; ?>)'><img src='../images/seleccione.svg' alt='Cumplir meta'></button>
                  <button class='meta-boton' type='button' name='editar' onclick='crear(8, <?php echo $tareas[0]; ?>)'><img src='../images/editar.svg' alt='Editar meta'></button>
                  <button class='meta-boton' type='button' name='eliminar' onclick='crear(9, <?php echo $tareas[0]; ?>)'><img src='../images/eliminar.svg' alt='Eliminar meta'></button>
                </div>
              <?php else: ?>
                <p class=tarea_estado style="text-align:center;">Esta tarea aún no ha sido cumplida</p>
              <?php endif; ?>
            <?php endif ?>
            <?php $contadorTareas++;
            $porcentaje_ocupado+=$tareas[5] ?>
            </div>
          <?php endwhile; ?>
          <?php if ($contadorTareas==0): ?>
            <?php if (isset($_GET['idBuscado'])): ?>
              <div class="tarea-nueva">
                <h2>¡Parece que <?php echo $user; ?> no tiene ninguna tarea en esta meta!</h2>
                <h3>Dile que añada una</h3>
              </div>
            <?php else: ?>
              <div class="tarea-nueva">
                <h2>¡Parece que no tienes ninguna tarea en esta meta!</h2>
                <h3>Para comenzar, añade una tarea pequeña para ir completando esta meta</h3>
                <h2>Añade una:</h2>
                <form action='' method='post'>
                  <input class='metaNueva' type='text' name='tareaNueva' placeholder='Ingresa una tarea' required>
                  <input class='metaNueva' type='number' name='porcentajeTarea' placeholder='Ingresa el porcentaje que da a la meta' required>
                  <input class='anadirBoton' type='submit' value='Añadir' name='anadir'>
                </form>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          <?php if ($contadorTareas>=1): ?>
            <?php if (!isset($_GET['idBuscado'])): ?>
              <div class="botones">
                <button type="button" name="crear" onclick="crear(7,0)"> <img src="../images/anadir.svg" alt="Crear meta"> <p>Crear</p></button>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div id=crear-meta class=crear-meta>
    </div>
    <canvas id="stars"></canvas>
    <script src="../../js/fondo.js"></script>
    <?php
    if(isset($_POST['anadir'])){
      $tarea=$_POST['tareaNueva'];
      $checarNoRepetida="SELECT * FROM minimetas WHERE texto_tarea='$tarea' AND meta_madre='$id_meta' AND usuario='$id_usu'";
      $checarTarea=mysqli_query($con, $checarNoRepetida);
      $porcentajeDara=$_POST['porcentajeTarea'];
      $counter=0;
      while($obj=mysqli_fetch_object($checarTarea)){
        $counter++;
      }
      if($counter<1){
        if(($porcentaje_ocupado+$porcentajeDara)<=100&&$porcentajeDara!=0&&$porcentajeDara>=0){
          $anadirMeta="INSERT INTO minimetas (meta_madre, usuario, texto_tarea, porcentaje) VALUES ('$id_meta', '$id_usu', '$tarea', $porcentajeDara)";
          $_SESSION['recargar']=true;
          $_SESSION['tareaRealizando']=$_GET['id_meta'];
          $_SESSION['hash']=$_GET['hash'];
          echo "<script type='text/javascript'>window.location='../';</script>";
          mysqli_query($con, $anadirMeta);
        }else{
          echo "<script type='text/javascript'>crear(6,0)</script>";
        }
      }
    }
    if(isset($_POST['editar'])){
      $id_tarea=$_POST['id_tarea_editar'];
      $porcentajeNuevo=$_POST['porcentajeEditada'];
      $tareaEditada=$_POST['tareaEditada'];
      $sql="SELECT * FROM minimetas WHERE meta_madre='$id_meta' AND usuario='$id_usu' AND texto_tarea='$tareaEditada'";
      $checarTarea=mysqli_query($con, $sql);
      $counter=0;
      while($obj=mysqli_fetch_object($checarTarea)){
        $counter++;
      }
      if($counter<1){
        if(($porcentaje_cumplido+$porcentajeNuevo)<=100&&$porcentajeNuevo!=0&&$porcentajeNuevo>0){
          $sql="UPDATE minimetas SET texto_tarea='$tareaEditada' WHERE id_tarea='$id_tarea'";
          mysqli_query($con, $sql);
          $sql="UPDATE minimetas SET porcentaje='$porcentajeNuevo' WHERE id_tarea='$id_tarea'";
          mysqli_query($con, $sql);
          $_SESSION['recargar']=true;
          $_SESSION['tareaRealizando']=$_GET['id_meta'];
          $_SESSION['hash']=$_GET['hash'];
          echo "<script type='text/javascript'>window.location='../';</script>";
        }else{
          echo "<script type='text/javascript'>crear(6,0)</script>";
        }
      }
    }
    ?>
  </body>
</html>
