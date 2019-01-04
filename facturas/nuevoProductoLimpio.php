<?php  include("../conn/conn.php");?>
<?php  include("includes/headerLimpio.php");

$busqueypagi = $_GET['busqueypagi'];
$busquedas = 'bNombre='.$_GET['bNombre'];
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
if (isset($_POST['guardar'])){
  $bError = "";
  $idProducto = $_POST['idProducto'];
  $codigo = $_POST['codigo'];
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

	$sql = "SELECT * FROM tproductos WHERE id = '$idProducto' AND estado = 1";
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
		$bError = "No se puede guardar el producto porque ya existe uno con el mismo código.";
	}
  
  if ($bError == ""){
	$query = mysql_query($sqlEditar, $conn)or die(mysql_error());
	registrarBitacora($userid, "Agregar producto: ".$nombre, $conn);  
	?>  
	<script type="text/javascript">
	parent.cargarProducto('<?php echo $idProducto?>', '<?php echo $codigo?>');
	document.location.href="nuevoProductoLimpio.php";
	</script>
	<?php  
	//exit();
  }
}

?>
<script type="text/javascript">
	function calcularPrecioVenta(){
		var precioUnitario = $('#costoUnitario').val();
		var porcentajeUtilidad = $('#porcentajeUtilidad').val();

		if (precioUnitario == ''){
			$('#errorPrecio').slideDown();
		}else{
			$('#errorPrecio').slideUp();
			if (porcentajeUtilidad == ''){
				$('#errorPorcentaje').slideDown();	
			}else{
				$('#errorPorcentaje').slideUp();
				var precioVenta = parseFloat(precioUnitario) + parseFloat(precioUnitario * (porcentajeUtilidad/100));

				$('#precioVenta').val(precioVenta);
			}
		}

	}
</script>
	<?php 
	if ($bError != ''){ ?>
	<script type="text/javascript">
		$('#bError').slideDown();
	</script>
	<?php  } ?>

	<div id="bError" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> <?php echo $bError?></div>

	<form role="form" action="" class="form-horizontal" method="post">
	<div class="form-group">
		<label for="idProducto" class="col-sm-3 control-label">Código:</label>
		
		<div class="col-sm-8">
			<input type="text" class="form-control" name="idProducto" required="required" autocomplete="off" id="idProducto" value="<?php echo $_POST['idProducto']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="nombre" class="col-sm-3 control-label">Nombre:</label>
		
		<div class="col-sm-8">
			<input type="text" class="form-control" name="nombre" required="required" autocomplete="off" id="nombre" value="<?php echo $_POST['nombre']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="descripcion" class="col-sm-3 control-label">Descripción:</label>
		
		<div class="col-sm-8">
			<textarea class="form-control" name="descripcion" autocomplete="off" id="descripcion"><?php echo $_POST['descripcion']?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="proveedor" class="col-sm-3 control-label">Proveedor:</label>
		
		<div class="col-sm-8">
			<select name="proveedor" id="proveedor" class="form-control">
	          <option value="0">Sin proveedor</option>
	          <?php 
	        $sql2 = "SELECT * FROM tproveedores WHERE estado = 1";
			$query2 = mysql_query($sql2,$conn);
			while($row2=mysql_fetch_assoc($query2)){ ?>
	          <option value="<?php  echo $row2['id']?>" <?php  if ($row2['id'] == $_POST['proveedor']){echo 'selected="selected"';}?>><?php  echo $row2['nombre']?></option>
	          <?php  } ?>
	        </select>
		</div>
	</div>
	<div class="form-group">
		<label for="familiaProducto" class="col-sm-3 control-label">Familia:</label>
		
		<div class="col-sm-8">
			<select name="familiaProducto" id="familiaProducto" class="form-control">
	          <option value="0">Sin familia</option>
	          <?php 
	        $sql2 = "SELECT * FROM tfamilias WHERE estado = 1";
			$query2 = mysql_query($sql2,$conn);
			while($row2=mysql_fetch_assoc($query2)){ ?>
	          <option value="<?php  echo $row2['id']?>" <?php  if ($row2['id'] == $_POST['familiaProducto']){echo 'selected="selected"';}?>><?php  echo $row2['nombre']?></option>
	          <?php  } ?>
	        </select>
		</div>
	</div>

	<div class="form-group" style="display: none">
		<label for="moneda" class="col-sm-3 control-label">Moneda:</label>
		
		<div class="col-sm-8">
			<select name="moneda" id="moneda" class="form-control">
	          <option value="1">¢ - Colones</option>
	          <option value="2">$ - Dolares</option>
	        </select>
		</div>
	</div>

	<div class="form-group">
		<label for="costoUnitario" class="col-sm-3 control-label">Costo unitario:</label>
		
		<div class="col-sm-8">
			<div id="errorPrecio" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> Por favor digite el costo unitario del producto</div>

			<input type="text" class="form-control" required="required" name="costoUnitario" autocomplete="off" id="costoUnitario" value="<?php echo $_POST['costoUnitario']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="porcentajeUtilidad" class="col-sm-3 control-label">Porcentaje de Utilidad:</label>
		
		<div class="col-sm-8">
			<div id="errorPorcentaje" class="alert alert-danger" style="display: none;"><strong>Atención!</strong> Por favor digite el porcentaje de utilidad del producto</div>

			<input type="text" class="form-control" required="required" name="porcentajeUtilidad" autocomplete="off" id="porcentajeUtilidad" value="<?php echo $_POST['porcentajeUtilidad']?>" onblur="calcularPrecioVenta()">
		</div>
	</div>
	<div class="form-group">
		<label for="precioVenta" class="col-sm-3 control-label">Precio Venta:</label>
		
		<div class="col-sm-8">
			<input type="text" class="form-control" required="required" name="precioVenta" autocomplete="off" id="precioVenta" value="<?php echo $_POST['precioVenta']?>">
		</div>
	</div>

	<div class="form-group">
		<label for="excento" class="col-sm-3 control-label">Excento:</label>
		
		<div class="col-sm-8">
			<div class="checkbox checkbox-replace color-primary">
				<input type="checkbox" id="excento" name="excento" value="1" <?php  if ($row['excento'] == 1){echo 'checked="checked"';}?>>
				<label><i>Marque el check para producto excentos de impuesto</i></label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="minimo" class="col-sm-3 control-label">Existencia mínima:</label>
		
		<div class="col-sm-8">
			<input type="text" class="form-control" name="minimo" autocomplete="off" id="minimo" value="<?php echo $_POST['minimo']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="existencia" class="col-sm-3 control-label">Existencia actual:</label>
		
		<div class="col-sm-8">
			<input type="text" class="form-control" name="existencia" autocomplete="off" id="existencia" value="<?php echo $_POST['existencia']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="transporte" class="col-sm-3 control-label">Costo por transporte:</label>
		
		<div class="col-sm-8">
			<input type="text" class="form-control" name="transporte" autocomplete="off" id="transporte" value="<?php echo $_POST['transporte']?>">
		</div>
	</div>

	<center>
	<div class="btn-group">
		<button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
	</div>
	</center>
	</form>

	<style type="text/css">
		body {
			width: 97%;
		}
	</style>
<?php  include("includes/footerLimpio.php");?>