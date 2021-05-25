var abrido=false;
function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
async function abrir(){
  abrido=!abrido;
  var anchoPantalla=window.innerWidth;
  var porcentajeAAbrir=0;
  console.log(anchoPantalla);
  if(anchoPantalla<=768){
    porcentajeAAbrir=40;
  }
  else{
    porcentajeAAbrir=100;
  }
  if(!abrido){
    document.getElementById('cerrarDesplegado').style='display: none; z-index:0;';
    document.getElementById('flecha').style = '-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);transform: rotate(0deg);';
    document.getElementById('borde').style='border-radius: 0px 18px 0px 0px;-moz-border-radius: 0px 18px 0px 0px;-webkit-border-radius: 0px 18px 0px 0px;';
    document.getElementById('fondo-abrido').style='display: none; z-index:0;';
    document.getElementById('metas').style='display: block;z-index: 6;';
    document.getElementById('conf').style='border-radius: 0px 0px 18px 0px;';
    document.getElementById('desplegar').style= 'left: -110%;';
    }else{
      document.getElementById('cerrarDesplegado').style='display: block; z-index:4;';
      document.getElementById('flecha').style = '-webkit-transform: rotate(-180deg);-moz-transform: rotate(-180deg);-ms-transform: rotate(-180deg);transform: rotate(-180deg);';
      document.getElementById('borde').style='border-radius: 0px 0px 0px 0px;-moz-border-radius: 0px 0px 0px 0px;-webkit-border-radius: 0px 0px 0px 0px;';
      document.getElementById('fondo-abrido').style='display: block; z-index:3;';
      document.getElementById('metas').style='display: block;z-index: 2;';
      document.getElementById('conf').style='border-radius: 0px 0px 0px 0px;';
      document.getElementById('desplegar').style= 'left: '+porcentajeAAbrir+'%;';
    }
  }
  async function minimizar(){
    var width=40;
    var height=30;
    document.getElementById('crear-meta').style='opacity: 0;z-index: 8; width:0%; height: 0;%';
    document.getElementById('fondo-abrido').style='display: none; z-index:0;';
  }
  async function crear(accionEspecifica, id_meta){
    var anchoPantalla=window.innerWidth;
    var anchoSalto=0;
    console.log(anchoPantalla);
    if(anchoPantalla<=600){
      anchoSalto=90;
    }
    else{
      anchoSalto=40;
    }
    if(document.getElementById('aclaracion')){
      document.getElementById('aclaracion').style='display: none;';
    }
    switch (accionEspecifica) {
      case 1:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Añadir una meta:</h2><form action='' method='post'><input class='metaNueva' type='text' name='metaNueva' placeholder='Ingresa una tarea' required><input class='anadirBoton' type='submit' value='Añadir' name='anadir' id=titulo></form>";
      break;
      case 2:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Editar la meta:</h2><form action='' method='post'><input type='text' name='id_meta_editar' value='"+id_meta+"' style='display: none;'><input class='metaNueva' type='text' name='metaEditada' placeholder='Ingresa una meta' required><input class='anadirBoton' type='submit' value='Editar' name='editar' id=titulo value='editar'></form>";
      break;
      case 3:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>¿Estás seguro de eliminar esta meta?</h2><form action='' method='post'><input type='text' name='id_meta_eliminar' value='"+id_meta+"' style='display: none;'> <div class='linea'><input class='anadirBoton' type='submit' value='Sí' name='eliminar' value='eliminar' id=titulo><button type='button' name='no' onclick='minimizar()' class='no'><p>No</p></button></div></form>";
      break;
      case 4:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>¿Estás seguro de que cumpliste esta tarea?</h2><p class='texto-aclaracion' id=aclaracion>Le recordamos que no tenemos forma de comprobarlo, pero contamos con su completa honestidad al respecto.</h3><form action='' method='post'><input type='text' name='id_tarea_cumplir' value='"+id_meta+"' style='display: none;'> <div class='linea'><input class='anadirBoton' type='submit' value='Sí' name='cumplir' id=titulo><button type='button' name='no' onclick='minimizar()' class='no'><p>No</p></button></div></form>";
      break;
      case 5:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Parece que ya has cumplido esta tarea. <br>¡Bien hecho!</h2><button type='button' name='no' onclick='minimizar()' class='no'><p>Cerrar</p></button>";
      break;
      case 6:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Porcentaje inválido</h2><p class='texto-aclaracion' id=aclaracion>Parece que quiere añadir una meta que completará su meta a más del 100% o el porcentaje es negativo. Inténtelo de nuevo.</p><button type='button' name='no' onclick='minimizar()' class='no'><p>Cerrar</p></button>";
      break;
      case 7:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Añadir una tarea:</h2><form action='' method='post'><input class='metaNueva' type='text' name='tareaNueva' placeholder='Ingresa una tarea' required><input class='metaNueva' type='number' name='porcentajeTarea' placeholder='Ingresa el porcentaje que da a la meta' required><input class='anadirBoton' type='submit' value='Añadir' name='anadir' id=titulo></form>";
      break;
      case 8:
      var porcentajeActualDeLaMeta=document.getElementById('porcentajeDeLaMeta_'+id_meta).value;
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Editar la tarea:</h2><form action='' method='post'><input type='text' name='id_tarea_editar' value='"+id_meta+"' style='display: none;'><input class='metaNueva' type='text' name='tareaEditada' placeholder='Ingresa una tarea' required><div class='linea'><input class='metaNueva' type='number' name='porcentajeEditada' placeholder='Ingresa un porcentaje nuevo' value="+porcentajeActualDeLaMeta+" required><p>%</p></div><input class='anadirBoton' type='submit' value='Editar' name='editar' id=titulo value='editar'></form>";
      break;
      case 9:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>¿Estás seguro de eliminar esta tarea?</h2><form action='' method='post'><input type='text' name='id_tarea_eliminar' value='"+id_meta+"' style='display: none;'> <div class='linea'><input class='anadirBoton' type='submit' value='Sí' name='eliminar' value='eliminar' id=titulo><button type='button' name='no' onclick='minimizar()' class='no'><p>No</p></button></div></form>";
      break;
      case 10:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'>"+
        "<img src='images/cerrar.svg' alt='Cerrar'>"+
      "</button>"+
      "<h2 id=titulo>¡Parece que quieres añadir una tarea que ya existe!</h2>"+
      "<h3>Intenta de nuevo con otra meta.</h3>"+
      "<form>"+
      "<button type='button' onclick=crear(1,0) class='no'><p>Intentar de nuevo</p></button>"+
      "</form>"
      break;
      case 11:
      var html_botones_generados="";
      for(var i=1; i<=5;i++){
        html_botones_generados+="<button class=btn_estrella  type='button' name='estrella_"+i+"' onclick='rellenar("+i+")'> <img id='btn"+i+"' src='../images/estrella.svg' alt='estrella'></button>";
      }
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'>"+
      "<img src='../images/cerrar.svg' alt='Cerrar'></button>"+
      "<h2 id=titulo>¡Califica esta meta!</h2>"+
      "<form method='post'>"+
      "<div class='linea_estrellas'>"+
      html_botones_generados+
      "</div>"+
      "<input type='hidden' name='valor_estrellas' value='' id=valor>"+
      "<input type='hidden' name='meta_calificar' value="+id_meta+">"+
      "<input type='submit' name='enviar_estrellas' value='Enviar ⭐ de calificación'>"+
      "</form>";
      break;
      case 12:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'>"+
        "<img src='../images/cerrar.svg' alt='Cerrar'>"+
      "</button>"+
      "<h2 id=titulo>¡Parece que quieres volver a calificar esta meta!</h2>"+
      "<h3>Esa acción es solo válida para administradores.</h3>"+
      "<form>"+
      "<button type='button' onclick=minimizar() class='no'><p>¡Entendido! 👍</p></button>"+
      "</form>"
      break;
    }
    document.getElementById('fondo-abrido').style='display: block; z-index:7;';
    console.log("Por alguna razón, no se actualiza en el servidor");
    document.getElementById('crear-meta').style='opacity:1;z-index: 8; width: '+anchoSalto+'%;height: 30%;';
  }
  function rellenar(id_estrella_maxima){
    document.getElementById('valor').value=id_estrella_maxima;
    for (var i = 1; i <= 5; i++) {
      var imagenCambiarSrc=document.getElementById('btn'+i);
      imagenCambiarSrc.src="../images/estrella.svg";
    }
    for (var i = 1; i <= id_estrella_maxima; i++) {
      var imagenCambiarSrc=document.getElementById('btn'+i);
      imagenCambiarSrc.src="../images/estrella_llena.svg";
    }
  }
