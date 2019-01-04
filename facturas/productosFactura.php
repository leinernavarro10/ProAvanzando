<?php  include ("../conn/connpg.php");



decode_get2($_SERVER["REQUEST_URI"]); 
$error = 0;

$idProforma = $_GET['idProforma'];
$tipoCambio = $_GET['tipoCambio'];
$estado = $_GET['estado'];

$sql = "SELECT * FROM tfacturas WHERE id = '$idProforma' AND sistema = '".$cuentaid."' ";
$query = mysql_query($sql, $conn) or die(mysql_error());
while($row=mysql_fetch_assoc($query)){
	$descuento = $row['descuento'];
  $exonerado = $row['exonerar'];
}

if (isset($_GET['codigo'])){
  $PRNO=1;
  $codigo = $_GET['codigo'];

  $cantidad = $_GET['cantidad'];

  if (!validarCantidad($codigo, $cantidad, $conn)){
     $myError = 1;
      $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      while ($row2=mysql_fetch_assoc($query2)) {
        $existencia = $row2['existencia'];
      }
      $mensaje = 'Solo hay una existencia de <strong>'.$existencia.'</strong> en el inventario';
  }else{
    if ($cantidad < 0){
      $myError = 1;
      $mensaje = 'La cantidad no puede ser un número negativo.';
    }else{
      $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' AND codigo = '$codigo' ";
      $query = mysql_query($sql, $conn);
      if (mysql_num_rows($query) == 0){
        $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        if (mysql_num_rows($query2) == 0){
          $errorExistencia=1;
          $myError = 1;
        	$mensaje = 'El producto con el código <strong>'.$codigo.'</strong> no se encuentra';
        }else{
      	  while ($row2=mysql_fetch_assoc($query2)) {
          $mostrar = 1;
      		$idProducto = $row2['id'];
      		$nombreProducto = $row2['nombre'];
      		$codigo = $row2['codigo'];
      		$precio = $row2['precioVenta']; 
      		$moneda = $row2['moneda']; 
      		$transporte = $row2['transporte']; 
          $excento = $row2['excento']; 
          $tipo = $row2['tipo']; 
          if ($transporte != 0 or $transporte != ""){
            $precio = $precio + $transporte;
          }
          if ($excento == 1){
            $impuesto = 0;
          }else{
            $impuesto = 1;
          }
      		$descripcionProducto = $row2['descripcion'];
      		$existencia = $row2['cantidad'];
      	  }
        }
      }else{
        while ($row=mysql_fetch_assoc($query)) {
          $cantidadOld = $row['cantidad'];
        }
        $sql2 = "UPDATE tproductos SET existencia  = existencia  - '$cantidad' WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        if($_GET['codigo2']==""){
          $cantidad = $cantidad + $cantidadOld;
        }else{  
          $cantidad = $cantidad;
        }
        $sql2 = "UPDATE tproductosfactura SET cantidad = '$cantidad' WHERE idCotizacion = '$idProforma' AND codigo = '$codigo'";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());

        $PRNO=0;
      }
    }
  }
}

if (isset($_GET['del'])){
  $idProducto = $_GET['del'];

  $sql2 = "SELECT * FROM tproductosfactura WHERE idProducto = '$idProducto' AND idCotizacion = '$idProforma'";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());
  while($row2=mysql_fetch_assoc($query2)){
    $cantidad = $row2['cantidad'];
  }

  $sql2 = "UPDATE tproductos SET existencia  = existencia  + '$cantidad' WHERE codigo = '$idProducto' AND sistema = '".$cuentaid."' ";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());

  $sql2 = "DELETE FROM tproductosfactura WHERE idProducto = '$idProducto' AND idCotizacion = '$idProforma'";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());
}


if (isset($_GET['agregarLinea']) AND $PRNO!=0 AND $idProducto!=""){

  $idProducto = $idProducto;
  $codigo=$_GET['codigo'];
  $cantidad = $_GET['cantidad'];
  $tipo = $tipo;
  $descuento = '0';
  $nombreProducto = $nombreProducto;
  $descripcionProducto = $descripcionProducto;
  $moneda = 1;
//echo $cantidad;
  if (!validarCantidad($idProducto, $cantidad, $conn)){
     $myError = 1;
      $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      while ($row2=mysql_fetch_assoc($query2)) {
        $existencia = $row2['existencia'];
      }
      $mensaje = 'Solo hay una existencia de <strong>'.$existencia.'</strong> en el inventario';
  }else{
    
    if ($descuento == "" or $descuento < 0){
      $descuento = 0;
    }
    //echo $excento;
  	if ($excento==0){
  		$gravado = 1; 
  	}else{
  		$gravado = 0;
  	}	
  	$precio =  $precio;
  	
    if ($cantidad <= 0){
      $myError = 1;
      $mensaje = 'La cantidad no puede ser un numero negativo';
      $cantidad = 1;
    }
    if ($idProducto == ""){$idProducto = 'N'.rand(100,999);}
  	

    $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' AND idProducto = '$idProducto' ";
    $query = mysql_query($sql, $conn);
    if (mysql_num_rows($query) == 0){

      $sql2 = "SELECT * FROM tproductos WHERE codigo = '$idProducto' AND sistema = '".$cuentaid."'";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      if (mysql_num_rows($query2) != 0){
        while ($row2=mysql_fetch_assoc($query2)) {
          $cantidadOld = $row2['existencia'];
        }
        $cantidadNueva = $cantidadOld - $cantidad;
        $sql2 = "UPDATE tproductos SET existencia = '$cantidadNueva' WHERE codigo = '$idProducto' AND sistema = '".$cuentaid."'";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        }
        $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' ORDER BY numero ASC ";
        $query = mysql_query($sql, $conn);
        $numero=0;
        while($rowN=mysql_fetch_assoc($query)){
          $numero=$rowN['numero'];
        }
        $numero++;
        $sql2 = "INSERT INTO tproductosfactura VALUES ('$idProforma', '$idProducto', '$codigo', '$nombreProducto', '$descripcionProducto', '$gravado', '$moneda', '$descuento', '$precio', '$cantidad', '$tipo','".$numero."')";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      $_GET['edit']=$idProducto;
    }else{
      $myError = 1;
      $mensaje = 'Ya existe una línea con ese código.';
    }
    
  }
}


if (isset($_GET['agregarLineaNuevo'])){


   $codigo = $_GET['codigoLinea'];
  $cantidad = $_GET['cantidad'];
  $tipo = $_GET['tipo'];
  $descuento = $_GET['descuento'];
  $nombreProducto = $_GET['descripcion'];
  $descripcionProducto = '';
  $moneda = 1;

  if (!validarCantidad($idProducto, $cantidad, $conn)){
     $myError = 1;
      $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      while ($row2=mysql_fetch_assoc($query2)) {
        $existencia = $row2['existencia'];
      }
      $mensaje = 'Solo hay una existencia de <strong>'.$existencia.'</strong> en el inventario';
  }else{
    
    if ($descuento == "" or $descuento < 0){
      $descuento = 0;
    }

  if (isset($_GET['gravado'])){
    $gravado = 1; 
  }else{
    $gravado = 0;
  } 
  $precio = $_GET['precio'];
    
    if ($cantidad <= 0){
      $myError = 1;
      $mensaje = 'La cantidad no puede ser un numero negativo';
      $cantidad = 1;
    }
    if ($idProducto == ""){$idProducto = rand(100,999);}
    

    $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' AND idProducto = '$idProducto'";
    $query = mysql_query($sql, $conn);
    if (mysql_num_rows($query) == 0){

      $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."'";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      if (mysql_num_rows($query2) != 0){
        while ($row2=mysql_fetch_assoc($query2)) {
          $cantidadOld = $row2['existencia'];
        }
        $cantidadNueva = $cantidadOld - $cantidad;
        $sql2 = "UPDATE tproductos SET existencia = '$cantidadNueva' WHERE codigo = '$idProducto' AND sistema = '".$cuentaid."'";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        }
        
        $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' ORDER BY numero ASC ";
        $query = mysql_query($sql, $conn);
        $numero=0;
        while($rowN=mysql_fetch_assoc($query)){
          $numero=$rowN['numero'];
        }
        $numero++;
        $sql2 = "INSERT INTO tproductosfactura VALUES ('$idProforma', '$idProducto', '$codigo', '$nombreProducto', '$descripcionProducto', '$gravado', '$moneda', '$descuento', '$precio', '$cantidad', '$tipo','$numero')";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      $_GET['edit']=$idProducto;
    }else{
      $myError = 1;
      $mensaje = 'Ya existe una línea con ese código.';
    }
    
  }
}

if (isset($_GET['editarLinea']) AND $PRNO!=0 ){
  
  if($errorExistencia!="1"){
    $codigo = $_GET['codigo'];
    $codigo2 = $_GET['codigo2'];
    $cantidad = $_GET['cantidad'];
    $descuento = $_GET['descuento'];
    $tipo = $_GET['tipo'];
    
    $moneda = 1;

    $sql2 = "SELECT * FROM tproductosfactura WHERE idProducto = '$idProducto' AND idCotizacion = '$idProforma'";
    $query2 = mysql_query($sql2, $conn) or die(mysql_error());
    while($row2=mysql_fetch_assoc($query2)){
      $menosCantidad = $row2['cantidad'];
    }
    if (!validarCantidadEditar($idProducto, $cantidad, $menosCantidad, $conn)){
        $myError = 1;

        $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."'";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        while ($row2=mysql_fetch_assoc($query2)) {
          $existencia = $row2['existencia'];
        }
        $existencia = $existencia + $menosCantidad;
        $mensaje = 'Solo hay una existencia de <strong>'.$existencia.'</strong> en el inventario';
    }else{
    
      $sql2 = "SELECT * FROM tproductosfactura WHERE codigo = '$codigo2' AND idCotizacion = '$idProforma'";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());
      while($row2=mysql_fetch_assoc($query2)){
        $cantidadNueva = $row2['cantidad'];
      }

      $sql2 = "UPDATE tproductos SET existencia  = existencia  + '$cantidadNueva' WHERE codigo = '$codigo2' AND sistema = '".$cuentaid."' ";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());

      $sql2 = "DELETE FROM tproductosfactura WHERE codigo = '$codigo2' AND idCotizacion = '$idProforma'";
      $query2 = mysql_query($sql2, $conn) or die(mysql_error());

     if ($descuento == "" or $descuento < 0){
        $descuento = 0;
      }
      echo $descuento;
      if ($excento==0){
        $gravado = 1; 
      }else{
        $gravado = 0;
      } 
      $precio =  $precio;
      
      if ($cantidad <= 0){
        $myError = 1;
        $mensaje = 'La cantidad no puede ser un numero negativo';
        $cantidad = 1;
      }
      if ($idProducto == ""){$idProducto = 'N'.rand(100,999);}
      
      $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' AND codigo = '$codigo'";
      $query = mysql_query($sql, $conn);
      if (mysql_num_rows($query) == 0){

        $sql2 = "SELECT * FROM tproductos WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        if (mysql_num_rows($query2) != 0){
          while ($row2=mysql_fetch_assoc($query2)) {
            $cantidadOld = $row2['existencia'];
          }
          $cantidadNueva = $cantidadOld - $cantidad;
          $sql2 = "UPDATE tproductos SET existencia = '$cantidadNueva' WHERE codigo = '$codigo' AND sistema = '".$cuentaid."' ";
          $query2 = mysql_query($sql2, $conn) or die(mysql_error());
        }

        $numero=$_GET['numero'];

        $sql2 = "INSERT INTO tproductosfactura VALUES ('$idProforma', '$idProducto', '$codigo', '$nombreProducto', '$descripcionProducto', '$gravado', '$moneda', '$descuento', '$precio', '$cantidad', '$tipo','".$numero."')";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());

      }else{
        $myError = 1;
        $mensaje = 'Ya existe una línea con ese código.';
      }
      
    }
  }
}


function validarCantidad($idProducto, $cantidad, $conn){
  $sql = "SELECT * FROM tproductos WHERE codigo = '$idProducto' AND sistema = '".$cuentaid."'";
  $query = mysql_query($sql, $conn);
  if (mysql_num_rows($query) == 0){
    return true;
  }else{
    while ($row=mysql_fetch_assoc($query)) {
      $cantidadOld = $row['existencia'];
      if ($cantidad > $cantidadOld){
        return false;
      }else{
        return true;
      }
    }
  }
}

function validarCantidadEditar($idProducto, $cantidad, $menosCantidad, $conn){
  $sql = "SELECT * FROM tproductos WHERE codigo = '$idProducto' AND sistema = '".$cuentaid."'";
  $query = mysql_query($sql, $conn);
  if (mysql_num_rows($query) == 0){
    return true;
  }else{
    while ($row=mysql_fetch_assoc($query)) {
      $cantidadOld = $row['existencia'] + $menosCantidad;
      if ($cantidad > $cantidadOld){
        return false;
      }else{
        return true;
      }
    }
  }
}
?>

<?php  include("../includes/headerLimpio.php");?>       
<?php  include("../includes/alerts.php");?>       

    <link rel="stylesheet" href="style.css">


<div id="escaner"></div>


   </center>
  <?php  
	$sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' ORDER BY numero DESC";
  $query = mysql_query($sql, $conn)or die(mysql_error());
  $queryPeque = mysql_query($sql, $conn)or die(mysql_error());
	$numProductos = mysql_num_rows($query);
	if ($estado == 1){
		if ($numProductos > 350){?>
			<center>
            	<i class="entypo-info-circled" style="font-size:18px"></i><br />Solo se pueden agregar 150 productos por factura, por una cuestion de espacio en la impresión. Si desea facturar más productos, por favor cree una nueva factura.
            </center>
		<?php  }else{ ?>

<div class="panel panel-dark" data-collapsed="0">
          
          <!-- panel head -->
          <div class="panel-heading" style="background: #044701;color: #FFFFFF;">
            <div class="panel-title">Datos Factura</div>
            
            <div class="panel-options">
              <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
               <a href="productosFactura.php?nuevaLinea=1&idProforma=<?php echo $idProforma?>&tipoCambio=<?php echo $tipoCambio?>&estado=<?php echo $estado?>" title="Nueva línea (Productos no registrados)"><i class="entypo-list-add"></i></a>
            </div>
          </div>
          
          <!-- panel body -->
          <div class="panel-body">
<!--
   <?php  
    //PREVIEW DE PRODUCTO
    
    if (isset($_GET['edit'])){ 
      $edit = $_GET['edit'];
      $sql2 = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idProforma' AND idProducto = '$edit'";
      $query2 = mysql_query($sql2, $conn);
      while ($row2=mysql_fetch_assoc($query2)) {
        $cantidad = $row2['cantidad'];
        $idProducto = $row2['idProducto'];
        $codigo = $row2['codigo'];
        $nombreProducto = $row2['nombre'];
        $impuesto = $row2['impuesto'];
        $descuento = $row2['descuento'];
        $precio = $row2['precio'];
        $tipo = $row2['tipo'];
        $impuesto = $row2['impuesto'];
      }

      ?>
    
        <form action="" method="get" autocomplete="off">

        <input type="hidden" name="idProforma" value="<?php echo $idProforma?>"/>
        <input type="hidden" name="tipoCambio" value="<?php echo $tipoCambio?>"/>
        <input type="hidden" name="estado" value="<?php echo $estado?>"/>

        <style type="text/css">
          .nuevaLinea td{
            padding-right: 5px;
            padding-left: 5px;
            text-align: center;
          }
        </style>
        
              <div class="input-spinner cabeN1">
                <button type="button" class="btn btn-default">-</button>
                <input type="text" id="cantidad21" onblur="validarCantidad2()" onkeydown="PulsarTecla('1')" name="cantidad"  class="form-control size-1" value="<?php echo $cantidad?>" />
                <button type="button" class="btn btn-default">+</button>
              </div>
            
            <div class="cabeN2"><input type="hidden" class="form-control" name="idProducto"   value="<?php echo $idProducto?>">

                <input type="text" class="form-control" placeholder="Código" name="codigoLinea" autocomplete="off" id="codigoLinea" readonly value="<?php echo $codigo?>">
            </div>
            <div class="cabeN3">
              <input type="text" class="form-control" placeholder="Descripción (Puede dividir el producto en líneas utilizando - )" required="required" readonly name="descripcion" autocomplete="off" id="descripcion" value="<?php echo $nombreProducto?>">          
           </div>
            <div class="cabeN4">
              <div class="checkbox checkbox-replace color-primary">
                <input type="checkbox" <?php echo $disabled?> id="gravado" <?php  if ($impuesto == 1){echo 'checked="checked"';}?> name="gravado" value="1">
                <label> Imp</label>
              </div>
            
              <div class="checkbox checkbox-replace color-primary">
                <input type="checkbox" <?php echo $disabled?> id="tipo" <?php  if ($tipo == 1){echo 'checked="checked"';}?> name="tipo" value="1" >
                <label> Ser</label>
              </div>
            </div>
          <div class="cabeN5">
                <input type="number" min="0" step="any" class="form-control" placeholder="Desc" name="descuento" autocomplete="off" id="Descuento">
          </div>
          <div class="cabeN5">
                <input type="text" placeholder="Precio" required="required" id="precio" name="precio" class="form-control" readonly value="<?php echo $precio?>" readonly>
          </div>      
          <div class="cabeN6">   
                  <button class="btn btn-primary" type="button" onclick="menosImp()">-13%</button>
                  <button class="btn btn-primary" name="editarLinea" id="editarLinea" type="submit">Modificar</button>
          </div>
             

      </form>
   <?php  } ?>

-->
    <?php  if (!isset($_GET['nuevaLinea'])){
	    if ($numProductos < 35){?>
    <center>
      <div id="totalGR" class="cabe4">   
      </div>
    </center>
       
    <?php  }
	}else{?>

		<form action="" method="get" autocomplete="off">

    <input type="hidden" name="idProforma" value="<?php echo $idProforma?>"/>
    <input type="hidden" name="tipoCambio" value="<?php echo $tipoCambio?>"/>
    <input type="hidden" name="estado" value="<?php echo $estado?>"/>

  
      <table width="100%" style="font-weight: 600;" class="table">
        <tr>
          <th colspan="2"> <center>Nuevo producto</center></th>
        </tr>
        <tr>
          <td>Cantidad: </td>
          <td> 
            <div class="input-spinner cabeN1">
              <button type="button" class="btn btn-default">-</button>
              <input type="text" id="cantidad2" onblur="validarCantidad2()" name="cantidad"  class="form-control size-1" value="1" />
              <button type="button" class="btn btn-default">+</button>
            </div>
          </td>
        </tr>
        <tr>
          <td>Código: </td>
          <td><input type="text" class="form-control" placeholder="Código" name="codigoLinea" autocomplete="off" id="codigoLinea" required></td>
        </tr>

        <tr>
          <td>Dscripción: </td>
          <td><input type="text" class="form-control" placeholder="Descripción" required="required" name="descripcion" autocomplete="off" id="descripcion">  </td>
        </tr>

        <tr>
          <td></td>
          <td> 
            <div class="checkbox checkbox-replace color-primary" style="display: inline-block;">
              <input type="checkbox" <?php echo $disabled?> id="gravado" <?php echo $check?> name="gravado" value="1" checked>
              <label> Impuesto</label>
            </div> 
         
            <div class="checkbox checkbox-replace color-primary"  style="display: inline-block;">
              <input type="checkbox" <?php echo $disabled?> id="tipo" name="tipo" value="1" >
              <label> Servicio</label>
            </div>
          </td>
        </tr>
        <tr>
          <td>Descuento: </td>
          <td><input type="number" min="0" step="any" class="form-control" placeholder="Desc" name="descuento" autocomplete="off" id="Descuento"></td>
        </tr>
        <tr>
          <td>Precio: </td>
          <td>
            <div class="input-group">
              <input type="text" placeholder="Precio" required="required" id="precio" name="precio" class="form-control"><span class="input-group-btn"><button class="btn btn-primary" type="button" onclick="menosImp()">-13%</button></span>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
          

              <button class="btn btn-primary btn-block" name="agregarLineaNuevo" id="agregarLinea" type="submit">Agregar Nueva Linea</button>
          </td>
        </tr>
      </table>
    
        

  </form>
	<?php  } ?>
    
<?php 
	}
	}
	 
 ?>
   
























      <table width="100%" class="table table-bordered responsive pequeno2" >
 <?php  if ($estado == 1){?>
   <form action="" method="get" autocomplete="off">
        <input type="hidden" name="idProforma" value="<?php echo $idProforma?>"/>
        <input type="hidden" name="tipoCambio" value="<?php echo $tipoCambio?>"/>
        <input type="hidden" name="estado" value="<?php echo $estado?>"/>
      <tr style="background: #84BB5F">
        <td>
        
            <input type="number" name="cantidad" id="cantidad" min="0" onblur="validarCantidad()" class="form-control size-1" value="1" />
           
        </td>
        <td colspan="3">
          <div class="input-group">
           <input type="number" placeholder="Digite el id o código de producto." required="required" id="codigo" name="codigo" class="form-control ">
        <span class="input-group-btn"><button onclick="parent.mostrarBuscarProducto()" <?php echo $disabled?> class="btn btn-primary" type="button" id="verpr"><i class="entypo-search"></i> <i class="esconder2">(Shift)</i></button><button class="btn btn-primary" name="agregarLinea" id="agregar2" type="submit">Agregar</button></span>
          </div>
        </td>
      </tr> 
      </form>
 <?php  }?>
     
      <tr style="background: #DADADA;font-weight: 600">
        <td  width="125" style="">Cantidad</td>
        <td >Precio</td>
        <td  width="75">Descuento <?php  if ($estado == 1){?><a href="javascript: void(0)" onclick="aplicarTodos()">TODOS</a><?php  } ?></td>
        <td  width="100">Subtotal</td>
  


      <?php  
      $totalGravado = 0;
      $totalExcento = 0;
      $descuentoTotal = 0;
      $cont=0;
     
        while ($row=mysql_fetch_assoc($queryPeque)) {
        $cont++; 
          $descuentoLinea = $row['descuento'];

          //AQUI EMPEZAMOS
         
           
             
                $lineas++;
              ?>
          <tr>
            <td>
              <?php  if ($estado == 1){?>
        <center>
          <div class="input-group">
           
              <a href="productosFactura.php?del=<?php echo $row['idProducto']?>&tipoCambio=<?php echo $tipoCambio?>&idProforma=<?php echo $idProforma?>&estado=<?php echo $estado?>" class="btn btn-danger" type="button"><i class="entypo-trash"></i></a><br>
              <!---
              <a href="productosFactura.php?edit=<?php echo $row['idProducto']?>&tipoCambio=<?php echo $tipoCambio?>&idProforma=<?php echo $idProforma?>&estado=<?php echo $estado?>" class="btn btn-default" id="ah<?php echo $cont;?>"><i class="entypo-pencil"></i></a>
           -->
          </div>
        </center>
            <?php  }?>
            </td>
            <td colspan="3" valign="middle">
                 <?php $impuesto = $row['impuesto'];  if ($impuesto == 0){echo '* ';}?> 
      <?php  echo ''.getSubString($row['nombre'],70).''; ?><br>
      (
              <?php  
                
                echo $codigo = $row['idProducto'];
              ?>
                )
            </td>
          </tr>

          <tr  style='border-bottom: 2px dotted #530202' >
            <td valign="middle" >
              <center>
                <strong>
              <?php  echo $row['cantidad']?>
              </strong>
              </center>
            </td>

            
            <td valign="middle">
      <?php  
        if ($impuesto == 0){
                  
                  $precioIMP=$row['precio'];
                }else{
                  $precioIMP=$row['precio']*0.13;
                  $precioIMP=$row['precio']+$precioIMP;
                }
            if ($row['moneda'] == 1){
              echo "¢ ";
        echo number_format($precioIMP,2,',',' ');
        ?><input type="hidden" value="<?php echo $row['precio']?>" id="precio_<?php echo $row['idProducto']?>" /><?php 
            }else{
              echo "¢ ";
        echo number_format($precioIMP*$tipoCambio,2);
        ?><input type="hidden" value="<?php echo $row['precio']*$tipoCambio?>" id="precio_<?php echo $row['idProducto']?>" /><?php 
            }
            ?></td>
            <td width="75" valign="middle">

                <center>

                 <?php  if ($estado == 1){?>
                <input type="number" min="0" step="any" class="form-control descuento" name="Descuento" onkeypress="enter(event);" onblur="cambiarDescuento(this)" autocomplete="off" id="descuento_<?php echo $row['idProducto']?>" value="<?php  echo $descuentoLinea?>">
                 <?php  }else{ 
                  echo $descuentoLinea.' %';
                  }?>
                </center>
            </td>
            <td valign="middle"><?php  
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
            echo '<span id="total_'.$row['idProducto'].'">'.number_format($totalIMP,2,',',' ').'</span>';
            //echo number_format($totalIMP,2);
            ?></td>
             

            </tr>   
  <?php  
        
      
    
  
}

?>

 <input type="hidden" name="" id="cantRG" value="<?php echo $cont;?>"> 

  <?php $subTotal = $totalExcento + $totalGravado;
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
           <td colspan="1" rowspan="3" class="noborder" style="border-bottom: none;" valign="top">

           </td>
           <td width="150">Precio</td>
           <td width="150" colspan="2" style="border-right: 2px solid #270000"><strong>&cent <?php echo number_format($subTotal,2,',',' ');?></strong></td>
          </tr>
          <tr>
            <td width="150">Impuesto</td>
            <td width="150" colspan="2" style="border-right: 2px solid #270000"><strong>&cent; <?php echo number_format($impuestoTotal,2,',',' ')?></strong></td>
          </tr> 
          <tr style="border-bottom: 2px solid #270000">
            <td width="150">Total a pagar</td>
            <td width="150" colspan="2" style="border-right: 2px solid #270000;color: #760000"><strong>&cent; <?php echo number_format($totalPagar,2,',',' ');?></strong>
            <input type="hidden" name="totalPagar" id="totalPagar" value="<?php echo number_format($totalPagar,2,',',' ');?>">

            </td>
           
          </tr>
          <tr style="border-bottom: 2px solid #270000">
            <td colspan="4" style="border-right: 2px solid #270000"><?php echo strtoupper(num2letras($dec[0], false, '.').' CON '.$dec[1].'/100')?> COLONES
            <br>
           <?php  if($descuentoTotal != 0){?>
              --- Descuento aplicado: <strong>&cent; <?php echo number_format($descuentoTotal,2);?></strong>
           <?php  } ?> 

             </td>
          </tr>
         
    </table> 

































<!---- Grande   --------->
  
 


    <table width="100%" class="table table-bordered responsive grande">
        <?php  if ($estado == 1){?>

      <form action="" method="get" autocomplete="off">
        <input type="hidden" name="idProforma" value="<?php echo $idProforma?>"/>
        <input type="hidden" name="tipoCambio" value="<?php echo $tipoCambio?>"/>
        <input type="hidden" name="estado" value="<?php echo $estado?>"/>
      <tr style="background: #84BB5F">
        <td colspan="2">
           <input type="text" name="cantidad" id="cantidad2" min="1" onblur="validarCantidad()" onkeyup="verproductos(event)" class="form-control size-1" value="1" />
        </td>
        <td colspan="6">
          <div class="input-group">

           <input type="text" placeholder="Digite el id o código de producto.(F2)" onkeyup="verproductos(event)" required="required" id="codigo2" name="codigo" class="form-control ">
        <span class="input-group-btn"><button onclick="parent.mostrarBuscarProducto()" <?php echo $disabled?> class="btn btn-primary" type="button" id="verpr"><i class="entypo-search"></i> <i class="esconder2">(Shift)</i></button><button class="btn btn-primary" name="agregarLinea" id="agregar" type="submit">Agregar</button></span>
          </div>
        </td>
      </tr> 
      </form>
<?php }?>

     
      <tr style="background: #DADADA;font-weight: 600">
        <td width="75" style="text-align:center">Cantidad</td>
        <td width="100">Código</td>
        <td width="500">Producto</td>
        <td width="20">Exist</td>
        <td width="20">I.V</td>
        <td width="120">Precio</td>
        <td  width="90">Descuento <?php  if ($estado == 1){?><a href="javascript: void(0)" onclick="aplicarTodos()">TODOS</a><?php  } ?></td>
        <td  width="120">Subtotal</td>
        
      </tr>
      


      <?php  
	  	$totalGravado = 0;
		  $totalExcento = 0;
      $descuentoTotal = 0;
      $cont=0;
        while ($row=mysql_fetch_assoc($query)) {
        $cont++; 
          $descuentoLinea = $row['descuento'];

                $lineas++;
              ?>

          <tr id="tr<?php echo $cont;?>">
          
            <td valign="middle">
            <?php  if ($estado == 1){?>
          
          <input type="hidden" id="id-<?php echo $cont;?>" name="id" value="<?php  echo $row['idProducto']?>" />
          <input type="hidden" id="numero-<?php echo $cont;?>" name="id" value="<?php  echo $row['numero']?>" />
          <input type="hidden" id="codigo2-<?php echo $cont;?>" name="id" value="<?php  echo $row['codigo']?>" />

          <input type="text" id="1-<?php echo $cont;?>" onblur="validarCantidad2(<?php echo $cont;?>)" onkeyup='controlfocus("1",<?php echo $cont;?>,event)'  name="cantidad"  class="form-control size-1" value="<?php  echo $row['cantidad']?>" onchange='guardar("1",<?php echo $cont;?>)' />
          </div>  
            </center>
            <?php  }else{
			echo $row['cantidad'];
			} ?>
            </td>


          <td valign="middle"><?php  
  			    $impuesto = $row['impuesto'];
            $codigo = $row['codigo'];
            if ($estado == 1){?>
            <input type="text" class="form-control" placeholder="Código" name="codigoLinea" autocomplete="off" id="2-<?php echo $cont;?>" value="<?php echo $codigo?>" onkeyup='controlfocus("2",<?php echo $cont;?>,event)' onchange='guardar("2",<?php echo $cont;?>)' >
            <?php  }else{
              echo $codigo;
            }
                
            ?>
          </td>
         
          <td valign="middle">
      			
      			<?php  echo getSubString($row['nombre'],70); ?>
          </td>
          <td>
              <span <?php 
              $sqlpr="SELECT existencia  FROM tproductos WHERE codigo ='".$codigo."' AND sistema='".$cuentaid."'";
               $querypr=mysql_query($sqlpr,$conn)or die(mysql_error());
              while($rowpr=mysql_fetch_assoc($querypr)){
                $existencia=$rowpr['existencia'];
              }
                if($existencia<=0){echo "style='color:#AC0000;font-weight: 700'";}else{echo "style='color:#060606;font-weight: 700'";}?>>
                <?php echo $existencia?>
                
              </span>
          </td>
          <td>
            <?php if ($impuesto == 0){echo 'Exe';}else{echo '13%';} ?>
          </td>
          <td valign="middle">
      			<?php  
            if ($impuesto == 0){
              $precioIMP=$row['precio'];
            }else{
              $precioIMP=$row['precio']*0.13;
              $precioIMP=$row['precio']+$precioIMP;
            }
            if ($estado == 1){?> 
              <input type="text" placeholder="Precio" required="required" id="3-<?php echo $cont;?>" name="precio" class="form-control" value="<?php echo number_format($precioIMP,2,',',' ');?>" onkeyup='controlfocus("3",<?php echo $cont;?>,event)' onchange='guardar("3",<?php echo $cont;?>)' >
              <?php
            }else{
              echo "¢ ";
  			      echo number_format($precioIMP,2,',',' ');?>
             <?php 
            }
            ?>
          </td>

            <td width="75" valign="middle">
                <center>
                 <?php  if ($estado == 1){?>
                <input type="text" class="form-control" name="Descuento" autocomplete="off" id="4-<?php echo $cont;?>" value="<?php  echo $descuentoLinea?>" onkeyup='controlfocus("4",<?php echo $cont;?>,event)' onchange='guardar("4",<?php echo $cont;?>)'>
                 <?php  }else{ 
                  echo $descuentoLinea.' %';
                  }?>
                </center>
            </td>
            <td valign="middle"><?php  
           
			  if ($impuesto == 0){
				  $totalCompra = $row['cantidad']*$row['precio'];
          $esteDescuento = $totalCompra * ($descuentoLinea/100);
          $totalCompra = $totalCompra - $esteDescuento;
          $descuentoTotal += $esteDescuento;
				  $totalExcento = $totalExcento + $totalCompra;
          ?>
          <input type="hidden" id="descuento-<?php echo $cont;?>" name="id" value="<?php  echo $esteDescuento?>" />
          <input type="hidden" id="totalExcento-<?php echo $cont;?>" name="id" value="<?php  echo $totalCompra?>" />
          <?php
			  }else{
				  $totalCompra = $row['cantidad']*$row['precio'];
          $esteDescuento = $totalCompra * ($descuentoLinea/100);
          $totalCompra = $totalCompra - $esteDescuento;
          $descuentoTotal += $esteDescuento;
				  $totalGravado = $totalGravado + $totalCompra;		
          ?>
          <input type="hidden" id="descuento-<?php echo $cont;?>" name="id" value="<?php  echo $esteDescuento?>" />
          <input type="hidden" id="totalGravado-<?php echo $cont;?>" name="id" value="<?php  echo $totalCompra?>" />
          <?php	  
			  }
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
         

            </tr>   
  <?php  
   
}


//----------- editar

?>

 <input type="hidden" name="" id="cantRG" value="<?php echo $cont;?>"> 

 
  <tfoot id="trprecio">
    <tr><td colspan="8">Esperando datos</td></tr>
  </tfoot>
  		
    </table>    

   <?php 


    
if ($myError == 1){
  echo '<script type="text/javascript">miAlerta("'.$mensaje.'",1);</script>';
}
?>

<br>
<script type="text/javascript">




var recnum=0; 

 function verproductos(event)
{
  
    var tecla =event.which || event.keyCode;

    
    if (event.ctrlKey && event.keyCode === 32) {
      parent.$('#btnFinalizar').click();
    }else if(tecla==109){
        var id=$("#id-1").val();
         document.location.href="productosFactura.php?del="+id+"&tipoCambio=<?php echo $tipoCambio?>&idProforma=<?php echo $idProforma?>&estado=<?php echo $estado?>";   
      }else if(tecla==113){
        $("#codigo2").focus().select();
      }else if(tecla==16){
        val=$("#codigo2").val();
        $("#codigo2").focus().select();
         parent.mostrarBuscarProducto();
      }else if(tecla==39){
         $("#codigo2").focus().select();
      }else if(tecla==40){
        cambiar(1,1)
      }else  if(tecla==37){
        $("#cantidad2").focus().select();
      }else if(tecla==115){
      parent.$("#cliente").focus().select();
    }else{
        val=$("#codigo2").val();
        if(isNaN(val)){
          parent.mostrarBuscarProducto(val);  
        }
      }
   
    
}



function controlfocus(id,num,event){
   var tecla =event.which || event.keyCode;
  //alert(tecla);
    if (event.ctrlKey && event.keyCode === 32) {
      parent.$('#btnFinalizar').click();
    }else if(tecla==13 || tecla == 39){

    if(id==4){
     
      controlfocus(0,num+1,event);

      if(num==$("#cantRG").val()) {
        parent.$("#cliente").focus().select();
      }
    }else{
      id=parseInt(id)+1;
      $("#"+id+"-"+num).focus().select();
     
    }
  }else if(tecla==37){
      if(id==1){
      controlfocus(5,num-1,event)
    }else{
      id=parseInt(id)-1;
      $("#"+id+"-"+num).focus().select();
     
    }
  }else if(tecla==40){
    //Bajar
    
    cambiar(id,num+1);
  }else if(tecla==38){
    //Subir
     if((num-1)<=0){
         $("#cantidad2").focus().select();
     }else{
         cambiar(id,num-1);
     }
   
  }else if(tecla==109){
    //borrar{}
    var id=$("#id-"+num).val();
    //alert(id);
    //document.location.href="productosFactura.php?del="+id+"&tipoCambio=<?php echo $tipoCambio?>&idProforma=<?php echo $idProforma?>&estado=<?php echo $estado?>";    
   guardar(id,num);
  }else if(tecla==113){
    $("#codigo2").focus().select();
  }else if(tecla==115){
    parent.$("#cliente").focus().select();
  }

}



function cambiar(id,num){
  if(num<= $("#cantRG").val()){
    $("#"+id+"-"+num).focus().select();
  }else{
    parent.$("#cliente").focus().select();
  }
}


function cargarOnload(){
  var ventana_ancho = $(window).width();

    if(ventana_ancho>=768){
     $('#cantidad2').select().focus();
    }
    precios();

    //$("#totalGR").html("Total de la factura IVI:<br>&cent; <?php echo number_format($totalPagar,2);?>");


}


function precios(){
  var totalExcento=0;
  var totalGravado=0;
  var descuentoTotal=0;
  var idProforma='<?php echo $idProforma?>';
  
  var cantRG=$("#cantRG").val();
  for(var e=1;e<=cantRG;e++){
    //alert($("#totalExcento-"+e).val()+"\n"+$("#totalGravado-"+e).val());
      if($("#totalExcento-"+e).val()>=0){
        totalExcento+=parseFloat($("#totalExcento-"+e).val());
      }
      if($("#totalGravado-"+e).val()>=0){
        totalGravado+=parseFloat($("#totalGravado-"+e).val());
      }
      if($("#descuento-"+e).val()>=0){
        descuentoTotal+=parseFloat($("#descuento-"+e).val());
      }
  }

  $.post( "funcionesAJAXgrid.php", { f: 'PRECIOS',totalGravado:totalGravado,totalExcento:totalExcento,idProforma:idProforma,descuentoTotal:descuentoTotal})
      .done(function(data) {
        $("#trprecio").html(data);
        $("#totalGR").html("Total de la factura IVI:<br>&cent; "+$("#totalPagar").val());
        parent.resizeIframe(parent.document.getElementById("productosProforma"));
      });
}

function guardar(idTxt,num){
  
 
   $("#cargador").html('<img src="../assets/images/cargando.gif" width="15px">');
    var idProforma="<?php echo $idProforma?>";
      var id=$("#id-"+num).val();
      var codigo=$("#2-"+num).val();
      var cantidad=$("#1-"+num).val();
      var numero=$("#numero-"+num).val();
      var codigo2=$("#codigo2-"+num).val();
      var descuento=$("#4-"+num).val();
      var html=$("#tr"+num).html();

      $.post( "funcionesAJAXgrid.php", { f: 'CAMBIAR-PRODUCTO',idProforma:idProforma,id:id,codigo:codigo,cantidad:cantidad,numero:numero,codigo2:codigo2,descuento:descuento,num:num})
      .done(function(data) {
       //alert(data);
        if(data==3){
          $("#tr"+num).html(html);
          $("#cargador").html("<span style='color:#14AA00'>No se encontro producto.</span>");
          $("#2-"+num).focus().select();
        }else if(data==4){
          $("#tr"+num).html(html);
          $("#cargador").html("<span style='color:#14AA00'>Ya existe una línea con ese código.</span>");
          $("#2-"+num).focus().select();
        }else if(data==5){
          //borrar
          $("#tr"+num).html("");
          $("#cargador").html("");
            $("#cantidad2").focus().select();
        }else{
            $("#tr"+num).html(data);
          $("#cargador").html("");
          idTxt=parseInt(idTxt)+1;
          $("#"+idTxt+"-"+num).focus().select();
          precios();
        }
        


          })  .fail(function() {
        alert("Error al cargar cambio de producto");
        document.reload();
    });

     // document.location.href="productosFactura.php?editarLinea='modificar'&idProducto="+id+"&codigo="+codigo+"&codigo2="+codigo2+"&cantidad="+cantidad+"&numero="+numero+"&descuento="+descuento+"&tipoCambio=<?php echo $tipoCambio?>&idProforma=<?php echo $idProforma?>&estado=<?php echo $estado?>"; 
    
}


function menosImp(){
  var precio = document.getElementById("precio").value;
  var imp = precio / 1.13;
  document.getElementById("precio").value = number_format(imp,2,'.','');
  document.getElementById('agregarLinea').focus();
}

function aplicarTodos(){
  var i = 0;
  var valor = 0;
  jQuery(".descuento").each(function (index) { 
     i++;
     if (i == 1){
      valor = jQuery(this).val();
     }
  });
  jQuery.ajax({
  type: "POST",
  url: "funcionesAJAXGeneral.php",
  data: { idProforma: '<?php echo $idProforma?>', descuento: valor, f: 'CAMBIAR-DESCUENTO-FACTURA-TODOS'}
  }).done(function( msg ) {
    document.location.href ='productosFactura.php?idProforma=<?php echo $_GET['idProforma']?>&tipoCambio=<?php echo $_GET['tipoCambio']?>&estado=<?php echo $_GET['estado']?>';
  });
}

function enter(e){
  if (e.keyCode == 13){
    document.getElementById("codigo").focus();
  }
}

function cambiarDescuento(field){
  var valor = field.value;
  if (valor < 0){
    field.value = 1;
  }else if (valor == ""){
    field.value = 1;
  }
  id = field.id.split("_");
  jQuery.ajax({
  type: "POST",
  url: "funcionesAJAXGeneral.php",
  data: { idProforma: '<?php echo $idProforma?>', idProducto: id[1], descuento: field.value, f: 'CAMBIAR-DESCUENTO-FACTURA'}
  }).done(function( msg ) {
    document.location.href ='productosFactura.php?idProforma=<?php echo $_GET['idProforma']?>&tipoCambio=<?php echo $_GET['tipoCambio']?>&estado=<?php echo $_GET['estado']?>';
  });
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


function validar(id){
	var valor = document.getElementById('cantidad_'+id).value;
	if (valor < 1){
		document.getElementById('cantidad_'+id).value = 1;
	}else if (valor == ""){
    document.getElementById('cantidad_'+id).value = 1;
  }
  guardarCantidad(id); 
}

function guardarCantidad(id, tipo){
	var cantidad = parseFloat(document.getElementById('cantidad_'+id).value);
	jQuery.ajax({
	type: "POST",
	url: "funcionesAJAXGeneral.php",
	data: { idProforma: '<?php echo $idProforma?>', idProducto: id, cantidad: cantidad, f: 'CAMBIAR-CANTIDAD-FACTURA'}
	}).done(function( msg ) {
    document.location.href ='productosFactura.php?idProforma=<?php echo $_GET['idProforma']?>&tipoCambio=<?php echo $_GET['tipoCambio']?>&estado=<?php echo $_GET['estado']?>';
	});	
}

function validarCantidad(){
	var cantidad = parseFloat($('#cantidad2').val());	
  
	if (cantidad <= 0 || (cantidad.toString())=="NaN"){
    
		$('#cantidad2').val("1");
	}else{
    $('#cantidad2').val(cantidad);
  }
}

function validarCantidad2(num){
	var cantidad = parseFloat($('#1-'+num).val());
 
	if (cantidad <=0 || (cantidad.toString())=="NaN"){
		$('#1-'+num).val("1");
	}else{
    $('#1-'+num).val(cantidad);
  }
  //guardar("1",num);
}




</script>
</div>
          
        </div>
<!-- FIN CONTENIDO -->
<?php  //include("../includes/footerLimpio.php");?>