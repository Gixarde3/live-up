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
    document.getElementById('flecha').style = '-webkit-transform: rotate(0deg);-moz-transform: rotate(0deg);-ms-transform: rotate(0deg);transform: rotate(0deg);';
    document.getElementById('borde').style='border-radius: 0px 18px 0px 0px;-moz-border-radius: 0px 18px 0px 0px;-webkit-border-radius: 0px 18px 0px 0px;';
    document.getElementById('fondo-abrido').style='display: none; z-index:0;';
    document.getElementById('metas').style='position: relative; z-index: 6;';
    document.getElementById('conf').style='border-radius: 0px 0px 18px 0px;';
    for (var x = porcentajeAAbrir; x > -110 ; x=x-10) {
      document.getElementById('desplegar').style= 'left: '+x+'%;';
        await sleep(1);
      }
    }else{
      document.getElementById('flecha').style = '-webkit-transform: rotate(-180deg);-moz-transform: rotate(-180deg);-ms-transform: rotate(-180deg);transform: rotate(-180deg);';
      document.getElementById('borde').style='border-radius: 0px 0px 0px 0px;-moz-border-radius: 0px 0px 0px 0px;-webkit-border-radius: 0px 0px 0px 0px;';
      document.getElementById('fondo-abrido').style='display: block; z-index:3;';
      document.getElementById('metas').style='position: relative; z-index: 2;';
      document.getElementById('conf').style='border-radius: 0px 0px 0px 0px;';
      for (var x = -100; x < porcentajeAAbrir+10; x=x+10) {
        document.getElementById('desplegar').style= 'left: '+x+'%;';
          await sleep(1);
      }
    }
  }
  async function minimizar(){
    var width=40;
    var height=30;
    for (var i = 10; i > 0; i=i-1) {
      width=width-4;
      height=height-3;
      document.getElementById('crear-meta').style='display: flex;z-index: 8; width: '+width+'%;left: '+(50-(width/2))+'%;height: '+height+'%;top: '+(50-(height/2))+'%;';
      document.getElementById('titulo').style='font-size: '+height+'px;';
      if(document.getElementById('aclaracion')){
        document.getElementById('aclaracion').style='display: none;';
      }
      await sleep(1);
    }
    document.getElementById('fondo-abrido').style='display: none; z-index:0;';
    document.getElementById('crear-meta').style='display: none; z-index: 0;'
  }
  async function crear(accionEspecifica, id_meta){
    var anchoPantalla=window.innerWidth;
    var anchoSalto=0;
    console.log(anchoPantalla);
    if(anchoPantalla<=600){
      anchoSalto=9;
    }
    else{
      anchoSalto=4;
    }
    if(document.getElementById('aclaracion')){
      document.getElementById('aclaracion').style='display: none;';
    }
    switch (accionEspecifica) {
      case 1:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Añadir una meta:</h2><form action='' method='post'><input class='metaNueva' type='text' name='tareaNueva' placeholder='Ingresa una tarea' required><input class='metaNueva' type='text' name='porcentajeTarea' placeholder='Ingresa el porcentaje que da a la meta' required><input class='anadirBoton' type='submit' value='Añadir' name='anadir' id=titulo></form>";
      break;
      case 2:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Editar la tarea:</h2><form action='' method='post'><input type='text' name='id_tarea_editar' value='"+id_meta+"' style='display: none;'><input class='metaNueva' type='text' name='tareaEditada' placeholder='Ingresa una meta' required><input class='anadirBoton' type='submit' value='Editar' name='editar' id=titulo value='editar'></form>";
      break;
      case 3:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>¿Estás seguro de eliminar esta tarea?</h2><form action='' method='post'><input type='text' name='id_tarea_eliminar' value='"+id_meta+"' style='display: none;'> <div class='linea'><input class='anadirBoton' type='submit' value='Sí' name='eliminar' value='eliminar' id=titulo><button type='button' name='no' onclick='minimizar()' class='no'><p>No</p></button></div></form>";
      break;
      case 4:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>¿Estás seguro de que cumpliste esta tarea?</h2><p class='texto-aclaracion' id=aclaracion>Le recordamos que no tenemos forma de comprobarlo, pero contamos con su completa honestidad al respecto.</h3><form action='' method='post'><input type='text' name='id_tarea_cumplir' value='"+id_meta+"' style='display: none;'> <div class='linea'><input class='anadirBoton' type='submit' value='Sí' name='cumplir' id=titulo><button type='button' name='no' onclick='minimizar()' class='no'><p>No</p></button></div></form>";
      break;
      case 5:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='../images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Parece que ya has cumplido esta tarea. <br>¡Bien hecho!</h2><button type='button' name='no' onclick='minimizar()' class='no'><p>Cerrar</p></button>";
      break;
      case 6:
      document.getElementById('crear-meta').innerHTML="<button class='cerrar' type='button' name='cerrar' onclick='minimizar()'><img src='images/cerrar.svg' alt='Cerrar'></button><h2 id=titulo>Porcentaje inválido</h2><p class='texto-aclaracion' id=aclaracion>Parece que quiere añadir una meta que completará su meta a más del 100%. Inténtelo de nuevo.</p><button type='button' name='no' onclick='minimizar()' class='no'><p>Cerrar</p></button>";
      break;
    }
    document.getElementById('fondo-abrido').style='display: block; z-index:7;';
    console.log("Por alguna razón, no se actualiza en el servidor");
    var width=0;
    var height=0;
    for (var i = 0; i < 10; i++) {
      width=width+anchoSalto;
      height=height+3;
      document.getElementById('crear-meta').style='display: flex;z-index: 8; width: '+width+'%;left: '+(50-(width/2))+'%;height: '+height+'%;top: '+(50-(height/2))+'%;';
      document.getElementById('titulo').style='font-size: '+height+'px;';
      await sleep(1);
    }
    if(document.getElementById('aclaracion')){
      document.getElementById('aclaracion').style='display: block;';
    }
  }
