<?php include("../conn/connpg.php");
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);

if (isset($_GET['t'])){
	$archivo = 'Compras a proveedores - '.date("d/m/Y");
	$t = $_GET['t'];
	if ($t != 3){
		if ($t == 1){
			$tipo = 'excel';
			$extension = '.xls';
		}else if ($t == 2){
			$tipo = 'word';
			$extension = '.doc';
		}	
		header("Content-type: application/vnd.ms-$tipo");
		header("Content-Disposition: attachment; filename=$archivo$extension");
		header("Pragma: no-cache");
		header("Expires: 0");
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
	<title><?php echo $nombreEmpresa?> - Compras</title>
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
	<img src="tituloReportes.png" width="225">
	<a href="javascript: void(0)" onclick="imprimir()" style="float: right; "><img src="print.png" height="19" align="absmiddle"></a>
	<a href="<?php echo $_SERVER['REQUEST_URI']?>&t=3" style="float: right; margin-right: 15px;"><img src="pdf.png" height="19" align="absmiddle"></a> 
	<a href="<?php echo $_SERVER['REQUEST_URI']?>&t=2" style="float: right; margin-right: 15px;"><img src="word.png" height="19" align="absmiddle"></a> 
	<a href="<?php echo $_SERVER['REQUEST_URI']?>&t=1" style="float: right; margin-right: 15px;"><img src="excel.png" height="19" align="absmiddle"></a> 
	<img src="separador.png" style="float: right;" width="89" class="esconder1">
</div>
<?php } ?>
<div id="cuerpoReporte">
<center><span style="font-size: 30px"><?php echo $nombreEmpresa?></span><br>
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

$idProveedor = $_GET['idProveedor'];
if ($idProveedor == 0){
	$conProv = "";
	$comodin = ""; 
}else{
	$conProv = " AND idProveedor = '$idProveedor'";

	$sql2 = "SELECT * FROM tclientes WHERE id = '$idProveedor'";
	$query2 = mysql_query($sql2, $conn);
	while ($row2=mysql_fetch_assoc($query2)) {
		$nombreProveedor = $row2['nombre'];
	}
	$comodin = "<br>PROVEEDOR: ".$nombreProveedor;
}

if ($tipo == 1){
	$m = $_GET['m']; 
	$y = $_GET['y']; ?>
	<span style="font-size: 24px; font-weight: bold">REPORTE DE COMPRAS A PROVEEDORES - MENSUAL</span><br>
	<span style="font-size: 24px; font-weight: bold">Mes: <?php echo genMonth_Text($m)?>/<?php echo $y?></span><?php echo $comodin?></center><br>
	<?php 
	$sql = "SELECT * FROM tmensajereceptorfe WHERE fechaEmision like '%".$y."-".$m."%' AND estado = 1 AND  sistema ='".$cuentaid."'";
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
	<span style="font-size: 24px; font-weight: bold">REPORTE DE COMPRAS A PROVEEDORES - PERIODO DE TIEMPO</span><br>
	<span style="font-size: 24px; font-weight: bold">Desde: <?php echo $inicio?> Hasta: <?php echo $fin?></span><?php echo $comodin?></center><br>
	<?php 
	$sql = "SELECT * FROM tmensajereceptorfe WHERE STR_TO_DATE(fechaEmision, '%Y-%m-%d') BETWEEN '$inicioN' AND '$finN' AND estado = 1";
}
$tipo='';
if($_GET['tipoFE']!=""){
	 $tipo=" AND tipoFA='".$_GET['tipoFE']."' ";
}


$query = mysql_query($sql.$tipo, $conn);
if (mysql_num_rows($query) == 0){
	echo '<center>No se han registrado compras a proveedores para los parámetros seleccionados.</center>';
}else{
?>
<table border="1" width="100%" class="table table-bordered responsive tablaGrande">
		<tr>
			<th>Proveedor</th> 
			<th width="150">N° Factura</th>
			<th width="150">Fecha</th>
			<th width="150">Impuesto</th> 
			<th width="150">Monto</th> 
			<th></th> 
		</tr>
<?php
$total = 0;
while ($row=mysql_fetch_assoc($query)) {
$idProveedor = $row['idProveedor'];
$cedulaEmisor = preg_replace('/^0+/', '', $row['cedulaEmisor']); 
$sql2 = "SELECT * FROM tclientes WHERE identificacion = '".$cedulaEmisor."' AND usuario ='".$cuentaid."' ";

$query2 = mysql_query($sql2, $conn);
while ($row2=mysql_fetch_assoc($query2)) {
	$nombreProveedor = $row2['nombre'];
}

	?>
		<tr>
		  <td><?php echo $nombreProveedor?></td>
		  <td><?php echo $row['consecutivoFE']?></td>
		  <td><?php echo $row['fechaEmision']?></td>
		  <td><?php echo number_format($row['totalImpuesto'],2,'.','');$totalImpuesto += $row['totalImpuesto'];?></td>
		  <td><?php echo number_format($row['totalFactura'],2,'.',''); $total += $row['totalFactura'];?></td>
		  <td><?php if ($row['tipoFA'] == 0){echo 'Compra';}else if ($row['tipoFA'] == 1){echo '<span title="Cuentas por pagar">CXP</span>';};?></td>   
		</tr>
	
<?php } ?>
<tr>
	<td></td>
	<td></td>
	<td><b>TOTAL</b></td>
	<td><b><?php echo number_format($totalImpuesto,2,'.','')?></b></td>
	<td><b><?php echo number_format($total,2,'.','')?></b></td>
	<td></td>
</tr>
</table>
<?php 
}
?>

<br>
<i>Generado el <b><?php echo date("d/m/Y h:i:s")?></b> por <?php 

$idUsuario = $_COOKIE['x23dhscs'];
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