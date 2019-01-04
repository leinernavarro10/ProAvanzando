<?php include ("../conn/connpg.php");



if (isset($_POST['guardarLogotipo'])){

	if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
        if ($_FILES["foto"]["type"]=="image/png")    {
          
          $info = pathinfo($_FILES["foto"]["name"]);
          $extension = $info['extension'];
          $nombre= "logo".rand(100000, 9999999).".png";
          $foto2 = "../assets/images/logoORI.png";
          move_uploaded_file($_FILES['foto']['tmp_name'], $foto2);
        }else{
            $bError = "El formato de la foto tiene que ser PNG.";
        }
    }
}

if (isset($_POST['guardarIsotipo'])){

	if (is_uploaded_file($_FILES["foto"]["tmp_name"])){
        if ($_FILES["foto"]["type"]=="image/png")    {
           
          $info = pathinfo($_FILES["foto"]["name"]);
          $extension = $info['extension'];
          $foto2 = "../assets/images/isoORI.png";
          move_uploaded_file($_FILES['foto']['tmp_name'], $foto2);

        }else{
            $bError = "El formato de la foto tiene que ser PNG.";
        }
    }
}



$pagina="Parámetros";
$skin='green';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("../inicio/includes/menu.php");
 include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb bc-2" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="#"><?php echo $pagina?></a></li>
</ol>

 <h2><?php echo $pagina?></h2>
<br />

<?php include("../includes/alerts.php");?>


<div class="col-md-3">
	<div class="panel panel-dark" id="factura">
		<div class="panel-heading">
			<div class="panel-title">Logos</div>
			<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
			</div>
		</div>
		<div class="panel-body">
			<h3>Logotipo</h3>
				<form role="form" action="" class="form-horizontal" method="post"  enctype="multipart/form-data">
					<center>
						<div style="width: 160px; height: 60px; border: 5px solid #7F7F7F">	
							<img src="../assets/images/logoORI.png?<?=rand()?>" id="fotoLOGO" width="150" height="50">
						</div>
						<br>
						<i>* La imágen debe ser 640 X 200 pixeles para que se mantenga la relación de aspecto.</i>
						<br>
						<input type="file" name="foto" onchange="readLOGO(this, 'fotoLOGO');" class="form-control file2 inline btn btn-primary" accept="image/*" >
						<br><hr>
						<button type="submit" class="btn btn-green" name="guardarLogotipo">Guardar logotipo</button>
					</center>
				</form>
					<hr>
					<h3>Isotipo</h3>
					<form role="form" action="" class="form-horizontal" method="post"  enctype="multipart/form-data">
					<center>
						<div style="width: 60px; height: 60px; border: 5px solid #7F7F7F">	
							<img src="../assets/images/isoORI.png?<?=rand()?>" id="fotoISO" width="50" height="50">
						</div>
						<br>
						<i>* La imágen debe ser 200 X 200 pixeles para que se mantenga la relación de aspecto.</i>
						<br>
						<input type="file" name="foto" onchange="readISO(this, 'fotoISO');" class="form-control file2 inline btn btn-primary" accept="image/*" >
						<br><hr>
						<button type="submit" class="btn btn-green" name="guardarIsotipo">Guardar isotipo</button>
					</center>
				</form>							
		</div>
	</div>

</div>


<div class="col-md-9">
			
	<div class="panel panel-dark" id="factura">
		<div class="panel-heading">
			<div class="panel-title">Parámetros</div>
			<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
			</div>
		</div>
		<div class="panel-body">
				<form role="form" action="" class="form-horizontal" method="post">
					<div class="form-group">
											<label for="valor-13" class="col-sm-3 control-label">Tipo de identificación:</label>
												<div class="col-sm-5">
													<select name="tipocedula" id="valor-13" class="form-control">
											          <option value="01" <?php if ($row['tipocedula'] == '01'){echo 'selected="selected"';} ?>>Cédula física</option>
											          <option value="02" <?php if ($row['tipocedula'] == '02'){echo 'selected="selected"';} ?>>Cédula jurídica</option>
											          <option value="03" <?php if ($row['tipocedula'] == '03'){echo 'selected="selected"';} ?>>DIMEX</option>
											          <option value="04" <?php if ($row['tipocedula'] == '04'){echo 'selected="selected"';} ?>>NITE</option>
											        </select>
												</div>
					</div>
											
					<div class="form-group">
						<label for="valor-3" class="col-sm-3 control-label">Cédula (Jurídica o Física):</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="cedula" autocomplete="off" id="valor-12" value="<?php echo $row['cedula']?>">
						</div>
					</div>
										
											<hr>											
											<center>Dirección:</center>
											<div id="divDireccion" style="border: 1px solid #000000; border-radius: 5px; padding: 10px; margin-bottom: 10px;"></div>

											<center>Cambiar dirección:</center>
											<div class="form-group">
											<label class="col-sm-3 control-label">Provincia:</label>
												<div class="col-sm-5">
													<select class="form-control" onchange="cambiarProvincia(this.value)">
													  <option value="0" <?php if ($row2['provincia'] == 0 || $row2['provincia'] == ''){echo 'selected="selected"';} ?>>Seleccione la provincia</option>
													  <?php 
													  $sql2 = "SELECT * FROM tubicacionFE GROUP BY provincia ORDER BY provincia ASC";
													  $query2 = mysql_query($sql2, $conn);
													  while ($row2=mysql_fetch_assoc($query2)) {
													  ?>
											          <option value="<?php echo $row2['provincia']?>" <?php if ($row2['provincia'] == $row['provincia']){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreProvincia'])?></option>
											          <?php } ?>
											        </select>
												</div>
											</div>

											<div id="divCanton"></div>

											<div id="divDistrito"></div>

											<div id="divBarrio"></div>


											<script type="text/javascript">
												function cambiarProvincia(provincia){

													if (provincia == "0"){
													  $("#divCanton").html("");
													  $("#divDistrito").html("");
												      $("#divBarrio").html("");
													}else{
													
														$.post('../facturas/funcionesAJAXGeneral.php', { f: "CARGAR-CANTONES", provincia: provincia}, function(data) {
															 $("#divCanton").html(data);
														      $("#divDistrito").html("");
														      $("#divBarrio").html("");
														      cargarDireccion();
														});
													}
												  }

												  function cambiarCanton(provincia, canton){
													if (canton == "0"){
													  $("#divDistrito").html("");
												      $("#divBarrio").html("");
													}else{
													  $.post( "../facturas/funcionesAJAXGeneral.php", { f: "CARGAR-DISTRITOS", provincia: provincia, canton: canton})
													    .done(function( data ) {
													      $("#divDistrito").html(data);
													      $("#divBarrio").html("");
													      cargarDireccion();
													    });
													}
												  }

												  function cambiarDistrito(provincia, canton, distrito){
													if (distrito == "0"){
												      $("#divBarrio").html("");
													}else{
													  $.post( "../facturas/funcionesAJAXGeneral.php", { f: "CARGAR-BARRIOS", provincia: provincia, canton: canton, distrito: distrito})
													    .done(function( data ) {
													      $("#divBarrio").html(data);
													      cargarDireccion();
													    });
													}
												  }


												  function guardarBarrio(provincia, canton, distrito, barrio){
													  $.post( "../facturas/funcionesAJAXGeneral.php", { f: "GUARDAR-BARRIO", provincia: provincia, canton: canton, distrito: distrito, barrio: barrio})
													    .done(function( data ) {
													    	
													      cargarDireccion();
													    });
												  }

												  function cargarDireccion(){
													$.post( "../facturas/funcionesAJAXGeneral.php", { f: "CARGAR-DIRECCION"})
												    .done(function( data ) {
												    	
												      $("#divDireccion").html(data);
												    });
												  }

												  cargarDireccion();
											</script>

											<hr>

											
											<div class="form-group">
												<label for="valor-4" class="col-sm-3 control-label">Otras señas:</label>
												
												<div class="col-sm-5">
													<input type="text" class="form-control" name="otrasSenas" autocomplete="off" id="valor-4" value="<?php echo $row['otrasSenas']?>" required>
												</div>
											</div>
											
										
					
				</form>
		
											
							
								
											

											<div class="form-group">
												
												<label for="valor-2" class="col-sm-3 control-label">Último número de factura:</label>
												<?php 
													$sql2 = "SELECT * FROM tfacturas WHERE numero != ''";
													$query2 = mysql_query($sql2, $conn);
													if (mysql_num_rows($query2) == 0){
														$dis = '';
													}else{
														$dis = 'readonly';
													}
												?>
												<div class="col-sm-5">
													<input type="number" required="required"  class="form-control" <?php echo $dis?> name="factura" autocomplete="off" id="valor-2" value="<?php echo $row['factura']?>">
												</div>
											</div>
											

											<div class="form-group">
												
												<label for="valor-17" class="col-sm-3 control-label">Último número de recibo de dinero:</label>
												<?php 
													$sql2 = "SELECT * FROM trecibosDinero WHERE numero != ''";
													$query2 = mysql_query($sql2, $conn);
													if (mysql_num_rows($query2) == 0){
														$dis = '';
													}else{
														$dis = 'readonly';
													}
												?>
												<div class="col-sm-5">
													<input type="number" required="required" class="form-control" <?php echo $dis?> name="recibo" autocomplete="off" id="valor-17" value="<?php echo $row['recibo']?>">
												</div>
											</div>
											

											

											<div class="form-group">
												<input type="hidden" name="tipo-37" value="0">
												<label for="valor-37" class="col-sm-3 control-label">Último Mensaje de Receptor:</label>
												
												<div class="col-sm-5">
													<input type="number"  class="form-control"  name="valor-37" autocomplete="off" id="valor-37" value="<?=$row['valor']?>">
												</div>
											</div>
											
											
			
											<hr>

											
											
											<div class="form-group">
												<label class="col-sm-3 control-label">¿Factura gráfica?:</label>
											
												<div class="col-sm-5">
													<div class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
														<input type="checkbox" 
														<?php 
															if ($row['facturaGrafica'] == 1){
																echo 'checked';
															}
														?> name="facturaGrafica" id="valor-16" value="1"/>
													</div>
												</div>
											</div>

											
											<div class="form-group">
												<label class="col-sm-3 control-label">Tamaño factura:</label> 
												<div class="col-sm-5">
													<div class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
														<input type="checkbox" 
														<?php 
															if ($row['tamanoFactura'] == 1){
																echo 'checked';
															}
														?> name="tamanoFactura" id="valor-20" value="1"/> 
													</div>
													<i>Solo para factura gráfica. <i class='entypo-check'></i>: Hoja Carta / <i class='entypo-cancel'>: Media página</i>
												</div>
											</div>

																						
											<div class="form-group">
												<label class="col-sm-3 control-label">¿Imprimir copia de factura?</label>
												
												<div class="col-sm-5">
													<div class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
														<input type="checkbox" 
														<?php 
															if ($row['imprimirCopia'] == 1){
																echo 'checked';
															}
														?> name="imprimirCopia" id="valor-21" value="1"/>
													</div>
													<i>Solo para factura gráfica tamaño hoja carta</i>
												</div>

											</div>

										
							

					<center>
					<div class="btn-group">
						<button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
					</div>
					<div class="btn-group">
						<a href="index.php"><button type="button" class="btn btn-red">Cancelar</button></a>
					</div>
				</center>
				</form>

		</div>



	<div class="panel panel-dark" id="factura">
		
		<!-- panel head -->
		<div class="panel-heading">
			<div class="panel-title">Factura electrónica</div>
			
			<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
			</div>
		</div>
		
		<!-- panel body -->

		<div class="panel-body">
				<form role="form" action="" class="form-horizontal" method="post"  enctype="multipart/form-data">
													
										<div class="form-group">
													<label class="col-sm-8 control-label">Factura electrónica activada:</label>
													<div class="col-sm-3">
														<div class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
															<input type="checkbox" 
															<?php 
																if ($row['facturaElectronica'] == 1){
																	echo 'checked';
																}
															?> name="facturaElectronica" id="valor-22" value="1"/>
														</div>
													</div>
												</div>
												<center>
												<?php


												if (file_exists("archivos/".$row['firmador'].".p12") AND $row['firmador']!=""){
													echo '<div style="color: #005500">SE HA ENCONTRADO UN ARCHIVO</div>';
												}else{
													echo '<div style="color: #ff0000">NO HAY FIRMA EN EL SISTEMA</div>';
												}
												?>
												</center>
												<br>
												<center>Archivo firma digital<br>
													<input type="hidden" name="firmaViejo" value="<?php echo $row['firmador'];?>">
												<input type="file" name="firma">
												<br><br>
												</center>

												<div class="form-group">
													<label for="valor-23" class="col-sm-4 control-label">Usuario FE:</label>
													
													<div class="col-sm-6">
														<input type="text" class="form-control" name="usuarioFE" autocomplete="off" id="valor-23" value="<?php echo $row['usuarioFE']?>">
													</div>
												</div>

												<div class="form-group">
													<label for="valor-23" class="col-sm-4 control-label">PIN FE:</label>
													
													<div class="col-sm-6">
														<input type="text" class="form-control" name="pinFE" autocomplete="off" id="valor-24" value="<?php echo $row['pinFE']?>">
													</div>
												</div>
												
												<div class="form-group">
													<label for="valor-23" class="col-sm-4 control-label">Password: </label>
													
													<div class="col-sm-6">
														<input type="text" class="form-control" name="passFE" autocomplete="off" id="valor-31" value="<?php echo $row['passFE']?>">
													</div>
												</div>
												

												
												<div class="form-group">
													<label class="col-sm-8 control-label">Producción:ON / Pruebas:OFF</label>
													<div class="col-sm-3">
														<div class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
															<input type="checkbox" 
															<?php 
																if ($row['produccion'] == 1){
																	echo 'checked';
																}
															?> name="produccion" id="valor-32" value="1"/>
														</div>
													</div>
												</div>
												<center>
												
												<center>
												<button type="submit" class="btn btn-green" name="guardarFE">Guardar</button>
												</center>
											</form>
										
		</div>
	</div>

</div>

<script type="text/javascript">


function readLOGO(input, id) {
	  if (input.files && input.files[0]) {
	  var reader = new FileReader();
	  reader.onload = function (e) {
	  jQuery('#'+id)
	  .attr('src', e.target.result)
	  .attr('height', 50)
	  .attr('width', 150)
	  };
	  reader.readAsDataURL(input.files[0]);
	  }
  }

  function readISO(input, id) {
	  if (input.files && input.files[0]) {
	  var reader = new FileReader();
	  reader.onload = function (e) {
	  jQuery('#'+id)
	  .attr('src', e.target.result)
	  .attr('height', 50)
	  .attr('width', 50)
	  };
	  reader.readAsDataURL(input.files[0]);
	  }
  }

</script>

	<!-- Bottom scripts (common) -->
	<script type="text/javascript" src="../assets/js/gsap/TweenMax.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>


	<!-- Imported scripts on this page -->

	<!-- Demo Settings -->
<?php if ($guardado == 1){ ?>
<script type="text/javascript">
	mensajeExito("Las parametros se guardaron correctamente.",1);
</script>
<?php } ?>
<?php if ($bError != ""){ ?>
<script type="text/javascript">
	mensajeExito("<?php echo $bError;?>",2);
</script>
<?php } ?>

<?php if ($_GET['etp'] == "etp"){ ?>
<script type="text/javascript">
	$(".page-container").addClass('sidebar-collapsed');
	$(".sidebar-user-info").hide();
	$("#main-menu").hide(); 

	mensajeExito("Debes configurar la cuenta de facturación para poder continuar ",2);
</script>
<?php } ?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>