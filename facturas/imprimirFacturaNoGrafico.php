<?php  include ("../conn/connpg.php");

//controlAcceso(8);

$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)){
      $nombreEmpresa = $row['nombre'];
      $nombreComercial = $row['nomComercial'];
      $direccion = $row['otrasSenas'];    
      $emailEmpresa = $row['email'];
      $telefonoEmpresa = $row['telefono'];
      $tributacion = $row['leyendaTributaria'];
      $cedula = $row['cedula'];
      $tipoCedula = $row['tipocedula'];
      $observaciones = $row['valor'];
      $imprimirCopia = $row['imprimirCopia'];
      $facturaElectronica = $row['facturaElectronica'];
      $logotipo=$row['logotipo'];
      $isotipo=$row['isotipo'];
}

$idProforma = $_GET['idProforma'];
$tipo = $_GET['tipo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Factura</title>
  <style type="text/css">
    body {
      font-family: "Times New Roman", Times, serif;
    }
  </style>
</head>
<body>

<center><?php  
echo $nombreEmpresa.'<br>'.$nombreComercial.'<br>';
if ($tipoCedula == 0){
  $tipoCedula = "C.F.:";
}else{
  $tipoCedula = "C.J.:";
}
echo $tipoCedula.' '.$cedula.'<br>';
echo $direccion.'<br>';
echo 'Email: '.$emailEmpresa.'<br>';
echo 'Tel.: '.$telefonoEmpresa;
echo '<hr>';
?>
</center>

<?php 
$sql = "SELECT tp.*, DATE_ADD(STR_TO_DATE(tp.fechaCierre,'%d/%m/%Y'),INTERVAL tp.validez DAY) AS validoHasta FROM tfacturas as tp WHERE tp.id = '$idProforma'";
$query = mysql_query($sql, $conn)or die(mysql_error());
while ($row=mysql_fetch_assoc($query)){
  $tipoCambio = $row['tipoCambio'];
  $descuento = $row['descuento'];
  $numero = $row['numero'];
  $exoneradoFactura = $row['exonerar'];
  $nombreCliente = $row['nombreCliente'];
  $fecha = $row['fechaCreacion'];
  //$identificacionCliente = $row['identificacionCliente'];
  $email = $row['email'];
  $telefono  = $row['telefono'];
  $fechaCierre =  $row['fechaCierre'];
  $vendedor = $row['idVendedor'];

  $estado = $row['estado'];

  $claveFE = $row['claveFE'];
  $consecutivoFE = $row['consecutivoFE'];
}
?>

<center><span style="font-size: 24px">FACTURA 
      <?php  
        if ($estado == 4){
          echo 'DE CRÉDITO ';
        }else{
          echo 'DE CONTADO ';
        }
      ?>: <?php echo addZero($numero)?></span></center><br>
FECHA: <?php echo $fecha?><br>
CLIENTE: <?php echo $nombreCliente?><br>
VENDEDOR: <?php  
  $sql = "SELECT * FROM usuarios WHERE id = '$vendedor'";
  $query = mysql_query($sql, $conn);
  while ($row=mysql_fetch_assoc($query)) {
    echo $row['nombre'];
  }
?>
<br><br>

<style type="text/css">
  .tabla {
    margin: 0px;
    padding: 0px;
  }

  body {
    font-family: ;
  }
</style>
<table width="100%" class="tabla">
<tr>
  <td style="text-align: right;">Cant.</td>
  <td width="20%" style="text-align: right;">Código.</td>
  <td style="text-align: right;">P.Unitario</td>
  <td style="text-align: right;">Total</td>
</tr> 

<tr>
  <td colspan="4">
  <hr>
  </td>
</tr> 

  <?php  
    $lineas = 0;
    $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma'";
    $query = mysql_query($sql, $conn)or die(mysql_error($conn));
    $cantidadProductos = mysql_num_rows($query);
    
    $totalGravado = 0;
    $totalExcento = 0;
    $descuentoTotal = 0;

        while ($row=mysql_fetch_assoc($query)) { 
          if ($row['descuento'] == "" or $row['descuento'] == 0){
            $descuentoLinea = 0;
          }else{
            $descuentoLinea = $row['descuento'];  
          }

          $impuesto = $row['impuesto'];
          ?>
          <tr>
            <td width="20%" colspan="4">
              <?php  if ($impuesto == 0){echo '* ';}?> 
              <?php echo $row['nombre']?>
            </td>
          </tr>
          <tr>
            <td  style="border-bottom: 1px dashed #000000"><?php  echo $row['cantidad']; ?></td>
            <td style="border-bottom: 1px dashed #000000"><?php  echo $codigo = $row['idProducto'];?></td>
            <td style="border-bottom: 1px dashed #000000; text-align: right;"><?php 
               if ($row['moneda'] == 1){ 
                 echo "¢ ";
               }else{
                 echo "$ ";
               }

               if ($impuesto == 0){
                  
                  $precioIMP=$row['precio'];
                }else{
                  $precioIMP=$row['precio']*0.13;
                  $precioIMP=$row['precio']+$precioIMP;
                }

             echo number_format($row['precio'],2);

             if ($descuentoLinea != 0){
              echo ' - ('.$descuentoLinea.'%)';
             }

             ?></td>
            <td style="border-bottom: 1px dashed #000000; text-align: right;">
              
              <?php  
                  if ($row['moneda'] == 1){
              if ($impuesto == 0){
                $totalCompra = $row['cantidad']*$row['precio'];
                $esteDescuento = $totalCompra * ($descuentoLinea/100);
                $totalCompra = $totalCompra - $esteDescuento;
                $descuentoTotal += $esteDescuento;
                $totalExcento = $totalExcento + $totalCompra;
              }else{
                $totalCompra = $row['cantidad']*$row['precio'];
                $esteDescuento = $totalCompra * ($descuentoLinea/100);
                $totalCompra = $totalCompra - $esteDescuento;
                $descuentoTotal += $esteDescuento;
                $totalGravado = $totalGravado + $totalCompra;       
              }
              echo "¢ ";
                  }else{
              if ($impuesto == 0){
                $totalCompra = ($row['cantidad']*$row['precio'])*$tipoCambio;
                $esteDescuento = $totalCompra * ($descuentoLinea/100);
                $totalCompra = $totalCompra - $esteDescuento;
                $descuentoTotal += $esteDescuento;
                $totalExcento = $totalExcento + $totalCompra;
              }else{
                $totalCompra = ($row['cantidad']*$row['precio'])*$tipoCambio;
                $esteDescuento = $totalCompra * ($descuentoLinea/100);
                $totalCompra = $totalCompra - $esteDescuento;
                $descuentoTotal += $esteDescuento;
                $totalGravado = $totalGravado + $totalCompra;       
              } 

              echo "$ ";
                  }
                   if ($impuesto == 0){
                  
                  $totalIMP=$totalCompra;
                }else{
                  $totalIMP=$totalCompra*0.13;
                  $totalIMP=$totalCompra+$totalIMP;
                }
                  echo '<span id="total_'.$row['idProducto'].'">'.number_format($totalCompra,2).'</span>';
                  ?>
                
            </td>
          </tr>
  <?php  } 

  $subTotal = $totalExcento + $totalGravado;
  $impuestoTotal = ($totalGravado - $descuentoGravado)*0.13;
  
  if ($exoneradoFactura == 1){
  $impuestoTotal = 0;
  }
  
  $totalPagar = ($subTotal) + $impuestoTotal;
  $dec = explode(".",number_format($totalPagar,2,".","")); 

  ?>

<tr>
  <td colspan="4">
  <hr>
  </td>
</tr>
<tr>
  <td></td>
  <td></td>
  <td width="101" style="text-align: right;">SUBTOTAL</td>
  <td width="109" style="text-align: right;"><strong>&cent <?php echo number_format($subTotal,2);?></strong></td>
</tr>
<tr>
  <td></td>
  <td></td>
  <td width="101" style="text-align: right;">IMPUESTO</td>
  <td width="109" style="text-align: right;"><strong>&cent; <?php echo number_format($impuestoTotal,2)?></strong></td>
</tr> 
<tr>
  <td></td>
  <td></td>
  <td width="101" style="text-align: right;">TOTAL A PAGAR</td>
  <td width="109" style="text-align: right;"><strong>&cent; <?php echo number_format($totalPagar,2);?></strong></td>
</tr> 
<tr>
  <td colspan="4">
  <center>
    <br>
    <i>Líneas con * son excentas de I.V.</i><br />
     <?php  if($descuentoTotal != 0){?>
        DESCUENTO APLICADO: <strong>&cent; <?php echo number_format($descuentoTotal,2);?></strong>
     <?php  } ?> 
  </center>
  </td>
</tr>

</table>
<br><br>
<center><?php echo $tributacion?></center>

<?php  
if ($facturaElectronica == 1 AND $claveFE != ''){?>
<br><center>Clave FE: <?php echo $claveFE?> Consecutivo FE: <?php echo $consecutivoFE?></center>
<?php  } ?>

<script type="text/javascript">
    window.print();
</script>
</body>
</html>