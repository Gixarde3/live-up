<?php
function listarAmigos($id_usu, $con){
  $contadorAmigos=0;
  $sql="SELECT * FROM amigos_".$id_usu;
  $amigos=mysqli_query($con, $sql);
  echo "<ul style='margin-top:20px;'>";
  while($amigo=mysqli_fetch_array($amigos)){
    $sql="SELECT * FROM users WHERE idusuario='$amigo[0]'";
    $amigoResultante=mysqli_query($con, $sql);
    while ($resultado=mysqli_fetch_object($amigoResultante)) {
      echo "<li><p>".$resultado->usuario."</p><div class='barra-porcentaje meta-barra'><span class='porcentaje' style='width: ".$resultado->porcentaje_nivel."%'></span></div><div class='linea'><p>Nv: ".$resultado->nivel."</p><p>".$resultado->porcentaje_nivel."%</p></div></li>";
      $contadorAmigos++;
    }
  }
  if($contadorAmigos<1){
    echo "<h2>¡Parece que aún no añades a ninún amigo!<br>¡Busca algunos!</h2>";
  }
  echo "</ul>";
}
function checarSiAmigo($id_usu,$id_buscar, $con){
  $sql="SELECT * FROM amigos_".$id_usu." WHERE id_amigo='$id_buscar'";
  $resultado=mysqli_query($con,$sql);
  $contador=0;
  while($res=mysqli_fetch_object($resultado)){
    $contador++;
  }
  if($contador==0){
    return "a";
  }else{
    return "b";
  }
}
?>
