<?php include("conn/conn.php");?>	
<?php 
controlAcceso(34);

//START PHP CODE
$busqueypagi = $_GET['busqueypagi'];
$busquedas = 'bNombre='.$_GET['bNombre'];
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
if (isset($_POST['guardar'])){
  $bError = "";
  $idProducto = $_POST['idProducto'];
  $codigo = $_POST['codigo'];
  $codigoAutomatico = $_POST['codigoAutomatico'];
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $proveedor = $_POST['proveedor']; 
  $costoUnitario = $_POST['costoUnitario'];
  $precioVenta = $_POST['precioVenta']; 
  $excento = $_POST['excento']; 
  $porcentajeUtilidad = $_POST['porcentajeUtilidad'];
  $minimo = $_POST['minimo']; 
  $existencia = $_POST['existencia'];
  $familiaProducto = $_POST['familiaProducto']; 
  $transporte = $_POST['transporte']; 
  $tipo = $_POST['tipo']; 

  function str_first_replace($aguja, $sustituta, $pajar){
    return ($pos=strpos($pajar, $aguja))!==FALSE?substr_replace($pajar, $sustituta, $pos, strlen($aguja)):$pajar;
  }

  if ($codigoAutomatico == 1){
  	if ($familiaProducto == 0){
  		$bError = "Ha elegido la opción de código automático. Debe seleccionar la familia del producto.";
  	}else{
  		$repetido = 1;
  		while ($repetido == 1) {
  			$nuevoCodigo = rand(100000,999999);
  			$sql = "SELECT * FROM tproductos WHERE idProducto = '$nuevoCodigo'";
  			$query = mysql_query($sql, $conn);
  			if (mysql_num_rows($query) == 0){
  				$repetido = 0;
  			}else{
  				$repetido = 1;
  			}
  		}
  		$idProducto = $nuevoCodigo;
  	}
  }else{
  	if ($idProducto == ''){
  		$bError = "Debe indicar el código del producto.";
  	}
  }

	$sql = "SELECT * FROM tproductos WHERE id = '$idProducto' AND codigo != '$codigo' AND estado = 1";
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) == 0){
		$sqlEditar = "INSERT INTO tproductos VALUES ( 
		'$idProducto',
		'$codigo',
		'$nombre',
		'$descripcion', 
		'$proveedor', 
		'1',
		'$costoUnitario', 
		'$precioVenta', 
		'$excento', 
		'$porcentajeUtilidad', 
		'$minimo',
		'$existencia',
		'$familiaProducto',
		'$transporte',
		'$tipo',
		1
		)";
	}else{
		$bError = "No se puede guardar el producto porque ya existe uno con el mismo id o código.";
	}
  
  if ($bError == ""){
	$query = mysql_query($sqlEditar, $conn)or die(mysql_error());
	registrarBitacora($userid, "Agregar producto: ".$nombre, $conn);  
	?>  
	<script type="text/javascript">
	document.location.href="productos.php?<?=$busqueypagi?>";
	</script>
	<?php 
	exit();
  }
}
//END PHP CODE
?>
<?php include("includes/header.php");?>				
<?php include("includes/sidebar.php");?>
<?php include("includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="index.php"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="productos.php">Productos</a></li>
	<li><a href="#">Agregar producto</a></li>
</ol>

<h2>Nuevo producto</h2> 
<?php include("includes/alerts.php");
if ($bError != ''){?>
<script type="text/javascript">
	miAlerta('<?=$bError?>');
</script>
<?php } ?>
<form role="form" action="" class="form-horizontal" method="post" onsubmit="return enviarForm()">
	<div class="form-group">
		<label for="idProducto" class="col-sm-3 control-label">Código:</label>
		
		<div class="col-sm-5">
			<input type="text" class="form-control" name="idProducto" autocomplete="off" id="idProducto" value="<?=$_POST['idProducto']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="codigoAutomatico" class="col-sm-3 control-label">Generar código automáticamente:</label>
		
		<div class="col-sm-5">
			<div class="checkbox checkbox-replace color-primary">
				<input type="checkbox" id="codigoAutomatico" name="codigoAutomatico" value="1" onclick="CKcodigoAutomatico(this)" <? if ($_POST['codigoAutomatico'] == 1){echo 'checked="checked"';}?>>
				<label><i>Utilizando el id de familia mas el ultimo producto agrega de la familia</i></label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="nombre" class="col-sm-3 control-label">Nombre:</label>
		
		<div class="col-sm-5">
			<input type="text" class="form-control" name="nombre" required="required" autocomplete="off" id="nombre" value="<?=$_POST['nombre']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="descripcion" class="col-sm-3 control-label">Descripción:</label>
		
		<div class="col-sm-5">
			<textarea class="form-control" name="descripcion" autocomplete="off" id="descripcion"><?=$_POST['descripcion']?></textarea>
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
	          <option value="<?php echo $row2['id']?>" <?php if ($row2['id'] == $_POST['proveedor']){echo 'selected="selected"';}?>><?php echo $row2['nombre']?></option>
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
	          <option value="<?php echo $row2['id']?>" <?php if ($row2['id'] == $_POST['familiaProducto']){echo 'selected="selected"';}?>><?php echo $row2['nombre']?></option>
	          <?php } ?>
	        </select>
	        <?php }?>
		</div>
	</div>

	<div class="form-group" style="display: none">
		<label for="moneda" class="col-sm-3 control-label">Moneda:</label>
		
		<div class="col-sm-5">
			<select name="moneda" id="moneda" class="form-control">
	          <option value="1">¢ - Colones</option>
	          <option value="2">$ - Dolares</option>
	        </select>
		</div>
	</div>

	<div class="form-group">
		<label for="costoUnitario" class="col-sm-3 control-label">Costo unitario:</label>
		
		<div class="col-sm-5">
			<div id="errorPrecio" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> Por favor digite el costo unitario del producto</div>

			<input type="number" min="0" step="any" class="form-control" required="required" name="costoUnitario" autocomplete="off" id="costoUnitario" value="<?=$_POST['costoUnitario']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="porcentajeUtilidad" class="col-sm-3 control-label">Porcentaje de Utilidad:</label>
		
		<div class="col-sm-5">
			<div id="errorPorcentaje" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> Por favor digite el porcentaje de utilidad del producto</div>

			<input type="number" min="0" step="any" class="form-control" required="required" name="porcentajeUtilidad" autocomplete="off" id="porcentajeUtilidad" value="<?=$row['porcentajeUtilidad']?>" onkeyup="calcularPrecioVenta(2)">
		</div>
	</div>
	<div class="form-group">
		<label for="precioVenta" class="col-sm-3 control-label">Precio Venta:</label>
		
		<div class="col-sm-5">
			<input type="number" min="0" step="any"  class="form-control" required="required" name="precioVenta" autocomplete="off" id="precioVenta" onkeyup="calcularPrecioVenta(3)" onblur="verificar()" value="<?=$row['precioVenta']?>">
		</div>
	</div>
	
	<div class="form-group">
		<label for="excento" class="col-sm-3 control-label">Excento:</label>
		
		<div class="col-sm-5">
			<div class="checkbox checkbox-replace color-primary">
				<input type="checkbox" id="excento" name="excento" value="1" <? if ($row['excento'] == 1){echo 'checked="checked"';}?> onclick="calcularPrecioVenta(3)">
				<label><i>Marque para productos excentos (Sin impuestos)</i></label>
				
				
			</div>
		</div>
	</div>
<div class="form-group">
		<label for="excento" class="col-sm-3 control-label">Precio Final:</label>
		<?php 
			if ($row['excento'] == 1){
				$preciofinal=$row['precioVenta'];
			}else{
				$preciofinal=($row['precioVenta']*0.13)+$row['precioVenta'];
			}

		?>
		<div class="col-sm-5">
			
				<input type="number" class="form-control" required="required" name="preciofinal1" autocomplete="off" id="preciofinal1" onkeyup="calcularPrecioVenta(4)" onblur="" value="<?=$preciofinal?>">

		</div>
	</div>
	<div class="form-group">
		<label for="excento" class="col-sm-3 control-label">Producto/Servicio:</label>
		
		<div class="col-sm-5">
			<div class="checkbox checkbox-replace color-primary">
				<input type="checkbox" id="tipo" name="tipo" value="1" <? if ($row['tipo'] == 1){echo 'checked="checked"';}?>>
				<label><i>Marque para servicios</i></label>
			</div>
		</div>
	</div>

	

	<div class="form-group">
		<label for="minimo" class="col-sm-3 control-label">Existencia mínima:</label>
		
		<div class="col-sm-5">
			<input type="number" min="0" step="any" class="form-control" name="minimo" autocomplete="off" id="minimo" value="<?=$_POST['minimo']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="existencia" class="col-sm-3 control-label">Existencia actual:</label>
		
		<div class="col-sm-5">
			<input type="number" min="0" step="any" class="form-control" name="existencia" autocomplete="off" id="existencia" value="<?=$_POST['existencia']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="transporte" class="col-sm-3 control-label">Costo de transporte:</label>
		
		<div class="col-sm-5">
			<input type="number" min="0" step="any" class="form-control" name="transporte" autocomplete="off" id="transporte" value="<?=$_POST['transporte']?>">
		</div>
	</div>

<center>
	<div class="btn-group">
		<button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
	</div>
	<div class="btn-group">
		<a href="productos.php?<?=$busqueypagi?>"><button type="button" class="btn btn-red">Cancelar</button></a>
	</div>
</center>
</form>


<script type="text/javascript">
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
<?
if ($bError != ''){ ?>
<script type="text/javascript">
	$('#bError').slideDown();
</script>
<? } ?>
<!-- FIN CONTENIDO -->
<?php include("includes/footer.php");?>
