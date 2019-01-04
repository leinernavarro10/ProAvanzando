
$(document).ready(function(){ 

llenarCart();

if(localStorage.getItem("cant")>0){
	$("#cant_produc").val(localStorage.getItem("cant"));
}else{
	$("#cant_produc").val('0');
}

});


function addcart(codigo){
	//alert(codigo);
var cant=$("#cant_produc").val();
cant=parseInt(cant)+1;
cant2=cant;
var tabla=$("#verProductos").html();

var nombre=$(".item_name","#"+codigo).html();
var precio=$(".item_price","#"+codigo).html();
var red =$(".item_red","#"+codigo).html();
var paquete =$(".item_paquete","#"+codigo).html();

var cantidad=1;

for(e=1;e<=cant;e++){
	if(localStorage.getItem("codigo"+e) == codigo){
		cant=e;
		cantidad=localStorage.getItem("cantidad"+e);
		cantidad=parseInt(cantidad)+1;
		break;
	}
}



 localStorage.setItem("Nombre"+cant, nombre);
 localStorage.setItem("precio"+cant, precio);
 localStorage.setItem("codigo"+cant, codigo);
 localStorage.setItem("cantidad"+cant, cantidad);

 localStorage.setItem("red"+cant, red);
 localStorage.setItem("paquete"+cant, paquete);

if(cant2==cant){
localStorage.setItem("cant", cant2);
$("#cant_produc").val(cant2);
}


llenarCart();
}



function llenarCart(){

var moneda=$("#moneda").val();
var data="";

var sigla="USD";
var mdolar=1;
var mvalor=1;
var cantPRo=0,TTotal=0;

	$.post('ajaxCarrito.php', {moneda: moneda}, function(data1) {
		
		data1=data1.split("||");

		 sigla=data1[0];
		 mdolar=data1[1];
		 mvalor=data1[2];


var cant= localStorage.getItem("cant");
for(e=1;e<=cant;e++){

var Nombre= localStorage.getItem("Nombre"+e);
var precio=	localStorage.getItem("precio"+e);
var codigo=	localStorage.getItem("codigo"+e);

var cantidad= localStorage.getItem("cantidad"+e);

var red =localStorage.getItem("red"+e);
var paquete =localStorage.getItem("paquete"+e);

cantPRo=parseInt(cantPRo)+parseInt(cantidad);
TTotal=parseInt(TTotal)+(cantidad*precio);


data+="<tr><td>"+codigo+"";

data+="<input type='hidden' name='item_name_"+e+"' value='"+codigo+"'>";
data+="<input type='hidden' name='item_quantity_"+e+"' value='"+cantidad+"'>";
data+="<input type='hidden' name='item_price_"+e+"' value='"+precio+"'>";
data+="<input type='hidden' name='item_paquete_"+e+"' value='"+paquete+"'>";
data+="<input type='hidden' name='item_red_"+e+"' value='"+red+"'>";

data+="</td><td>"+Nombre+"</td><td style='text-align: right;'>"+sigla+" "+((precio/mdolar)*mvalor).toFixed(2)+"</td>";

if(cantidad>1){
data+="<td><center><a href='javascript: sumar("+e+", 2)' class='entypo-minus-circled'></a> "+cantidad+" <a href='javascript: sumar("+e+", 1)' class='entypo-plus-circled'></a></center></td>";
}else{
data+="<td><center>"+cantidad+" <a href='javascript: sumar("+e+", 1)' class='entypo-plus-circled'></a></center></td>";
}

data+="<td style='text-align: right;'>"+sigla+" "+(((cantidad*precio)/mdolar)*mvalor)+"</td>";
data+="<td><center><input type='button' class='btn btn-red btn-sm' value='Eliminar' onclick='borrar("+e+")'><center></td></tr>";
}

 	

 	$(".quantity").html(cantPRo);
 	$(".total").html(sigla+" "+TTotal.toFixed(2));

$("#verProductos").html(data);



	});
	
}

function sumar(e, est){
	var cantidad=localStorage.getItem("cantidad"+e);
	if(est==1){
		
 		localStorage.setItem("cantidad"+e, parseInt(cantidad)+1);
	 }else{

 		localStorage.setItem("cantidad"+e, parseInt(cantidad)-1);
	 }


 	llenarCart();
}

function borrar(e){
alert("En proceso.");
	/*localStorage.removeItem("Nombre"+e);
	localStorage.removeItem("precio"+e);
	localStorage.removeItem("codigo"+e);
	localStorage.removeItem("cantidad"+e);

	llenarCart();
	*/
}


function storageclear(){
	localStorage.clear();
	$("#cant_produc").val('0');
	llenarCart();
}