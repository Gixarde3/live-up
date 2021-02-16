let canvas = document.getElementById('stars');
let ctx = canvas.getContext('2d');
let w = canvas.width = window.innerWidth;
let h = canvas.height = window.innerHeight;
let num = 500;
let tamano = 4;
let elementos = [];
inicio();
caida();


function inicio() {
  for (var i = 0; i < num; i++) {
    elementos[i] = {
      x: Math.ceil(Math.random() * w),
      y: Math.ceil(Math.random() * h),
      toX: -1,
      toY: 0,
      tamano: Math.random() * tamano,
    }
  }
}
function caida() {
  ctx.clearRect(0, 0, w, h);
  for (var i = 0; i < num; i++) {
    var e = elementos[i];
    ctx.beginPath();
    ctx.fillStyle = "rgba(255,255,255,0.3)";
    ctx.arc(e.x, e.y,e.tamano,0,2*Math.PI);
    ctx.fill();
    e.x = e.x + e.toX;
    e.y = e.y + e.toY;
    if (e.x > w) { e.x = 0;}
	  if (e.x < 0) { e.x = w;}
    if (e.y > h) { e.y = 0;}
  }

  timer = setTimeout(caida,10);
  function resize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    h=canvas.height;
    w=canvas.width;
    inicio();
    console.log("la pantalla cambió de tamaño");
  }
  window.onresize = resize;
}
