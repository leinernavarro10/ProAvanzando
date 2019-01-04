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
<?php $pagina="Pago cxc"; include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="cuentasCobrar.php">Cuentas por cobrar</a></li>
	<li><a href="#">Nuevo abono</a></li>
</ol>
<h2>Nuevo abono</h2>
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

	if ($fecha == ""){
		echo "<script type=\"text/javascript\">miAlerta('Por favor indica una fecha.')</script>";
	}else{
		if ($monto == ""){
			echo "<script type=\"text/javascript\">miAlerta('El monto no puede estár vacío.')</script>";
		}else{
			$sql = "SELECT * FROM tabonoscobrar WHERE numeroRecibo = '$recibo' AND sistema='".$cuentaid."' AND estado = 1";
			$query = mysql_query($sql, $conn);
			if (mysql_num_rows($query) != 0){
				echo "<script type=\"text/javascript\">miAlerta('El número de recibo ya fue utilizado anteriormente.')</script>";
			}else{
				if ($monto > $saldo){
					echo "<script type=\"text/javascript\">miAlerta('El abono no puede ser mayor al saldo.')</script>";
				}else{
					$concepto="";
					if($monto == $saldo){
						$concepto="Pago de factura numero ";
					}

					if($monto == $saldo){
						$concepto="Pago de factura numero: ".$numeroFactura;
					}

					if($monto < $saldo){
						$concepto="Abono a la factura numero: ".$numeroFactura;
					}


					$sql = "INSERT INTO tabonoscobrar VALUES (null,'".$_POST['idCliente']."','$numeroFactura', '$recibo', '$fecha','".$concepto."','".$saldo."','".$monto."','".($saldo-$monto)."', 1,'".$userid."','".$cuentaid."')";
					$query = mysql_query($sql, $conn);
					$idTA=mysql_insert_id();

					if(($saldo-$monto)==0){
						$sql4 = "UPDATE tcuentascobrar SET estado = '3' WHERE numero = '".$numeroFactura."' AND sistema='".$cuentaid."' ";
						$query4 = mysql_query($sql4, $conn);
					}

					$sql4 = "UPDATE tparametros SET recibo = '$recibo' WHERE id = '".$cuentaid."' ";
					$query4 = mysql_query($sql4, $conn);

					echo "<script type=\"text/javascript\">mensajeExito('El abono se registró correctamente.')</script>";
				}
			}	
		}
	}
}

if (isset($_GET['idEliminar'])){
	$idEliminar = $_GET['idEliminar'];
	
	$sql = "UPDATE tabonoscobrar SET estado = 2 WHERE id = '$idEliminar' AND sistema='".$cuentaid."' ";
	$query = mysql_query($sql, $conn);

	$sql = "SELECT * FROM tcuentascobrar WHERE numeroFactura = '$numeroFactura' AND sistema='".$cuentaid."' AND estado = 1";
  	$query = mysql_query($sql, $conn);
  	while ($row=mysql_fetch_assoc($query)) {
    	$montoAbonos += $row['monto'];
	}

	$sql = "SELECT * FROM tabonoscobrar WHERE numeroFactura = '$numeroFactura' AND sistema='".$cuentaid."' AND estado = 1";
  	$query = mysql_query($sql, $conn);
  	while ($row=mysql_fetch_assoc($query)) {
    	$montoAbonos += $row['monto'];
	}

      $sql3 = "UPDATE tcuentascobrar SET estado = 1 WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."'";
      $query3 = mysql_query($sql3, $conn);
?>
	<script type="text/javascript">document.location.href="agregarAbono.php?<?php echo $busqueypagi?>&id=<?php echo $_GET['id']?>&numero=<?php echo $_GET['numero']?>"</script>";
<?php
	break;
}
?>

<div class="row">
		
			<div class="col-md-3">
			
				<div class="panel panel-dark">
					
					<!-- panel head -->
					<div class="panel-heading">
						<div class="panel-title">Nuevo abono</div>
						
						<div class="panel-options">
							
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
			
					<div class="panel-body">
						<form action="" method="post" class="form-horizontal">
						<?php $sql = "SELECT * FROM tcuentascobrar WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."' ";
						$query = mysql_query($sql, $conn) or die(mysql_error());
						while ($row=mysql_fetch_assoc($query)) { $idCliente=$row['idCliente'];$estado=$row['estado']; ?>
						<table width="100%" class="table">
							<tr>
								<td colspan="2" style="text-align: center; font-size: 36px; color: #C30000"><strong><?php echo addZero($row['numero'])?></strong></td>								
							</tr>
							<tr>
								<td colspan="2" style="border-top: none; text-align: center;"><strong><?php echo $row['nombreCliente']?></strong></td>							
							</tr>
							<tr>
								<td width="75">Fecha: </td>
								<td><strong><?php echo $row['fechaFactura']?></strong></td>								
							</tr>
							<tr>
								<td>Monto: </td>
								<td><strong>&cent; <?php echo number_format($row['monto'],2)?></strong></td>								
							</tr>
							<tr>
								<td>Saldo: </td>
								<td ><strong><?php
 								  $montoAbonos = 0;
								  $saldo = $row['monto']; 
								  $sql = "SELECT * FROM tabonoscobrar WHERE numeroFactura = '$numeroFactura' AND sistema='".$cuentaid."' AND estado = 1";
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
						<hr><center><strong>NUEVO ABONO</strong></center><hr>

							<input type="hidden" name="idCliente" id="idCliente" value="<?php echo $idCliente?>">
							<input type="hidden" name="numero" id="numero" value="<?php echo $row['numero']?>">

							<input type="hidden" name="saldo" id="saldo" value="<?php echo $saldo?>">
							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" class="form-control" readonly placeholder="Número recibo" name="recibo" value="<?php echo addZero('0')?>" autocomplete="off" id="recibo" >
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" class="form-control datepicker" readonly placeholder="Fecha recibo" name="fecha" autocomplete="off" id="fecha" value="<?php echo date('d/m/Y')?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<input type="number" min="0" step="any" required="required" class="form-control" placeholder="Monto de abono" name="monto" autocomplete="off" id="monto">
								</div>
							</div>
							
						<center>
							<div class="btn-group">
								<button type="submit" class="btn btn-primary" name="guardar">Hacer Recibo</button>
							</div>
							<div class="btn-group">
								<a href="cuentasCobrar.php?<?php echo $busqueypagi?>"><button type="button" class="btn btn-red">Cancelar</button></a>
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
						<div class="panel-title">Abonos</div>
						
						<div class="panel-options">
							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
						</div>
					</div>
					
					<!-- panel body -->
					<div class="panel-body">
						<?php 
						$sql = "SELECT * FROM tabonoscobrar WHERE numeroFactura = '$numeroFactura' AND sistema = '".$cuentaid."'  AND estado = 1";
						$query = mysql_query($sql, $conn)or die(mysql_error());
						if (mysql_num_rows($query) == 0){ ?>
							<center><i class="entypo-info-circled"></i><br>No hay abonos para esta cuenta.</center>
						<?php }else{ ?>

						<table width="100%" class="table table-bordered responsive tablaGrande">
						<thead>
						<tr>
						<th width="55"></th>
						<th>Número factura</th> 
						<th>Número recibo</th> 
						<th width="150">Fecha recibo</th> 
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
						  	<a href="javascript: imprimir(<?php echo $row['id']?>)"  class="btn btn-sm btn-primary"><i class="entypo-print"></i></a>

						  	<a href="agregarAbono.php?<?php echo $busqueypagi.'&idEliminar='.$row['id'].'&numero='.$row['numeroFactura']?>" class="btn btn-sm btn-danger"><i class="entypo-trash"></i></a>
						  </td>
						  <td><?php $numeroFactura = $row['numeroFactura'];echo addZero($row['numeroFactura']);?></td>  
						  <td><?php echo $row['numeroRecibo'];?></td> 
						  <td><?php echo $row['fechaRecibo'];?></td>     
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
				<?php //visibility: hidden; height: 0px;?>
				<div id="iframes" style="visibility: hidden; height: 0px;"></div>
			</div>
			
		</div>
	
<script type="text/javascript">
	

  function imprimir(id){


<?php 
if ($tipoFacturaParametro == 0){ ?>
	document.getElementById('iframes').innerHTML = "";
  	
  	var iframe = '<iframe id="generarDocumento" src="imprimirReciboNoGrafico.php?idRecibo='+id+'&tipo=1" style="width: 8cm; height: 150px;"></iframe>';
    document.getElementById('iframes').innerHTML+=iframe; 
<?php }else{ ?>
	document.getElementById('iframes').innerHTML = "";
  
  		var iframe = '<iframe id="generarDocumento" src="imprimirRecibo.php?idRecibo='+id+'&tipo=1" style="width: 850px; height: 150px;"></iframe>';
    
    document.getElementById('iframes').innerHTML+=iframe; 
<?php } ?>
  }

<?php 
if($idTA!=""){
	?>
imprimir(<?php echo $idTA?>)
	<?php 
}
?>

</script>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>