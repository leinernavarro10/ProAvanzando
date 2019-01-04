<?php include("../conn/connpg.php");
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);

if (isset($_GET['t'])){
	$archivo = 'Ventas - '.date("d/m/Y");
	$t = $_GET['t'];
	if ($t != 3){
		if ($t == 1){
			$tipo = 'excel';
			$extension = '.xls';
			header("Content-type:   application/x-msexcel; charset=utf-8");
		}else if ($t == 2){
			$tipo = 'word';
			$extension = '.doc';
		}	
		header("Content-type: application/vnd.ms-".$tipo."; charset=utf-8");		

		header("Content-Disposition: attachment; filename=".$archivo."".$extension." ");
		header("Pragma: no-cache");

		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
	}else{
		ob_start();
	}
}

$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)){
      $nombreEmpresa = $row['nombre'];
      $nombreComercial = $row['nomComercial'];
      $direccion = $row['otrasSenas'];
      $web = "";
      $emailEmpresa = $row['email'];
      $telefonoEmpresa = $row['telefono'];
      $tributacion = $row['leyendaTributaria'];
      $cedula = $row['cedula'];
      $tipoCedula = $row['tipocedula'];
      $observaciones = "";
      $imprimirCopia = $row['imprimirCopia'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="TecnoSoluciones Brunca S. A." />

	<link rel="icon" href="../assets/images/faviconCoope.ico">
	<title><?php echo $nombreEmpresa?> - Reporte de Ventas</title>
	<meta charset="UTF-8">

	<script type="text/javascript">
	function imprimir(){
		window.print();
	}
	</script>
</head>
<body>

<style type="text/css">
	body {
		margin: 0px;
		padding: 0px;
	}
	.titulo {
		font-size: 24px;
	}
	#cuerpoReporte {
		width: 98%;
		margin: auto;
	}
	#menu {
		width: 99%;
		height: 25px;
		background: #e9e9e9;
		padding-right: 1%;
		padding-top: 13px;
		padding-bottom: 10px;
		border-bottom: 5px solid #313131;
		color: #313131;
		margin-bottom: 10px;

		-webkit-box-shadow: 2px 2px 21px 0px rgba(0,0,0,0.75);
		-moz-box-shadow: 2px 2px 21px 0px rgba(0,0,0,0.75);
		box-shadow: 2px 2px 21px 0px rgba(0,0,0,0.75);
	}
	#menu a{
		color: #313131;
		text-decoration: none;
	}
	
	@media(max-width: 844px){
		.esconder1{
			display: none;
		}
		.table{
			font-size: 10px;
		}
	}

</style>
<style media="print">
.noPrint {
	display: none;
}
</style>

<?php if (!isset($_GET['t'])){ ?>
<div id="menu" class="noPrint">
	<img src="tituloReportes.png" width="225" >
	<a href="javascript: void(0)" onclick="imprimir()" style="float: right; "><img src="print.png" height="19" align="absmiddle"></a>
	<a href="<?php echo $_SERVER['REQUEST_URI']?>&t=3" style="float: right; margin-right: 15px;"><img src="pdf.png" height="19" align="absmiddle"></a> 
	<a href="<?php echo $_SERVER['REQUEST_URI']?>&t=2" style="float: right; margin-right: 15px;"><img src="word.png" height="19" align="absmiddle"></a> 
	<a href="<?php echo $_SERVER['REQUEST_URI']?>&t=1" style="float: right; margin-right: 15px;"><img src="excel.png" height="19" align="absmiddle"></a> 
	<img src="separador.png" style="float: right;" width="89" class="esconder1">
</div>
<?php } ?>
<div id="cuerpoReporte">
<center><span style="font-size: 30px" class="esconder1"><?php echo $nombreEmpresa?></span><br>
	<?php if($nombreComercial!=$nombreEmpresa){ echo $nombreComercial;} ?><br>
<?php if ($tipoCedula == 0){
$tipoCedula = "Cédula fisica:";
}else{
$tipoCedula = "Cédula jurídica:";
}
?>
<?php echo $tipoCedula.': '.$cedula;?><br>

<?php 

// generamos los meses 
function genMonth_Text($m) { 
 switch ($m) { 
  case '01': $month_text = "Enero"; break; 
  case '02': $month_text = "Febrero"; break; 
  case '03': $month_text = "Marzo"; break; 
  case '04': $month_text = "Abril"; break; 
  case '05': $month_text = "Mayo"; break; 
  case '06': $month_text = "Junio"; break; 
  case '07': $month_text = "Julio"; break; 
  case '08': $month_text = "Agosto"; break; 
  case '09': $month_text = "Septiembre"; break; 
  case '10': $month_text = "Octubre"; break; 
  case '11': $month_text = "Noviembre"; break; 
  case '12': $month_text = "Diciembre"; break; 
 } 
 return ($month_text); 
}

$tipo = $_GET['tipo'];
if (!isset($_GET['abiertas'])){
	$abiertas = " AND numero != ''";
	$comodin = "";
}else{
	$abiertas = "";
	$comodin = "<br>Se muestran las facturas abiertas.";
}
if ($tipo == 1){
	$m = $_GET['m']; 
	$y = $_GET['y']; ?>
	<span style="font-size: 18px; font-weight: bold">REPORTE DE VENTAS MENSUAL</span><br>
	<span style="font-size: 18px; font-weight: bold">Mes: <?php echo genMonth_Text($m)?>/<?php echo $y?></span><?php echo $comodin?></center><br>
	<?php 
	$sql = "SELECT * FROM tfacturas WHERE sistema='".$cuentaid."' AND (fechaCierre like '%$m/$y' OR (fechaCierre = '' AND  fechaCreacion like '%$m/$y')) AND estado != '' ".$abiertas;
}else if ($tipo == 2){
	$rango = $_GET['rango']; 
	$rango = explode('-', $_GET['rango']);
	$inicio = trim($rango[0]);
	$in = explode("/", $inicio);
	$inicioN = $in[2].'-'.$in[1].'-'.$in[0];
	$fin = trim($rango[1]);
	$fn= explode("/", $fin);
	$finN = $fn[2].'-'.$fn[1].'-'.$fn[0];

	?>
	<span style="font-size: 18px; font-weight: bold">REPORTE DE VENTAS POR PERIODO DE TIEMPO</span><br>
	<span style="font-size: 18px; font-weight: bold">Desde: <?php echo $inicio?> Hasta: <?php echo $fin?></span><?php echo $comodin?></center><br>
	<?php 
	$sql = "SELECT * FROM tfacturas WHERE sistema='".$cuentaid."' AND (STR_TO_DATE(fechaCierre, '%d/%m/%Y') BETWEEN '$inicioN' AND '$finN') AND estado != '' ".$abiertas;
}

$query = mysql_query($sql, $conn);
if (mysql_num_rows($query) == 0){
	echo '<center>No se han registrado facturas para los parámetros seleccionados.</center>';
}else{
?>
<table border="1" width="100%" class="table table-bordered responsive tablaGrande">
		<tr>
			<th class="esconder1">Cliente</th>
			<th>Número</th>
			<th>Fecha</th>
			<th>Exento</th>
			<th>Gravado</th>
			<th>Impuesto</th>
			<th>Total</th>
			<th></th>
		</tr>
<?php
$excento = 0;
$gravado = 0;
while ($row=mysql_fetch_assoc($query)) {
$totalPR=0;$totalIMP=0;$totalEX=0;$totalGR=0;
	 $sql2 = "SELECT * FROM tproductosfactura WHERE idCotizacion = '".$row['id']."' ";
      $query2 = mysql_query($sql2, $conn);
      while ($row2=mysql_fetch_assoc($query2)) {

      		 if ($row2['impuesto'] == 0){   
                  
                  $totalEX+=$row2['precio']*$row2['cantidad'];
                }else{
                	$totalGR+=$row2['precio']*$row2['cantidad'];
                  $prIMP=($row2['precio']*$row2['cantidad'])*0.13;
                  $totalIMP+=$prIMP;
                }



                

       
      }

$totalPR+=$totalEX+$totalGR+$totalIMP;
	?>

		<tr>
			<td class="esconder1"><?php echo $row['nombreCliente'];?></td>
			<td><?php echo addZero($row['numero']);?></td>
			<td><?php echo $row['fechaCierre'];?></td>

			<td><?php echo ''.number_format($totalEX,2,',','.'); $totalTotalEX+=$totalEX; ?></td>
			<td><?php echo ''.number_format($totalGR,2,',','.');$totalTotalGR+=$totalGR;?></td>
			<td><?php echo ''.number_format($totalIMP,2,',','.');$totalTotalIMP+=$totalIMP;?></td>

			<td><?php echo ''.number_format($totalPR,2,',','.');$totalTotalPR+=$totalPR;?></td>
			<td><?php if ($row['estado'] == 1){echo 'Abierta';}else if($row['estado'] == 2){echo 'Efectivo';}else if($row['estado'] == 4){echo 'Cuenta por cobrar';}else if($row['estado'] == 3){echo 'Anulada';}?></td>
		</tr>
	<?php 
		if($row['estado'] == 3){
		
			$sqlNC = "SELECT * FROM tnotascreditofe WHERE sistema='".$cuentaid."' AND numeroFactura = '".$row['numero']."' ORDER by id DESC"; 
			$queryNC=mysql_query($sqlNC,$conn)or die(mysql_errno());
			if(mysql_num_rows($queryNC)>0){
				while($rowNC=mysql_fetch_assoc($queryNC)){
				?>
				<tr>
					<td class="esconder1" style="text-align: right;">Nota de credito</td>
					<td><?php echo substr($rowNC['consecutivoNC'], -5) ;?></td>
					<td><?php $fechaar = explode("T", $rowNC['fechaNC']); echo $fechaar[0];?></td>

					<td><?php echo ''.number_format(-$totalEX,2,',','.'); $totalTotalEX+=-$totalEX; ?></td>
					<td><?php echo ''.number_format(-$totalGR,2,',','.');$totalTotalGR+=-$totalGR;?></td>
					<td><?php echo ''.number_format(-$totalIMP,2,',','.');$totalTotalIMP+=-$totalIMP;?></td>

					<td><?php echo ''.number_format(-$totalPR,2,',','.');$totalTotalPR+=-$totalPR;?></td>
					<td>Nota de credito</td>
				</tr>
				<?php
				}
			}

		 }
	?>
<?php } ?>
<tr>
	<td class="esconder1"></td>
	<td></td>
	<td></td>
	<td><b><?php echo number_format($totalTotalEX,2,',','.')?></b></td>
	<td><b><?php echo number_format($totalTotalGR,2,',','.')?></b></td>
	<td><b><?php echo number_format($totalTotalIMP,2,',','.')?></b></td>
	<td><b><?php echo number_format($totalTotalPR,2,',','.')?></b></td>
	<td></td>
</tr>
</table>
<?php 
}
?>

<br>
<i>Generado el <b><?php echo date("d/m/Y h:i:s")?></b> por <?php 


$sql = "SELECT * FROM usuarios WHERE id = '$userid'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)) {
	echo '<b>'.$row['usuario'].'</b>';
}
?></i>
</div>
</body>
</html>

<?php 
if (isset($_GET['t']) and $_GET['t'] == 3){
	$salida_html = ob_get_contents();
	ob_end_clean();  

	require_once '../assets/libs/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($salida_html);
	$dompdf->set_paper ('letter','landscape'); 
	$dompdf->render();
	$dompdf->stream($archivo.".pdf");
}
?>