<?php  include ("../conn/connpg.php"); 


controlAcceso('C-0');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="../assets/js/jquery-1.11.3.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factura <?php echo $numero?> (<?php echo $identificacionCliente?>) <?php echo $nombreCliente?></title>
<style type="text/css">

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
<div id="contenedor">
<?php 
$sql = "SELECT * FROM casientos WHERE id = '".$_GET['id']."'";
$query = mysql_query($sql, $conn)or die(mysql_error());
while ($row=mysql_fetch_assoc($query)){
 
}
?>

  <table width="100%" border="0">
  <tr>
    <td>Comprobante</td>
    <td><center>
      <strong><?php  echo $nombreEmpresa?></strong><br /><?php  echo $nomComercial?></center></td>
    <td width="150"><center>
      FACTURA 
      <?php  
        if ($estado == 4){
          echo 'DE CRÉDITO ';
        }else{
          echo 'DE CONTADO ';
        }
      ?>N° 
    </center></td>
  </tr>
  <tr>
    <td></td>
    <td><center>
      
      <?php  if ($tipoCedula == 0){
        $tipoCedula = "Cédula fisica:";
      }else{
        $tipoCedula = "Cédula jurídica:";
      }
      ?>
      <?php  echo $tipoCedula?> <?php  echo $cedula?><br />
      <?php  echo $direccion?>
    </center></td>
    <td rowspan="2">
      <div id="numeroFactura"><?php echo addZero($numero);?></div>
    </td>
  </tr>
  <tr>
    <td></td>
    <td><center>
      <?php  echo $emailEmpresa?> <?php  echo $telefonoEmpresa?>
    </center></td>
    </tr>
</table>

<table width="100%" border="0">
  <tr>
    <td width="7%">Cliente: </td>
    <td width="68%"><strong>
   <?php  echo $nombreCliente?></strong>  
    
    </td>
    <td width="13%">Fecha creación: </td>
    <td width="12%"><strong><?php echo $fecha?></strong></td>
  </tr>
  <tr>
    <td>Contacto: </td>
    <td><strong><?php echo $telefono?> - <?php echo $email?></strong></td>
    <td>Fecha cierre: </td>
    <td><strong><?php echo $fechaCierre?></strong></td>
  </tr>
  <tr>
    <td>Vendedor: </td>
    <td><strong>
      <?php 

        $sql = "SELECT * FROM tusuarios WHERE id = '$vendedor'";
        $query = mysql_query($sql, $conn);
        while ($row=mysql_fetch_assoc($query)) {
          echo $row['nombre'];
        }

      ?>

    </strong></td>
    <td> </td>
    <td></td>
  </tr>
</table>
<table width="100%" border="1" cellpadding="1" cellspacing="0" class="tabla">
  <tr class="tabla_titulo">
    <td width="49">CANT</td>
    <td width="76">CÓDIGO</td>
    <td width="443">DESCRIPCIÓN</td>
    <td width="101">PRECIO UNIT</td>
    <td width="75" class="colDesc">DESC</td>
    <td width="109">PRECIO TOTAL</td>
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

          if ($lineas <= $cantidadLinasParam){
          //AQUI EMPEZAMOS
          $str = explode("-", $row['nombre']);
          for  ($ab = 0; $ab <= count($str); $ab++){ 
            if ($lineas < $cantidadLinasParam){
              if ($ab != 0){
                if ($str[$ab] != ""){
                  $lineas++;?>
                
                <tr>
                      <td></td>
                      <td></td>
                      <td>- <?php echo getSubString($str[$ab],70); ?></td>
                      <td></td>
                      <td class="colDesc"></td>
                      <td></td>   
                  </tr>

              <?php  } 
              }else{
                $lineas++;
              ?>

          <tr>
            <td>
            <?php  
              echo $row['cantidad'];
            ?>
            </td>
            <td><?php  
            $impuesto = $row['impuesto'];
                echo $codigo = $row['idProducto'];
            ?></td>
            <td>
      <?php  if ($impuesto == 0){echo '* ';}?> 
      <?php  echo getSubString($str[$ab],70) ?></td>
            <td>
      <?php  
            if ($row['moneda'] == 1){
              echo "¢ ";
        echo number_format($row['precio'],2);
        ?><input type="hidden" value="<?php echo $row['precio']?>" id="precio_<?php echo $row['idProducto']?>" /><?php 
            }else{
              echo "¢ ";
        echo number_format($row['precio']*$tipoCambio,2);
        ?><input type="hidden" value="<?php echo $row['precio']*$tipoCambio?>" id="precio_<?php echo $row['idProducto']?>" /><?php 
            }
            ?></td>
            <td width="75" class="colDesc">

                <center>
                 <?php  
                  echo $descuentoLinea.' %';
                  ?>
                </center>
            </td>
            <td><?php  
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
            echo '<span id="total_'.$row['idProducto'].'">'.number_format($totalCompra,2).'</span>';
            ?></td>
            </tr> 


        <?php    } 
            }
          }
      }
   } 
  
  
  $subTotal = $totalExcento + $totalGravado;
  $impuestoTotal = ($totalGravado - $descuentoGravado)*0.13;
  
  $exoneradoFactura;
  if ($exoneradoFactura == 1){
  $impuestoTotal = 0;
  }
  
  $totalPagar = ($subTotal) + $impuestoTotal;
  $dec = explode(".",number_format($totalPagar,2,".","")); 
  
  $i = 0;
  if ($lineas < $cantidadLinasParam){
  $dif = $cantidadLinasParam - $lineas;
  for ($x = 1; $x <= $dif; $x++){
  $i++;
  $ul = '&nbsp;';
  if ($i == 1){
    $ul = '<center>****ÚLTIMA LÍNEA****</center>';
  }?>
  <tr>
    <td></td>
    <td></td>
    <td><?php echo $ul?></td>
    <td class="izq">
    </td>
    <td class="izq colDesc">
    </td>
    <td class="izq">
    </td>
  </tr>   
    <?php  
  }
}
  ?>
      <tr>
           <td id="celdaAbajo" colspan="4" rowspan="3" class="noborder" style="border-bottom: none;" valign="top"><?php echo strtoupper(num2letras($dec[0], ',', '.').' CON '.$dec[1].'/100')?><br />
           <i>Las líneas marcadas con * son excentas de impuesto de ventas.</i><br />
           <?php  if($descuentoTotal != 0){?>
              --- Descuento aplicado: <strong>&cent; <?php echo number_format($descuentoTotal,2);?></strong>
           <?php  } ?> 
           <br /><br>
            <table width="100%">
                <tr>
                    <td style="border-bottom: none;"><center>___________________________________<br />Firma cliente</center></td>
                    <td style="border-bottom: none;"><center>___________________________________<br />Firma autorizada</center></td>
                </tr>
            </table>
      </td>
           <td width="101">Subtotal</td>
           <td width="109"><strong>&cent <?php echo number_format($subTotal,2);?></strong></td>
          </tr>
          <tr>
            <td width="101">Impuesto</td>
            <td width="109"><strong>&cent; <?php echo number_format($impuestoTotal,2)?></strong></td>
          </tr> 
          <tr>
            <td width="101">Total a pagar</td>
            <td width="109"><strong>&cent; <?php echo number_format($totalPagar,2);?></strong></td>
          </tr>
          <tr>
            <td colspan="6" class="noborder" style="border-bottom: none;" valign="top">
            
        <center>
              <?php echo $tributacion?> - ORIGINAL CLIENTE
              <?php  
              if ($facturaElectronica == 1 AND $claveFE != ''){?>
              <br><span style="font-size: 8px;">Clave FE: <?php echo $claveFE?> Consecutivo FE: <?php echo $consecutivoFE?></span>
              <?php  } ?>
        </center>    
            </td>
          </tr>
    </table> 
</div>

</body>
</html>



<script type="text/javascript">

/*
    window.print();
 
  */
</script>