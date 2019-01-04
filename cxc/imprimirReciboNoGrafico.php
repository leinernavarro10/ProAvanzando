<?php include ("../conn/connpg.php"); 

//controlAcceso(12);

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

       $logotipo=$row['logotipo'];
        $isotipo=$row['isotipo'];
}

$idProforma = $_GET['idRecibo'];
$tipo = $_GET['tipo'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factura</title>
<script src="../assets/js/jquery-1.11.3.min.js"></script>
<style type="text/css">
@font-face {
    font-family: "Century Gothic";
    src: url(fonts/CenturyGothic.ttf) format("truetype");
  
  font-family: "Angelina";
    src: url(fonts/angelina.ttf) format("truetype");
}

body {
  font-family: Century Gothic;
  font-size: 12px;
  margin: 10px;
  padding: 10px;
}
.redondo {
  -moz-border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;
  border: 1px solid #333;
}
#numero {
  width: 95%;
  height: 30px;
  font-size: 25px;
  padding-top: 2px;
  padding-bottom: 3px;
  color: #F00;
}

.parte {width: 100%; height: 470px; display: block; }
.separador {
  width: 100%;
  height: 10px;
}

#detalle {
  width: 95%;
  margin: auto;
}

#tabla td {
  border-top: 1px solid #484848;}
  
.check {
  display: block;
  border: 1px solid #CCC;
  width: 15px;
  height: 15px;
  float: left;
  margin-left: 5px; 
  margin-right: 5px;}
  
.concepto {
  height: 170px;}

@font-face {
    font-family: "Century Gothic";
    src: url(assets/css/fonts/CenturyGothic.ttf) format("truetype");
}

body {
  font-family: Century Gothic;
  font-size: 12px;
  min-height: 400px;
  overflow: hidden;
}

#contenedor {
  width: 800px;
  margin: auto;
}

#numeroFactura {
  font-size: 35px;
  color: #F00;
  text-align:center;
  
  border: 1px solid #F00;
  border-radius: 5px;
}

.tabla td {
  border-bottom: 1px solid #CCC;
}

.tabla_titulo {
  background: #484848;
  height: 30px;
  color: #FFF;
  text-align: center}
  
td .tabla_final {
  border-top: 3px solid #484848;
  border-bottom: none;
}

ul {
  margin:0;
  padding:0;
}

.izq {
  text-align: right;
}

.especial li{
    margin-left: 30px;
}
</style>
</head>

<body>
<?php $sql = "SELECT * FROM tabonoscobrar WHERE id = '".$_GET['idRecibo']."'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)){

  $numero = $row['numeroRecibo'];
  
  $idCliente = $row['idCliente'];
  $fecha = $row['fechaCreacion'];
 
  $vendedor = $row['idUsuario'];
  $monto = $row['monto'];
  $saldoActual = $row['saldoActual'];
  $nuevoSaldo = $row['nuevoSaldo'];

  $sql2 = "SELECT * FROM tclientes WHERE id = '".$idCliente."'";
  $query2 = mysql_query($sql2, $conn)or die(mysql_error());
  while ($row2=mysql_fetch_assoc($query2)){
    $nombreCliente = $row2['nombre'];
  }

  $sql2 = "SELECT * FROM usuarios WHERE id = '".$row['idUsuario']."'";
  $query2 = mysql_query($sql2, $conn)or die(mysql_error());
  while ($row2=mysql_fetch_assoc($query2)){
    $nombreUsuario = $row2['nombre'];
  }
  $dec = explode(".",number_format($row['monto'],2,".","")); 
  ?>

    <div style="width: 100%; height: 950px; display: block;" id="factura">
    
      <div class="parte">

<center><?php 
echo $nombreEmpresa.'<br>'.$nombreComercial.'<br>';
if ($tipoCedula == 0){
  $tipoCedula = "C.F.:";
}else{
  $tipoCedula = "C.J.:";
}
echo $tipoCedula.' '.$cedula.'<br><br>';
echo $direccion.'<br><br>';
echo 'Email: '.$emailEmpresa.'<br>';
echo 'Tel.: '.$telefonoEmpresa;
echo '<hr>';
?>
</center>
<center><span style="font-size: 24px">RECIBO N°: <?php echo addZero($numero)?></span></center><br>
FECHA: <?php echo $row['fechaRecibo']?><br>
CLIENTE: <?php echo $nombreCliente?><br>
USUARIO: <?php echo $nombreUsuario;
?>
<br><br>
<hr>
<center>RECIBIMOS LA SUMA DE: &cent;<?php echo number_format($monto, 2)?><br><?php echo strtoupper(num2letras($dec[0], false, '.').' CON '.$dec[1].'/100')?></center>
<br><br>
<table width="100%">
  <tr>
    <td>POR CONCEPTO DE: </td>
  </tr>
  <tr>
    <td style="border: 1px dashed #A9A9A9; padding-top: 5px; padding-bottom: 5px;">
      <?php echo strtoupper(str_replace('&nbsp;', '', $row['concepto']))?>
    </td>
  </tr>

  <tr>
    <td>
     <table width="300" style="float: right;">
      <tr>
        <td width="150"><b>SALDO ACTUAL: </b></td>
        <td width="150">&cent;<?php echo number_format($saldoActual, 2)?></td>
      </tr>
      <tr>
        <td><b>MONTO DE ABONO:</b></td>
        <td>&cent;<?php echo number_format($monto, 2)?></td>
      </tr>
      <tr>
        <td><b>NUEVO SALDO:</b></td>
        <td>&cent;<?php echo number_format($nuevoSaldo, 2)?></td>
      </tr>
    </table>
    </td>
  </tr>

</table>
<br><br>

________________________<br>
FIRMA CLIENTE<br><br>
<center>
<span style="font-size: 10px"><?php echo $tributacion?></span>
</center>
<?php } ?>
</body>
</html>

<?php include("../includes/exportar.php");?>

<script type="text/javascript">

  <?php 
  if ($tipo == 1){ ?>
    window.print();
  <?php }else if($tipo == 2){ ?>
    capturar("r", "<?php echo $idProforma?>");
  <?php } ?>
  
</script>