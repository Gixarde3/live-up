<?php session_start();?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <script src="../js/cerrar.js"></script>
    <link rel="shortcut icon" type="image/ico" href="../../img/U.ico">
    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../css/home.css">
    <meta charset="utf-8">
    <title>Tareas - <?php
    if(isset($_SESSION['usuario'])){
      echo $_SESSION['usuario'].' </title>';
    }
    else{
      echo 'Redireccionando';
      echo '</title><script type="text/javascript">window.location="/";</script>';
    }
    ?>
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
        $puntos=$obj->puntaje;
        $porcentaje=$obj->porcentaje_nivel;
      }
    $id_meta=$_GET['id_meta'];
    $hash=$_GET['hash'];
    $sql="SELECT * FROM metas_".$id_usu." WHERE id_meta='$id_meta' AND hash='$hash'";
    $resultadoDeLaMeta=mysqli_query($con, $sql);
    while($obj=mysqli_fetch_object($resultadoDeLaMeta)){
      $texto_meta=$obj->texto_meta;
      $porcentaje_meta=$obj->porcentaje;
    }
    $sql="SELECT * FROM minimetas WHERE meta_madre='$id_meta' AND usuario='$id_usu'";
    $resultado=mysqli_query($con,$sql);
    if(isset($_POST['editar'])){
      $id_tarea=$_POST['id_tarea_editar'];
      $tareaEditada=$_POST['tareaEditada'];
      $sql="UPDATE minimetas SET texto_tarea='$tareaEditada' WHERE id_tarea='$id_tarea'";
      mysqli_query($con, $sql);
      $_SESSION['recargar']=true;
      $_SESSION['tareaRealizando']=$_GET['id_meta'];
      $_SESSION['hash']=$_GET['hash'];
      echo "<script type='text/javascript'>window.location='../';</script>";
    }
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
      $sql="SELECT * FROM metas_".$id_usu." WHERE id_meta='$metaMadre'";
      $result=mysqli_query($con, $sql);
      while ($obj=mysqli_fetch_object($result)) {
        $porcentaje=$obj->porcentaje;
      }
      $porcentajeFinal=$porcentaje+$porcentajeAnadir;
      $sql="UPDATE metas_".$id_usu." SET porcentaje='$porcentajeFinal' WHERE id_meta='$metaMadre'";
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
            <p>Puntos: <?php  echo $puntos;?></p>
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
          <div class="opcion">
            <img src="../images/trofeo.svg" alt="Leaderboards">
            <p>Leaderboards</p>
          </div>
          <div class="opcion">
            <img src="../images/clasificacion.svg" alt="Calificar Metas">
            <p>Calificar metas</p>
          </div>
          <div class="opcion configuracion" id="conf">
            <img src="../images/configuraciones.svg" alt="Configuaración">
            <p>Configuración</p>
          </div>
        </div>
        <div class="metas" id=metas style="display:block;">
          <a class="hiper" href="../<?php echo isset($_GET['idBuscado'])?"?idBuscado=".$_GET['idBuscado']:"" ?>"><h2>Tareas de: <?php echo $texto_meta ?><?php echo isset($_GET['idBuscado'])?" de ".$user:""; ?></h2><img src="../images/hogar.svg" alt=""></a>
          <?php
          $porcentaje_ocupado=0;
          $contadorTareas=0;
          while($tareas=mysqli_fetch_array($resultado)){
            echo"<div class='meta tarea'>";
            if($tareas[3]==1){
              echo "<div class='linea linea-tarea moviles' style='justify-content: space-around;'><div class='espacio'><p style='color: #43df30;'>".$tareas[4]."</p><p style='color: #43df30;'>Esta tarea otorgó ".$tareas[5]."% a la meta</p></div>";
              if(!isset($_GET['idBuscado'])){
                echo "<div class='botones-tarea'><button class='meta-boton' type='button' name='cumplir' onclick='crear(5, ".$tareas[0].")'> <img src='../images/seleccione.svg' alt='Cumplir meta' class='cumplir'></button><button class='meta-boton' type='button' name='editar' onclick='crear(8, ".$tareas[0].")'> <img src='../images/editar.svg' alt='Editar meta'></button><button class='meta-boton' type='button' name='eliminar' onclick='crear(9, ".$tareas[0].")''> <img src='../images/eliminar.svg' alt='Eliminar meta'></button></div>";
              }else{
                echo "<p style='color: #43df30 text-align:center; width: 15%;'>Esta tarea ya ha sido cumplida</p>";
              }
              echo "</div></div>";
            }else{
              echo "<div class='linea linea-tarea moviles' style='justify-content: space-around;'><div class='espacio'><p>".$tareas[4]."</p><p>Esta tarea otorgó ".$tareas[5]."% a la meta</p></div>";
              if(!isset($_GET['idBuscado'])){
                echo "<div class='botones-tarea'><button class='meta-boton' type='button' name='cumplir' onclick='crear(4, ".$tareas[0].")'> <img src='../images/seleccione.svg' alt='Cumplir meta'></button><button class='meta-boton' type='button' name='editar' onclick='crear(8, ".$tareas[0].")'> <img src='../images/editar.svg' alt='Editar meta'></button><button class='meta-boton' type='button' name='eliminar' onclick='crear(9, ".$tareas[0].")''> <img src='../images/eliminar.svg' alt='Eliminar meta'></button></div>";
              }else{
                echo "<p style='text-align:center; width: 15%;'>Esta tarea no ha sido cumplida</p>";
              }
              echo "</div></div>";
            }
          $contadorTareas++;
          $porcentaje_ocupado+=$tareas[5];
          }
          ?>
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
                  <input class='metaNueva' type='text' name='porcentajeTarea' placeholder='Ingresa el porcentaje que da a la meta' required>
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
          <?php if (isset($_GET['idBuscado'])): ?>
            <div class="botones">
              <a href="../" style="width:20%; text-align: center;"> <img src="../images/hogar.svg" alt="Regresar a mi perfil"> Regresar a mi perfil</a>
            </div>
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
        if(($porcentaje_ocupado+$porcentajeDara)<=100&&$porcentajeDara!=0){
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
    ?>
  </body>
</html>
