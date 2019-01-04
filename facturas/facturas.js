var cantidadCks = 0;
var cantidadGenerados = 0;
var myInterval = "";

function agregarCk(ck){
	if (ck.checked){
	  cantidadCks++;
	}else{
	  cantidadCks--;
	}
}

function verificarFE(numeroFactura){
  var idFactura = $("#idFactura"+numeroFactura).val();
  var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+idFactura+'&tipo=2" style="width: 850px; height: 150px;"></iframe>';
  document.getElementById('iframes').innerHTML+=iframe; 
  var old = $("#estadoFE-"+numeroFactura).html();
  $("#estadoFE-"+numeroFactura).html("<img src='../assets/images/cargando.gif'> VERIFICANDO...");
  jQuery.post( "funcionesAJAXFacturas.php", { f: 'VERIFICAR', numeroFactura: numeroFactura})
      .done(function( data ) {
        console.log(data);
        if (data.indexOf('<br />') >= 0){
        	alert("No se pudo establecer comunicación con el servidor de hacienda. \nIntentelo más tarde por favor.");
        	$("#estadoFE-"+numeroFactura).html(old);
        }else{
	        if (data.indexOf('[http_code] => 400') >= 0){
	          $("#estadoFE-"+numeroFactura).html('No enviada');
	        }else if (data.indexOf('404</h2>') >= 0){
	          $("#estadoFE-"+numeroFactura).html('No enviada');
	        }else{
	          var estado = "";
	          var resp = JSON.parse(data);
	          if (resp['ind-estado'] == 'aceptado'){
	            $("#estadoFE-"+numeroFactura).html('Aceptada');
	            estado = 1;
	          }else if (resp['ind-estado'] == 'rechazado'){
	            $("#estadoFE-"+numeroFactura).html('Rechazada');
	            estado = 3;
	          }else{
	            $("#estadoFE-"+numeroFactura).html('Procesando');
	            estado = 2;
	          }
	          jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: estado, xmlRespuesta: resp['respuesta-xml']})
	          .done(function( data ) {
           
              if (estado == 1){
                var emailNC = $("#email"+numeroFactura).val();
                var idFactura = $("#idFactura"+numeroFactura).val();
              ids = '<?php echo $idFactura; ?>-';
 
                if (emailNC != ''){
                 

                  jQuery.ajax({
                    type: "POST",
                    url: "sendEmail.php",
                    data: { 
                       pre: 'f',
                       NC: '0',
                       id: idFactura,
                       email: emailNC,
                       mensaje: 'Saludos. Adjunto encontrará los documentos relacionados con el Documento Electrónico con el número: '+numeroFactura
                    }
                  }).done(function(msg) {
                    console.log(msg);
                    //document.getElementById('cancelarResponsiveCorreo').click();
                  });
                }
              }

            });
	        }
    	}
    });
}

  function mostrarEnviar(){
    newprogress = 0;
    document.getElementById("barraPro").style.display = "none";
    jQuery('#progreso').width(newprogress+"%").attr('aria-valuenow', newprogress+"%");
    if (cantidadCks == 0){
      alert('Por favor seleccione al menos una factura para poder continuar.');
    }else{
      jQuery('#modalEnviarCorreo').modal('show', {backdrop: 'static'});
    }
  }

  function enviarEmail(){
    var email = document.getElementById('enviarEmailEmail').value;
    var mensaje = document.getElementById('enviarEmailMensaje').value;
    if (email == ""){
      miAlerta("Debe digitar una dirección de correo electrónico válida.",1);
    }else{
      if (email.indexOf('@',0) == -1 || email.indexOf('.',0) == -1){
        miAlerta("Debe digitar una dirección de correo electrónico válida.",1);
      }else{
        ids = '';
        jQuery(".ck").each(function(){
           if(jQuery(this).is(':checked')){
              ids += jQuery(this).val()+'-';
           }
        });

        generar('f',ids, email, mensaje);
      }
    }
  }

  function generar(pre,ids, email, mensaje){
    
    aumento = (75/cantidadCks);
    newprogress = 0;
    jQuery('#progreso').width("0%").attr('aria-valuenow', "0%");
    document.getElementById("barraPro").style.display = "block";
    document.getElementById('iframes').innerHTML = "";
    idP = ids.split("-");
    var x = 0;
    for (x = 0; x < idP.length; x++){
      if (idP[x] != ''){
        var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+idP[x]+'&tipo=2" style="width: 850px; height: 150px;"></iframe>';
        document.getElementById('iframes').innerHTML+=iframe; 
        newprogress += aumento;
        jQuery('#progreso').width(newprogress+"%").attr('aria-valuenow', newprogress+"%");
      }
    }
    myInterval = setInterval(function(){ verificarGenerados(ids, email, mensaje) }, 1000);
  }

  function verificarGenerados(ids, email, mensaje){
    console.log(cantidadGenerados+'-'+cantidadCks);
    if (cantidadGenerados >= cantidadCks){
      jQuery('#progreso').width("100%").attr('aria-valuenow', "100%");
      enviarCorreo("f", ids, email, mensaje);

      clearInterval(myInterval);
      cantidadGenerados = 0;
    }
  }

  function aumentarGenerados(){
    cantidadGenerados++;
  }


  function enviarCorreo(pre, ids, email, mensaje){
      jQuery.ajax({
        type: "POST",
        url: "sendEmail.php",
        data: { 
           pre: pre,
           id: ids,
           email: email,
           mensaje: mensaje
        }
      }).done(function(msg) {
        alert(msg);
        document.getElementById('cancelarResponsive').click();
      });
}
  

function previewProforma(id){
  jQuery('#modal-8').modal('show', {backdrop: 'static'});
  
  document.getElementById("frameCuentasPagar").src = 'previewFactura.php?idProforma='+id;
}


function previewXML(xml,tipo){
  jQuery('#modal-9').modal('show', {backdrop: 'static'});
  
  document.getElementById("frameXML").src = 'previewXML.php?xml='+xml+'&tipo='+tipo;
}


///NOTA DE CREDITO

function mostrarCerrar(numeroFactura, idFactura, email, tipoDocFE, consecutivo){
    $("#emailNC").val("");
    $("#numeroFactura").val(numeroFactura);
    $("#idFactura").val(idFactura);
    $("#emailNC").val(email);

    if (tipoDocFE == "01"){
      $("#tipoDocRef").html('Factura Electrónica<br><span style="font-size: 14px">'+consecutivo+'</span>');

    }else if(tipoDocFE == "04"){
      $("#tipoDocRef").html('Tiquete Electrónico<br><span style="font-size: 14px">'+consecutivo+'</span>');
    }
    //d0ocument.getElementById('enviarEmailEmail').value = document.getElementById('email').value;
    jQuery('#modalMostrarCerrar').modal('show', {backdrop: 'static'});
}

function anular(numeroFactura, idFactura, ori){
  var tipoDoc = $("#tipoDoc").val();

	console.clear();
	$("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Generando XML.</center>");

	$.post("funcionesAJAXFacturas.php", { f: 'GENERAR-XML-NC', numeroFactura: numeroFactura, idFactura: idFactura, tipoDoc: tipoDoc})
	  .done(function(data) {
     // alert(data);
	    if (data == 1){
	      firmarXML(numeroFactura, idFactura);
	    }else if (data == 2){
	      location = location;  
	    }
	}).fail(function(error) {
      alert( "Error" );
   });

}

function firmarXML(numeroFactura, idFactura){
  console.log(numeroFactura);
  $("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Firmando archivo XML.</center>");
  jQuery.post( "funcionesAJAXFacturas.php", { f: 'FIRMAR-XML-NC', numeroFactura: numeroFactura})
      .done(function( data ) {
          //console.log(data);
          token(numeroFactura, idFactura);
    });
}

function token(numeroFactura, idFactura){
  $("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Solicitando token de acceso a hacienda.</center>");
  jQuery.post( "funcionesAJAXFacturas.php", { f: 'OBTENER-TOKEN'})
      .done(function( token ) {
      	if (token.indexOf('<br />') >= 0){
          	alert("No se pudo obtener comunicación con el servidor de hacienda. \nPor favor inténtelo más tarde.");
          	jQuery('.cerrarModal').click();
  	    }else{
  	  		enviarFactura(numeroFactura, token, idFactura);
  	    }
        console.log(token);
    });
}

function enviarFactura(numeroFactura, token, idFactura){
  ids = idFactura+'-';
  var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+idFactura+'&tipo=2&nu=1" style="width: 850px; height: 150px;"></iframe>';
  document.getElementById('iframes').innerHTML+=iframe; 

  $("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Enviando factura a hacienda.</center>");
  jQuery.post( "funcionesAJAXFacturas.php", { f: 'ENVIAR-FACTURA-NC', numeroFactura: numeroFactura, token: token})
      .done(function( data ) {
        
        if (data != "error"){
      $("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> VERIFICANDO...</center>");
	    	jQuery.post( "funcionesAJAXFacturas.php", { f: 'VERIFICAR-NC', numeroFactura: numeroFactura})
	        .done(function( data ) {
	          if (data.indexOf('404</h2>') >= 0){
	//            $("#estadoFE-"+numeroFactura).html('No enviada');
	        	alert("No se pudo obtener comunicación con el servidor de hacienda. \nPor favor inténtelo más tarde.");
	        	jQuery('.cerrarModal').click();
	        	$("#botoneraFinalizar").html('<table width="100%"><tr><button type="button" class="btn btn-red"  id="cancelarResponsiveProductos" onclick="anular($(\'#numeroFactura\').val(),$(\'#idFactura\').val())">Anular</button><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td></tr></table>');
	          }else{
	            console.log(data);
	            mensajeExito("La factura se finalizó exitosamente.",tipo = 1);  
	        	$("#botoneraFinalizar").html('<table width="100%"><tr><button type="button" class="btn btn-red"  id="cancelarResponsiveProductos" onclick="anular($(\'#numeroFactura\').val(),$(\'#idFactura\').val())">Anular</button><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td></tr></table>');
	          	jQuery('.cerrarModal').click();

	            var estado = "";
	            var resp = JSON.parse(data);
	            if (resp['ind-estado'] == 'aceptado'){
	//              $("#estadoFE-"+numeroFactura).html('Aceptado');
	              estado = 1;
	            }else if (resp['ind-estado'] == 'rechazado'){
	//              $("#estadoFE-"+numeroFactura).html('Rechazada');
	              estado = 3;
	            }else{
	//              $("#estadoFE-"+numeroFactura).html('Procesando');
	              estado = 2;
	            }
	            jQuery.post( "funcionesAJAXFacturas.php", { f: 'ELIMINARFACTURA', numeroFactura: numeroFactura, estado:estado, xmlRespuesta: resp['respuesta-xml']})
	            .done(function( data ) {

                if (estado == 1){
                  var emailNC = $("#email"+numeroFactura).val();
                  var idFactura = $("#idFactura"+numeroFactura).val();

                  if (emailNC != ''){
                    jQuery.ajax({
                      type: "POST",
                      url: "sendEmail.php",
                      data: { 
                         pre: 'f',
                         NC: '1',
                         id: idFactura,
                         email: emailNC,
                         mensaje: 'Saludos. Adjunto encontrará los documentos relacionados con la Nota de Crédito Electrónica que anula la Factura con el número: '+numeroFactura
                      }
                    }).done(function(msg) {
                      console.log(msg);
                      document.location.href='facturasAnuladas.php';
                     
                    });
                  }
                }else{
                  document.location.href='facturasAnuladas.php';
                }
	            });
	          }
	      });
    	}else{
    		alert("No se pudo obtener comunicación con el servidor de hacienda. \nPor favor inténtelo más tarde.");
    		$("#botoneraFinalizar").html('<table width="100%"><tr><button type="button" class="btn btn-red"  id="cancelarResponsiveProductos" onclick="anular($(\'#numeroFactura\').val(),$(\'#idFactura\').val())">Anular</button><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td></tr></table>');
    		jQuery('.cerrarModal').click();

    	} 
    });
}

///////// REENVIO DE FACTURA ELECTRONICA

function tokenR(numeroFactura){
	$("#estadoFE-"+numeroFactura).html('<img src="../assets/images/cargando.gif"> Token...');
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'OBTENER-TOKEN'})
      .done(function( token ) {
      	if (token.indexOf('<br />') >= 0){
      		jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: 4, xmlRespuesta: ""})
	          .done(function( data ) {
	          	alert("No se pudo obtener comunicación con el servidor de hacienda. \nEl documento se guardará y será enviado luego.");
	          	$("#estadoFE-"+numeroFactura).html('<a href="javascript: void(0);" onclick="tokenR(\''+numeroFactura+'\')">No enviada</a>');
	          });
  	    }else{
  	  		enviarFacturaR(numeroFactura, token);
  	    }
      	//console.log(token);
    });
}


function enviarFacturaR(numeroFactura, token){
	ids = '<?php echo $idFactura; ?>-';
  var emailNC = $("#email"+numeroFactura).val();
  if(emailNC!=""){
    var idFactura = $("#idFactura"+numeroFactura).val();
	  var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+idFactura+'&tipo=2" style="width: 850px; height: 150px;"></iframe>';
    document.getElementById('iframes').innerHTML+=iframe; 
  }
	$("#estadoFE-"+numeroFactura).html('<img src="../assets/images/cargando.gif"> Enviando...');
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'ENVIAR-FACTURA', numeroFactura: numeroFactura, token: token})
      .done(function( data ) {

      	console.log(data);
      	if (data != "error"){
//  	  $("#estadoFE-"+numeroFactura).html("VERIFICANDO...");
	  jQuery.post( "funcionesAJAXFacturas.php", { f: 'VERIFICAR', numeroFactura: numeroFactura})
	      .done(function( data ){
	        if (data.indexOf('[http_code] => 400') >= 0){
          		alert('Ocurrió un error.');
        	}else if (data.indexOf('404</h2>') >= 0){
//	          $("#estadoFE-"+numeroFactura).html('No enviada');
			  jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: 4, xmlRespuesta: ""})
	          .done(function( data ) {
	          	alert("No se pudo establecer comunicación con el servidor de Hacienda. \nIntentelo más tarde.");
	          	$("#estadoFE-"+numeroFactura).html('<a href="javascript: void(0);" onclick="tokenR(\''+numeroFactura+'\')">No enviada</a>');
	          	//imprimir();
	          });
	        }else{
	          //mensajeExito("La factura se finalizó exitosamente.",tipo = 1);  

		        var estado = "";
	          var resp = JSON.parse(data);
	          if (resp['ind-estado'] == 'aceptado'){
	          	$("#estadoFE-"+numeroFactura).html('Aceptada');
	            estado = 1;
	          }else if (resp['ind-estado'] == 'rechazado'){
	          	$("#estadoFE-"+numeroFactura).html('Rechazada');
	            estado = 3;
	          }else{
	          	$("#estadoFE-"+numeroFactura).html('Procesando');
	            estado = 2;
	          }
	          jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: estado, xmlRespuesta: resp['respuesta-xml']})
	          .done(function( data ) {
	          	
              if (estado == 1){
                var emailNC = $("#email"+numeroFactura).val();
                var idFactura = $("#idFactura"+numeroFactura).val();

                if (emailNC != ''){
                  jQuery.ajax({
                    type: "POST",
                    url: "sendEmail.php",
                    data: { 
                       pre: 'f',
                       NC: '0',
                       id: idFactura,
                       email: emailNC,
                       mensaje: 'Saludos. Adjunto encontrará los documentos relacionados con el Documento Electrónico con el número: '+numeroFactura
                    }
                  }).done(function(msg) {
                    
                    console.log(msg);
                    //document.getElementById('cancelarResponsiveCorreo').click();
                  });
                }
              }

	          });
	        }
	    });
	    }else{
    		alert("No se pudo establecer comunicación con el servidor de Hacienda. \nIntentelo más tarde.");
    		$("#estadoFE-"+numeroFactura).html('<a href="javascript: void(0);" onclick="tokenR(\''+numeroFactura+'\')">No enviada</a>');
    	} 
    });
}

function domo(){
  //CERRAR MODALES
  $(document).bind('keydown.esc', function(){
   jQuery('.cerrarModal').click();
   return false;
  });
}