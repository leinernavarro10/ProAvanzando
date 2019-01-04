<?php  include ("../conn/connpg.php");



decode_get2($_SERVER["REQUEST_URI"]); 
$error = 0;

$idProforma = $_GET['idProforma'];
$tipoCambio = $_GET['tipoCambio'];
$estado = $_GET['estado'];

$sql = "SELECT * FROM tfacturas WHERE id = '$idProforma'";
$query = mysql_query($sql, $conn) or die(mysql_error());
while($row=mysql_fetch_assoc($query)){
	$descuento = $row['descuento'];
  $exonerado = $row['exonerar'];
  $monto = $row['monto'];
}

?>

<?php  include("../includes/headerLimpio.php");?>       
<?php  include("../includes/alerts.php");?>       

  <style type="text/css">
    body {
      margin: 0px;
      padding: 0px;
      overflow: auto;
    }    
  </style>

    <?php  
	$sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma'";
  $query = mysql_query($sql, $conn)or die(mysql_error());
	$numProductos = mysql_num_rows($query);
	 
   if (mysql_num_rows($query) == 0){
    echo '<center><i class="entypo-info-circled" style="font-size:18px"></i><br>No se encuentran productos en la cotizacion.</center>';
   }else{?>

    <table width="100%" class="table table-bordered responsive">
      <thead>
      <tr>
        <th class="tabla_titulo" width="125" style="text-align:center">Cantidad</th>
        <th class="tabla_titulo">Producto</th>
      </tr>
      </thead>

      <?php  
	  	$totalGravado = 0;
		  $totalExcento = 0;
      $descuentoTotal = 0;
        while ($row=mysql_fetch_assoc($query)) { 
          $descuentoLinea = $row['descuento'];

          $str = explode("-", $row['nombre']);
          for  ($ab = 0; $ab <= count($str); $ab++){ 
              if ($ab != 0){
                if ($str[$ab] != ""){ ?>
                  <tr>
                      <td></td>
                      <td>- <?php echo getSubString($str[$ab],70); ?></td>
                  </tr>
              <?php  } 
              }else{ ?>
            <tr>
              <td>
              <?php  echo $row['cantidad']; ?>
              </td>
              <td>
  			<?php  if ($impuesto == 0){echo '* ';}?> 
  			<?php  echo '<a href="https://www.google.com/search?q='.$str[$ab].'&source=lnms&tbm=isch&sa=X&ved=0ahUKEwiE1uyY0JfQAhUWz2MKHYl8BFcQ_AUICCgB&biw=1440&bih=794" title="'.$row['nombre'].'" target="_blank">'.getSubString($str[$ab],70).'</a>'; ?></td>
            </tr>   
  <?php  
        } } 
  ?>

<!-- FIN CONTENIDO -->
<?php  } } ?>
  </table> 
<center>Total a pagar: &cent; <?php echo number_format($monto,2);?></center>

<?php 
include("../includes/footerLimpio.php");?>