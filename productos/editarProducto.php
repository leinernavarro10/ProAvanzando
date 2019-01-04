<?php include("../conn/connpg.php");?>	
<?php 
//controlAcceso(35);

//START PHP CODE
$busqueypagi = $_GET['busqueypagi'];
$busquedas = 'bNombre='.$_GET['bNombre'];
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
if (isset($_POST['guardar'])){
  $bError = "";
  $idOld = $_POST['idOld'];
  $idProducto = $_POST['idProducto'];
  $nombre = $_POST['nombre'];
  $codigo = $_POST['codigo'];
  $descripcion = $_POST['descripcion'];
  $proveedor = $_POST['proveedor']; 
  $moneda = $_POST['moneda']; 
  $costoUnitario = $_POST['costoUnitario'];
  $precioVenta = $_POST['precioVenta']; 
  $excento = $_POST['excento']; 
  $porcentajeUtilidad = $_POST['porcentajeUtilidad'];
  $minimo = $_POST['minimo']; 
  $existencia = $_POST['existencia'];
  $familiaProducto = $_POST['familiaProducto']; 
  $transporte = $_POST['transporte']; 
  $tipo = $_POST['tipo']; 
 
  		$sql = "SELECT * FROM tproductos WHERE codigo = '$idProducto' AND id != '$idOld' AND estado = 1 AND sistema = '".$cuentaid."'";
	  	$query = mysql_query($sql, $conn);
	  	if (mysql_num_rows($query) == 0){
	  		$sqlEditar = "UPDATE tproductos SET 
		  	codigo = '$idProducto',
		  	nombre = '$nombre',
		  	
		  	descripcion = '$descripcion', 
		  	proveedor = '$proveedor', 
		  	costoUnitario = '$costoUnitario', 
		  	precioVenta = '$precioVenta', 
		  	excento = '$excento', 
		  	porcentajeUtilidad = '$porcentajeUtilidad', 
		  	minimo = '$minimo',
		  	existencia = '$existencia',
		  	familiaProducto = '$familiaProducto',
		  	transporte = '$transporte',
		  	tipo = '$tipo' 
		  	WHERE id = '$idOld' AND sistema = '".$cuentaid."'";
	  	}else{
	  		$bError = "No se puede cambiar el id de producto porque ya existe uno con el mismo id.";
	  	}	
 

  if ($bError == ""){
	$query = mysql_query($sqlEditar, $conn)or die(mysql_error());
	//registrarBitacora($userid, "Modificar producto: ".$nombre, $conn);  
	?>  
	<script type="text/javascript">
	document.location.href="../productos/?<?php echo $busqueypagi?>";
	</script>
	<?php 
	exit();
  }
}
//END PHP CODE
?>
<?php $pagina="Nuevo Producto"; include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="index.php"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="productos.php">Productos</a></li>
	<li><a href="#">Editar producto</a></li>
</ol>

<h2>Editar producto</h2>

<?php
$idProducto = $_GET['id'];
$sql = "SELECT * FROM tproductos WHERE id = '$idProducto'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)) {
?>

<div id="bError" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> <?php echo $bError?></div>

<form role="form" action="" class="form-horizontal" method="post">
	<input type="hidden" class="form-control" name="idOld" value="<?php echo $row['id']?>">
	

<div class="row">

			<div id="" class="col-md-8" >
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" style="background: #570101;color: #FFFFFF">
						<div class="panel-title">Datos Generales</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						
	<div class="form-group">
		<label for="idProducto" class="col-sm-3 control-label">Código:</label>
		
		<div class="col-sm-5">
			<input type="text" class="form-control" name="idProducto" required="required" autocomplete="off" id="idProducto" value="<?php echo $row['codigo']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="nombre" class="col-sm-3 control-label">Nombre:</label>
		
		<div class="col-sm-5">
			<input type="text" class="form-control" name="nombre" required="required" autocomplete="off" id="nombre" value="<?php echo $row['nombre']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="descripcion" class="col-sm-3 control-label">Descripción:</label>
		
		<div class="col-sm-5">
			<textarea class="form-control" name="descripcion" autocomplete="off" id="descripcion"><?php echo $row['descripcion']?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="proveedor" class="col-sm-3 control-label">Proveedor:</label>
		
		<div class="col-sm-5">
			<?php 
			$sql2 = "SELECT * FROM tproveedores WHERE estado = 1";
			$query2 = mysql_query($sql2,$conn);
			if (mysql_num_rows($query2) == 0){
				echo 'No se han agregado proveedores al sistema.';
			}else{
			?>
			<select name="proveedor" id="proveedor" class="form-control">
	          <option value="0">Sin proveedor</option>
	          <?php
	       
			while($row2=mysql_fetch_assoc($query2)){ ?>
	          <option value="<?php echo $row2['id']?>" <?php if ($row2['id'] == $row['proveedor']){echo 'selected="selected"';}?>><?php echo $row2['nombre']?></option>
	          <?php } ?>
	        </select>
	        <?php } ?>
		</div>
	</div>
	<div class="form-group">
		<label for="familiaProducto" class="col-sm-3 control-label">Familia:</label>
		
		<div class="col-sm-5">
			<?php 
			$sql2 = "SELECT * FROM tfamilias WHERE estado = 1";
			$query2 = mysql_query($sql2,$conn);
			if (mysql_num_rows($query2) == 0){
				echo 'No se han agregado familias al sistema.';
			}else{
			?>
			<select name="familiaProducto" id="familiaProducto" class="form-control">
	          <option value="0">Sin familia</option>
	          <?php
	        
			while($row2=mysql_fetch_assoc($query2)){ ?>
	          <option value="<?php echo $row2['id']?>" <?php if ($row2['id'] == $row['familiaProducto']){echo 'selected="selected"';}?>><?php echo $row2['nombre']?></option>
	          <?php } ?>
	        </select>
	        <?php }?>
		</div>
	</div>

	<div class="form-group" style="display: none;">
		<label for="moneda" class="col-sm-3 control-label">Moneda:</label>
		
		<div class="col-sm-5">
			<select name="moneda" id="moneda" class="form-control">
	          <option value="1" <?php if ($row['moneda'] == 1){echo 'selected="selected"';}?>>¢ - Colones</option>
	          <option value="2" <?php if ($row['moneda'] == 2){echo 'selected="selected"';}?>>$ - Dolares</option>
	        </select>
		</div>
	</div>
							

					</div>
				</div>
			</div>

			<div id="" class="col-md-4" >
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" style="background: #276701;color: #FFFFFF">
						<div class="panel-title">Datos Generales</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						<div class="form-group">
						<label for="excento" class="col-sm-4 control-label">Excento:</label>
						
						<div class="col-sm-6">
							<div class="checkbox checkbox-replace color-primary">
								<input type="checkbox" id="excento" name="excento" value="1" <?php if ($row['excento'] == 1){echo 'checked="checked"';}?> onclick="calcularPrecioVenta(3)">
								<label><i>Marque para productos excentos (Sin impuestos)</i></label>
								
								
							</div>
						</div>
					</div>
											
							<div class="form-group">
								<label for="excento" class="col-sm-4 control-label">Producto/Servicio:</label>
								
								<div class="col-sm-6">
									<div class="checkbox checkbox-replace color-primary">
										<input type="checkbox" id="tipo" name="tipo" value="1" <?php if ($row['tipo'] == 1){echo 'checked="checked"';}?>>
										<label><i>Marque para servicios</i></label>
									</div>
								</div>
							</div>


							<div class="form-group">
								<label for="proveedor" class="col-sm-4 control-label">Proveedor:</label>
								
								<div class="col-sm-6">
									<?php 
									$sql2 = "SELECT * FROM tproveedores WHERE estado = 1";
									$query2 = mysql_query($sql2,$conn);
									if (mysql_num_rows($query2) == 0){
										echo 'No se han agregado proveedores al sistema.';
									}else{
									?>
									<select name="proveedor" id="proveedor" class="form-control">
							          <option value="0">Sin proveedor</option>
							          <?php
							       
									while($row2=mysql_fetch_assoc($query2)){ ?>
							          <option value="<?php echo $row2['id']?>" <?php if ($row2['id'] == $_POST['proveedor']){echo 'selected="selected"';}?>><?php echo $row2['nombre']?></option>
							          <?php } ?>
							        </select>
							        <?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="familiaProducto" class="col-sm-4 control-label">Familia:</label>
								
								<div class="col-sm-6">
									<?php 
									$sql2 = "SELECT * FROM tfamilias WHERE estado = 1";
									$query2 = mysql_query($sql2,$conn);
									if (mysql_num_rows($query2) == 0){
										echo 'No se han agregado familias al sistema.';
									}else{
									?>
									<select name="familiaProducto" id="familiaProducto" class="form-control">
							          <option value="0">Sin familia</option>
							          <?php
							        
									while($row2=mysql_fetch_assoc($query2)){ ?>
							          <option value="<?php echo $row2['id']?>" <?php if ($row2['id'] == $_POST['familiaProducto']){echo 'selected="selected"';}?>><?php echo $row2['nombre']?></option>
							          <?php } ?>
							        </select>
							        <?php }?>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">

			<div id="" class="col-md-6" >
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" style="background: #00AA18;color: #FFFFFF">
						<div class="panel-title">Precios</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						<div id="errorPrecio" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> Por favor digite el costo unitario del producto</div>
						<div class="form-group">
								<label for="costoUnitario" class="col-sm-6 control-label">Costo unitario sin IV</label>
								
								<div class="col-sm-5">
									<input type="number" min="0" step="any" class="form-control"  required="required" name="costoUnitario" autocomplete="off" id="costoUnitario" onblur="coIVA('1')" value="<?php echo $row['costoUnitario']?>" onkeyup="calcularPrecioVenta(1)"  >
								</div>
							</div>
							<?php 
								if ($row['excento'] == 1){
									$costofinal=$row['costoUnitario'];
								}else{
									$costofinal=($row['costoUnitario']*0.13)+$row['costoUnitario'];
								}

							?>
							<div class="form-group">
								<label for="costoUnitario" class="col-sm-6 control-label">Costo unitario IVI</label>
								
								<div class="col-sm-5">
									
									<input type="number" min="0" step="any" class="form-control" required="required" name="costoUnitarioIVA" autocomplete="off" id="costoUnitarioIVA" onblur="coIVA('2')" value="<?php echo $costofinal;?>" >
								</div>
							</div>

					</div>

				</div>
			</div>
			<div id="" class="col-md-6" >
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" style="background: #ED6C03;color: #FFFFFF">
						<div class="panel-title">Precios</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						
						<div class="form-group">
							<label for="porcentajeUtilidad" class="col-sm-5 control-label">Porcentaje de Utilidad:</label>
							
							<div class="col-sm-5">
								<div id="errorPorcentaje" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> Por favor digite el porcentaje de utilidad del producto</div>

								<input type="number" min="0" step="any" class="form-control" required="required" name="porcentajeUtilidad" autocomplete="off" id="porcentajeUtilidad" value="10" onkeyup="calcularPrecioVenta(2)">
							</div>
						</div>
						<div class="form-group">
							<label for="precioVenta" class="col-sm-5 control-label">Precio venta sin IV:</label>
							
							<div class="col-sm-5">
								<input type="number" min="0" step="any"  class="form-control" required="required" name="precioVenta" autocomplete="off" id="precioVenta" onkeyup="calcularPrecioVenta(3)" onblur="verificar()" value="<?php echo $row['precioVenta']?>">
							</div>
						</div>
						
						
					<div class="form-group">
							<label for="excento" class="col-sm-5 control-label">Precio venta IVI:</label>
							<?php 
								if ($row['excento'] == 1){
									$preciofinal=$row['precioVenta'];
								}else{
									$preciofinal=($row['precioVenta']*0.13)+$row['precioVenta'];
								}

							?>
							<div class="col-sm-5">
								
									<input type="number" min="0" step="any" class="form-control"  required="required" name="preciofinal1" autocomplete="off" id="preciofinal1" onkeyup="calcularPrecioVenta(4)" onblur="" value="<?php echo $preciofinal?>">

							</div>
						</div>
	
					</div>
				</div>
			</div>
		
			<div id="" class="col-md-12" >
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" >
						<div class="panel-title">Informacion Adicional</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
					<div class="form-group">
						<label for="minimo" class="col-sm-3 control-label">Existencia mínima:</label>
						
						<div class="col-sm-5">
							<input type="number" min="0" step="any" class="form-control" name="minimo" autocomplete="off" id="minimo" value="<?php echo $row['minimo']?>">
						</div>
					</div>
					<div class="form-group">
						<label for="existencia" class="col-sm-3 control-label">Existencia actual:</label>
						
						<div class="col-sm-5">
							<input type="number" min="0" step="any" class="form-control" name="existencia" autocomplete="off" id="existencia" value="<?php echo $row['existencia']?>">
						</div>
					</div>
					<div class="form-group">
						<label for="transporte" class="col-sm-3 control-label">Costo de transporte:</label>
						
						<div class="col-sm-5">
							<input type="number" min="0" step="any" class="form-control" name="transporte" autocomplete="off" id="transporte" value="<?php echo $row['transporte']?>">
						</div>
					</div>

				<center>
					<div class="btn-group">
						<button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
					</div>
					<div class="btn-group">
						<a href="../productos/?<?php echo $busqueypagi?>"><button type="button" class="btn btn-red">Cancelar</button></a>
					</div>
				</center>
					</div>
				</div>
			</div>

	</div>

	


	

	
</form>
<?php } ?>

<script type="text/javascript">

function coIVA(val){
	var PRreal
	if(val==2){
		PRreal=$("#costoUnitarioIVA").val();
	}else{
		PRreal=$("#costoUnitario").val();
	}
	

	var costoUnitario=$("#costoUnitario").val();
	var costoUnitarioIVA=$("#costoUnitarioIVA").val();
	var exento=$('input:checkbox[name=excento]:checked').val();
	//alert(exento);
	if(exento==1){
		//exento SIN
		if(val==2){
			$("#costoUnitario").val(PRreal);
		}
		$("#costoUnitarioIVA").val(PRreal);
	}else{
		// Con
		
		if(val==2){
			var impus=(PRreal*100)/113;

			$("#costoUnitario").val(impus);
		}else{
			var impus=PRreal*0.13;
			impus=parseFloat(costoUnitario)+parseFloat(impus);
			$("#costoUnitarioIVA").val(impus);
		}
		
	}
	calcularPrecioVenta(2);
}







function CKcodigoAutomatico(field){
	if (field.checked == true){
		$('#idProducto').attr("disabled", "disabled");
		$('#idProducto').val("");
	}else{
		$('#idProducto').removeAttr("disabled");
	}	
}

	function calcularPrecioVenta(donde){
		var precioUnitario = $('#costoUnitario').val();
		var porcentajeUtilidad = $('#porcentajeUtilidad').val();
		var precioVenta = $('#precioVenta').val();
		var exento=$('input:checkbox[name=excento]:checked').val();
		
		if (precioUnitario == ''){
			$('#errorPrecio').slideDown();
		}else{
			$('#errorPrecio').slideUp();
			

				if(donde==1){
					var precioVenta = parseFloat(precioUnitario) + parseFloat(precioUnitario * (porcentajeUtilidad/100));
					$('#precioVenta').val(precioVenta.toFixed(2));
					if(exento==1){
						var preciofinal=parseFloat(precioVenta);
					}else{
						var preciofinal=(parseFloat(precioVenta)*0.13)+parseFloat(precioVenta);
					}
					
					$('#preciofinal1').val(preciofinal.toFixed(2));

				}
					if(donde==2){
						if (porcentajeUtilidad == ''){
					$('#errorPorcentaje').slideDown();	
					}else{
					$('#errorPorcentaje').slideUp();
						var precioVenta = parseFloat(precioUnitario) + parseFloat(precioUnitario * (porcentajeUtilidad/100));
						$('#precioVenta').val(precioVenta.toFixed(2));

						if(exento==1){
						var preciofinal=parseFloat(precioVenta);
						}else{
							var preciofinal=(parseFloat(precioVenta)*0.13)+parseFloat(precioVenta);
						}
						

						$('#preciofinal1').val(preciofinal.toFixed(2));

					}
				}
				if(donde==3){
					var porcentajeUtilidad = (parseFloat(precioVenta)*100)/parseFloat(precioUnitario)-100;

					$('#porcentajeUtilidad').val(porcentajeUtilidad.toFixed(2));

					if(exento==1){
						var preciofinal=parseFloat(precioVenta);
					}else{
						var preciofinal=(parseFloat(precioVenta)*0.13)+parseFloat(precioVenta);
					}
					coIVA();
					$('#preciofinal1').val(preciofinal.toFixed(2));
				}


				if(donde==4){
					var preciofinal1=$('#preciofinal1').val();
					

					if(exento==1){
						var precioventa=parseFloat(preciofinal1);
					}else{
						var precioventa=(parseFloat(preciofinal1)*100)/113;
					}
					
					$('#precioVenta').val(precioventa.toFixed(2));

					var porcentajeUtilidad = (parseFloat(precioventa)*100)/parseFloat(precioUnitario)-100;

					$('#porcentajeUtilidad').val(porcentajeUtilidad.toFixed(2));

					//calcularPrecioVenta(3);
				}
							
		}

	}
	function verificar(){
		var precioUnitario = $('#costoUnitario').val();
		var porcentajeUtilidad = $('#porcentajeUtilidad').val();
		var precioVenta = $('#precioVenta').val();
		if(precioUnitario>precioVenta){
			alert("El precio de unitario no puede ser mayor al precio de venta");
			$('#precioVenta').val("");
		}
	}


</script>

<?php
if ($bError != ''){ ?>
<script type="text/javascript">
	$('#bError').slideDown();
</script>
<?php } ?>
<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>
