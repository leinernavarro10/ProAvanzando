<?php 
include ("../conn/connpg.php");


$f = $_POST['f'];
if ($f == 'CAMBIAR-PRODUCTO'){
	$idProforma=$_POST['idProforma'];
	$id=$_POST['id'];
    $codigo=$_POST['codigo'];
    $cantidad=$_POST['cantidad'];
    $numero=$_POST['numero'];
    $codigo2=$_POST['codigo2'];
    $descuento=$_POST['descuento'];
	$cont=$_POST['num'];
	$mystring = 'abc';

/*$buscar   = '-';
$Bcantidad = strpos($cantidad, $buscar);
$Bcodigo = strpos($codigo2, $buscar);
$Bdescuento = strpos($descuento, $buscar);
*/

$Bcantidad = str_split($cantidad);
$Bcodigo = str_split($codigo2);
$Bdescuento = str_split($descuento);

if (in_array("-", $Bcantidad)) {

	$idProducto = $_POST['id'];

  $sql2 = "SELECT * FROM tproductosfactura WHERE codigo = '".$codigo2."' AND idCotizacion = '$idProforma'";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());
  while($row2=mysql_fetch_assoc($query2)){
    $cantidad = $row2['cantidad'];
  }

  $sql2 = "UPDATE tproductos SET existencia  = existencia  + '$cantidad' WHERE codigo = '".$codigo2."' AND sistema = '".$cuentaid."' ";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());

  $sql2 = "DELETE FROM tproductosfactura WHERE codigo = '".$codigo2."' AND idCotizacion = '$idProforma'";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());

  echo "5";

} else {
   


	$sql="SELECT * FROM tproductos WHERE codigo ='".$codigo."' AND sistema='".$cuentaid."'";
	$query=mysql_query($sql,$conn)or die(mysql_error());
	
	if(mysql_num_rows($query)==1){
		$sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '".$idProforma."' AND codigo = '".$codigo."' AND codigo!='".$codigo2."'  ";
      	$query = mysql_query($sql, $conn);
      	if (mysql_num_rows($query) == 0){

			$sql2 = "SELECT * FROM tproductosfactura WHERE codigo = '".$codigo2."' AND idCotizacion = '".$idProforma."' ";
		    $query2 = mysql_query($sql2, $conn) or die(mysql_error());
		    while($row2=mysql_fetch_assoc($query2)){
		       $cantidadNueva = $row2['cantidad'];
		    }

		    $sql2 = "UPDATE tproductos SET existencia  = existencia  + '".$cantidadNueva."' WHERE codigo = '".$codigo2."' AND sistema = '".$cuentaid."' ";
		    $query2 = mysql_query($sql2, $conn) or die(mysql_error());

		    $sql2 = "DELETE FROM tproductosfactura WHERE codigo = '".$codigo2."' AND idCotizacion = '".$idProforma."' ";
		    $query2 = mysql_query($sql2, $conn) or die(mysql_error());

		    

		    $sql2 = "UPDATE tproductos SET existencia = existencia-'$cantidad' WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
          	$query2 = mysql_query($sql2, $conn) or die(mysql_error());

          	$sqlpr="SELECT id, nombre,descripcion,excento,moneda,tipo,precioVenta,existencia  FROM tproductos WHERE codigo ='".$codigo."' AND sistema='".$cuentaid."'";
			$querypr=mysql_query($sqlpr,$conn)or die(mysql_error());
			while($rowpr=mysql_fetch_assoc($querypr)){
				 $idProducto=$rowpr['id'];
				 $nombreProducto=$rowpr['nombre'];
				 $descripcionProducto=$rowpr['descripcion'];
				 $excento=$rowpr['excento'];
				 $moneda=$rowpr['moneda'];
				 $tipo=$rowpr['tipo'];
				 $precioVenta=$rowpr['precioVenta'];
				 $existencia=$rowpr['existencia'];
				 
			}

			if ($descuento == "" or $descuento < 0){
		     $descuento = 0;
		    }
		     
		    if ($excento==0){
		     $gravado = 1; 
		    }else{
		     $gravado = 0;
		    } 


		    $sql2 = "INSERT INTO tproductosfactura VALUES ('$idProforma', '$idProducto', '$codigo', '$nombreProducto', '$descripcionProducto', '$gravado', '$moneda', '$descuento', '$precioVenta', '$cantidad', '$tipo','".$numero."')";
	        $query2 = mysql_query($sql2, $conn) or die(mysql_error());

	        ?>
			
			


			<td valign="middle">
            
          <center>
         
          <input type="hidden" id="id-<?php echo $cont;?>" name="id" value="<?php  echo $idProducto?>" />
          <input type="hidden" id="numero-<?php echo $cont;?>" name="id" value="<?php  echo $numero?>" />
          <input type="hidden" id="codigo2-<?php echo $cont;?>" name="id" value="<?php  echo $codigo?>" />

          <input type="text" id="1-<?php echo $cont;?>" onblur="validarCantidad2(<?php echo $cont;?>)" onkeyup='controlfocus("1",<?php echo $cont;?>,event)'  name="cantidad"  class="form-control size-1" value="<?php  echo $cantidad?>" onchange='guardar("1",<?php echo $cont;?>)' />
          </div>  
            </center>
          
            </td>


          <td valign="middle"><?php  
  			    $impuesto = $gravado;
            
            ?>
            <input type="text" class="form-control" placeholder="Código" name="codigoLinea" autocomplete="off" id="2-<?php echo $cont;?>" value="<?php echo $codigo?>" onkeyup='controlfocus("2",<?php echo $cont;?>,event)' onchange='guardar("2",<?php echo $cont;?>)' >
           
          </td>
         
          <td valign="middle">
      			
      			<?php  echo getSubString($nombreProducto,70); ?>
          </td>
          <td>
          <span <?php if($existencia<=0){echo "style='color:#AC0000;font-weight: 700'";}else{echo "style='color:#060606;font-weight: 700'";}?>><?php echo $existencia?></span>
          </td>
          <td>
            <?php if ($impuesto == 0){echo 'Exe';}else{echo '13%';} ?>
          </td>
          <td valign="middle">
      			<?php  
            if ($impuesto == 0){
              $precioIMP=$precioVenta;
            }else{
              $precioIMP=$precioVenta*0.13;
              $precioIMP=$precioVenta+$precioIMP;
            }
           ?>
              <input type="text" placeholder="Precio" required="required" id="3-<?php echo $cont;?>" name="precio" class="form-control" value="<?php echo number_format($precioIMP,2,',',' ');?>" onkeyup='controlfocus("3",<?php echo $cont;?>,event)' onchange='guardar("3",<?php echo $cont;?>)' >
             
          </td>

            <td width="75" valign="middle">
                <center>
                
                <input type="text" class="form-control" name="Descuento" autocomplete="off" id="4-<?php echo $cont;?>" value="<?php  echo $descuento?>" onkeyup='controlfocus("4",<?php echo $cont;?>,event)' onchange='guardar("4",<?php echo $cont;?>)'>
                
                </center>
            </td>
            <td valign="middle"><?php  
           
			  if ($impuesto == 0){
				  $totalCompra = $cantidad*$precioVenta;
		          $esteDescuento = $totalCompra * ($descuento/100);
		          $totalCompra = $totalCompra - $esteDescuento;
		          $descuentoTotal += $esteDescuento;
				  $totalExcento = $totalExcento + $totalCompra;
				  ?>
				  <input type="hidden" id="descuento-<?php echo $cont;?>" name="id" value="<?php  echo $esteDescuento?>" />
		          <input type="hidden" id="totalExcento-<?php echo $cont;?>" name="id" value="<?php  echo $totalCompra?>" />
		          <?php
			  }else{
				  $totalCompra = $cantidad*$precioVenta;
		          $esteDescuento = $totalCompra * ($descuento/100);
		          $totalCompra = $totalCompra - $esteDescuento;
		          $descuentoTotal += $esteDescuento;
				  $totalGravado = $totalGravado + $totalCompra;
				   ?>
				  <input type="hidden" id="descuento-<?php echo $cont;?>" name="id" value="<?php  echo $esteDescuento?>" />
		          <input type="hidden" id="totalGravado-<?php echo $cont;?>" name="id" value="<?php  echo $totalCompra?>" />
		          <?php				  
			  }
			   ?>
		          
		          <?php	
        		echo "¢ ";
             	if ($impuesto == 0){  
                  $totalIMP=$totalCompra;
                }else{
                  $totalIMP=$totalCompra*0.13;
                  $totalIMP=$totalCompra+$totalIMP;
                }
            echo '<span id="total-'.$cont.'">'.number_format($totalIMP,2,',',' ').'</span>';
            ?>

           
            </td>




	        <?php

	    }else{
	    	echo "4";
	    }

	}else{	
		echo "3";
	}

}

}

if ($f == 'PRECIOS'){
$totalExcento=$_POST['totalExcento'];
$totalGravado=$_POST['totalGravado'];
$idProforma=$_POST['idProforma'];
$descuentoTotal=$_POST['descuentoTotal'];
 $subTotal = $totalExcento + $totalGravado;
  /*$descuentoExcento = $totalExcento * ($descuento/100);
  $descuentoGravado = $totalGravado * ($descuento/100);
  $descuentoTotal = $descuentoExcento + $descuentoGravado;*/

  $impuestoTotal = ($totalGravado - $descuentoGravado)*0.13;
  if ($exonerado == 1){
	$impuestoTotal = 0;
  }
  $totalPagar = $subTotal + $impuestoTotal;

  $dec = explode(".",number_format($totalPagar,2,".","")); 
  ?>
  		<tr style="border-top: 2px solid #270000">
           <td colspan="5" rowspan="3" id="cargador" class="noborder" style="border-bottom: none;" valign="top">
  
           </td>
           <td width="150" colspan="2">Precio</td>
           <td width="150" style="border-right: 2px solid #270000"><strong>&cent <?php echo number_format($subTotal,2,',',' ');?></strong></td>
          </tr>
          <tr>
            <td width="150" colspan="2">Impuesto</td>
            <td width="150" style="border-right: 2px solid #270000"><strong>&cent; <?php echo number_format($impuestoTotal,2,',',' ')?></strong></td>
          </tr> 
          <tr style="border: 2px solid #270000;border-left: 0px">
            <td width="150" colspan="2">Total a pagar</td>
            <td width="150" class="" style="font-size: 18px"><strong>&cent; <?php echo number_format($totalPagar,2,',',' ');?></strong>
              <input type="hidden" name="totalPagar2" id="totalPagar2" value="<?php echo number_format($totalPagar,2,',',' ');?>">
            </td>
           
          </tr>
         <tr>
            <td colspan="8" class="noborder" style="border-bottom: none;" valign="top"><?php echo strtoupper(num2letras($dec[0], false, '.').' CON '.$dec[1].'/100')?> COLONES
            <br>
           <?php  if($descuentoTotal != 0){?>
              --- Descuento aplicado: <strong>&cent; <?php echo number_format($descuentoTotal,2);?></strong>
           <?php  } ?> 
           </td>
         </tr>
  

   <?php 

$sql = "UPDATE tfacturas SET 
monto = '$totalPagar' 
WHERE id = '$idProforma' AND sistema = '".$cuentaid."' ";
$query = mysql_query($sql, $conn)or die(mysql_error());
}


?>