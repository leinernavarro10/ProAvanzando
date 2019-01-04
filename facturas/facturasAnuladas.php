<?php include("../conn/connpg.php");?>	
<?php 
//controlAcceso(8);

if (isset($_GET['n'])){
  $fecha = date("d/m/Y");
  $sql = "INSERT INTO tfacturas VALUES (null, '','0','Estimado cliente','','','','$fecha','','$idUsuario',1)";
  $query = mysql_query($sql, $conn)or die(mysql_error());
  $sql = "SELECT * FROM tfacturas WHERE idUsuario = '$idUsuario' ORDER BY id DESC LIMIT 1";
  $query = mysql_query($sql, $conn);
  while ($row=mysql_fetch_assoc($query)) {
    $id = $row['id'];
  }

  //registrarBitacora($userid, "Crear factura: (".$id.") Estimado cliente", $conn); 

  echo "<script type='text/javascript'>
  parent.location.href = 'editarFactura.php?".encode_this('id='.$id)."';
  </script>";
  exit();
}

if (isset($_GET['id'])){
	  $idCotizacion = $_GET['id'];
	  $nombre = html_entity_decode($_GET['nombre']);
	  $sql = "UPDATE tfacturas SET 
	  estado = 3 
	  WHERE id = '$idCotizacion'";
	  $query = mysql_query($sql, $conn)or die(mysql_error());

	  //registrarBitacora($userid, "Eliminar factura: (".$idCotizacion.")".$nombre, $conn);   
}

if (isset($_GET['idDuplicar'])){
	$idDuplicar = $_GET['idDuplicar'];
	$sql = "SELECT * FROM tfacturas WHERE id = '$idDuplicar'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		$idCliente = $row['idCliente'];
		$nombreCliente = $row['nombreCliente'];
		$mail = $row['email'];
		$exonerar = $row['exonerar'];
		$descuento = $row['descuento'];
	}
	$fecha = date("d/m/Y");
	$sql = "INSERT INTO tfacturas VALUES (null, '','$idCliente','$nombreCliente','$email','$exonerar','$descuento','$fecha','','$idUsuario',1)";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	$sql = "SELECT * FROM tfacturas WHERE idUsuario = '$idUsuario' ORDER BY id DESC LIMIT 1";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
	  $id = $row['id'];
	}

	$sql = "SELECT * FROM tproductoscotizacion WHERE idCotizacion = '$idDuplicar'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		$sql2 = "INSERT INTO tproductoscotizacion VALUES ('".$id."', '".$row['idProducto']."', '".$row['codigo']."', '".$row['nombre']."', '".$row['descripcion']."', '".$row['impuesto']."', '".$row['moneda']."', '".$row['precio']."', '".$row['cantidad']."')";
		$query2 = mysql_query($sql2, $conn);
	}

	//registrarBitacora($userid, "Duplicar factura: (".$idDuplicar.")".$nombreCliente, $conn);   

	echo "<script type='text/javascript'>
	parent.location.href = 'editarFactura.php?".encode_this('id='.$id)."';
	</script>";
	exit();
}
?>
<?php $pagina="Facturas Anuladas";
 include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>

<style type="text/css">
  .custom-width2{
      width: 85%;
    }
 @media(max-width : 794px){
    .custom-width2{
        width: 97%;
    }
  }
</style>

<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
  <li><a href="../facturas/">Facturas</a></li>
	<li><a href="#">Facturas anuladas</a></li>
</ol>

<h2>Facturas anuladas

<a href="../facturas/?n=1" class="btn btn-primary pull-right">
			<i class="entypo-plus"></i>Nueva Factura
			
		</a></h2>
<br />

<form autocomplete="off" action="" method="get">
    <div class="form-group">
      <div class="col-md-3 pull-right">
        <div class="input-group">
          <input type="text" class="form-control" name="bNombre" placeholder="Buscar">
          <span class="input-group-btn">
            <button class="btn btn-blue" type="submit">Buscar</button>
          </span>
        </div>
      </div>
    </div>
    <br><br>
    <?php 
    if (isset($_GET['bNombre'])){
      if ($_GET['bNombre'] != ""){
        $bNombre = utf8_decode($_GET['bNombre']);
        $sql = "SELECT * FROM tfacturas WHERE sistema='".$cuentaid."' AND (nombreCliente like '%$bNombre%' OR numero like '%$bNombre%' OR fechaCreacion like '%$bNombre%') AND estado = 3 ORDER by id DESC";  
        $mostrando = 'Mostrando el resultado de la búsqueda por: <b>'.utf8_encode($bNombre).'</b> :: <a href="facturasAnuladas.php"><i class="entypo-cw"></i>Restablecer</a>'; 
      }else{
        $sql = "SELECT * FROM tfacturas WHERE sistema='".$cuentaid."' AND estado = 3 ORDER by id DESC"; 
      }
    }else{
      $sql = "SELECT * FROM tfacturas WHERE sistema='".$cuentaid."' AND estado = 3 ORDER by id DESC";  
    }
    $query = mysql_query($sql, $conn);

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i><br>
        No hay facturas anuladas.
      </center>
      <?php
    }else{
      //mostramos la tabla con las editoriales 
      ?>

        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}
        if ($bError != ''){echo '<div class="bError">'.$bError.'</div><hr>';}?>

<center>

<script type="text/javascript">
  var pagina = 'facturas.php';
  var paginaEditar = 'editarFactura.php';
</script>

  <div class="flechas"><i class="entypo-switch"></i></div>
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">

<table width="100%" class="table table-bordered responsive tablaGrande" >
<thead>
<tr>
<th width="50"></th>

<th>Cliente</th> 
<th width="150">Fecha de creación</th>
<th width="150">Estado</th> 
<?php 
  $sql2 = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
  $query2 = mysql_query($sql2, $conn);
  while($row2=mysql_fetch_assoc($query2)){
    if ($row2['facturaElectronica'] == 1){ ?>
      <th colspan="2" width="150">Estado FE</th>
   <?php }
  }
?> 
</tr>
</thead>
<tbody>
<?php  

$busquedas = 'bNombre='.$bNombre.'&';
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
include("../includes/paginationTop.php"); 
while ($row = mysql_fetch_array($result)){ ?>

<tr>
  <td> 
    <input type="hidden" name="email<?php echo $row['numero']?>" id="email<?php echo $row['numero']?>" value="<?php echo $row['email']?>">
    <input type="hidden" name="idFactura<?php echo $row['numero']?>" id="idFactura<?php echo $row['numero']?>" value="<?php echo $row['id']?>">
  	<a href="editarFactura.php?<?php echo $busqueypagi.'&id='.$row['id']?>" style="margin-right: 3px; margin-bottom: 3px;" class="btn btn-sm btn-default pull-left"><i class="entypo-eye"></i></a>
  </td>
  <?php 
  $sql2 = "SELECT * FROM tnotasCreditoFE WHERE numeroFactura = '".$row['numero']."'";
  $query2 = mysql_query($sql2, $conn);
  while($row2=mysql_fetch_assoc($query2)){
    $estadoNC = $row2['estadoFE'];
  }

 
?> 
  <td>
    
  <?php   $sql2 = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
  $query2 = mysql_query($sql2, $conn);
  while($row2=mysql_fetch_assoc($query2)){
    if ($row2['facturaElectronica'] == 1 and $row['claveFE'] != ""){ ?>
     <a href="javascript: void(0);" style="color: #610000;font-weight: 600;" onclick="verificarFE('<?php echo $row['numero']?>')"><?php echo addZero($row['numero'])?></a>
   <?php }else{ ?>
     <span style="color: #610000;font-weight: 600;">(<?php echo addZero($row['numero'])?>)</span>
   <?php }
  }
?>
    <?php echo $row['nombreCliente']?></td>
  <td><?php if ($row['estado'] == 1){echo $row['fechaCreacion'];}else if($row['estado'] == 2 OR $row['estado'] == 3){echo $row['fechaCierre'];}?></td>
  <td><?php if ($row['estado'] == 1){echo 'Abierta';}else if($row['estado'] == 2){echo 'Cerrada';}else{echo 'Anulada';}?></td>   
  <?php 
  $sql2 = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
  $query2 = mysql_query($sql2, $conn);
  while($row2=mysql_fetch_assoc($query2)){
    $nombreComercial=$row2['nomComercial'];
    if ($row2['facturaElectronica'] == 1){ ?>
     <td><span id="estadoFE-<?php echo $row['numero']?>">
      <?php if ($row['claveFE'] == ""){
        echo 'No es F.E.';
      }else if ($estadoNC == 1){
        echo 'Aceptada';
      }else if(
        $estadoNC == 2){
          echo 'Procesando';
      }else if(
          $estadoNC == 3){
            echo 'Rechazada';
      }?></span></td>
     <td width="110">
      <?php if ($row['claveFE'] != ''){?>
    <?php 
    $nombre_fichero="../parametros/archivos/firmados/NCredito-".$row['numero']."-".$cuentaid."-firmada.xml";
    if(file_exists($nombre_fichero)){
    ?>
      <a href="javascript:void(0)" onclick="javascript: previewXML('<?php echo $nombre_fichero?>','3')" class="btn btn-sm btn-success pull-left" style="margin-left: 3px;"><i class="entypo-download"></i></a>
    <?php }?>
    <?php 
    $nombre_fichero="../parametros/archivos/respuestas/NCredito-".$row['numero']."-".$cuentaid."-resp.xml";
    if(file_exists($nombre_fichero)){
    ?>
      <a href="javascript:void(0)" onclick="previewXML('<?php echo $nombre_fichero?>','2')"  class="btn btn-sm btn-success pull-left" style="margin-left: 3px;"><i class="entypo-info"></i></a>
    <?php }?>
      <?php } ?>
     </td> 
   <?php }
  }
?>                
</tr>
<?php
}
?>
</tbody>
</table>

</div>
Simbología: <i class="entypo-pencil"></i> Modificar <i class="entypo-trash"></i> Eliminar</center>
<?php
include("../includes/paginationBottom.php");
}
?>
<div id="iframes" style="visibility: hidden; height: 0px;"></div>




<div class="modal custom-width fade" id="modal-9">
    <div class="modal-dialog custom-width2">
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Datos Comprobante</h4>
        </div>
        
        <div class="modal-body">
        
          <iframe src="" id="frameXML" frameborder="0" width="100%" height="400"></iframe>
          
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal" id="cerrarFrame">Cerrar</button>
        </div>
      </div>
    </div>
</div>  




<script type="text/javascript">
  
  function previewXML(xml,tipo){
  jQuery('#modal-9').modal('show', {backdrop: 'static'});
  
  document.getElementById("frameXML").src = 'previewXML.php?xml='+xml+'&tipo='+tipo;
}

  function verificarFE(numeroFactura){
    var idFactura = $("#idFactura"+numeroFactura).val();
     var iframe = '<iframe id="generarDocumento" src="imprimirFactura.php?idProforma='+idFactura+'&tipo=2&nu=1" style="width: 850px; height: 150px;"></iframe>';
  document.getElementById('iframes').innerHTML+=iframe; 

  $("#estadoFE-"+numeroFactura).html("<center><img src='../assets/images/cargando.gif'> VERIFICANDO...</center>");
  jQuery.post( "funcionesAJAXFacturas.php", { f: 'VERIFICAR-NC', numeroFactura: numeroFactura})
      .done(function( data ) {
        console.log(data);
        if (data.indexOf('404</h2>') >= 0){
          $("#estadoFE-"+numeroFactura).html('No enviada');
        }else{
          var estado = "";
          var resp = JSON.parse(data);
          if (resp['ind-estado'] == 'aceptado'){
            $("#estadoFE-"+numeroFactura).html('Aceptado');
            estado = 1;
          }else if (resp['ind-estado'] == 'rechazado'){
            $("#estadoFE-"+numeroFactura).html('Rechazada');
            estado = 3;
          }else{
            $("#estadoFE-"+numeroFactura).html('Procesando');
            estado = 2;
          }
          jQuery.post( "funcionesAJAXFacturas.php", { f: 'ELIMINARFACTURA', numeroFactura: numeroFactura, estado: estado, xmlRespuesta: resp['respuesta-xml']})
          .done(function( data ) {

              if (estado == 1){
                  var emailNC = $("#email"+numeroFactura).val();
                  var idFactura = $("#idFactura"+numeroFactura).val();

                  if (emailNC != ''){
                    jQuery.ajax({
                      type: "POST",
                      url: "sendEmail.php",
                      data: { 
                         pre: 'f',
                         NC: '1',
                         id: idFactura,
                         email: emailNC,
                         mensaje: 'Saludos. Adjunto encontrará los documentos relacionados con la Nota de Crédito Electrónica que anula la Factura con el número: '+numeroFactura
                      }
                    }).done(function(msg) {
                      console.log(msg);
                      location = location;
                    });
                  }
                }else{
                  location = location;
                }



          });
        }
    });
}


</script>

<?php include("../includes/confirmaEliminar.php");?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>