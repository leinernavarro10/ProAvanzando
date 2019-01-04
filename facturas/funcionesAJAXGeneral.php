<?php  
include ("../conn/connpg.php");

require '../parametros/libs/PHPMailer/PHPMailerAutoload.php';

$f = $_POST['f'];

if ($f == 'BUSCAR-CAJA'){
	$sql2 = "SELECT * FROM usuarios WHERE id='".$_POST['id']."' ";
	$query2 = mysql_query($sql2, $conn);
	while ($row=mysql_fetch_assoc($query2)) {
		echo $row['caja'];
	}
	
  }



//BUSCAR CLIENTE
if ($f == 'BUSCAR-CLIENTE'){
	$txtBuscar = $_POST['txtBuscar'];
	if ($txtBuscar == ""){
		$sql = "SELECT * FROM tclientes WHERE fav='1' AND usuario='$cuentaid' LIMIT 5";
	}else{
		$txtBuscar=explode(' ',$txtBuscar);
		foreach ($txtBuscar as $valor) {
	   		$buscar.=" nombre LIKE '%$valor%' AND";
		}
		$sql = "SELECT * FROM tclientes WHERE identificacion like '%$txtBuscar%' OR $buscar estado = 1 AND usuario='$cuentaid' LIMIT 5";
	}
		$query = mysql_query($sql, $conn);
		if (mysql_num_rows($query) == 0){
			echo '<center><i class="entypo-info"></i><br>No se encuentra lo que estas buscando.</center>';
		}else{ ?>
			<table width="100%" class="table table-bordered responsive">
			<thead>
			<tr>
				
				<th>Cliente</th>
			</tr>
			</thead>
			<tbody>
			<?php  
			$connt1=0;
				while ($row=mysql_fetch_assoc($query)) {
					$connt1++;
					$nombre = $row['nombre'];
					$identificacion = $row['identificacion'];
					if ($identificacion != 0 AND $identificacion != ""){
						$nombre = '('.$identificacion.') '.$nombre;
					}
					echo '
					<tr id="trCL'.$connt1.'" onclick="cargarCliente(\''.$row['id'].'\',\''.$nombre.'\',\''.$row['email'].'\')" >
						
						<td>'.$nombre.' ';
						if($row['fav']=='1'){
							echo "<div class='iradio_square-yellow checked'  style='float: right;'  style='position: relative;'></div>";
						}

						echo '</td>
					</tr>';
				}
			?>
			</tbody>
			</table>
			<input type="hidden" name="" id="cantRG2" value="<?php echo $connt1;?>">
		<?php  
		}
	
}

//NUEVA NOTA
if ($f == 'NUEVA-NOTA'){
	$titulo = $_POST['titulo'];
	$descripcion = $_POST['descripcion'];

	$sql = "INSERT INTO tnotas VALUES (null, '$titulo', '$descripcion', '', '$idUsuario')";
	$query = mysql_query($sql, $conn) or die(mysql_error());
	$sql = "SELECT * FROM tnotas WHERE idUsuario = '$idUsuario' ORDER BY id DESC LIMIT 1";
	$query = mysql_query($sql, $conn) or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)) {
		echo $row['id'];
	}
}

//NUEVA NOTA
if ($f == 'MODIFICAR-NOTA'){
	$id = $_POST['id']; 
	$titulo = $_POST['titulo'];
	$descripcion = $_POST['descripcion'];
	$contenido = $_POST['contenido'];

	$sql = "UPDATE tnotas SET 
	titulo = '$titulo', 
	descripcion = '$descripcion', 
	contenido = '$contenido' 
	WHERE id = '$id'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}

//QUITAR NOTA
if ($f == 'QUITAR-NOTA'){
	$id = $_POST['id']; 
	
	$sql = "DELETE FROM tnotas WHERE id = '$id'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}

//CAMBIAR CANTIDAD EN LA COTIZACION
if ($f == 'CAMBIAR-CANTIDAD-COTIZACION'){
	$idProforma = $_POST['idProforma']; 
	$idProducto = $_POST['idProducto']; 
	$cantidad = $_POST['cantidad']; 
	
	$sql = "UPDATE tproductoscotizacion SET cantidad = '$cantidad' WHERE idCotizacion = '$idProforma' AND idProducto = '$idProducto'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}

//CAMBIAR CANTIDAD EN LA FACTURA
if ($f == 'CAMBIAR-CANTIDAD-FACTURA'){
	$idProforma = $_POST['idProforma']; 
	$idProducto = $_POST['idProducto']; 
	$cantidad = $_POST['cantidad'];

	$sql2 = "SELECT * FROM tproductosfactura WHERE idProducto = '$idProducto' AND idCotizacion = '$idProforma'";
    $query2 = mysql_query($sql2, $conn) or die(mysql_error());
    while($row2=mysql_fetch_assoc($query2)){
      $cantidadOLD = $row2['cantidad'];
    }

    if ($cantidad < $cantidadOLD){
    	$diferencia = $cantidadOLD - $cantidad;
    	$sql2 = "UPDATE tproductos SET existencia  = existencia + '$diferencia' WHERE id = '$idProducto'";
  		$query2 = mysql_query($sql2, $conn) or die(mysql_error());
    }else{
    	$diferencia = $cantidad - $cantidadOLD;
    	$sql2 = "UPDATE tproductos SET existencia  = existencia - '$diferencia' WHERE id = '$idProducto'";
  		$query2 = mysql_query($sql2, $conn) or die(mysql_error());
    }
	
	$sql = "UPDATE tproductosfactura SET cantidad = '$cantidad' WHERE idCotizacion = '$idProforma' AND idProducto = '$idProducto'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}

//CAMBIAR DESCUENTO EN LA COTIZACION
if ($f == 'CAMBIAR-DESCUENTO-COTIZACION'){
	$idProforma = $_POST['idProforma']; 
	$idProducto = $_POST['idProducto']; 
	$descuento = $_POST['descuento']; 
	
	$sql = "UPDATE tproductoscotizacion SET descuento = '$descuento' WHERE idCotizacion = '$idProforma' AND idProducto = '$idProducto'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}


//CAMBIAR DESCUENTO EN LA FACTURA
if ($f == 'CAMBIAR-DESCUENTO-FACTURA'){
	$idProforma = $_POST['idProforma']; 
	$idProducto = $_POST['idProducto']; 
	$descuento = $_POST['descuento']; 
	
	$sql = "UPDATE tproductosfactura SET descuento = '$descuento' WHERE idCotizacion = '$idProforma' AND idProducto = '$idProducto'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}


//CAMBIAR DESCUENTO EN LA COTIZACION - TODOS
if ($f == 'CAMBIAR-DESCUENTO-COTIZACION-TODOS'){
	$idProforma = $_POST['idProforma']; 
	$descuento = $_POST['descuento']; 
	
	$sql = "UPDATE tproductoscotizacion SET descuento = '$descuento' WHERE idCotizacion = '$idProforma'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}

//CAMBIAR DESCUENTO EN LA FACTURA - TODOS
if ($f == 'CAMBIAR-DESCUENTO-FACTURA-TODOS'){
	$idProforma = $_POST['idProforma']; 
	$descuento = $_POST['descuento']; 
	
	$sql = "UPDATE tproductosfactura SET descuento = '$descuento' WHERE idCotizacion = '$idProforma'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}


//AGREGAR EVENTO AL CALENDARIO
if ($f == 'AGREGAR-EVENTO'){
	$title = $_POST['title']; 
	$start = $_POST['start']; 
	$end = $_POST['end']; 
	
	$sql = "INSERT INTO teventos VALUES (null, '$titulo', '$start', '$end')";
	$query = mysql_query($sql, $conn) or die(mysql_error());
}

//BUSCAR PRODUCTO
if ($f == 'BUSCAR-PRODUCTO'){
	$txtBuscar = $_POST['txtBuscar'];
	$codigo = $_POST['txtBuscar'];
	if ($txtBuscar == ""){
		echo '<center><i class="entypo-info"></i><br>Por favor digite el nombre o descripción del producto.</center>';
	}else{
		$txtBuscar=explode(' ',$txtBuscar);
		foreach ($txtBuscar as $valor) {
	   		$buscar.=" nombre LIKE '%$valor%' AND";
		}
		$sql = "SELECT * FROM tproductos WHERE codigo LIKE '%$codigo%' OR $buscar estado = 1  AND sistema = '".$cuentaid."' LIMIT 10";

		//$sql = "SELECT * FROM tproductos WHERE (FULLTEXT(name, description) nombre like '%$txtBuscar%' OR descripcion like '%$txtBuscar%' OR codigo like '%$txtBuscar%' OR id like '%$txtBuscar%') AND estado = 1 AND sistema = '".$cuentaid."' LIMIT 5";
		$query = mysql_query($sql, $conn)or die(mysql_error());
		if (mysql_num_rows($query) == 0){
			echo '<center><i class="entypo-info"></i><br>No se encuentra lo que estas buscando.</center>';
		}else{ ?>
			<table width="100%" class="table table-bordered">
			<thead>
			<tr>
				
				<th style="background: #FDD6C0;color: #555555;font-weight: 700"><span class="esconderPR">Cogido</span></th>
				<th class="esconderPR" style="font-weight: 700;color: #555555">Familia</th>
				<th style="background: #5B4848;color: #FFFFFF;font-weight: 700">Producto</th>
				<th style="font-weight: 700;color: #555555">I.V</th>
				<th style="background: #F7C8AE;color: #555555;font-weight: 700">Exist</th>
				<th style="background: #627559;color: #FFFFFF;font-weight: 700">Pre Detal</th>
				<th class="esconderPR">Pre Mayo</th>
			</tr>

			</thead>
			<tbody>
			<?php  
			$connt1=0;
				while ($row=mysql_fetch_assoc($query)) {
					$connt1++;
					$codigo=$row['codigo'];
					?>
					
					<tr id="trb<?php echo $connt1?>" class="trb" onclick="cargarProducto(<?php echo $row['id']?>,'<?php echo $codigo?>')">
						
						<td><?php echo $row['codigo']?></td>
						<td class="esconderPR"><?php if($row['familiaProducto']==0){echo "NINGUNA";}else{}?></td>
						<td><strong  style="color: #965100;"><?php echo $row['nombre']?></strong></td>
						<td>  <?php if ($row['excento'] == 1){echo 'Exe';}else{echo '13%';} ?></td>

						<td><span <?php if($row['existencia']<=0){echo "style='color:#AC0000;font-weight: 700'";}else{echo "style='color:#060606;font-weight: 700'";}?>><?php echo $row['existencia']?></span></td>

						<td><strong style='color:#333333'><?php    
							if ($row['excento'] == 1){
			                  $totalIMP=$row['precioVenta'];
			                }else{
			                  $totalIMP=$row['precioVenta']*0.13;
			                  $totalIMP=$row['precioVenta']+$totalIMP;
			                }

							   echo "¢ ".number_format($totalIMP,2,',',' ');
							  ?>
							 </strong>
						 </td>
						 <td class="esconderPR" ><?php echo "¢ ".number_format($totalIMP,2,',',' ');?></td>
					</tr>
			<?php	}
			?>
			</tbody>
			</table>
			 <input type="hidden" name="" id="cantRG1" value="<?php echo $connt1;?>">
		<?php  
		}	
	}
}

// DIRECCION
if ($f == 'CARGAR-DIRECCION'){
	echo '<center>';
	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
		  $provincia = $row['provincia'];
		  $canton = $row['canton'];
		  $distrito = $row['distrito'];
		  $barrio = $row['barrio'];
		  $OtrasSenas = $row['OtrasSenas'];
		 
	  
	}

	$sql = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' GROUP BY provincia";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		echo utf8_encode($row['nombreProvincia']);
	}
	echo ' / ';

	$sql = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' GROUP BY canton";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		echo utf8_encode($row['nombreCanton']);
	}
	echo ' / ';

	$sql = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' AND distrito = '$distrito' GROUP BY distrito";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		echo utf8_encode($row['nombreDistrito']);
	}
	echo ' / ';

	$sql = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' AND distrito = '$distrito' AND barrio = '$barrio' GROUP by barrio";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		echo utf8_encode($row['nombreBarrio']);
	}
	echo '</center>';
}

//CARGAR CANTONES
if ($f == 'CARGAR-CANTONES'){
	$provincia = $_POST['provincia'];

	$sql = "UPDATE tparametros SET provincia = '$provincia' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET canton = '0' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET distrito = '0' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET barrio = '0' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	?>
	<div class="form-group">
	<label for="" class="col-sm-3 control-label">Cantón:</label>
		<div class="col-sm-5">
			<select name="" id="" class="form-control" onchange="cambiarCanton('<?php echo $provincia?>', this.value)">
				<option value="0">Seleccione el cantón</option>
			  <?php  
			  $sql2 = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' GROUP BY canton ORDER BY canton ASC";
			  $query2 = mysql_query($sql2, $conn);
			  while ($row2=mysql_fetch_assoc($query2)) {
			  ?>
	          <option value="<?php echo $row2['canton']?>"><?php echo utf8_encode($row2['nombreCanton'])?></option>
	          <?php  } ?>
	        </select>
		</div>
	</div>
	<?php  
}

//CARGAR DISTRITOS
if ($f == 'CARGAR-DISTRITOS'){
	$provincia = $_POST['provincia'];
	$canton = $_POST['canton'];

	$sql = "UPDATE tparametros SET provincia = '$provincia' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET canton = '$canton' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET distrito = '0' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET barrio = '0' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	?>
	<div class="form-group">
	<label for="" class="col-sm-3 control-label">Distritos:</label>
		<div class="col-sm-5">
			<select name="" id="" class="form-control" onchange="cambiarDistrito('<?php echo $provincia?>', '<?php echo $canton?>', this.value)">
			  <option value="0">Seleccione el cantón</option>
			  <?php  
			  $sql2 = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' GROUP BY distrito ORDER BY distrito ASC";
			  $query2 = mysql_query($sql2, $conn);
			  while ($row2=mysql_fetch_assoc($query2)) {
			  ?>
	          <option value="<?php echo $row2['distrito']?>"><?php echo utf8_encode($row2['nombreDistrito'])?></option>
	          <?php  } ?>
	        </select>
		</div>
	</div>
	<?php  
}

//CARGAR BARRIOS
if ($f == 'CARGAR-BARRIOS'){
	$provincia = $_POST['provincia'];
	$canton = $_POST['canton'];
	$distrito = $_POST['distrito'];

	$sql = "UPDATE tparametros SET provincia = '$provincia' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET canton = '$canton' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET distrito = '$distrito' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET barrio = '0' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	?>
	<div class="form-group">
	<label for="" class="col-sm-3 control-label">Barrio:</label>
		<div class="col-sm-5">
			<select name="" id="" class="form-control" onchange="guardarBarrio('<?php echo $provincia?>', '<?php echo $canton?>', '<?php echo $distrito?>', this.value)">
			  <option value="0">Seleccione el barrio</option>
			  <?php  
			  $sql2 = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' AND distrito = '$distrito' GROUP BY barrio ORDER BY barrio ASC";
			  $query2 = mysql_query($sql2, $conn)or die(mysql_error());
			  while ($row2=mysql_fetch_assoc($query2)) {
			  ?>
	          <option value="<?php echo $row2['barrio']?>"><?php echo utf8_encode($row2['nombreBarrio'])?></option>
	          <?php  } ?>
	        </select>
		</div>
	</div>
	<?php  
}

//CARGAR BARRIOS
if ($f == 'GUARDAR-BARRIO'){
	$provincia = $_POST['provincia'];
	$canton = $_POST['canton'];
	$distrito = $_POST['distrito'];
	$barrio = $_POST['barrio'];

	$sql = "UPDATE tparametros SET provincia = '$provincia' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET canton = '$canton' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET distrito = '$distrito' WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	$sql = "UPDATE tparametros SET barrio = '$barrio' WHERE id = '".$cuentaid."' "; 
	$query = mysql_query($sql, $conn);
}


//////////////////// DIRECCION CLIENTES ////////////////////////////

//CARGAR CANTONES
if ($f == 'CARGAR-PROVINCIA-CLI'){
	$provincia = $_POST['idProvincia'];
	$idCantonCLI = $_POST['idCantonCLI'];
	?>
	<select name="idCanton" id="idCanton" class="form-control" onchange="cambiarCanton()">
		<option value="0">Seleccione el cantón</option>
	  <?php  
	  $sql2 = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' GROUP BY canton ORDER BY canton ASC";
	  $query2 = mysql_query($sql2, $conn);
	  while ($row2=mysql_fetch_assoc($query2)) {
	  ?>
      <option value="<?php echo $row2['canton']?>" <?php  if ($row2['canton'] == $idCantonCLI){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreCanton'])?></option>
      <?php  } ?>
    </select>
	<?php  
}

//CARGAR CANTONES
if ($f == 'CARGAR-DISTRITO-CLI'){
	$provincia = $_POST['idProvincia'];
	$canton = $_POST['idCanton'];
	$idDistritoCLI = $_POST['idDistritoCLI'];
	?>
	<select name="idDistrito" id="idDistrito" class="form-control" onchange="cambiarDistrito()">
	  <option value="0">Seleccione el cantón</option>
	  <?php  
	  $sql2 = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' GROUP BY distrito ORDER BY distrito ASC";
	  $query2 = mysql_query($sql2, $conn);
	  while ($row2=mysql_fetch_assoc($query2)) {
	  ?>
      <option value="<?php echo $row2['distrito']?>" <?php  if ($row2['distrito'] == $idDistritoCLI){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreDistrito'])?></option>
      <?php  } ?>
    </select>
	<?php  
}

//CARGAR CANTONES
if ($f == 'CARGAR-BARRIO-CLI'){
	$provincia = $_POST['idProvincia'];
	$canton = $_POST['idCanton'];
	$distrito = $_POST['idDistrito'];
	$idBarrioCLI = $_POST['idBarrioCLI'];
	?>
	<select name="idBarrio" id="idBarrio" class="form-control">
	  <option value="0">Seleccione el barrio</option>
	  <?php  
	  $sql2 = "SELECT * FROM tubicacionFE WHERE provincia = '$provincia' AND canton = '$canton' AND distrito = '$distrito' GROUP BY barrio ORDER BY barrio ASC";
	  $query2 = mysql_query($sql2, $conn)or die(mysql_error());
	  while ($row2=mysql_fetch_assoc($query2)) {
	  ?>
      <option value="<?php echo $row2['barrio']?>" <?php  if ($row2['barrio'] == $idBarrioCLI){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreBarrio'])?></option>
      <?php  } ?>
    </select>
	<?php  
}

      

?>
