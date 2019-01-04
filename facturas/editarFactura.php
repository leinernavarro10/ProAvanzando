<?php include("../conn/connpg.php");

$idFactura = $_GET['id'];

$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)){
      	$tipoFacturaParametro = $row['facturaGrafica'];
 		$tamanoFactura = $row['tamanoFactura'];
       	$facturaElectronica = $row['facturaElectronica'];
}


?>	
<?php $pagina="Editar Factura"; include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/bodyLimpio.php");?>
<script type="text/javascript">
	function resizeIframe(obj){
	
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	}
</script>

<div class="row">
<a href="../facturas/" class="btn btn-red pull-right" style="margin-right: 17px; margin-bottom: 10px;"><i class="entypo-cancel-circled"></i></a>
<a href="../facturas/?n=1" class="btn btn-primary pull-right" style="margin-right: 17px; margin-bottom: 10px;"><i class="entypo-plus"></i> Nueva factura</a>
</div>
<!-- INICIA CONTENIDO -->
<?php include("../includes/alerts.php");?>

<style type="text/css">
	.btn {
		margin-top: 3px;
	}
	.tr2{
        background-color: #C5C5C5;
        color: #000000;
    } 

    .trb:hover td{
		
		cursor: pointer;
    }
    
    
    .custom-width2{
    	width: 85%;
    }
    @media(max-width : 794px){
    .custom-width2{
        width: 97%;
    }
    .esconderPR{
    	display: none;
    }   
}


</style>

<div class="row">

			<?php 
			$sql = "SELECT * FROM tfacturas WHERE id = '$idFactura' AND sistema = '".$cuentaid."' ";
			$query = mysql_query($sql, $conn);
			if(mysql_num_rows($query)>0){
			while ($row=mysql_fetch_assoc($query)) {
				$estadoFE = $row['estadoFE'];

				$estado = $row['estado'];
				if ($estado != 1){
					$disabled = 'disabled="disabled"';
					$disabled2 = '';
				}else{
					$disabled = '';
					$disabled2 = 'disabled="disabled"';
				}
				if ($row['numero'] == ''){
							$numeroFactura = 0;	
						}else{
							$numeroFactura = $row['numero'];
						}
						$estadoFactura = $row['estado'];
			?>
			
				<div id="iframe" class="col-md-9">
			
				
					<iframe id="productosProforma" src="productosFactura.php?idProforma=<?php echo $idFactura?>&tipoCambio=<?php echo $tipoCambio?>&estado=<?php echo $estadoFactura?>" frameborder="0" style="width: 100%;" ></iframe>
					
					
				
			</div>
			
	

			<div id="datosCliente" class="col-md-3" >
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" style="background: #570101;color: #FFFFFF">
						<div class="panel-title">Cliente (F4)</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						
						<div id="ocultar">

						<p><center>
							<?php if($row['estado']!="3"){?>
						Número de factura:<?php }else{echo "<font color='#730000' style='font-weight:700;font-size:16px'>Factura Anulada</font>";}?> <br>
						<span class="numeroFactura">
						<?php
						
			  			echo addZero($numeroFactura);
			  			

						?></span>
						</center>
						<style type="text/css">
							.numeroFactura {
								font-size: 36px;
								color: #E20000;
							}
						</style>

						<form role="form" action="" class="form-horizontal" method="post" name="formulario" id="formulario">
							<input type="hidden" name="tipoFacturaText" id="tipoFacturaText" value="">
							
						

							<input type="hidden" name="idFactura" id="idFactura" value="<?php echo $row['id']?>">
							<input type="hidden" name="idCliente" id="idCliente" value="<?php echo $row['idCliente']?>">
							<div class="form-group">
									
								<div class="col-sm-12">
								
									<div class="input-group">
										<input type="text" placeholder="Cliente" required="required" onkeyup="abrirbuscarCliente(this.value,event);" value="<?php echo $row['nombreCliente']?>" onclick="$('#cliente').select()" <?php echo $disabled?> id="cliente" name="cliente" class="form-control">
										<span class="input-group-btn"><button onclick="mostrarBusqueda('')" <?php echo $disabled?> class="btn btn-primary" type="button"><i class="entypo-search"></i></button></span>
									</div>
									
								</div>
							</div>
									
							<div class="form-group">
								<div class="col-sm-12">
									Email:
									<input type="text" class="form-control" <?php echo $disabled?> value="<?php echo $row['email']?>" placeholder="Email" name="email" autocomplete="off" id="email">
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									Plazo crédito: <i>(Solo para facturas de crédito)</i>
									<input type="text" class="form-control" <?php echo $disabled?> value="<?php if ($row['plazo'] == ""){echo '30';}else{echo $row['plazo'];}?>" name="plazo" autocomplete="off" id="plazo">
								</div>
							</div>

							<div class="form-group">
								
								<div class="col-sm-12">
									<div class="checkbox checkbox-replace color-primary">
										<?php 
										if ($row['exonerar'] == 1){
											$check = 'checked="checked"';
										}else{
											$check = '';
										}

										?>
										<input type="checkbox" <?php echo $disabled?> id="exonerar" <?php echo $check?> name="exonerar" value="1">
										<label> Exonerar
									</div>
								</div>
							</div>
							<div class="form-group" style="display: none">
															
								<div class="col-sm-12">
									<input type="text" <?php echo $disabled?> placeholder="Descuento"  value="<?php echo $row['descuento']?>" class="form-control" name="descuento" autocomplete="off" id="descuento">
								</div>
							</div>
							<input type="hidden" name="vendedor" value="<?php echo $userid?>">
							


							<div class="form-group">
							<div class="col-sm-12">
									Caja: 
									<?php 
										$sql2 = "SELECT * FROM tsucursales WHERE estado = 1 AND sistema = '".$cuentaid."'";
										$query2 = mysql_query($sql2, $conn);
										if (mysql_num_rows($query2) == 0){
											echo '<center>No hay cajas.</center>';
										}else{?>
											<select id="caja" name="caja" class="form-control" <?php echo $disabled;?> >
											<?php while ($row2=mysql_fetch_assoc($query2)) {?>

												<optgroup label="<?php echo $row2['nombre'];?> :: #<?php echo $row2['id'];?>" title="<?php echo $row2['descripcion'];?>" >
													<?php $sql3 = "SELECT * FROM tcajas WHERE estado = 1 AND idSucursal='".$row2['id']."' AND sistema = '".$cuentaid."'";
													$query3 = mysql_query($sql3, $conn)or die(mysql_error());
													if(mysql_num_rows($query3)>0){
														while($row3=mysql_fetch_assoc($query3)) { ?>
														<option value="<?php echo $row3['id'];?>" title="<?php echo $row3['descripcion'];?>" ><?php echo $row3['nombre'];?></option>
														<?php
														}	
													 }else{?>
														<option value="" disabled>No hay cajas</option>
													<?php }?>
												</optgroup> 
											<?php }?>
											</select></center>
										<?php }
									?>
								</div>
							</div>

							<?php 
							$observacionFactura = "";
							$sql2 = "SELECT * FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
							$query2 = mysql_query($sql2, $conn);
							while ($row2=mysql_fetch_assoc($query2)) {
								$observacionFactura = $row2['observacion'];
							}
							?>
							<div class="form-group">
								<div class="col-sm-12">
									Observaciones: <i>(Solamente uso interno)</i>
									<textarea class="form-control" name="observacion" id="observacion" onblur="guardarObservacion(this.value)"><?php echo $observacionFactura?></textarea>
								</div>
							</div>
							

							</div> <!--div ocultar-->

							<center>
									<button id="btnGuardar" type="button" class="btn btn-primary" <?php echo $disabled?> name="guardar" onclick="guardarFactura()"><i class="entypo-floppy"></i></button>
									<button id="btnFinalizar" onclick="mostrarCerrar()" type="button" class="btn btn-primary" <?php echo $disabled?> name="finalizar"><i class="entypo-check"></i>Facturar</button>
									<button id="btnImprimir" onclick="imprimir()" type="button" class="btn btn-primary" <?php echo $disabled2?>><i class="entypo-print"></i></button>
									<button id="btnEmail" onclick="mostrarEnviar()" type="button" <?php echo $disabled2?> class="btn btn-primary " name="guardar"><i class="entypo-mail"></i></button>
									<button id="btnCerrar" type="button" class="btn btn-red" onclick="document.location.href='../facturas/'"><i class="entypo-cancel-circled"></i></button>
							</center>

						</form>
						</p>
						
					</div>
					
				</div>
			
			</div>
			<?php }

		}else{
			echo "<center>No se encuentra la factura</center>";
		}

			 ?>
			
			
		
		</div>


<style type="text/css">
	.btn {
		margin-top: 3px;
	}
</style>

<script type="text/javascript">
  <?php 
  if ($mensajeCC == 1){ ?>
  	alert("La factura ha sido enviada a cuentas por cobrar exitosamente.");
  <?php } ?>

  var cantidadCks = 1;
  var cantidadGenerados = 0;
  var myInterval = "";

var ventana_ancho = $(window).width();

if(ventana_ancho>=768){
	$(".page-container").addClass("sidebar-collapsed");

}else{
	$(".col-md-9").removeClass('col-md-9');
	$(".col-md-3").removeClass('col-md-3');
}
  


  function imprimir(){
<?php 
if ($tipoFacturaParametro == 0){ ?>


	document.getElementById('iframes').innerHTML = "";
  	var id = '<?php echo $idFactura?>';
  	var iframe = '<iframe id="generarDocumento" src="imprimirFacturaNoGrafico.php?idProforma='+id+'&tipo=1" style="width: 8cm; height: 150px;"></iframe>';
    document.getElementById('iframes').innerHTML+=iframe; 
<?php }else{ ?>
	document.getElementById('iframes').innerHTML = "";
  	var id = '<?php echo $idFactura?>';
  	<?php if ($tamanoFactura == 1){ ?>
  	
  		var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+id+'&tipo=1" style="width: 850px; height: 150px;"></iframe>';
    <?php }else{ ?>
  		var iframe = '<iframe id="generarDocumento" src="imprimirMediaFactura.php?idProforma='+id+'&tipo=1" style="width: 850px; height: 150px;"></iframe>';
    <?php } ?>
    document.getElementById('iframes').innerHTML+=iframe; 
<?php } ?>

  }


  function pasoMouseIframe(){
  	$("#datosCliente").removeClass("col-md-3");
  	$("#iframe").removeClass("col-md-9");
  	$("#datosCliente").addClass("col-md-1");
  	$("#iframe").addClass("col-md-11");
  	$("#ocultar").css("display", "none");
  	//$("#datosCliente").css("width", "125px");
  }


  function pasoMouseCliente(){
  	$("#datosCliente").removeClass("col-md-1");
  	$("#iframe").removeClass("col-md-11");
  	$("#datosCliente").addClass("col-md-3");
  	$("#iframe").addClass("col-md-9");
  	$("#ocultar").css("display", "block");
  	//$("#datosCliente").css("width", "auto");
  }

  function abrirbuscarCliente(value,event){
  	var tecla =event.which || event.keyCode;
  	if(tecla==38){
  		var numPR=$("#productosProforma").contents().find("#cantRG").val();
  		$("#productosProforma").contents().find("#4-"+numPR).focus().select();
  	}else{
  		mostrarBusqueda(value);
  	}
  }

  function mostrarBusqueda(value){
  	//alert(value);
    jQuery('#modalBusqueda').modal('show', {backdrop: 'static'});
    	$('#modalBusqueda').on('shown.bs.modal', function () {
  		//alert("leier");
  		 $(this).find('#txtBuscar').focus();
  		 //$("#txtBuscar").val(value);
	  		if(strpos($value, 'Estimado cliente') !== false){
	    		//$("#txtBuscar").val(value);
			}
    });
    $("#txtBuscar").val(value);
    buscarCliente2();

  }
var num2=0;
  function buscarCliente(event){
  	var tecla =event.which || event.keyCode;
  	
  	if (event.ctrlKey && event.keyCode === 32) {
      $('#btnFinalizar').click();
     
    }else if(tecla==40){
    	//abajo
    	if(num2<$("#cantRG2").val()){
    		num2++;
    		$("#trCL"+(num2-1)).removeClass('tdbselect2');
    		$("#trCL"+num2).addClass('tdbselect2');
    	}
    }else if(tecla==38){
    	//arriba
    	
    	if(num2>1){
    		num2--;
    		$("#trCL"+(num2+1)).removeClass('tdbselect2');
    		$("#trCL"+num2).addClass('tdbselect2');
    	}
    }else if(tecla==13){
    	$(".tdbselect2").click();
    }else{
		buscarCliente2();
	}
  }

function buscarCliente2(){
	num2=0;
	  	var txtBuscar = $("#txtBuscar").val();
	  	jQuery.post( "funcionesAJAXGeneral.php", {f: 'BUSCAR-CLIENTE', txtBuscar: txtBuscar})
	    .done(function( data ) {
	       jQuery('#resultadoBusqueda').html(data);
	    });
}

  function cargarCliente(id,nombre,email){
  	jQuery('#resultadoBusqueda').html('<center><i class="entypo-info"></i><br>Por favor digite el nombre de un cliente.</center>');
  	$("#txtBuscar").val(nombre);
  	$("#cliente").val(nombre);
  	$("#email").val(email);
  	$("#idCliente").val(id);
  	$('#cancelarResponsive').click();
  	guardarFactura();
  	$("#productosProforma").contents().find("#cantidad2").focus().select();
  }

  function mostrarEnviar(){
  	document.getElementById('enviarEmailEmail').value = document.getElementById('email').value;
    newprogress = 0;
    document.getElementById("barraPro").style.display = "none";
    jQuery('#progreso').width(newprogress+"%").attr('aria-valuenow', newprogress+"%");
    jQuery('#modalEnviarCorreo').modal('show', {backdrop: 'static'});
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
        ids = '<?php echo $idFactura; ?>-';
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
  	//console.log(cantidadGenerados);
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
        document.getElementById('cancelarResponsiveCorreo').click();
      });
}

  function mostrarBuscarProducto(val){
  	$('#modalBuscarProducto').modal();
  	$('#modalBuscarProducto').on('shown.bs.modal', function () {
  		//alert("leier");
  		 $(this).find('#txtProducto').focus();
  		 $("#txtProducto").val(val);
    });
   	
   	num=0;
  }

  function buscarProducto(event){

    var tecla =event.which || event.keyCode;

    if(tecla==40){
    	//abajo
    	if(num<$("#cantRG1").val()){
    		num++;
    		$("#trb"+(num-1)).removeClass('tdbselect');
    		$("#trb"+num).addClass('tdbselect');
    	}
    }else if(tecla==38){
    	//arriba
    	
    	if(num>1){
    		num--;
    		$("#trb"+(num+1)).removeClass('tdbselect');
    		$("#trb"+num).addClass('tdbselect');
    	}
    }else if(tecla==13){
    	$(".tdbselect").click();
    }else{
    	num=0;
	  	var txtBuscar = $("#txtProducto").val();
	  	$.post( "funcionesAJAXGeneral.php", {f: 'BUSCAR-PRODUCTO', txtBuscar: txtBuscar})
	    .done(function( data ) {
	       jQuery('#resultadoBusquedaProductos').html(data);
	    });	
	}
  }

 function cargarProducto(id,codigo){
  	valor = codigo;

	if(ventana_ancho>=768){
		
  	$("#productosProforma").contents().find("#codigo2").val(valor);
  	$("#productosProforma").contents().find("#codigo2").focus();
  	$('#resultadoBusquedaProductos').html('<center><i class="entypo-info"></i><br>Por favor digite el nombre o la descripcion del producto.</center>');
  	$("#txtProducto").val("");
  	$('#cancelarResponsiveProductos').click();
  	$("#productosProforma").contents().find("#agregar").click();

	}else{
		
  	$("#productosProforma").contents().find("#codigo").val(valor);
  	$("#productosProforma").contents().find("#codigo").focus();
  	$('#resultadoBusquedaProductos').html('<center><i class="entypo-info"></i><br>Por favor digite el nombre o la descripcion del producto.</center>');
  	$("#txtProducto").val("");
  	$('#cancelarResponsiveProductos').click();
  	$("#productosProforma").contents().find("#agregar2").click();

	}

  }


  function mostrarCerrar(){
  	//d0ocument.getElementById('enviarEmailEmail').value = document.getElementById('email').value;
  	$('#emailFE').val($("#email").val());
  	$('#modalMostrarCerrar').modal('show');
  	$('#modalMostrarCerrar').on('shown.bs.modal', function () {
  		 $(this).find('#vuelto').focus();
  		 $(this).find('#vuelto').val("");
    });
    var total=$("#productosProforma").contents().find("#totalPagar2").val();
    $("#totalVL").val(total);
    $("#totalVLB").val(total);
  }

$(document).ready(function(){
    $(".modal").on('hidden.bs.modal', function () {
    	
    	if(facturacion==0){   	
    		$("#productosProforma").contents().find("#cantidad2").focus().select();
    	}else{
    		location.reload(true);
    	}
    });
});


</script>
<div class="modal fade" id="modalBusqueda" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Buscar cliente</h4>
      </div>
        <div class="modal-body">        
	        <div class="form-group">
				<div class="col-sm-12">
				
						<input type="text" onkeyup="buscarCliente(event)" id="txtBuscar" placeholder="Buscar cliente" class="form-control" autocomplete="false">
				
				</div>
				<br>
				<br>
				<br>
				<div id="resultadoBusqueda">
					<center><i class="entypo-info"></i><br>Por favor digite el nombre de un cliente.</center>
				</div>
			</div>
	      </div>
      
      <div class="modal-footer">
      	<table width="100%">
      		<tr>
      			<td><a href="../personas/nuevoPersonas.php" target="_blank" class="btn btn-green"><i class="entypo-plus"></i></a></td>
      			<td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsive">Cancelar</button></td>
      		</tr>
      	</table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEnviarCorreo" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Enviar cotización por Email</h4>
      </div>
        <div class="modal-body">  

          <?php include("../includes/alerts.php");?>

          <div class="form-group">
        <div class="col-sm-12">
          <input type="text" placeholder="Email" class="form-control" required="required" name="enviarEmailEmail" autocomplete="off" id="enviarEmailEmail">
        </div>
      </div>

      <div class="form-group">
        <label for="enviarEmailMensaje" class="col-sm-3 control-label">Mensaje:</label>
        
        <div class="col-sm-12">
          <textarea style="height: 200px;" class="form-control wysihtml5" name="enviarEmailMensaje" id="enviarEmailMensaje"></textarea>
        </div>
      </div>
      <br>
      <br>
      <br>
      <br>
      <br>
      <center><i>La(s) cotización(es) será(n) enviada(s) como un adjunto en el correo electrónico.</i></center>
      
      <div class="row" id="barraPro" style="display: none">
      <hr>
        <div class="col-md-12">

          Generando las cotizaciones. 
          <div class="progress">
            <div id="progreso" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
              <span class="sr-only">35% Complete (success)</span>
            </div>
          </div>
        </div>
          
      </div>

      </div>
      
      <div class="modal-footer">
        <table width="100%">
          <tr>
            <td><button onclick="enviarEmail()" target="_blank" class="btn btn-green"><i class="entypo-direction"></i> Enviar</button></td>
            <td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsiveCorreo">Cancelar</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div><!---visibility: hidden; height: 0px;-->
<div id="iframes" style="visibility: hidden; height: 0px;"></div>
<div id="iframesXML" style="visibility: hidden; height: 0px;"></div>

<div class="modal custom-width fade" id="modalBuscarProducto" data-backdrop="static">
  <div class="modal-dialog custom-width2"  >
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Buscar producto</h4>
      </div>
					<div class="panel-body">
						<div class="tab-content">
							<div class="tab-pane active" id="profile-1">
								<div class="form-group">
								<div class="col-sm-12">
										<input type="text" onkeyup="buscarProducto(event)" id="txtProducto" placeholder="Buscar producto" class="form-control" autocomplete="false">
								</div>
								<br>
								<br>
								<br>
								<div id="resultadoBusquedaProductos">
									<center><i class="entypo-info"></i><br>Por favor digite el nombre o descripción del producto.</center>
								</div>
							</div>

							</div>
							
							
						</div>
						
					</div>
				
      <div class="modal-footer">
      	<table width="100%">
      		<tr>
      			<td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsiveProductos">Cancelar</button></td>
      		</tr>
      	</table>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalMostrarCerrar" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Terminar factura</h4>
      </div>
        <div class="modal-body" div="contenidoFacturaFE">  
        <form role="form" action="" class="form-horizontal" method="post" name="formulario" id="formulario">
              
        	<?php if ($facturaElectronica == 1){ ?>
              <table width="100%">
              	<tr>
              		<td>
              		  <div class="form-group">
		              <div class="col-sm-12">
		                  Tipo de documento de referencia: 
		                  <select name="tipoDoc" id="tipoDoc" class="form-control">
		                    <option value="04" >Tiquete electrónico</option>                  	
		                    <option value="01" selected="selected">Factura electrónica</option>
		                    <!--<option value="02">Nota de debido electrónica</option>
		                    <option value="03">Nota de crédito electrónica</option>
		                    <option value="05">Nota de despacho</option>
		                    <option value="06">Contrato</option>
		                    <option value="07">Procedimiento</option>
		                    <option value="99">Otros</option>-->
		                  </select>
		                </div>
		              </div>

              		</td>
              		<td style="text-align: center;" width="150">
              			<div class="form-group" >
                			<div class="col-sm-12">
                  			Contingencia: </i>
                  			<input type="checkbox" class="form-control" name="contingencia" id="contingencia" value="1" onclick="cambioTipoDoc(this.checked)">
                			</div>
              			</div>
              		</td>
              	</tr>
              </table>

              <div class="form-group" id="refContingencia" style="display: none; border: 3px dashed #DBDBDB; margin: 5px; padding-bottom: 10px;">

              	 <div class="form-group" style="padding-left: 20px">
					<div class="col-sm-12">
						<div class="checkbox checkbox-replace color-primary">
							<input type="checkbox" id="sininternet" name="sininternet" value="1">
							<label> Sin internet </label>
						</div>
					</div>
				</div>

                <div class="col-sm-12">
                  Documento de referencia: </i>
                  <input type="text" class="form-control" name="referencia" id="referencia" value="">
                </div>

                <div class="col-sm-12">
                  Fecha de emisión del documento de referencia: </i>
                  <input type="text" class="form-control " name="fechaReferencia" id="fechaReferencia" placeholder="dd/mm/AAAA">
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-12">
                  El archivo xml y la representación gráfica seran enviadas al email: </i>
                  <input type="text" class="form-control" name="emailFE" id="emailFE" onkeyup="$('#email').val(this.value)">
                </div>
              </div>
          <?php } ?>

          </form>
        <div class="tile-block" id='divvuelto'> 
          	<div class="tile-header">
          		PAGA CON 
          	</div> 
          	<div class="tile-content"> 
				<div class="form-group"> 
					<label class="col-sm-3 control-label">Paga con:</label> 
					<div class="col-sm-9"> 
						<div class="input-group"> 
							<span class="input-group-btn">
								<button class="btn" type="button">&cent;</button>
							</span> 
							<input type="text" class="form-control" id="vuelto" data-mask="fdecimal" data-dec=" " data-rad="," maxlength="15" style="text-align: right;font-size: 20px" onkeyup="vueltof(this.value,event)">
								
						</div> 
					</div> 
				</div>
<br>
				<div class="form-group"> 
					<label class="col-sm-3 control-label">Total: </label> 
					<div class="col-sm-9"> 
						<div class="input-group"> 
							<span class="input-group-btn">
								<button class="btn" type="button">&cent;</button>
							</span> 
							<input type="text" id="totalVL" class="form-control" data-mask="fdecimal" readonly  data-dec=" " data-rad="," maxlength="15" style="text-align: right;font-size: 20px;color: #FF0000" >
						</div> 
					</div> 
				</div>
<br>
				<div class="form-group"> 
					<label class="col-sm-3 control-label">Vuelto:</label> 
					<div class="col-sm-9"> 
						<div class="input-group"> 
							<span class="input-group-btn">
								<button class="btn" type="button">&cent;</button>
							</span> 
							<input type="text" class="form-control" id="vueltoRE" data-mask="fdecimal" data-dec=" " data-rad="," maxlength="15" style="text-align: right;font-weight: 700;font-size: 20px;color: #0CFF00" >
						</div> 
					</div> 
				</div>
				<br><br>
          	</div> 
        </div> 	
          	    
   
          <button type="button" class="btn btn-default cerrarModal" style="visibility: hidden;" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button>
       <div class="modal-footer" id="botoneraFinalizar">
      	<table width="100%">
      		<tr>
      			<td width="80"><button type="button" class="btn btn-orange"  id="cancelarResponsiveProductos" onclick="tipoFactura(1)">Crédito<i>(F2)</i></button>
      			<button type="button" class="btn btn-green"  id="cancelarResponsiveProductos" onclick="tipoFactura(0)">Contado <i>(Ctrl+Espacio)</i></button>
      			<button type="button" class="btn btn-default cerrarModal"  data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td>
      		</tr>
      	</table>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript" src="../assets/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
function milesNumeros(numero) {
	//strEx = numero.replace(" ","");
	
	strEx = numero.replace(/ /g,"");
	strEx = strEx.replace(",",".");
    return strEx;
};
function formato(numero) {
	strEx = numero.replace(" ","");
	strEx = strEx.replace(",",".");
    return strEx;
};

function vueltof(val,event){
var res=milesNumeros(val)-milesNumeros($("#totalVL").val());
//alert(milesNumeros($("#totalVL").val()));
var tecla =event.which || event.keyCode;
 if (event.ctrlKey && event.keyCode === 32) {
     	tipoFactura(0);
    }else if(tecla==113){
    	tipoFactura(1);
    }else{


    	$("#montovuelto").html("hola");


		if(res>=0){
			$('#vueltoRE').val(res.toFixed(2));	
		}else{
			$('#vueltoRE').val("");
		}
	}
}




function cambioTipoDoc(tipo){
	if (tipo == true){
		$("#refContingencia").css("display", "block");
	}else{
		$("#refContingencia").css("display", "none");
	}
}
var facturacion=0;
function tipoFactura(tipo){
	facturacion=1;
	var html="<div class='tile-content'><center><h2 style='color:#FFFFFF'>GRACIAS POR SU COMPRA</h2>";
	if(tipo==0){
		html+="Factura de contado<br>";
		if($("#vueltoRE").val()!=""){
			
			html+="<h3 style='color:#FFFFFF'>SU VUELTO:<br><span style='color:#0CFF00'>&cent;"+$("#vueltoRE").val()+"</span></h3>";
			html+="<strong style='color:#FFFFFF'>Total a pagar: <span style='color:#FF0000'>&cent;"+$("#totalVL").val()+"</span></strong> :: ";
			html+="<strong style='color:#FFFFFF'>Paga con: <span style='color:#FFFFFF'>&cent;"+$("#vuelto").val()+"</span></strong>";
		}
		html+="</center></div>";
		
	}else{
			html+="Factura de credito<br>";
	}
	$("#divvuelto").html(html);
	
	<?php if ($facturaElectronica == 1){ ?>
	var tipoDoc = document.getElementById('tipoDoc').value;
	$("#tipoFacturaText").val(tipo);
	//document.getElementById('tipoFacturaText').value = tipo;
	finalizarFactura(tipoDoc);
	<?php }else{ ?>
	
	$("#tipoFacturaText").val(tipo);
	finalizarFactura(0, 0);
	<?php } ?>
	//document.getElementById('finalizar').click();
	//document.getElementById('formulario').submit();
	
}

function guardarFactura(){
	var idFactura = $("#idFactura").val();
	var idCliente = $("#idCliente").val();
	var cliente = $("#cliente").val();
	var email = $("#email").val();
	var descuento = $("#descuento").val();
	var vendedor = $("#vendedor").val();
	var tipoFactura = $("#tipoFacturaText").val();
	var observacion = $("#observacion").val();
	var caja = $("#caja").val();
	var plazo = $("#plazo").val();
	
	if ($("#exonerar").prop('checked')){
		exonerar = 1;
	}else{
		exonerar = 0;
	}
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'GUARDAR-FACTURA', idFactura: idFactura, idCliente: idCliente, cliente: cliente, email: email, descuento: descuento, vendedor: vendedor, tipoFactura: tipoFactura, observacion: observacion, exonerar: exonerar, caja: caja, plazo: plazo})
      .done(function( data ) {
       mensajeExito("La factura se guardó exitosamente.",tipo = 1)
    });
}

function guardarObservacion(observacion){
	var idFactura = $("#idFactura").val();
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'GUARDAR-OBSERVACION', idFactura: idFactura, observacion: observacion})
      .done(function( data ) {
       mensajeExito("La observacion ha sido guardada.",tipo = 1)
    });
}

function finalizarFactura(tipoDoc){
	if(navigator.onLine){
		var idFactura = $("#idFactura").val();
		var idCliente = $("#idCliente").val();
		var cliente = $("#cliente").val();
		var email = $("#email").val();
		var descuento = $("#descuento").val();
		var vendedor = $("#vendedor").val();
		var tipoFactura = $("#tipoFacturaText").val();
		var observacion = $("#observacion").val();
		var caja = $("#caja").val();
		var plazo = $("#plazo").val();
		
		if ($("#exonerar").prop('checked')){
			exonerar = 1;
		}else{
			exonerar = 0;
		}

		if ($("#contingencia").prop('checked')){
			contingencia = 1;
			referencia = $("#referencia").val();
			fechaReferencia = $("#fechaReferencia").val();
		}else{
			contingencia = 0;
			referencia = '';
			fechaReferencia = '';
		}

		if ($("#sininternet").prop('checked')){
			sininternet = 1;
		}else{
			sininternet = 0;
		}

		error = 0;
		if (contingencia == 1){
			if (referencia == ''){
				error = 1;
				alert("Debe indicar el número de referencia del documento.");
				$("#referencia").focus();
			}else if (fechaReferencia == ''){
				error = 1;
				alert("Debe indicar la fecha de referencia en formato dd/mm/AAAA.");
				$("#fechaReferencia").focus();
			}
		}

		if (error == 0){
			$("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> La factura se está guardando.</center>");
			jQuery.post( "funcionesAJAXFacturas.php", { f: 'FINALIZAR-FACTURA', idFactura: idFactura, idCliente: idCliente, cliente: cliente, email: email, descuento: descuento, vendedor: vendedor, tipoFactura: tipoFactura, observacion: observacion, exonerar: exonerar, finalizar: 1, caja: caja, plazo: plazo, tipoDoc: tipoDoc, contingencia: contingencia, sininternet: sininternet, referencia, referencia, fechaReferencia: fechaReferencia})
		      .done(function( data ) {
		       console.log(data);
		      
		       var tFactura = data.split("-");
		       if (tFactura[0] != 'error'){
			       if (tFactura[1] == '0'){
				       	if (tFactura[0] == '1'){
				       	  alert("La factura se envió exitosamente a cuentas por cobrar.");
				       	  imprimir();
				       	  //location = location;
				       	}else{
				       	  alert("La factura se finalizó exitosamente.");	
				       	  imprimir();
				       	  //location = location;
				       	}
				       	 document.location.href = '../facturas';
			       }else{
			       	 var emailNC = $("#emailFE").val();
						//alert(emailNC);
						if(emailNC!=""){ 	  
					    	
							var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+idFactura+'&tipo=2" style="width: 850px; height: 150px;"></iframe>';
							document.getElementById('iframesXML').innerHTML+=iframe; 
						}
			       	  generarXML(tFactura[1], tipoDoc);
			       }
			   }else{
					//$("#botoneraFinalizar").html("<center>"+tFactura[1]+"</center>");
					alert(tFactura[1]);
					location = location;
			   }
		    });
	    }

	}else{
		alert('Revise su conexion a Internet y vuela a intentarlo');
		jQuery('.cerrarModal').click();
	}
}

function generarXML(numeroFactura, tipoDoc){
	$("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Generar archivo XML.</center>");
	var idFactura = $("#idFactura").val();
	var idCliente = $("#idCliente").val();
	var cliente = $("#cliente").val();
	var email = $("#email").val();
	var descuento = $("#descuento").val();
	var vendedor = $("#vendedor").val();
	var tipoFactura = $("#tipoFacturaText").val();
	var observacion = $("#observacion").val();

	if ($("#contingencia").prop('checked')){
		contingencia = 1;
		referencia = $("#referencia").val();
		fechaReferencia = $("#fechaReferencia").val();
	}else{
		contingencia = 0;
		referencia = '';
		fechaReferencia = '';
	}

	if ($("#sininternet").prop('checked')){
		sininternet = 1;
	}else{
		sininternet = 0;
	}
	
	if ($("#exonerar").prop('checked')){
		exonerar = 1;
	}else{
		exonerar = 0;
	}
	
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'GENERAR-XML-FE', idFactura: idFactura, idCliente: idCliente, cliente: cliente, email: email, descuento: descuento, vendedor: vendedor, tipoFactura: tipoFactura, observacion: observacion, exonerar: exonerar, finalizar: 1, numeroFactura: numeroFactura, tipoDoc: tipoDoc, contingencia: contingencia, sininternet: sininternet, referencia, referencia, fechaReferencia: fechaReferencia})
      .done(function( data ) {
      	  //console.log(data);
   	  firmarXML(numeroFactura, tipoDoc);
    });
}

function firmarXML(numeroFactura, tipoDoc){
	$("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Firmando archivo XML.</center>");
	 imprimir();
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'FIRMAR-XML', numeroFactura: numeroFactura, tipoDoc: tipoDoc})
      .done(function( data ) {
 
     $("#botoneraFinalizar").html("<center style='color: #019C30'>Factura firmada...</center>");
     $("#botoneraFinalizar").html("<div class='modal-footer' id='botoneraFinalizar'><table width='100%'><tr><td width='80'><span>Factura Finalizada ::  </span><a href='../facturas/?n=1' id='xmlFactura' class='btn btn-green'>Nueva Factura</a> <button type='button' class='btn btn-default cerrarModal'  data-dismiss='modal'><i class='entypo-cancel'></i>Cerrar ventana <i>Esc</i></button></td></tr></table><center><i>Digite cualquier tecla para nueva factura</i></center></div>");

     	$("html").keypress(function(e){
	      e.preventDefault();
	      var tecla =e.which || e.keyCode;
	     	if(tecla==27){
	       		$(".cerrarModal").click();
	       	 	//alert(tecla+ ": " + String.fromCharCode(e.which));
	       	}else{

 				document.location.href='../facturas/?n=1';
	       	}
		
	     
	      
   		});
    }).fail(function(error) {
	    alert( "Error" );
	 });
}

function token(numeroFactura){
	$("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Solicitando token de acceso a hacienda.</center>");
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'OBTENER-TOKEN'})
      .done(function( token ) {
      	if (token.indexOf('<br />') >= 0){
      		jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: 4, xmlRespuesta: ""})
	          .done(function( data ) {
	          	alert("No se pudo obtener comunicación con el servidor de hacienda. \nEl documento se guardará y será enviado luego.");
	          	imprimir();
	          });
  	    }else{
  	  		enviarFactura(numeroFactura, token);
  	    }
      	//console.log(token);
    });
}

function enviarFactura(numeroFactura, token){

	ids = '<?php echo $idFactura; ?>-';
	var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+<?php echo $idFactura;?>+'&tipo=2" style="width: 850px; height: 150px;"></iframe>';
	document.getElementById('iframes').innerHTML+=iframe; 
	
	$("#botoneraFinalizar").html("<center><img src='../assets/images/cargando.gif'> Enviando factura a hacienda.</center>");
	jQuery.post( "funcionesAJAXFacturas.php", { f: 'ENVIAR-FACTURA', numeroFactura: numeroFactura, token: token})
      .done(function( data ) {
      	imprimir();
      	console.log(data);
      	if (data != "error"){
      	document.location.href = '../facturas/';
      	/*
//  	  $("#estadoFE-"+numeroFactura).html("VERIFICANDO...");
	  	jQuery.post( "funcionesAJAXFacturas.php", { f: 'VERIFICAR', numeroFactura: numeroFactura})
	      .done(function( data ){
	      	
	        if (data.indexOf('[http_code] => 400') >= 0){
          		alert('Ocurrió un error.');
        	}else if (data.indexOf('404</h2>') >= 0){
//	          $("#estadoFE-"+numeroFactura).html('No enviada');
			  jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: 4, xmlRespuesta: ""})
	          .done(function( data ) {
	          	alert("Ocurrió un error en el envío de la factura. \nEl documento se guardará y será enviado luego.");
	          	
	          });
	        }else{
	          //mensajeExito("La factura se finalizó exitosamente.",tipo = 1);  
			  $("#botoneraFinalizar").html('<table width="100%"><tr><td width="80"><button type="button" class="btn btn-orange"  id="cancelarResponsiveProductos" onclick="tipoFactura(1)">Cuenta por cobrar</button><button type="button" class="btn btn-green"  id="cancelarResponsiveProductos" onclick="tipoFactura(0)">Contado</button><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td></tr></table>');
		      jQuery('.cerrarModal').click();


		      $( "#btnGuardar" ).prop( "disabled", true );
		      $( "#btnFinalizar" ).prop( "disabled", true );
		      $( "#btnImprimir" ).prop( "disabled", false );
		      $( "#btnEmail" ).prop( "disabled", false );
		     

	          var estado = "";
	          var resp = JSON.parse(data);
	          if (resp['ind-estado'] == 'aceptado'){

	          	  
//	            $("#estadoFE-"+numeroFactura).html('Aceptado');
	            estado = 1;
	          }else if (resp['ind-estado'] == 'rechazado'){
//	            $("#estadoFE-"+numeroFactura).html('Rechazada');
	            estado = 3;
	          }else{
//	            $("#estadoFE-"+numeroFactura).html('Procesando');
	            estado = 2;
	          }
	          jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: estado, xmlRespuesta: resp['respuesta-xml']})
	          .done(function( data ) {

	          	if (estado == 1){
                var emailNC = $("#emailFE").val();
                var idFactura = $("#idFactura").val();
                alert(emailNC+" "+idFactura);
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
                    alert(msg);
                    document.location.href = '../facturas/';
                  });
                }
                }else{
              	  //location = location;
              	  document.location.href = '../facturas/';
                }

	          });
	        }
	    });
	    */
    	}else{
    		jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: 4, xmlRespuesta: ""})
	          .done(function( data ) {
	          	alert("Ocurrió un error en el envío de la factura. \nEl documento se guardará y será enviado luego.");
	          document.location.href = '../facturas/';
	          });
    	}
    });
}
/*
$(document).keyup(function(event){
    if(event.which==27)
    {
       $('.cerrarModal').click();
       $("#productosProforma").contents().find("#cantidad2").focus().select();
    }
    alert(event.which);
});*/
</script>

<!-- FIN CONTENIDO -->
<script src="../assets/js/jquery.inputmask.bundle.js" id="script-resource-8"></script>
<?php include("../includes/footer.php");?>