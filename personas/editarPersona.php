<?php  include ("../conn/connpg.php");

controlAcceso('P-0');


 include("../includes/headerLimpio.php");
?>
<div class="row" style="padding: 5px">

	<div class="panel minimal minimal-gray"> 
		<div class="panel-heading"> 
			<div class="panel-title"><h4>Modificar Datos</h4></div> 
			<div class="panel-options"> 
				<ul class="nav nav-tabs"> 
					<li class="active">
						<a href="#profile-1" data-toggle="tab" aria-expanded="true">Perfil</a>
					</li> 
					<li class="">
						<a href="#profile-2" data-toggle="tab" aria-expanded="false">Datos del Cliente</a>
					</li> 
				</ul> 
			</div> 
		</div> 
		<div class="panel-body"> 
			<div class="tab-content"> 
				<div class="tab-pane active" id="profile-1"> 
					<div class="col-md-9"> 
						<div class="panel panel-dark">
							<div class="panel-heading" style="background: #044701;color: #FFFFFF;"> 
								<div class="panel-title">Cliente</div> 
							</div> 
					<?php 
					$idCliente = $_POST['id'];
					$sql = "SELECT * FROM tpersonas WHERE id = '$idCliente' ";
					$query = mysql_query($sql, $conn);
					while ($row=mysql_fetch_assoc($query)) {
					?>

						<form role="form" action="javascript:guardar();" class="form-horizontal form-groups-bordered panel-body " id="formulario" method="post" onchange="cambio()" >
							<input type="hidden" id="f" name="f" value="GUARDAR-PERSONA">
							<div class="form-group">
								<label for="id" class="col-sm-1 control-label">ID:</label>
								<div class="col-sm-1">
									<input type="text" class="form-control" id="id" readonly name="idCliente" value="<?php echo $row['id']?>">
								</div>

								<label for="tipoIdentificacion" class="col-sm-2 control-label">Tipo de identificación:</label>
								<div class="col-sm-3">
									<select name="tipoIdentificacion" id="tipoIdentificacion" class="form-control" required>
							          <option value="01" <?php  if ($row['tipoIdentificacion'] == '01'){echo 'selected="selected"';} ?>>Cédula física</option>
							          <option value="02" <?php  if ($row['tipoIdentificacion'] == '02'){echo 'selected="selected"';} ?>>Cédula jurídica</option>
							          <option value="03" <?php  if ($row['tipoIdentificacion'] == '03'){echo 'selected="selected"';} ?>>DIMEX</option>
							          <option value="04" <?php  if ($row['tipoIdentificacion'] == '04'){echo 'selected="selected"';} ?>>NITE</option>
							        </select>
								</div>

								<label for="identificacion" class="col-sm-2 control-label">Identificación:</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="identificacion" autocomplete="off" id="identificacion" value="<?php echo $row['identificacion']?>" required>
								</div>
							</div>
						
							<div class="form-group">
								<label for="nombre" class="col-sm-1 control-label">Nombre:</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="nombre" autocomplete="off" id="nombre" value="<?php echo $row['nombre']?>" required>
								</div>

								<label for="tipoCliente" class="col-sm-2 control-label">Tipo Persona:</label>
								<div class="col-sm-2">
									<select name="tipoCliente" id="tipoCliente" class="form-control">
							          <option value="0" <?php  if ($row['tipoPersona'] == '0'){echo 'selected="selected"';} ?>>Cliente</option>
							          <option value="1" <?php  if ($row['tipoPersona'] == '1'){echo 'selected="selected"';} ?>>Proveedor</option>
							      <?php if(revisarPrivilegios('P-3')){?>
							          <option value="2" <?php  if ($row['tipoPersona'] == '2'){echo 'selected="selected"';} ?>>Socios</option>
							          <option value="3" <?php  if ($row['tipoPersona'] == '3'){echo 'selected="selected"';} ?>>Usuario del sistema</option>
							      <?php }?>
							      <option value="4" <?php  if ($row['tipoPersona'] == '4'){echo 'selected="selected"';} ?>>Empleado</option>
							        </select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="nomComercial" class="col-sm-1 control-label">Nombre Comercial:</label>
								<div class="col-sm-7">
									<input type="text" class="form-control" name="nomComercial" autocomplete="off" id="nomComercial" value="<?php echo $row['nomComercial']?>">
								</div>

								<label for="sexo" class="col-sm-2 control-label">Genero:</label>
								<div class="col-sm-2">
									<select name="sexo" id="sexo" class="form-control">
							          <option value="1" <?php  if ($row['sexo'] == '1'){echo 'selected="selected"';} ?>>Hombre</option>
							          <option value="2" <?php  if ($row['sexo'] == '2'){echo 'selected="selected"';} ?>>Mujer</option>
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
							          <option value="<?php echo $row2['provincia']?>" <?php  if ($row2['provincia'] == $row['idProvincia']){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreProvincia'])?></option>
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
									<textarea class="form-control" name="direccion" autocomplete="off" id="direccion"><?php echo $row['direccion']?></textarea>
								</div>
							</div>
							
							<div class="form-group">
								<label for="nacionalidad" class="col-sm-1 control-label">Nacionalidad</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" name="nacionalidad" autocomplete="off" id="nacionalidad" value="<?php echo $row['nacionalidad']?>">
								</div>

								<label for="fecha" class="col-sm-1 control-label">Fecha Nacimiento:</label>
								<div class="col-sm-3">
									<div class="input-group"> 
										<input type="text" name="fecha" autocomplete="off" id="fecha" class="form-control datepicker" data-format="dd/mm/yyyy" value="<?php echo $row['fechaNac']?>"> 
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

						<div class="panel panel-dark" style="display: none;" id="addContacto">
							<div class="panel-heading" style="background: #DD5300;color: #FFFFFF;"> 
								<div class="panel-title"><span id='noContacto1'>Nuevo</span> <span id="noContacto"></span></div> 
							</div> 
					
<?php if(revisarPrivilegios('P-4') OR revisarPrivilegios('P-5')){?>
							<div class="panel-body">
								<div class="form-group">
									<form action="javascript: guardarContacto()" method="get" id="formContacto">
										<input type="hidden" id="f1" name="f" value="">
										<input type="hidden" id="id" name="idCliente" value="<?php echo $row['id']?>">
										<input type="hidden" id="tipoConta" name="tipoConta">
										<input type="hidden" id="idCont" name="idCont">
										<table class="table responsive"> 
											<thead> 
												<tr> 
												    <th id="thtptipo"></th>
												    <th>Detalle</th>
												    <th>Activo</th>
												    <th></th>
												 </tr> 
											</thead> 
											<tbody> 
												<tr> 
												    <td><input type="text" class="form-control" name="dato" id="dato" required=""></td>
												    <td><input type="text" class="form-control" name="detalle" id="detalle"></td>
												    <td><input type="checkbox" value="1" name="estado" id="estado"></td>
												    <td><button class="btn btn-success" type="submit" title="Guardar"><i class="entypo-floppy"></i></button> 
												    	<button class="btn btn-red" type="button" title="Eliminar" id="eliminarCont" style="display: none;" onclick="borrarCont();"><i class="entypo-trash"></i></button> </td>
												 </tr>
											</tbody>
										</table>
										<center><i>Si el correo está activo se enviaran automáticamente todos los comprobantes</i></center>
									</form>
								</div>
							</div>
<?php }?>

						</div>


					</div>
					<div class="col-md-3">
						<div class="panel panel-dark"> 
							<div class="panel-heading" style="background: #470101;color: #FFFFFF;"> 
								<div class="panel-title">Contacto</div> 
							</div> 

							<div class="panel-body no-padding">
								<div class="panel minimal minimal-gray"> 
									<div class="panel-heading"> 
										<div class="panel-options"> 
											<ul class="nav nav-tabs"> 
												<li class="active">
													<a href="#telefono" data-toggle="tab" aria-expanded="true">Telefonos</a>
												</li> 
												<li class="">
													<a href="#correos" data-toggle="tab" aria-expanded="false">Correos</a>
												</li> 
											</ul> 
										</div> 
									</div> 
									<div class="panel-body"> 
										<div class="tab-content"> 
											<div class="tab-pane active" id="telefono">
											<?php if(revisarPrivilegios('P-5')){?>
												<center>
													<button type="button" class="btn btn-primary btn-sm" onclick="nuevo(1)">Nuevo</button>
												</center>
											<?php }?>
												<?php 
													$sqlCont="SELECT * FROM tcontacto WHERE idPersona ='".$row['id']."' AND tipo=1 ";
													$queryCont=mysql_query($sqlCont,$conn)or die(mysql_error());
													if(mysql_num_rows($queryCont)>0){
														?>	
														<hr>

<div style="width: 100%;max-width: 100%;overflow-x:scroll" >
												            <table class="table responsive"> 
												                <thead> 
												                  <tr> 
												                    <th>Telefono</th> 
												                    <th>Detalle</th>
												                  </tr> 
												                </thead> 
												                <tbody> 
														<?php
														while ($rowCont=mysql_fetch_assoc($queryCont)) {
															$idCont=$rowCont['id'];
															$dato=$rowCont['dato'];
															$detalle=$rowCont['detalle'];
															$estado=$rowCont['estado'];
															$tipo=$rowCont['tipo'];
															?>
															 <tr class="hover" <?php if(revisarPrivilegios('P-4')){?> onclick="verEditarCont('<?php echo $idCont;?>','<?php echo $dato;?>','<?php echo $detalle;?>','<?php echo $estado;?>','<?php echo $tipo;?>')" <?php }?> > 
											                    <td><?php echo $rowCont['dato'];?></td> 
											                    <td><?php echo $rowCont['detalle'];?></td> 
											                  </tr>   
		
															<?php
														}
														?>
														</tbody>
													</table>
</div>
														<?php
													}else{
														echo "<br><center>No se encuentran datos.</center>";
													}
												?>

											</div> 
											<div class="tab-pane" id="correos">
											<?php if(revisarPrivilegios('P-5')){?>
												<center>
													<button type="button" class="btn btn-primary btn-sm" onclick="nuevo(0)">Nuevo</button>
												</center>
											<?php }?>						
												<?php 
													$sqlCont="SELECT * FROM tcontacto WHERE idPersona ='".$row['id']."' AND tipo=0 ";
													$queryCont=mysql_query($sqlCont,$conn)or die(mysql_error());
													if(mysql_num_rows($queryCont)>0){
														?>	
														<hr>

<div style="width: 100%;max-width: 100%;overflow-x:scroll ">
												            <table class="table responsive"> 
												                <thead> 
												                  <tr> 
												                    <th>Correo</th>
												                    <th>Activo</th> 
												                    <th>Detalle</th>
												                  </tr> 
												                </thead> 
												                <tbody> 
														<?php
														while ($rowCont=mysql_fetch_assoc($queryCont)) {
															$idCont=$rowCont['id'];
															$dato=$rowCont['dato'];
															$detalle=$rowCont['detalle'];
															$estado=$rowCont['estado'];
															$tipo=$rowCont['tipo'];
															?>
															 <tr class="hover" <?php if(revisarPrivilegios('P-4')){?> onclick="verEditarCont('<?php echo $idCont;?>','<?php echo $dato;?>','<?php echo $detalle;?>','<?php echo $estado;?>','<?php echo $tipo;?>')" <?php }?> > 

											                    <td><?php echo $rowCont['dato'];?></td>
											                    <td><?php switch ($rowCont['estado']){
											                    	case 1:
											                    		echo "SI";
											                    		break;
											                    	case 0:
											                    		echo "No";
											                    		break;
											                    	
											                    } ?></td> 
											                    <td><?php echo $rowCont['detalle'];?></td> 
											                  </tr>   
		
															<?php
														}
														?>
														</tbody>
													</table>
</div>
													
														<?php
													}else{
														echo "<br><center>No se encuentran datos.</center>";
													}
												?>

											</div> 
										</div> 
									</div> 
								</div>
							</div>
						</div> 
					</div>

				</div> 
				<div class="tab-pane" id="profile-2"> <strong>Entire any had depend and figure winter</strong> <p>There worse by an of miles civil. Manner before lively wholly am mr indeed expect. Among every merry his yet has her. You mistress get dashwood children off. Met whose marry under the merit. In it do continual consulted no listening. Devonshire sir sex motionless travelling six themselves. So colonel as greatly shewing herself observe ashamed. Demands minutes regular ye to detract is.</p> <p>For norland produce age wishing. To figure on it spring season up. Her provision acuteness had excellent two why intention. As called mr needed praise at. Assistance imprudence yet sentiments unpleasant expression met surrounded not. Be at talked ye though secure nearer.</p> <p>Letter wooded direct two men indeed income sister. Impression up admiration he by partiality is. Instantly immediate his saw one day perceived. Old blushes respect but offices hearted minutes effects. Written parties winding oh as in without on started. Residence gentleman yet preserved few convinced. Coming regret simple longer little am sister on. Do danger in to adieus ladies houses oh eldest. Gone pure late gay ham. They sigh were not find are rent.</p> 
				</div> 
			</div> 
		</div> 
	</div>
</div>





<script type="text/javascript">

	function nuevo(val){
		var leyenda;
		if(val==1){
			leyenda='Telefono';
		}else{
			leyenda='Correo';
		}
		$("#addContacto").find("#noContacto1").html('Nuevo');
		$("#addContacto").show('slow');
		$("#addContacto").find("#tipoConta").val(val);
		$("#addContacto").find("#thtptipo").html(leyenda);
		$("#addContacto").find("#noContacto").html(leyenda);
		$("#addContacto").find("#dato").focus();
		$("#addContacto").find("#f1").val('GUARDAR-CONTACTO');

		$("#addContacto").find("#eliminarCont").hide('slow');
		$("#addContacto").find("#idCont").val("");
		$("#addContacto").find("#dato").val("");
		$("#addContacto").find("#detalle").val("");
	}

	function guardarContacto(){
		$("#cargadorPersona").html("<center><img src='../assets/images/cargando.gif'  width='20px'></center>");
		 $.ajax({                        
           type: "POST",                 
           url: '../personas/funcionesAJAXGeneral.php',                     
           data: $("#formContacto").serialize(), 
           success: function(data)             
           {
            $("#modalPersonas").find("#cargadorPersona").html("");
            editarUsuario(<?php echo $idCliente?>,'');        
           }
       });
	}
	
	function borrarCont(){
		$("#cargadorPersona").html("<center><img src='../assets/images/cargando.gif'  width='20px'></center>");
		$("#addContacto").find("#f1").val('BORRAR-CONTACTO');
		 $.ajax({                        
           type: "POST",                 
           url: '../personas/funcionesAJAXGeneral.php',                     
           data: $("#formContacto").serialize(), 
           success: function(data)             
           {
            $("#modalPersonas").find("#cargadorPersona").html("");
            editarUsuario(<?php echo $idCliente?>,'');        
           }
       });
	}

	function verEditarCont(idCont,dato,detalle,estado,tipo){
		var leyenda;
		if(tipo==1){
			leyenda='Telefono';
		}else{
			leyenda='Correo';
		}
		$("#addContacto").find("#noContacto1").html('Editar');
		$("#addContacto").find("#thtptipo").html(leyenda);
		$("#addContacto").find("#noContacto").html(leyenda);
		$("#addContacto").show('slow');
		$("#addContacto").find("#tipoConta").val(tipo);
		$("#addContacto").find("#dato").focus();
		$("#addContacto").find("#f1").val('EDITAR-CONTACTO');

		$("#addContacto").find("#eliminarCont").show('slow');
		$("#addContacto").find("#idCont").val(idCont);
		$("#addContacto").find("#dato").val(dato);
		$("#addContacto").find("#detalle").val(detalle);

		if(estado==1){
			$("#addContacto").find("#estado").prop('checked', true);
		}else{
			$("#addContacto").find("#estado").prop('checked', false);
		}



	}


	function guardar(){
		 $("#modalPersonas").find("#cargadorPersona").html("<center><img src='../assets/images/cargando.gif'  width='20px'></center>");
		 $.ajax({                        
           type: "POST",                 
           url: '../personas/funcionesAJAXGeneral.php',                     
           data: $("#formulario").serialize(), 
           success: function(data)             
           {
	           	if(data==""){
	           		$("#modalPersonas").find("#cargadorPersona").html("");
	             	$("#modalPersonas").find('#closeModalPerson').show('400');    
	             	editarUsuario(<?php echo $idCliente?>,''); 
	             	$("#modalPersonas").find('#txtBuscarP').removeAttr("disabled"); 
	           	}else{
	           		
	           		$("#modalPersonas").find('#closeModalPerson').show('400');   
	           		editarUsuario(<?php echo $idCliente?>,'');   
	           	}
           }
       });
	}
	

	var numcambio=0;
	function cambio(){
		numcambio++;
		$("#modalPersonas").find('#btnGuadarPer').show('400');
		$("#modalPersonas").find('#btnCancelarPer').show('400');
		$("#modalPersonas").find('#closeModalPerson').hide('400');
		$("#modalPersonas").find('#txtBuscarP').attr('disabled', 'disabled');
	}

	function cerrarModalPerson(){
		if(confirm("¿Desea salir sin guardar los cambios?")){
			$('#modalPersonas').modal('hide');
		}
	}

	function cambiarProvincia(){
		var idCantonCLI = '<?php echo $row['idCanton']?>';
		var idProvincia = $("#idProvincia").val();

		$.post("../personas/funcionesAJAXGeneral.php", { f: "CARGAR-PROVINCIA-CLI", idProvincia: idProvincia, idCantonCLI: idCantonCLI})
	    .done(function( data ) {
	      $("#modalPersonas").find("#canton").html(data);
	      cambiarCanton();
	    });
	}

	function cambiarCanton(){
		var idCantonCLI = '<?php echo $row['idCanton']?>';
		var idDistritoCLI = '<?php echo $row['idDistrito']?>';
		var idProvincia = $("#modalPersonas").find("#idProvincia").val();
		var idCanton = $("#modalPersonas").find("#idCanton").val();
		jQuery.post( "../personas/funcionesAJAXGeneral.php", { f: "CARGAR-DISTRITO-CLI", idProvincia: idProvincia, idCanton: idCanton, idCantonCLI: idCantonCLI,idDistritoCLI: idDistritoCLI})
	    .done(function( data ) {
	      $("#modalPersonas").find("#distrito").html(data);
	      cambiarDistrito();
	      //cargarDireccion();
	    });
	}

	function cambiarDistrito(){
		var idBarrioCLI = '<?php echo $row['idBarrio']?>';
		var idProvincia = $("#modalPersonas").find("#idProvincia").val();
		var idCanton = $("#modalPersonas").find("#idCanton").val();
		var idDistrito = $("#modalPersonas").find("#idDistrito").val();
		jQuery.post( "../personas/funcionesAJAXGeneral.php", { f: "CARGAR-BARRIO-CLI", idProvincia: idProvincia, idCanton: idCanton, idDistrito: idDistrito, idBarrioCLI: idBarrioCLI})
	    .done(function( data ) {
	      $("#modalPersonas").find("#barrio").html(data);
	      //cargarDireccion();
	    });
	}

	cambiarProvincia();
	<?php 
		if(!revisarPrivilegios('P-1')){
	?>
			$('#formulario').find('input, textarea, button, select').attr('disabled','disabled');
	<?php
		}
	?> 
</script>
<?php  } ?>

<!-- FIN CONTENIDO -->

<?php include("../includes/footerLimpio.php");?>