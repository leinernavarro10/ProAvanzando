<?php  include ("../conn/connpg.php");

controlAcceso('P-2');


 include("../includes/headerLimpio.php");
?>
<div class="row" style="padding: 5px">

<div class="col-md-12" id='errorNuevoPersona'> 
	
</div> 
					<div class="col-md-12"> 
						<div class="panel panel-dark">
							<div class="panel-heading" style="background: #044701;color: #FFFFFF;"> 
								<div class="panel-title">Nueva Persona</div> 
							</div> 
					

						<form role="form" action="javascript:guardar();" class="form-horizontal form-groups-bordered panel-body " id="formulario" method="post" onchange="cambio()" >
							<input type="hidden" id="f" name="f" value="GUARDAR-NUEVA-PERSONA">
							<div class="form-group">
								<label for="tipoIdentificacion" class="col-sm-1 control-label">Tipo de identificación:</label>
								<div class="col-sm-3">
									<select name="tipoIdentificacion" id="tipoIdentificacion" class="form-control" required>
							          <option value="01">Cédula física</option>
							          <option value="02">Cédula jurídica</option>
							          <option value="03">DIMEX</option>
							          <option value="04">NITE</option>
							        </select>
								</div>

								<label for="identificacion" class="col-sm-2 control-label">Identificación:</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="identificacion" id="identificacion"  autocomplete="off" id="identificacion" onblur="obtenerPersona(this.value)" required>

								</div>

								<div class="col-sm-3">
									 <span id="cargador" style="display: none;"><img src="../assets/images/cargando.gif" width="20px"></span><br>
									
								</div>

							</div>
						
							<div class="form-group">
								<label for="nombre" class="col-sm-1 control-label">Nombre:</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="nombre" autocomplete="off" id="nombre" required>
								</div>

								<label for="tipoCliente" class="col-sm-2 control-label">Tipo Persona:</label>
								<div class="col-sm-2">
									<select name="tipoCliente" id="tipoCliente" class="form-control">
							          <option value="0">Cliente</option>
							          <option value="1">Proveedor</option>
							      <?php if(revisarPrivilegios('P-3')){?>
							          <option value="2">Socios</option>
							          <option value="3">Usuario del sistema</option>
							      <?php }?>
							       <option value="4">Emplado</option>
							        </select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="nomComercial" class="col-sm-1 control-label">Nombre Comercial:</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="nomComercial" autocomplete="off" id="nomComercial">
								</div>

								<label for="sexo" class="col-sm-2 control-label">Genero:</label>
								<div class="col-sm-2">
									<select name="sexo" id="sexo" class="form-control">
							          <option value="1">Hombre</option>
							          <option value="2">Mujer</option>
							        </select>
								</div>
							</div>
							

							<div class="form-group">
								<label for="idProvincia" class="col-sm-1 control-label">Provincia:</label>
								
								<div class="col-sm-2">
									<select class="form-control" name="idProvincia" id="idProvincia" onchange="cambiarProvincia(this.value)" required>
									  <?php  
									  $sql2 = "SELECT * FROM tubicacionFE GROUP BY provincia ORDER BY provincia ASC";
									  $query2 = mysql_query($sql2, $conn);
									  while ($row2=mysql_fetch_assoc($query2)) {
									  ?>
							          <option value="<?php echo $row2['provincia']?>"><?php echo utf8_encode($row2['nombreProvincia'])?></option>
							          <?php  } ?>
							        </select>
								</div>
							
								<label for="canton" class="col-sm-1 control-label">Cantón:</label>
								<div class="col-sm-2">
									<div id="canton"><center><img src='../assets/images/cargando.gif' width='30px'>Cargando... </center></div>
								</div>
							
								<label for="distrito" class="col-sm-1 control-label">Distrito:</label>
								<div class="col-sm-2">
									<div id="distrito"><center><img src='../assets/images/cargando.gif' width='30px'>Cargando... </center></div>
								</div>

								<label for="barrio" class="col-sm-1 control-label">Barrio:</label>
								<div class="col-sm-2">
									<div id="barrio"><center><img src='../assets/images/cargando.gif' width='30px'>Cargando... </center></div>
								</div>
							</div>

							<div class="form-group">
								<label for="direccion" class="col-sm-1 control-label">Dirección:</label>
								
								<div class="col-sm-11">
									<textarea class="form-control" name="direccion" autocomplete="off" id="direccion"></textarea>
								</div>
							</div>
							
							<div class="form-group">
								<label for="nacionalidad" class="col-sm-1 control-label">Nacionalidad</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="nacionalidad" autocomplete="off" id="nacionalidad">
								</div>

								<label for="fecha" class="col-sm-1 control-label">Fecha Nacimiento:</label>
								<div class="col-sm-3">
									<div class="input-group"> 
										<input type="text" name="fecha" autocomplete="off" id="fecha" class="form-control datepicker" data-start-view="2" data-format="dd/mm/yyyy"> 
										<div class="input-group-addon"> 
											<a href="#"><i class="entypo-calendar"></i></a> 
										</div> 
									</div>
									
								</div>

								<div class="col-sm-4" style="text-align: right;">
									<div class="btn-group">
										<button type="submit" id="btnGuadarPer" class="btn btn-success" style="display: none;"  name="guardar">Guardar</button>
									</div>
									<div class="btn-group">
										<button type="button" id="btnCancelarPer" class="btn btn-red" style="display: none;" onclick="cerrarModalPerson()">Cancelar</button>
									</div>
									
								</div>
							</div>
						</form>
	 					</div>
					</div>
</div> 
			
<script type="text/javascript">

function obtenerPersona(cedula){

    if (cedula != ''){
    	var cedula2=cedula;
        var num = cedula;
        var str1=num;
        var str2=num;
        var str3=num;
        var n1=str1.substr(0,1);
        var n2=str2.substr(1,4);
        var n3=str3.substr(5,4);
        var cedula = n1+'-'+n2+'-'+n3;
        document.getElementById('cargador').style.display = 'inline-block';

        $.ajax({
        type: "POST",
        url: "../personas/personas.php",
        data: {cedula: ""+cedula+""}
        }).done(function( html ) {
        		$("#modalNuevaPersonas").find("#errorNuevoPersona").html("");
             if (html == ''){
                document.getElementById('cargador').style.display = 'none';
                	$("#modalNuevaPersonas").find("#errorNuevoPersona").html("<div class='alert alert-warning'><strong>Error!</strong> No se encuentra número de cedula. </div>");
                $("#modalNuevaPersonas").find('#errorNuevoPersona').css( "opacity", 10 );
                $("#modalNuevaPersonas").find('#errorNuevoPersona').animate({
                opacity: 0,
                }, 2500, function() {
                $("#modalNuevaPersonas").find("#errorNuevoPersona").html("");
                });
             }else{
                var myarr = html.split("||");
                cadena1 = myarr[1].replace(/(^\s*)|(\s*$)/g,"");
                cadena2 = myarr[2].replace(/(^\s*)|(\s*$)/g,"");
                cadena3 = myarr[3].replace(/(^\s*)|(\s*$)/g,"");
                document.getElementById('nombre').value = cadena2+' '+cadena3+' '+cadena1;
                
                if (myarr[4] == '0'){
                    document.getElementById('sexo').value = "2";
                }else{
                    document.getElementById('sexo').value = "1";
                }
                fechaNac = myarr[5].replace(' 00:00:00','');
                var fecha = fechaNac.split("-");
                var dia = parseInt(fecha[2],10);
                var mes = parseInt(fecha[1],10);
				document.getElementById('nacionalidad').value = 'Costa Rica';
                document.getElementById('fecha').value = dia+"/"+mes+"/"+fecha[0];
                /*document.getElementById('m_nac').value = mes;
                document.getElementById('a_nac').value = fecha[0];*/

                verpersonaNuevo(cedula2);
                
             }
        });
    }
}

function verpersonaNuevo(ced){
	$.post("../personas/funcionesAJAXGeneral.php", { f: "VER-PERSONA", ced: ced})
	    .done(function(data) {
	    	if(data=='Error'){
	           	$("#modalNuevaPersonas").find("#errorNuevoPersona").html("<div class='alert alert-danger'><strong>Error!</strong> Ya existe una persona con el mismo número de cedula </div>");
	           	document.getElementById('sexo').value = "1";
	           	document.getElementById('nombre').value = "";
	           	document.getElementById('identificacion').value = "";
                document.getElementById('fecha').value = "";
	        }
	        document.getElementById('cargador').style.display = 'none';
	    });
}

	
	function guardar(){
		 $("#modalNuevaPersonas").find("#errorNuevoPersona").html("<center><img src='../assets/images/cargando.gif'  width='20px'></center>");
		 $.ajax({                        
           type: "POST",                 
           url: '../personas/funcionesAJAXGeneral.php',                     
           data: $("#formulario").serialize(), 
           success: function(data)             
           {
	           	if(data=='Error'){
	           		
	           		$("#modalNuevaPersonas").find("#errorNuevoPersona").html("<div class='alert alert-danger'><strong>Error!</strong> Ya existe una persona con el mismo número de cedula </div>");
	           	}else{
	           		$("#modalNuevaPersonas").find("#cargadorPersona").html("");
	             	$("#modalNuevaPersonas").find('#closeModalPerson').show('400'); 
	             	 $('#modalNuevaPersonas').modal('hide');

	             	 $('#modalNuevaPersonas').on('hidden.bs.modal', function () {
				       	$('#modalPersonas').modal(); 
	             		$('#modalPersonas').on('shown.bs.modal', function () {
				        	editarUsuario(data,''); 
				        	$(this).find('#txtBuscarP').removeAttr('disabled').val("");
				    	});
				    });

	             	
					             	
	             	
	           		
	           	}
           }
       });
	}
	

	var numcambio=0;
	function cambio(){
		numcambio++;
		$("#modalNuevaPersonas").find('#btnGuadarPer').show('400');
		 $("#modalNuevaPersonas").find('#btnCancelarPer').show('400');
		 $("#modalNuevaPersonas").find('#closeModalPerson').hide('400');
		 $("#modalNuevaPersonas").find('#txtBuscarP').attr('disabled', 'disabled');
	}

	function cerrarModalPerson(){
		if(confirm("¿Desea salir sin guardar los cambios?")){
			$('#modalNuevaPersonas').modal('hide');
		}
	}

function cambiarProvincia(){
		var idCantonCLI = '0';
		var idProvincia = $("#modalNuevaPersonas").find("#idProvincia").val();
		$.post( "../personas/funcionesAJAXGeneral.php", { f: "CARGAR-PROVINCIA-CLI", idProvincia: idProvincia, idCantonCLI: idCantonCLI})
	    .done(function( data ) {
	      $("#modalNuevaPersonas").find("#canton").html(data);
	      cambiarCanton();
	    });
	}

	function cambiarCanton(){
		var idCantonCLI = '0';
		var idDistritoCLI = '0';
		var idProvincia = $("#modalNuevaPersonas").find("#idProvincia").val();
		var idCanton = $("#modalNuevaPersonas").find("#idCanton").val();
		jQuery.post( "../personas/funcionesAJAXGeneral.php", { f: "CARGAR-DISTRITO-CLI", idProvincia: idProvincia, idCanton: idCanton, idCantonCLI: idCantonCLI,idDistritoCLI: idDistritoCLI})
	    .done(function( data ) {
	      $("#modalNuevaPersonas").find("#distrito").html(data);
	      cambiarDistrito();
	      //cargarDireccion();
	    });
	}

	function cambiarDistrito(){
		var idBarrioCLI = '0';
		var idProvincia = $("#modalNuevaPersonas").find("#idProvincia").val();
		var idCanton = $("#modalNuevaPersonas").find("#idCanton").val();
		var idDistrito = $("#modalNuevaPersonas").find("#idDistrito").val();
		jQuery.post( "../personas/funcionesAJAXGeneral.php", { f: "CARGAR-BARRIO-CLI", idProvincia: idProvincia, idCanton: idCanton, idDistrito: idDistrito, idBarrioCLI: idBarrioCLI})
	    .done(function( data ) {
	      $("#modalNuevaPersonas").find("#barrio").html(data);
	      //cargarDireccion();
	    });
	}

	cambiarProvincia();
	
</script>


<!-- FIN CONTENIDO -->

<?php include("../includes/footerLimpio.php");?>