<?php include("../conn/connpg.php");?>	
<?php 
//controlAcceso(55);
?>
<?php $pagina="Mensaje Receptor";  include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<!-- INICIA CONTENIDO -->

<script type="text/javascript">
	var ventana_ancho = $(window).width();

if(ventana_ancho>=768){
	$(".page-container").addClass("sidebar-collapsed");

}

function verificarFE(clave){
  var old = $("#estadoFE").html();
  $("#estadoFE").html("VERIFICANDO...");
  jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'VERIFICAR-RECEPTOR', clave: clave})
      .done(function( data ) {
        console.log(data);

        if (data.indexOf('<br />') >= 0){
        	alert("No se pudo establecer comunicación con el servidor de hacienda. \nIntentelo más tarde por favor.");
        	$("#estadoFE").html(old);
        }else{
	        if (data.indexOf('[http_code] => 400') >= 0 || data == ""){
	          $("#estadoFE").html('');
	          $("#botoneraAceptar").css('display', ' none');
              $("#novalida").css('display', 'none');
              $("#procesando").css('display', 'none');
              $("#noenviada").css('display', 'block');
	        }else if (data.indexOf('404</h2>') >= 0){
	          $("#estadoFE").html('');
	          $("#botoneraAceptar").css('display', ' none');
              $("#novalida").css('display', 'none');
              $("#procesando").css('display', 'none');
              $("#noenviada").css('display', 'block');
	        }else{
	          var estado = "";
	          var resp = JSON.parse(data);
	          if (resp['ind-estado'] == 'aceptado'){
	            $("#estadoFE").html('');
	            estado = 1;
	            $("#botoneraAceptar").css('display', 'block');
	            $("#novalida").css('display', 'none');
	            $("#procesando").css('display', 'none');
	            $("#noenviada").css('display', 'none');
	          }else if (resp['ind-estado'] == 'rechazado'){
	            $("#estadoFE").html('');
	            estado = 3;
	            $("#botoneraAceptar").css('display', 'none');
	            $("#novalida").css('display', 'block');
	            $("#procesando").css('display', 'none');
	            $("#noenviada").css('display', 'none');
	          }else{
	            $("#estadoFE").html('');
	            estado = 2;
	            $("#botoneraAceptar").css('display', ' none');
	            $("#novalida").css('display', 'none');
	            $("#procesando").css('display', 'block');
	            $("#noenviada").css('display', 'none');
	          }
	          /*jQuery.post( "funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO', numeroFactura: numeroFactura, estado: estado, xmlRespuesta: resp['respuesta-xml']})
	          .done(function( data ) {});*/
	        }
    	}
    });
}
</script>


<ol class="breadcrumb" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="../receptor/">Compras</a></li>
	<li><a href="#">Agregar compra</a></li>
</ol>
<h2>Aceptar / Rechazar Facturas Electrónicas</h2>
<br />

<style type="text/css">
.bordered {
	padding: 10px;
	border-bottom: 3px dashed #616060; 
	border-top: 3px dashed #616060; 
	border-right: 3px dashed #616060
}
.divRE{
	margin: 2px;
	width: 32%;
	display: inline-table;
	border: 3px dotted #2F2F2F;
	
}

@media(max-width : 794px){
    .divRE{
        width: 100%;
        margin: 0px;
    }
  }
</style>

<?php 
if (!isset($_POST['guardar'])){
?>
<form role="form" action="" class="form-horizontal" method="post"  enctype="multipart/form-data">
	<center>
		Por favor, cargue el archivo XML de Factura Electrónica que ha recibido para poder iniciar con el proceso.
		<br><br><br><br>
		<input type="file" name="xml" class="form-control file2 inline btn btn-primary" data-label="<i class='glyphicon glyphicon-upload'></i> Buscar archivo XML" /> 
		<button type="submit" class="btn btn-green" name="guardar">Subir archivo</button>
	</center>
</form>

<?php 
}else if (isset($_POST['guardar'])){
	if (is_uploaded_file($_FILES["xml"]["tmp_name"])){
	    if ($_FILES["xml"]["type"]=="text/xml"){
	      $xml = "../parametros/archivos/temp/aceptando-".$idUsuario.".xml";
	      move_uploaded_file($_FILES['xml']['tmp_name'], $xml);

			  $xml = simplexml_load_file($xml);   
				?>
			
			<div>
						
						<table class="table table-bordered divRE" width="100%">
							<tr>
								<td colspan="2" style="text-align: center; font-weight: bold;">Factura Electrónica</td>
							</tr>
							<tr>
								<td colspan="2" style="font-weight: bold;text-align: center;">Clave FE:</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center;"><?php 
								echo $claveFE = $xml->Clave;
								?>
								<script type="text/javascript">
									verificarFE('<?php echo $claveFE?>');
								</script>	</td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Consecutivo:</td>
								<td><input type="hidden" id="numeroConsecutivo" value="<?php echo $xml->NumeroConsecutivo?>"><?php echo $xml->NumeroConsecutivo?></td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Fecha:</td>
								<td><input type="hidden" id="fechaEmision" value="<?php echo $xml->FechaEmision?>"><?php echo $xml->FechaEmision?></td>
							</tr>
							<tr style="display: none">
								<td colspan="2" style="text-align: center; font-weight: bold;">Emisor</td>
							</tr>
							<tr style="display: none">
								<td style="font-weight: bold;">Tipo ID:</td>
								<td><?php echo $xml->Emisor->Identificacion->Tipo?></td>
							</tr>
							<tr style="display: none">
								<td style="font-weight: bold;">Identificación:</td>
								<td><?php echo $identificacionProveedor = $xml->Emisor->Identificacion->Numero?></td>
							</tr>
							<tr style="display: none">
								<td style="font-weight: bold;">Nombre:</td>
								<td><?php echo $xml->Emisor->NombreComercial?></td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: center; font-weight: bold;">Montos</td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Subtotal:</td>
								<td><?php echo $xml->ResumenFactura->TotalVenta?></td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Descuentos:</td>
								<td>- <?php echo $xml->ResumenFactura->TotalDescuentos?></td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Impuestos:</td>
								<td>+ <?php echo $xml->ResumenFactura->TotalImpuesto?></td>
							</tr>
							<tr>
								<td style="font-weight: bold;">TOTAL:</td>
								<td style="font-weight: bold;"><u style="border-bottom: 1px double #333333;"><input type="hidden" id="totalComprobante" value="<?php echo $xml->ResumenFactura->TotalComprobante?>"><?php echo $xml->ResumenFactura->TotalComprobante?></u></td>
							</tr>
						</table>
				

					<div class="divRE">
						
						<table class="table table-bordered" >
							<tr>
								<td colspan="2" style="text-align: center; font-weight: bold;">Proveedor</td>
							</tr>
						</table>
						<br>
						<div id="datosProveedor">
							<?php 
							$sql = "SELECT * FROM tclientes WHERE identificacion = '$identificacionProveedor' AND usuario='$cuentaid' ";
							$query = mysql_query($sql, $conn);
							if (mysql_num_rows($query) == 0){ ?>
								<center>
									No se pudo relacionar la factura con algún proveedor del sistema.
									<br>
									<br>
									¿Desea agregarlo?
									<br>
										<button type="button" class="btn btn-primary"  id="cancelarResponsiveProductos" onclick="mostrarGuardarProveedor()">Guardar</button>
								</center>
							<?php }else{
							while ($row=mysql_fetch_assoc($query)) { ?>
								<input type="hidden" name="idProveedor" id="idProveedor" value="<?php echo $row['id']?>">
								<table width="100%" class="table table-bordered">
									<tr>
										<td style="font-weight: bold;">Identificación:</td>
										<td><?php echo $row['identificacion']?></td>
									</tr>
									<tr>
										<td style="font-weight: bold;">Nombre:</td>
										<td><?php echo $row['nombre']?></td>
									</tr>
									<tr>
										<td style="font-weight: bold;" width="150px">Teléfono:</td>
										<td><?php echo $row['telefono1']?></td>
									</tr>
									<tr>
										<td style="font-weight: bold;" width="150px">Email:</td>
										<td><?php echo $row['email']?></td>
									</tr>
									<tr>
										<td style="font-weight: bold;" width="150px">Dirección:</td>
										<td><?php echo $row['direccion']?></td>
									</tr>
									<tr>
										<td style="font-weight: bold;" width="150px">Contacto:</td>
										<td><?php echo $row['contacto']?></td>
									</tr>
									<tr>
										<td style="font-weight: bold;" width="150px">Tipo de mercadería:</td>
										<td><?php echo $row['tipoMercaderia']?></td>
									</tr>
						    	</table>	
						<?php }
							} ?>
						</div>

					</div>

					<div class="divRE">
							<table class="table table-bordered" >
								<tr>
									<td colspan="2" style="text-align: center; font-weight: bold;">Aceptar/Rechazar</td>
								</tr>
							</table>

							<div id="estadoFE" style="text-align: center;"><img src="../assets/images/cargando.gif"><br>VERIFICANDO ESTADO DE FACTURA EN SERVIDOR DE HACIENDA...</div>


							<!-- FACTURA CON ESTADO ACEPTADA EN SERVIDOR HACIENDA -->
							<div id="botoneraAceptar" style="display: none;">
								<center>
									Ahora puede aceptar o rechazar la factura. 
									<br>
									<br>

																	

									<button type="button" class="btn btn-green"  id="aceptarFactura" onclick="aceptarFactura()">Aceptar factura</button>
									<button type="button" class="btn btn-default"  id="rechazarFactura" onclick="rechazarFactura()">Rechazar factura</button>

									<br>
									Digite por favor el motivo del rechazo (En caso de rechazo):<br>
									<input type="text" class="form-control" name="" id="razonRechazo" maxlength="75">
									<br>
									<a href="aceptacionFE.php" class="btn btn-orange" style="width: 230px">Cancelar</a>
								</center>
							</div>

							<!-- FACTURA CON ESTADO RECHAZADA EN SERVIDOR HACIENDA -->
							<div id="novalida" style="display: none;">
							<center>
								<i class="entypo-cancel-circled" style="font-size: 50px; color: #C90000"></i><br>
								La factura tiene estado <b>Rechazado</b> en el servidor de hacienda. <br><br>Por favor comuníquese con su proveedor e solicite una factura nueva. <br><br>
								<br><br>
								<a href="aceptacionFE.php" type="button" class="btn btn-primary"  id="cancelarResponsiveProductos">Cargar una factura nueva.</a>
							</center>
							</div>

							<!-- FACTURA CON ESTADO NO SE ENCUENTRA EN SERVIDOR HACIENDA -->
							<div id="noenviada" style="display: none;">
							<center>
								<i class="entypo-cancel-circled" style="font-size: 50px; color: #C90000"></i><br>
								La factura <b>No ha sido encotrada</b> en el servidor de hacienda. <br><br>Por favor comuníquese con su proveedor e solicite una factura nueva.<br><br><i>Verifique que no está procesando una factura confeccionada en ambiente de pruebas</i><br><br>
								<a href="aceptacionFE.php" type="button" class="btn btn-primary"  id="cancelarResponsiveProductos">Cargar una factura nueva.</a>
							</center>
							</div>

							<!-- FACTURA CON ESTADO PROCESANDO EN SERVIDOR HACIENDA -->
							<div id="procesando" style="display: none;">
							<center>
								<i class="entypo-clock" style="font-size: 50px; color: #033D7F"></i><br>
								La factura de su proveedor aún no ha sido procesada por el servidor de hacienda. <br><br>Por favor espere e inténtelo más tarde.<br><br>
								<br><br>
								<a href="javascript: void(0);" type="button" class="btn btn-info"  id="cancelarResponsiveProductos" onclick="verificarFE('<?php echo $claveFE?>')">Verificar de nuevo.</a> 
								<a href="aceptacionFE.php" type="button" class="btn btn-primary"  id="cancelarResponsiveProductos">Cargar una factura nueva.</a>
							</center>
							</div>

						<div id="aceptada" style="display: none">
						<hr>
						<center>
							<i class="entypo-check" style="font-size: 50px; color: #228200"></i><br>
							La factura ha sido aceptada correctamente.<br><br>
							A continuación, puede agregar la factura a cuentas por pagar y registrarla como compra a proveedores. <br><br>
							<button type="button" class="btn btn-orange"  id="cuentaPagar" onclick="cuentaPagar()">Cuenta por pagar</button>
							<!--<button type="button" class="btn btn-info"  id="compraProveedor" onclick="compraProveedor()">Compra a proveedor</button>-->
						</center>

						<div id="confirmacionMensajes"></div>
						<br>
						<br>
						<!--<center>
							<button type="button" class="btn btn-gold"  id="compraProveedor" onclick="cargarInventario()">Cargar en inventario</button>
						</center>-->
						</div>
				
						
						<div id="rechazada" style="display: none;">
						<center>
							<i class="entypo-check" style="font-size: 50px; color: #AF0000"></i><br>
							La factura ha sido rechazada correctamente.<br><br>
							<br><br>
							<a href="aceptacionFE.php" type="button" class="btn btn-primary"  id="cancelarResponsiveProductos">Cargar una factura nueva.</a>
						</center>
						</div>

						<div id="error" style="display: none; margin-top: 40px; text-align: center;">
						<hr>
						<center>
							<i class="entypo-cancel-circled" style="font-size: 50px; color: #A60000"></i><br>
							Ocurrió un error en el envío de la Factura.<br><br>
							<br><br>
							<button style="display: none" type="button" class="btn btn-primary"  id="cancelarResponsiveProductos" onclick="intentarDeNuevo()">Intentar de nuevo.</button>
							<a href="aceptacionFE.php" type="button" class="btn btn-primary"  id="cancelarResponsiveProductos">Cargar una factura nueva.</a>
						</center>
						</div>
					</div>
		</div>	
		<br><br>		
			
<center>				
<div class="flechas"><i class="entypo-switch"></i></div>
</center>
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">
				
				
						<table width="100%" class="table table-bordered">
						<tr>
							<td colspan="10" style="text-align: center; font-weight: bold;">Linea de productos</td>
						</tr>
						<tr>
				              <td style="font-weight: bold;"></td>
				              <td style="font-weight: bold;">Cant</td>
				              <td style="font-weight: bold;">Cód. Prov</td>
				              <td style="font-weight: bold;">Descripción</td>
				              <td style="font-weight: bold;">Precio Prov</td>
				              <td style="font-weight: bold;">MontoTotal</td>
				              <td style="font-weight: bold;">SubTotal</td>
				              <td style="font-weight: bold;">Impuesto</td>
				              <td style="font-weight: bold;" >MontoTotalLinea</td>
						</tr>
					
						<?php 
						$mayor = 0;
						foreach ($xml->DetalleServicio->LineaDetalle as $linea) {
							$mayor++; 
							 ?>
							<tr>
				                <td><?php echo $numeroLinea = $linea->NumeroLinea?></td>
				                <td><?php echo $linea->Cantidad?></td>
				                <td><?php echo $codigo = $linea->Codigo->Codigo?></td>
				                <td><?php echo $linea->Detalle?></td>
				                <td><?php echo $linea->PrecioUnitario;?></td>
				                <td><?php echo $linea->MontoTotal;?></td>
				                <td><?php echo $linea->SubTotal;?></td>
				                <td><?php if(isset($linea->Impuesto)){
				                  echo $linea->Impuesto->Monto;
				                }else{echo "EXE";}?></td>
				                <td><?php echo $linea->MontoTotalLinea;?></td>
				              </tr> 
						<?php 
						} 
						?>

					</table>			
			</div>

			<?php 
	    }else{
	        $bError = "El formato del archivo de XML no corresponde.";
	    }
	}
}
?>

<div class="modal fade" id="modalGuardarProveedor" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Guardar proveedor.</h4>
      </div>
        <div class="modal-body" div="contenidoFacturaFE">  
          <form role="form" action="" class="form-horizontal" method="post" name="formulario" id="formulario">
              
        	<table width="100%" class="table table-bordered responsive tablaGrande">

        		<div class="col-sm-12">
                  Identificación: </i>
                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php echo $identificacionProveedor = $xml->Emisor->Identificacion->Numero?>">
                </div>

                <div class="col-sm-12">
                  Nombre: </i>
                  <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $xml->Emisor->NombreComercial?>">
                </div>

                <div class="col-sm-12">
                  Teléfono: </i>
                  <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $xml->Emisor->Telefono->NumTelefono?>">
                </div>

                <div class="col-sm-12">
                  Email: </i>
                  <input type="text" class="form-control" name="email" id="email" value="<?php echo $xml->Emisor->CorreoElectronico?>">
                </div>

                <div class="col-sm-12">
                  Dirección: </i>
                  <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $xml->Emisor->Ubicacion->OtrasSenas?>">
                </div>

				<div class="col-sm-12">
                  Contacto: </i>
                  <input type="text" class="form-control" name="contacto" id="contacto" value="">
                </div>
				
				<div class="col-sm-12">
                  Tipo de mercadería: </i>
                  <input type="text" class="form-control" name="tipoMercaderia" id="tipoMercaderia" value="">
                </div>
        	</table>

          </form>
      	<table width="100%">
      		<tr>
      			<td style="text-align: right;">
      			<button type="button" class="btn btn-green"  id="cancelarResponsiveProductos" onclick="guardarProveedor()">Guardar</button>
      			<button type="button" class="btn btn-default cerrarModal" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td>
      			</td>
      		</tr>
      	</table>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
	function tabular(e,obj) { 
	  tecla=(document.all) ? e.keyCode : e.which; 
	  if(tecla!=13) return; 
	  frm=obj.form; 
	  for(i=0;i<frm.elements.length;i++) 
	    if(frm.elements[i]==obj) { 
	      if (i==frm.elements.length-1) i=-1; 
	      break } 
	  frm.elements[i+1].focus(); 
	  return false; 
	} 

	function mostrarGuardarProveedor(){
		jQuery('#modalGuardarProveedor').modal('show', {backdrop: 'static'});
	}

	function guardarProveedor(){
		var identificacion = $("#identificacion").val();
		var nombre = $("#nombre").val();
		var direccion = $("#direccion").val();
		var telefono = $("#telefono").val();
		var email = $("#email").val();
		var contacto = $("#contacto").val();
		var tipoMercaderia = $("#tipoMercaderia").val();
		
		jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'GUARDAR-PROVEEDOR', identificacion: identificacion, nombre: nombre, telefono: telefono, email: email, direccion: direccion, contacto: contacto, tipoMercaderia: tipoMercaderia})
	      .done(function( data ) {
	       $("#datosProveedor").html(data);
	       $("#cerrarModal").click();
	    });		
	}

	function intentarDeNuevo(){
		//$("#aceptarFactura").removeAttr("disabled");
		//$("#rechazarFactura").removeAttr("disabled");
		$("#botoneraAceptar").css("display", "block");
		$("#error").css("display", "none");
	}

	/*function rechazarFactura(){
		$("#botoneraAceptar").css("display", "none");
		$("#rechazada").css("display", "block");
	}*/

/////ACEPTAR FACTURA
	function aceptarFactura(){
		$("#aceptarFactura").attr("disabled", "disabled");
		$("#rechazarFactura").attr("disabled", "disabled");
		var clave = '<?php echo $claveFE?>';
		var consecutivoFE= '<?php echo $xml->NumeroConsecutivo;?>';
		var tipoIdentificacionEmisor = '<?php echo $xml->Emisor->Identificacion->Tipo?>';
		var cedulaEmisor = '<?php echo $xml->Emisor->Identificacion->Numero?>';
		var fechaEmision = '<?php echo $xml->FechaEmision?>';
		var totalImpuesto = '<?php echo $xml->ResumenFactura->TotalImpuesto?>';
		var TotalFactura = '<?php echo $xml->ResumenFactura->TotalComprobante?>';
		var mensaje = '1';
		var detalleMensaje = 'Aceptado'; 
	
		jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'GENERAR-XML-MR', clave: clave,consecutivoFE:consecutivoFE,tipoIdentificacionEmisor: tipoIdentificacionEmisor, cedulaEmisor: cedulaEmisor, fechaEmision: fechaEmision, totalImpuesto: totalImpuesto, TotalFactura: TotalFactura, mensaje: mensaje, detalleMensaje: detalleMensaje})
	      .done(function( data ) {
	      	//console.log(data);
	      	if (data == 0){
	      		alert('La Factura ya se encuentra en las cuentas por pagar.');
	      		document.location.href='../receptor/';
	      	}else{
	      		firmarXML(data, mensaje);
	      		$("#botoneraAceptar").css("display", "none");
				$("#aceptada").css("display", "block");
		   		
	      	}
	    });	
	}

/////RECHAZAR FACTURA
	function rechazarFactura(){
		$("#aceptarFactura").attr("disabled", "disabled");
		$("#rechazarFactura").attr("disabled", "disabled");
		var detalleMensaje = document.getElementById("razonRechazo").value;
		if (detalleMensaje == ""){
			document.getElementById("razonRechazo").focus();
			alert("Por favor digite una razón para el rechazo");
			$("#aceptarFactura").removeAttr("disabled");
			$("#rechazarFactura").removeAttr("disabled");
		}else{
			$("#aceptarFactura").attr("disabled", "disabled");
			$("#rechazarFactura").attr("disabled", "disabled");
			var clave = '<?php echo $claveFE?>';
			var consecutivoFE= '<?php echo $xml->NumeroConsecutivo;?>';
			var tipoIdentificacionEmisor = '<?php echo $xml->Emisor->Identificacion->Tipo?>';
			var cedulaEmisor = '<?php echo $xml->Emisor->Identificacion->Numero?>';
			var fechaEmision = '<?php echo $xml->FechaEmision?>';
			var totalImpuesto = '<?php echo $xml->ResumenFactura->TotalImpuesto?>';
			var TotalFactura = '<?php echo $xml->ResumenFactura->TotalComprobante?>';
			var mensaje = '3';
			//var detalleMensaje = $("razonRechazo").val(); 
		
			jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'GENERAR-XML-MR', clave: clave,consecutivoFE:consecutivoFE, tipoIdentificacionEmisor: tipoIdentificacionEmisor, cedulaEmisor: cedulaEmisor, fechaEmision: fechaEmision, totalImpuesto: totalImpuesto, TotalFactura: TotalFactura, mensaje: mensaje, detalleMensaje: detalleMensaje})
		      .done(function( data ) {
		      	//console.log(data);
		      	if (data == 0){
		      		alert('La Factura ya se encuentra en las cuentas por pagar.');
		      		document.location.href='../receptor/';
		      	}else{
		      		firmarXML(data, mensaje);
		      		$("#botoneraAceptar").css("display", "none");
					$("#aceptada").css("display", "block");
			   		
		      	}
		    });		
		}
	}

	function firmarXML(consecutivo, mensaje){
	  $("#confirmacionMensajes").html("<center><img src='../assets/images/cargando.gif'><br>Firmando archivo XML.</center>");
	  jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'FIRMAR-XML-MR', consecutivo: consecutivo})
	      .done(function( data ) {
	          //console.log(data);
	          token(consecutivo, mensaje);
	    });
	}

	function token(consecutivo, mensaje){
	  $("#confirmacionMensajes").html("<center><img src='../assets/images/cargando.gif'><br>Solicitando token de acceso a hacienda.</center>");
	  jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'OBTENER-TOKEN'})
	      .done(function( token ) {
	      	if (token.indexOf('<br />') >= 0){
	          	alert("No se pudo obtener comunicación con el servidor de hacienda. \nPor favor inténtelo más tarde.");
	          	//jQuery('.cerrarModal').click();
	          	$("#botoneraAceptar").css("display", "none");
				$("#error").css("display", "block");
	  	    }else{
	  	  		enviarFactura(consecutivo, token, mensaje);
	  	    }
	        console.log(token);
	    });
	}

	function enviarFactura(consecutivo, token, mensaje){
		var clave = '<?php echo $claveFE?>';
	  $("#confirmacionMensajes").html("<center><img src='../assets/images/cargando.gif'><br>Enviando factura a hacienda.</center>");
	  jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'ENVIAR-FACTURA-MR', consecutivo: consecutivo, token: token})
	      .done(function( data ) {
	        console.log(data);
	       
	        if (data != "error"){
	     $("#confirmacionMensajes").html("<center><img src='../assets/images/cargando.gif'><br>VERIFICANDO...</center>");
		    	jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'VERIFICAR-MR',clave: clave, consecutivo: consecutivo})
		        .done(function(data) {
		        //alert(data);
		          if (data.indexOf('404</h2>') >= 0){
		//            $("#estadoFE-"+numeroFactura).html('No enviada');
		        	alert("No se pudo obtener comunicación con el servidor de hacienda. \nPor favor inténtelo más tarde.");
		        	$("#botoneraAceptar").css("display", "none");
				$("#error").css("display", "block");
		 
		          }else{
		            console.log(data);
		           	//mensajeExito("La factura se finalizó exitosamente.",tipo = 1);  
		 			
		 			if (mensaje == 1){
		 				$("#aceptada").css("display", "block");
						$("#error").css("display", "none");
		 			}else{
		 				$("#rechazada").css("display", "block");
						$("#error").css("display", "none");
		 			}

		            var estado = "";
		            var resp = JSON.parse(data);
		            if (resp['ind-estado'] == 'aceptado'){
		//              $("#estadoFE-"+numeroFactura).html('Aceptado');
	
	  				$("#confirmacionMensajes").html("<center>Aceptado.</center>");
		              estado = 1;
		            }else if (resp['ind-estado'] == 'rechazado'){
		//              $("#estadoFE-"+numeroFactura).html('Rechazada');
					$("#confirmacionMensajes").html("<center>Rechazada.</center>");
		              estado = 3;
		            }else{
		//              $("#estadoFE-"+numeroFactura).html('Procesando');
						$("#confirmacionMensajes").html("<center>Procesando.</center>");
		              estado = 2;


		            }
 
					$("#confirmacionMensajes").html("<center><img src='../assets/images/cargando.gif'><br>VERIFICANDO ESTADO</center>");
		             jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO-MR', consecutivo: consecutivo, estado: estado, xmlRespuesta: resp['respuesta-xml']})
			          .done(function( data ) {
			            console.log(data);
			          });
			        
		            document.location.href='../receptor/';
		          }
		      });
	    	}else{
	    		alert("No se pudo obtener comunicación con el servidor de hacienda. \nPor favor inténtelo más tarde.");
	    		$("#botoneraAceptar").css("display", "none");
				$("#error").css("display", "block");

	    	} 
	    });
	}
//////FIN ACEPTAR FACTURA

	function cuentaPagar(){
		$("#cuentaPagar").attr("disabled", "disabled");
		var clave = '<?php echo $claveFE?>';
		var numeroConsecutivo = $("#numeroConsecutivo").val();
		
	
		jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'GUARDAR-CUENTA-PAGAR',clave:clave ,numeroConsecutivo: numeroConsecutivo})
	      .done(function( data ) {
	      	//console.log(data);
	      	if (data == 0){
	      		alert('La Factura ya fue procesada anteriormente.');
	      	}else if (data == 1){
		   		$("#confirmacionMensajes").html($("#confirmacionMensajes").html() + "<br><i>Se agregó la cuenta por pagar correctamente.</i>");
	      	}else{
	      		alert('Ocurrió un error.');
	      	}
	    });	
	}

	function compraProveedor(){
		$("#compraProveedor").attr("disabled", "disabled");
		var idProveedor = $("#idProveedor").val();
		var nombre = "Factura";
		var fechaEmision = $("#fechaEmision").val();
		var numeroConsecutivo = $("#numeroConsecutivo").val();
		var totalComprobante = $("#totalComprobante").val();
	
		jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'GUARDAR-COMPRA-PROVEEDOR', idProveedor: idProveedor, nombre: nombre, fechaEmision: fechaEmision, numeroConsecutivo: numeroConsecutivo, totalComprobante: totalComprobante})
	      .done(function( data ) {
	      	//console.log(data);
	      	if (data == 0){
	      		alert('La Factura ya se encuentra en las compras a proveedores.');
	      	}else if (data == 1){
		   		$("#confirmacionMensajes").html($("#confirmacionMensajes").html() + "<br><i>Se registró como compra a proveedor correctamente.</i>");
	      	}else{
	      		alert('Ocurrió un error.');
	      	}
	    });			
	}

	function cargarInventario(){
		document.getElementById('cantidad-1').focus();
		$("#cargarInventario").css("display", "block");
	}

	function calcularPrecioVenta(utilidad, numeroLinea){
		if (utilidad == ""){
			utilidad = 0;
		}
		var precioInterno = parseFloat($("#precioInterno-"+numeroLinea).val());
		var precioVenta = precioInterno+(precioInterno * (utilidad/100));
		$("#precioVenta-"+numeroLinea).val(parseFloat(precioVenta));
	}

	function agregarProducto(numeroLinea){
		$("#boton-"+numeroLinea).attr("disabled", "disabled");
		var mayor = '<?php echo $mayor?>';

		var cantidad = $("#cantidad-"+numeroLinea).val();
		var impuesto = $("#impuesto-"+numeroLinea).val();
		var codigoInterno = $("#codigoInterno-"+numeroLinea).val();
		var precioInterno = $("#precioInterno-"+numeroLinea).val();
		var utilidad = $("#utilidad-"+numeroLinea).val();
		var precioVenta = $("#precioVenta-"+numeroLinea).val();
		var nombre = $("#nombre-"+numeroLinea).val();
		var idProveedor = $("#idProveedor").val();
	
		jQuery.post( "funcionesAJAXFacturas.php", { f: 'GUARDAR-PRODUCTO', codigoInterno: codigoInterno, cantidad: cantidad, precioInterno: precioInterno, utilidad: utilidad, precioVenta: precioVenta, idProveedor: idProveedor, nombre: nombre, impuesto: impuesto})
	      .done(function( data ) {
	      	console.log(data);
	      	if (data == 0){
	      		//alert('La Factura ya se encuentra en las cuentas por pagar.');
	      	}else if (data == 1){
	      		siguienteLinea = parseInt(numeroLinea) + 1;
		   		if (siguienteLinea <= mayor){
		   			document.getElementById('cantidad-'+siguienteLinea).focus();
		   		}else{
		   			document.getElementById('finalizarProceso').focus();
		   		}
	      	}else{
	      		alert('Ocurrió un error.');
	      	}
	    });	
	}

	function modificarProducto(numeroLinea){
		$("#boton-"+numeroLinea).attr("disabled", "disabled");
		var mayor = '<?php echo $mayor?>';

		var cantidad = $("#cantidad-"+numeroLinea).val();
		var impuesto = $("#impuesto-"+numeroLinea).val();
		var codigoInterno = $("#codigoInterno-"+numeroLinea).val();
		var precioInterno = $("#precioInterno-"+numeroLinea).val();
		var utilidad = $("#utilidad-"+numeroLinea).val();
		var precioVenta = $("#precioVenta-"+numeroLinea).val();
		var nombre = $("#nombre-"+numeroLinea).val();
		var idProveedor = $("#idProveedor").val();
	
		jQuery.post( "funcionesAJAXFacturas.php", { f: 'MODIFICAR-PRODUCTO', codigoInterno: codigoInterno, cantidad: cantidad, precioInterno: precioInterno, utilidad: utilidad, precioVenta: precioVenta, idProveedor: idProveedor, nombre: nombre, impuesto: impuesto})
	      .done(function( data ) {
	      	//console.log(data);
	      	if (data == 0){
	      		//alert('La Factura ya se encuentra en las cuentas por pagar.');
	      	}else if (data == 1){
	      		siguienteLinea = parseInt(numeroLinea) + 1;
		   		if (siguienteLinea <= mayor){
		   			document.getElementById('cantidad-'+siguienteLinea).focus();
		   		}else{
		   			document.getElementById('finalizarProceso').focus();
		   		}
	      	}else{
	      		alert('Ocurrió un error.');
	      	}
	    });	
	}

</script>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>