<?php include("../conn/connpg.php");
//controlAcceso(39);

$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)){
      	$tipoFacturaParametro = $row['facturaGrafica'];
 		$tamanoFactura = $row['tamanoFactura'];
       	$facturaElectronica = $row['facturaElectronica'];
}


?>	
<?php $pagina="Pago CXP"; include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="../receptor/">Compras</a></li>
	<li><a href="#">Pago</a></li>
</ol>
<h2>Nuevo abono (CXP)</h2>
<br />

<?php 
include("../includes/alerts.php");

$numeroFactura = $_GET['numero'];

if (isset($_POST['guardar'])){

	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	      $recibo = $row['recibo'];
	     // $nombreEmpresa=$row['nomComercial'];
	}
	$recibo=$recibo+1;
	//$recibo = $_POST['recibo'];
	$saldo = $_POST['saldo'];
	$fecha = $_POST['fecha'];
	$monto = $_POST['monto'];

	$detalle = $_POST['detalle'];
	$comprobante = $_POST['comprobante'];

	if ($fecha == ""){
		echo "<script type=\"text/javascript\">miAlerta('Por favor indica una fecha.')</script>";
	}else{
		if ($monto == ""){
			echo "<script type=\"text/javascript\">miAlerta('El monto no puede estár vacío.')</script>";
		}else{
			
				

					$sql = "INSERT INTO tabonospagar VALUES (null,'".$_GET['id']."','$detalle', '$comprobante', '$fecha','".$monto."','".$saldo."','1','".$userid."','".$cuentaid."')";
					$query = mysql_query($sql, $conn);
					
					if(($saldo-$monto)<=0){
						$sql4 = "UPDATE tmensajereceptorfe SET tipoFA = '2' WHERE id = '".$_GET['id']."' AND sistema='".$cuentaid."' ";
						$query4 = mysql_query($sql4, $conn);
					}

					

					echo "<script type=\"text/javascript\">mensajeExito('El abono se registró correctamente.')</script>";
				
				
		}
	}
}

if (isset($_GET['idEliminar'])){
	$idEliminar = $_GET['idEliminar'];
	
	$sql = "UPDATE tabonospagar SET estado = 2 WHERE id = '$idEliminar' AND sistema='".$cuentaid."' ";
	$query = mysql_query($sql, $conn);

	$sql = "SELECT * FROM tmensajereceptorfe WHERE id = '".$_GET['id']."' AND sistema='".$cuentaid."' AND estado = 1";
  	$query = mysql_query($sql, $conn);
  	while ($row=mysql_fetch_assoc($query)) {
    	$montoAbonos += $row['totalFactura'];
	}

	$sql = "SELECT * FROM tabonospagar WHERE id = '$idEliminar' AND sistema='".$cuentaid."' AND estado = 1";
  	$query = mysql_query($sql, $conn);
  	while ($row=mysql_fetch_assoc($query)) {
    	$montoAbonos += $row['monto'];
	}

      $sql3 = "UPDATE tmensajereceptorfe SET tipoFA = 1 WHERE id = '".$_GET['id']."' AND sistema='".$cuentaid."'";
      $query3 = mysql_query($sql3, $conn);
?>
	<script type="text/javascript">document.location.href="pagarcxp.php?<?php echo $busqueypagi?>&id=<?php echo $_GET['id']?>&numero=<?php echo $_GET['numero']?>"</script>";
<?php
	break;
}
?>

<div class="row">
		
			<div class="col-md-3">
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading" style="background: #FF4E00;color: #FFFFFF">
						<div class="panel-title">Nuevo abono (CXP)</div>
						
						<div class="panel-options">
							
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						<form action="" method="post" class="form-horizontal">
						<?php $sql = "SELECT * FROM tmensajereceptorfe WHERE sistema='$cuentaid' AND id='".$_GET['id']."' ";
						$query = mysql_query($sql, $conn) or die(mysql_error());
						while ($row=mysql_fetch_assoc($query)) { $idCliente=$row['idCliente'];$estado=$row['tipoFA']; ?>
						<table width="100%" class="table">
							<tr>
								<td colspan="2" style="text-align: center; font-size: 36px; color: #C30000"><strong><?php echo substr($row['consecutivoFE'], -5)?></strong></td>								
							</tr>
							<tr>
								<td colspan="2" style="border-top: none; text-align: center;"><strong><?php 
									$cedulaEmisor = preg_replace('/^0+/', '', $row['cedulaEmisor']); 
								  	$sql2 = "SELECT * FROM tclientes WHERE identificacion = '".$cedulaEmisor."' AND usuario='".$cuentaid."' ";
								  	$query2 = mysql_query($sql2, $conn);
								  	while ($row2=mysql_fetch_assoc($query2)) {
								    	echo $row2['nombre'];
								   	}?></strong></td>							
							</tr>
							<tr>
								<td width="75">Fecha: </td>
								<td><strong><?php echo date("d/m/Y", strtotime($row['fechaEmision']));?></strong></td>								
							</tr>
							<tr>
								<td>Monto: </td>
								<td><strong>&cent; <?php echo number_format($row['totalFactura'],2)?></strong></td>								
							</tr>
							<tr>
								<td>Saldo: </td>
								<td ><strong><?php
 								  $montoAbonos = 0;
								  $saldo = $row['totalFactura']; 
								  $sql = "SELECT * FROM tabonospagar WHERE idpago = '".$_GET['id']."' AND sistema='".$cuentaid."' AND estado = 1";
								  $query = mysql_query($sql, $conn);
								  while ($row=mysql_fetch_assoc($query)) {
								    $montoAbonos += $row['monto'];
								  }

								  $saldo = $saldo - $montoAbonos;
								  ?>
								  &cent; <a style="cursor: pointer;" onclick="$('#monto').val('<?php echo $saldo;?>');"><?php echo number_format($saldo,2)?></a>
			
							</strong>
								</td>								
							</tr>
						</table>
						<?php } ?>
						<?php if($estado==1){?>
						<hr><center><strong>NUEVO ABONO (CXC)</strong></center><hr>

							
							<input type="hidden" name="numero" id="numero" value="<?php echo $row['numero']?>">

							<input type="hidden" name="saldo" id="saldo" value="<?php echo $saldo?>">
							<div class="form-group">
								
								<div class="col-sm-12">
									Comprobante:
									<input type="text" class="form-control" placeholder="Comprobante" name="comprobante" autocomplete="off" id="comprobante" required >
								</div>
							</div>
							
							<div class="form-group">
								
								<div class="col-sm-12">
									Detalle:
									<input type="text" class="form-control" placeholder="Detalle" name="detalle" autocomplete="off" id="detalle" required>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" class="form-control datepicker" placeholder="Fecha recibo" name="fecha" autocomplete="off" id="fecha" value="<?php echo date('d/m/Y')?>" required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									Monto:
									<input type="text" min="0" step="any" required class="form-control" placeholder="Monto de abono" name="monto" autocomplete="off" id="monto">
								</div>
							</div>
							
						<center>
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" name="guardar">Hacer Recibo</button>
							</div>
							<div class="btn-group">
								<a href="../receptor/"><button type="button" class="btn btn-red">Cancelar</button></a>
							</div>
						</center>	
					<?php }?>
						</form>
					</div>
					
				</div>
			
			</div>

			
			<div class="col-md-9">
			
				<div class="panel panel-dark" data-collapsed="0">
					
					<!-- panel head -->
					<div class="panel-heading">
						<div class="panel-title">Abonos (CXP)</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
					<div class="panel-body">
						<?php 
						$sql = "SELECT * FROM tabonospagar WHERE idpago = '".$_GET['id']."' AND sistema = '".$cuentaid."'  AND estado = 1";
						$query = mysql_query($sql, $conn)or die(mysql_error());
						if (mysql_num_rows($query) == 0){ ?>
							<center><i class="entypo-info-circled"></i><br>No hay abonos para esta cuenta.</center>
						<?php }else{ ?>

						<table width="100%" class="table table-bordered responsive tablaGrande">
						<thead>
						<tr>
						<th width="55"></th>
						<th>Comprobante</th> 
						<th>Detalle</th> 
						<th width="150">Fecha</th> 
						<th>Monto</th>   
						</tr>
						</thead>
						<tbody>
						<?php  

						$busquedas = 'bNombre='.$bNombre.'&';
						$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
						include("../includes/paginationTop.php"); 
						while ($row = mysql_fetch_assoc($result)){ ?>

						<tr>
						  <td>
						  

						  	<a href="pagarcxp.php?<?php echo $busqueypagi.'&idEliminar='.$row['id'].'&id='.$_GET['id'].'&numero='.$row['numero']?>" class="btn btn-sm btn-danger"><i class="entypo-trash"></i></a>
						  </td>
						  <td><?php echo addZero($row['numero']);?></td>  
						  <td><?php echo $row['detalle'];?></td> 
						  <td><?php echo $row['fecha'];?></td>     
						  <td>¢ <?php echo number_format($row['monto'],2);
						  ?></td> 
						</tr>
						<?php
						}
						?>
						</tbody>
						</table>

						<center><strong style="font-size: 13px">MONTO TOTAL DE ABONOS: <br><span style="font-size: 24px">¢ <?php echo number_format($montoAbonos,2)?></span></strong></center>
						<?php } ?>
					</div>
					
				</div>
				
			</div>
			
		</div>
	


<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>