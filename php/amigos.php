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
      $puntos=$resultado->puntaje;
      $niveles = array(10,50,100,200,500,1000,2000,5000,10000);
      $nivel=1;
      for ($i=0; $i <sizeof($niveles); $i++) {
        if($puntos>$niveles[$i]){
          $nivel=$i+2;
        }else{
          $porcentaje=$puntos*100/$niveles[$i];
          break;
        }
      }
      $extraVerificado=$resultado->admin;
      $verificado=$extraVerificado==1?"<img class='verificado' src='https://liveupproject.000webhostapp.com/Home/images/cheque.svg' alt='Verificado'>":"";
      echo "<a href='https://liveupproject.000webhostapp.com/Home/?idBuscado=".$resultado->idusuario."'><p>".$resultado->usuario.$verificado."</p></a>"."
        <div class='barra-porcentaje meta-barra'>".
          "<span class='porcentaje' style='width: ".$porcentaje."%'></span>".
        "</div>".
        "<div class='linea'>".
          "<p>Nv: ".$nivel."</p>".
          "<p>Pt: ".$puntos."</p>".
          "<p>".$porcentaje."%</p>".
        "</div>";
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
