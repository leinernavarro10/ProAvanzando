function paso(step){
  if(step=='2' || step=='3'){
    if(validateStep1()){
      paso2(step);
    }
  }else if(step=='5'){
    
    if(validateStep3() && validateStep1()){
      paso2(step);
    }
  }else{
    paso2(step)
  } 
 
  }

function paso2(step){
    $(".step").hide('fast');
    $("#step-"+step).show('slow');
    if(step=='5'){resumen()}
      if(step=='4'){verArchivos()}
}  

function verArchivos() {
  var id=$("#id").val();
  $("#dvdVerArchivo").html("<center>Cargando...</center>")
  $.ajax({
    url: 'ajaxVerArchivos.php',
    type: 'POST',
    dataType: 'html',
    data: {id: id},
  })
  .done(function(data) {
    
    $("#dvdVerArchivo").html(data);
    
  });
  
  
  
}




function resumen(){
	document.getElementById('ridentificacion').innerHTML = document.getElementById('identificacion').value;	
	document.getElementById('rnombre').innerHTML = document.getElementById('apellidos').value+' '+document.getElementById('nombre').value;	
	document.getElementById('redad').innerHTML = document.getElementById('edad').value;	
	document.getElementById('rtelefono').innerHTML = document.getElementById('telefono').value;	
	document.getElementById('remail').innerHTML = document.getElementById('email').value;	
	var vargenero = '';
	switch (document.getElementById('genero').value){
		case '2':	{
			vargenero = 'Masculino';
		break;}
		case '1': {
			vargenero = 'Femenino';
		break;}
	}
	var varcivil = '';
	switch (document.getElementById('estadocivil').value){
		case '1':	{
			varcivil = 'Casado';
		break;}
		case '2': {
			varcivil = 'Soltero';
		break;}
		case '3': {
			varcivil = 'Divorciado';
		break;}
		case '4': {
			varcivil = 'Conviviente';
		break;}
	}
	
	document.getElementById('rgenero').innerHTML = vargenero;	
	document.getElementById('restadocivil').innerHTML = varcivil;	
	//document.getElementById('rdireccion').innerHTML = document.getElementById('direccion').value;	
	document.getElementById('rndep').innerHTML = document.getElementById('ndep').value;
	document.getElementById('rfdep').innerHTML = document.getElementById('fdep').value;
	document.getElementById('rmontodep').innerHTML = document.getElementById('montodep').value;	
}

function changeCase(id) {

frmObj = document.getElementById(id);
var index;
var tmpStr;
var tmpChar;
var preString;
var postString;
var strlen;
tmpStr = frmObj.value.toLowerCase();
strLen = tmpStr.length;
if (strLen > 0)  {
for (index = 0; index < strLen; index++)  {
if (index == 0)  {
tmpChar = tmpStr.substring(0,1).toUpperCase();
postString = tmpStr.substring(1,strLen);
tmpStr = tmpChar + postString;
}
else {
tmpChar = tmpStr.substring(index, index+1);
if (tmpChar == " " && index < (strLen-1))  {
tmpChar = tmpStr.substring(index+1, index+2).toUpperCase();
preString = tmpStr.substring(0, index+1);
postString = tmpStr.substring(index+2,strLen);
tmpStr = preString + tmpChar + postString;
         }
      }
   }
}
frmObj.value = tmpStr;
}  
    
function validateStep1(){
       var isValid = true; 

       // Validate Username

       var un = $('#nombre').val();
       if(!un && un.length <= 0){
         isValid = false;
         $('#msg_username').html('Por favor digite el nombre').show();
       }else{
         $('#msg_username').html('').hide();
       }
     
     var un = $('#identificacion').val();
       if(!un && un.length <= 0){
         isValid = false;
         $('#msg_identificacion').html('Por favor digite el número de identificación').show();
       }else{
         $('#msg_identificacion').html('').hide();
       }

       // validate password

       var pw = $('#apellidos').val();
       if(!pw && pw.length <= 0){
         isValid = false;
         $('#msg_password').html('Por favor digite los apellidos').show();         
       }else{
         $('#msg_password').html('').hide();
       }

       // validate confirm password

       var cpw = $('#telefono').val();
       if(!cpw && cpw.length <= 0){
         isValid = false;
         $('#msg_cpassword').html('Por favor ingrese teléfono').show();         
       }else{
         $('#msg_cpassword').html('').hide();
       }  
       
     var email = $('#email').val();
       if(email && email.length > 0){
         if(!isValidEmailAddress(email)){
           isValid = false;
           $('#msg_email').html('El email no es válido').show();           
         }else{
          $('#msg_email').html('').hide();
         }
       }else{
         //isValid = false;
         //jQuery('#msg_email').html('Por favor digite el email').show();
       } 
       // validate password match

       return isValid;
    }


    

    function validateStep3(){

      var isValid = true;    

      //validate email  email

     var cpw = jQuery('#ndep').val();
       if(!cpw && cpw.length <= 0){
         isValid = false;
         jQuery('#msg_ndep').html('Por favor ingrese el numero de deposito').show();         
       }else{
         jQuery('#msg_ndep').html('').hide();
       } 
     
     var cpw = jQuery('#fdep').val();
       if(!cpw && cpw.length <= 0){
         isValid = false;
         jQuery('#msg_fdep').html('Por favor ingrese la fecha del deposito').show();         
       }else{
         jQuery('#msg_fdep').html('').hide();
       }    
     
     var cpw = jQuery('#montodep').val();
       if(!cpw && cpw.length <= 0){
         isValid = false;
         jQuery('#msg_montodep').html('Por favor ingrese el monto del deposito').show();         
       }else{
         jQuery('#msg_monto').html('').hide();
       }      

      return isValid;

    }
    // Email Validation

    function isValidEmailAddress(emailAddress) {

      var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);

      return pattern.test(emailAddress);
    } 
  
	

	
	function calcular_edad(fecha){
   	//calculo la fecha de hoy 
   	hoy= new Date();
   	//alert(hoy) 
   	//calculo la fecha que recibo 
   	//La descompongo en un array 
   	var array_fecha = fecha.split("/") 
   	//si el array no tiene tres partes, la fecha es incorrecta 
   	if (array_fecha.length!=3) 
      	 return false 
   	//compruebo que los ano, mes, dia son correctos 
   	var ano 
   	ano = parseInt(array_fecha[2]); 
   	if (isNaN(ano)) 
      	 return false 
   	var mes 
   	mes = parseInt(array_fecha[1]); 
   	if (isNaN(mes)) 
      	 return false 
   	var dia 
   	dia = parseInt(array_fecha[0]);	
   	if (isNaN(dia)) 
      	 return false 
   	//resto los años de las dos fechas 

   	edad=hoy.getFullYear()- ano - 1; //-1 porque no se si ha cumplido años ya este año 
   	//si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido 
   	if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0 
      	 return edad 
   	if (hoy.getMonth() + 1 - mes > 0) 
      	 return edad+1 
   	//entonces es que eran iguales. miro los dias 
   	//si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido 
   	if (hoy.getUTCDate() - dia >= 0) 
      	 return edad + 1 
   	return edad 
}
function fnedad(edad){

	if (calcular_edad(edad)){
    if(calcular_edad(edad)>=16){
      if(calcular_edad(edad)<18){
	      $('#edadCont').removeClass('esconder');
      }else{
        $('#edadCont').addClass('esconder');
      }
      document.getElementById('edad').value = calcular_edad(edad);
    }else{
      $('#edadCont').addClass('esconder');
      document.getElementById('fnac').value ="";
      document.getElementById('edad').value ="";
      alert("Error de edad. La persona a afiliar debe ser mayor a 16 años.");
    }
  }
}

$(function($){
  $("#identificacion").mask("9-9999-9999");
	$("#fnac").mask("99/99/9999");
	$("#fdep").mask("99/99/9999");
});

function fnnacional(este){

  if (este.value == 1){
    $("#identificacion").mask("9-9999-9999");
    document.getElementById('identificacion').value = "";
    $("#lblNombre").html("Nombre");
    $("#lblApellido").html("Apellidos");
   $("#dvdGenero").show('slow');
   $("#dvdfecha").show('slow');
  }else if(este.value == 2){

    $("#identificacion").mask("9-999-999999");
    $("#lblNombre").html("Nombre Empresa");
    $("#lblApellido").html("Razón social");

    $("#dvdGenero").hide('slow');
    $("#dvdfecha").hide('slow');
    
  }else{
    $("#identificacion").unmask("");
    $("#lblNombre").html("Nombre");
    $("#lblApellido").html("Apellidos");
$("#dvdGenero").show('slow');
$("#dvdfecha").show('slow');

    document.getElementById('identificacion').value = "";
  }
  document.getElementById('identificacion').focus();
}



