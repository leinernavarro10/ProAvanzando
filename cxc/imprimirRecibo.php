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
    src: url(../fonts/CenturyGothic.ttf) format("truetype");
  
  font-family: "Angelina";
    src: url(../fonts/angelina.ttf) format("truetype");
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
  height: 600px;}

@font-face {
    font-family: "Century Gothic";
    src: url(../assets/css/fonts/CenturyGothic.ttf) format("truetype");
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

.noborder td {
   border: none;
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

<table width="100%" border="0">
  <tr>
    <td rowspan="3" width="220">
          <img src="../assets/logos/<?php echo $isotipo?>" width="100">
      </td>
    <td><center>
      <strong><?php echo $nombreEmpresa?></strong><br />
<?php  echo $nombreComercial?></center></td>
    <td><center>
      RECIBO POR DINERO N°
    </center></td>
  </tr>
  <tr>
    <td><center>
      
      <?php if ($tipoCedula == 0){
        $tipoCedula = "Cédula fisica:";
      }else{
        $tipoCedula = "Cédula jurídica:";
      }
      ?>
      <?php echo $tipoCedula?> <?php echo $cedula?><br />
      <?php echo $direccion?>
    </center></td>
    <td rowspan="2">
      <div id="numeroFactura"><?php echo addZero($numero);?></div>
    </td>
  </tr>
  <tr>
    <td><center>
      <?php echo $emailEmpresa?> <?php echo $telefonoEmpresa?>
    </center></td>
    </tr>
</table>

            
 <div id="detalle"><table border="0" width="100%" id="tabla" cellpadding="5" cellspacing="0">
  <tr>
    <td width="100"><strong>Recibimos de: </strong></td>
    <td style="text-transform: uppercase;"><?php echo $nombreCliente?></td>
    <td width="125"><div style="text-align: right"><strong>Fecha:</strong> <span class="pre"><?php echo $row['fechaRecibo']?></span></div></td>
  </tr>
  <tr>
    <td><strong>La suma de:</strong></td>
    <td colspan="2"><span class="pre" style="text-transform: uppercase;"><?php echo strtoupper(num2letras($dec[0], false, '.').' CON '.$dec[1].'/100')?> COLONES</span></td>
    </tr>
  <tr>
    <td colspan="2" style="border-bottom: none;"><strong>Por concepto de:</strong></td>
    <td style="border: 1px solid #484848;"><div style="text-align: right"><span class="pre"><strong>&cent;<?php echo number_format($monto, 2)?></strong></span></div></td>
    </tr>
  <tr>
    <td colspan="3" style="border-top: none;">
    <div class="concepto" style="display: block; width: 100%;
    "><?php echo strtoupper(str_replace('&nbsp;', '', $row['concepto']))?></div></td>
    </tr>
  <tr>
    <td colspan="3">
      
    <table width="100%" class="noborder">
      <tr>
        <td style="border: none" width="16.6%"><b>SALDO ACTUAL:</b> </td>
        <td style="border: none" width="16.6%">&cent;<?php echo number_format($saldoActual, 2)?></td>
        <td style="border: none" width="16.6%"><b>MONTO DE ABONO:</b> </td>
        <td style="border: none" width="16.6%">&cent;<?php echo number_format($monto, 2)?></td>
        <td style="border: none" width="16.6%"><b>NUEVO SALDO:</b> </td>
        <td style="border: none" width="16.6%">&cent;<?php echo number_format($nuevoSaldo, 2)?></td>
      </tr>
    </table>

    </td>
  </tr>
        </table>
        
    </div>

<table width="100%" border="0" style="text-align:center;">
  <tr>
    <td width="50%" valign="bottom" style="height: 100px;"><div style="width: 250px; margin: auto; border-top: 1px solid #484848;">Firma Cliente</div:></td>
    <td width="50%" valign="bottom"><div style="width: 250px; border-top: 1px solid #484848; margin: auto;">Firma Autorizada</div></td>
  </tr>
</table>
<center>
<span style="font-size: 10px"><?php echo $tributacion?> - ORIGINAL CLIENTE</span>
</center>
      </div>
 <!--
        <div class="separador" style="border-top: 1px dashed #B9B9B9; padding-top: 3px; margin-top: 40px;"></div>
 
  <div class="parte">
            
<table width="100%" border="0">
  <tr>
    <td rowspan="3" width="220"><img src="../assets/logos/<?php echo $isotipo?>" width="100"></td>
    <td><center>
      <strong><?php echo $nombreEmpresa?></strong><br /><?php  echo $nombreComercial?></center></center></td>
    <td><center>
      RECIBO POR DINERO N°
    </center></td>
  </tr>
  <tr>
    <td><center>
      
      <?php if ($tipoCedula == 0){
        $tipoCedula = "Cédula fisica:";
      }else{
        $tipoCedula = "Cédula jurídica:";
      }
      ?>
      <?php echo $tipoCedula?> <?php echo $cedula?><br />
      <?php echo $direccion?>
    </center></td>
    <td rowspan="2">
      <div id="numeroFactura"><?php echo addZero($numero);?></div>
    </td>
  </tr>
  <tr>
    <td><center>
      <?php echo $emailEmpresa?> <?php echo $telefonoEmpresa?>
    </center></td>
    </tr>
</table>

            
    <div id="detalle"><table border="0" width="100%" id="tabla" cellpadding="5" cellspacing="0">
  <tr>
    <td width="100"><strong>Recibimos de: </strong></td>
    <td style="text-transform: uppercase;"><?php echo $nombreCliente?></td>
    <td width="125"><div style="text-align: right"><strong>Fecha:</strong> <span class="pre"><?php echo $row['fecha']?></span></div></td>
  </tr>
  <tr>
    <td><strong>La suma de:</strong></td>
    <td colspan="2"><span class="pre" style="text-transform: uppercase;"><?php echo strtoupper(num2letras($dec[0], false, '.').' CON '.$dec[1].'/100')?> COLONES</span></td>
    </tr>
  <tr>
    <td colspan="2" style="border-bottom: none;"><strong>Por concepto de:</strong></td>
    <td style="border: 1px solid #484848;"><div style="text-align: right"><span class="pre"><strong>&cent;<?php echo number_format($monto, 2)?></strong></span></div></td>
    </tr>
  <tr>
    <td colspan="3" style="border-top: none;">
    <div class="concepto" style="display: block; width: 100%;
    "><?php echo strtoupper(str_replace('&nbsp;', '', $row['concepto']))?></div></td>
    </tr>
  <tr>
    <td colspan="3">
      
    <table width="100%" class="noborder">
      <tr>
        <td style="border: none" width="16.6%"><b>SALDO ACTUAL:</b> </td>
        <td style="border: none" width="16.6%">&cent;<?php echo number_format($saldoActual, 2)?></td>
        <td style="border: none" width="16.6%"><b>MONTO DE ABONO:</b> </td>
        <td style="border: none" width="16.6%">&cent;<?php echo number_format($monto, 2)?></td>
        <td style="border: none" width="16.6%"><b>NUEVO SALDO:</b> </td>
        <td style="border: none" width="16.6%">&cent;<?php echo number_format($nuevoSaldo, 2)?></td>
      </tr>
    </table>

    </td>
  </tr>
        </table>
        
    </div>

<table width="100%" border="0" style="text-align:center;">
  <tr>
    <td width="50%" valign="bottom" style="height: 75px;"><div style="width: 250px; margin: auto; border-top: 1px solid #484848;">Firma Cliente</div:></td>
    <td width="50%" valign="bottom"><div style="width: 250px; border-top: 1px solid #484848; margin: auto;">Firma Autorizada</div></td>
  </tr>
</table>
<center>
<span style="font-size: 10px"><?php echo $tributacion?> - COPIA CONTABILIDAD</span>
</center>

</div>
    </div>-->
 <?php }?>   
<script type="text/javascript">
//window.print();
</script>    
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