<?php include("../conn/connpg.php");

controlAcceso('C-0');

$ocultarAbierta = "";
if (isset($_GET['oa'])){
  $ocultarAbierta = " AND estado != 1";
}

if (isset($_GET['n'])){
  $fecha = date("d/m/Y");
 
    $sqlP="SELECT id FROM cperiodos WHERE estado='1' AND periodo='".date('m-Y')."' LIMIT 1";
    $queryP=mysql_query($sqlP,$conn);
    while($rowP=mysql_fetch_assoc($queryP)){
      $periodo=$rowP['id'];
    }
 
    $sqlP="SELECT id FROM cmonedas WHERE estado='1' LIMIT 1";
    $queryP=mysql_query($sqlP,$conn);
    while($rowP=mysql_fetch_assoc($queryP)){
      $moneda=$rowP['id'];
    }

    $sqlP="SELECT id FROM cdocumentos WHERE estado='1' LIMIT 1";
    $queryP=mysql_query($sqlP,$conn);
    while($rowP=mysql_fetch_assoc($queryP)){
      $documento=$rowP['id'];
    }

  $sql = "INSERT INTO casientos VALUES (
  null, 
  '$periodo',
  '$moneda',
  '$documento',
  '',
  '',
  '$fecha',
  '$fecha',
  '',
  '1',
  '1',
  '".$personaid."'
)";

 $query = mysql_query($sql, $conn)or die(mysql_error());
  $id=mysql_insert_id();

 

  echo "<script type='text/javascript'>
  parent.location.href = 'editarasientos.php?id=".$id."';
  </script>";
  exit();
}

if (isset($_GET['id'])){
    $idCotizacion = $_GET['id'];
    $nombre = html_entity_decode($_GET['nombre']);

    $sql = "SELECT * FROM tfacturas WHERE id = '$idCotizacion'";
    $query = mysql_query($sql, $conn);
    while ($row=mysql_fetch_assoc($query)) {
      $numero = $row['numero'];
    }
    if ($numero == 0 or $numero == ""){
      $sql = "UPDATE tfacturas SET 
      estado = 5 
      WHERE id = '$idCotizacion'";
      $query = mysql_query($sql, $conn)or die(mysql_error());
    }else{
      $sql = "UPDATE tfacturas SET 
      estado = 3 
      WHERE id = '$idCotizacion'";
      $query = mysql_query($sql, $conn)or die(mysql_error());

      $sql = "UPDATE tcuentascobrar SET 
      estado = 2 
      WHERE numero = '$numero'";
      $query = mysql_query($sql, $conn)or die(mysql_error());
    } 

    $sql2 = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idCotizacion'";
    $query2 = mysql_query($sql2, $conn) or die(mysql_error());
    while($row2=mysql_fetch_assoc($query2)){
      $idProducto =  $row2['idProducto'];
      $cantidad = $row2['cantidad'];

      $sql3 = "UPDATE tproductos SET existencia  = existencia  + '$cantidad' WHERE id = '$idProducto'";
      $query3 = mysql_query($sql3, $conn) or die(mysql_error());
    }

    
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
    $idVendedor = $row['idVendedor'];
    $validez = $row['validez'];
    $idCaja = $row['idCaja'];
    $plazo = $row['plazo'];
  }
  $codigoSeguridad = rand(10000000, 99999999);
  $fecha = date("d/m/Y");
  $sql = "INSERT INTO tfacturas VALUES (null, '','0','Estimado cliente','','','','$fecha', '', '', '0','$idUsuario', '$idVendedor', '$codigoSeguridad', '', '', '', '$idCaja', '$plazo', 1, '','".$cuentaid."')";
  $query = mysql_query($sql, $conn)or die(mysql_error());
  $sql = "SELECT * FROM tfacturas WHERE idUsuario = '$idUsuario' ORDER BY id DESC LIMIT 1";
  $query = mysql_query($sql, $conn);
  while ($row=mysql_fetch_assoc($query)) {
    $id = $row['id'];
  }

  $sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idDuplicar'";
  $query = mysql_query($sql, $conn);
  while ($row=mysql_fetch_assoc($query)) {
    $sql2 = "INSERT INTO tproductosfactura VALUES ('".$id."', '".$row['idProducto']."', '".$row['codigo']."', '".$row['nombre']."', '".$row['descripcion']."', '".$row['impuesto']."', '".$row['moneda']."', '".$row['descuento']."', '".$row['precio']."', '".$row['cantidad']."', '".$row['tipo']."','".$row['numero']."')";
    $query2 = mysql_query($sql2, $conn)or die(mysql_error());
  }

    

  echo "<script type='text/javascript'>
  parent.location.href = 'editarFactura.php?id=".$id."';
  </script>";
  exit();
}

if (isset($_GET['idParaCobrar'])){
  $idDuplicar = $_GET['idParaCobrar'];

  $sql = "SELECT * FROM tfacturas WHERE id = '$idDuplicar'";
  $query = mysql_query($sql, $conn);
  while ($row=mysql_fetch_assoc($query)) {
    $numero = $row['numero'];
    $idCliente = $row['idCliente'];
    $nombreCliente = $row['nombreCliente'];
    $fechaCierre = $row['fechaCierre'];
    
    $monto = $row['monto'];

    $sql = "INSERT INTO tcuentascobrar VALUES (null, '$numero', '$idCliente', '$nombreCliente', '$fechaCierre', '$monto', 1,'".$cuentaid."')";
    $query = mysql_query($sql, $conn);

    $sql = "UPDATE tfacturas SET 
    estado = 4 
    WHERE id = '$idDuplicar'";
    $query = mysql_query($sql, $conn);

    echo "<script type='text/javascript'>
    parent.location.href = '../facturas?cuentaporcobrar=1';
    </script>";
    exit();
  }

    

}


?>
<?php
$pagina="Asientos Contables";
$skin='facebook';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>
<style type="text/css" media="screen">
@media(max-width: 728px){
  .besconder2{
    display: none;
  }
}

@media(max-width: 460px){
  .esconder2{
    display: none;
  }
}
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
  <ol class="breadcrumb bc-2">
  <li>
    <a href="<?php echo base_url();?>">Home</a>
  </li>
 <li>
    <a href="../contabilidad/">Contabilidad</a>
  </li>
   
  <li class="active">
    <strong><?php echo $pagina?></strong>
  </li>
</ol> 

<h2><?php echo $pagina?>

<a href="../contabilidad/asientos.php?n=1" class="btn btn-primary pull-right"><i class="entypo-plus"></i> Nuevo asiento</a>

<?php
if (isset($_GET['oa'])){ ?>
  <a href="../contabilidad/asientos.php" class="btn btn-default pull-right" style="margin-right: 5px" ><i class="entypo-eye"></i> Restaurar vista</a>
<?php }else{?>
  <a href="../contabilidad/asientos.php?oa=1" class="btn btn-default pull-right" style="margin-right: 5px" ><i class="entypo-eye"></i> No mostrar asientos abiertas</a>
<?php } ?>


</h2>
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
</form>

    <br><br>
    <?php 
    if (isset($_GET['bNombre'])){
      if ($_GET['bNombre'] != ""){
        $bNombre = $_GET['bNombre'];
        $sql = "SELECT * FROM casientos WHERE numero like '%$bNombre%' OR id = $bNombre  AND  estado !='0' ".$ocultarAbierta.' ORDER by id DESC';  
        $mostrando = 'Mostrando el resultado de la búsqueda por: <b>'.$bNombre.'</b> :: <a href="asientos.php"><i class="entypo-cw"></i>Restablecer</a>'; 
       
      }else{
        $sql = "SELECT * FROM casientos WHERE estado != '0' ".$ocultarAbierta.' ORDER by id DESC';
      }
    }else{
      $sql = "SELECT * FROM casientos WHERE estado != '0' ".$ocultarAbierta.' ORDER by id DESC';
    }
    $query = mysql_query($sql, $conn)or die(mysql_error());

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda: </strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i><br>
        No hay asientos registradas.
      </center>
      <?php
    }else{
      //mostramos la tabla con las editoriales 
      ?>

        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda: </strong>'.$mostrando.'</center></div>';}
        if ($bError != ''){echo '<div class="bError">'.$bError.'</div><hr>';}?>

<center>


  <div class="flechas"><i class="entypo-switch"></i></div>
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">

<table width="100%" class="table table-bordered responsive tablaGrande">
<thead>
<tr>
<th width="190"></th>
<th>Numero Asiento</th>
<th>Periodo</th>
<th>Moneda</th>
<th>NO°</th>
<th width="150">Fecha de creación</th>
<th width="150">Estado</th>  
</tr>
</thead>
<tbody>
<?php  

$busquedas = 'bNombre='.$bNombre.'&';
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
include("../includes/paginationTop.php"); 
while ($row = mysql_fetch_array($result)){
$numero = $row['id'];
?>

<tr>
  <td>
    <input type="hidden" name="idFactura<?php echo $row['numero']?>" id="idFactura<?php echo $row['numero']?>" value="<?php echo $row['id']?>">
    <a href="editarasientos.php?<?php echo $busqueypagi.'&id='.$row['id']?>" style="margin-right: 3px; margin-bottom: 3px;" class="btn btn-sm btn-default pull-left"><i class="entypo-pencil"></i></a>

    <a href="javascript:;" onclick="confirmBorrar('../facturas/?<?php echo 'id='.$row['id'].'&nombre='.$row['nombreCliente']?>','<?php echo htmlentities($row['nombreCliente'])?>');" class="btn btn-sm btn-danger pull-left besconder2"><i class="entypo-trash"></i></a>
     
    <a href="../facturas/?<?php echo $busqueypagi.'&idDuplicar='.$row['id']?>" onclick="return confirm('¿Está seguro(a) que desea duplicar esta factura?')" class="btn btn-sm btn-info pull-left besconder2" style="margin-left: 3px;"><i class="entypo-docs"></i></a>

    <a href="javascript:void(0)" onclick="previewProforma(<?php echo $row['id']?>)" class="btn btn-sm btn-default pull-left besconder2" style="margin-left: 3px;"><i class="entypo-eye"></i></a>


  </td>
 
  <td>
    <span style="color: #610000;font-weight: 600;">
      (<?php echo addZero($numero);?>)
     </span>
  </td>
  <td>
    <?php
      if($row['idperiodo']!=0){

      }else{echo "N/A";}
    ?>
  </td>
  <td>
    <?php
      if($row['idmoneda']!=0){

      }else{echo "N/A";}
    ?>
  </td>
   <td>
    <?php
      if($row['numero']!=""){
        echo $row['numero'];
      }else{echo "N/A";}
    ?>
  </td>
  <td><?php echo $row['fecha'];?></td>

  <td><?php if ($row['estado'] == 1){echo 'Abierta';}else if($row['estado'] == 2){echo 'Aplicado';}?></td>      

        
                 
</tr>
<?php
}
?>
</tbody>
</table>
</div>
Simbología: <i class="entypo-pencil"></i> Modificar <i class="entypo-trash"></i> Eliminar <i class="entypo-docs"></i> Duplicar <i class="entypo-briefcase"></i> Enviar a cuentas por cobrar</center>
<?php
include("../includes/paginationBottom.php");
}
?>

<div class="modal fade" id="modalEnviarCorreo" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Enviar factura por Email</h4>
      </div>
        <div class="modal-body">  

          <?php include("../includes/alerts.php");?>

          <div class="form-group">
        <div class="col-sm-12">
          <input type="text" placeholder="Email" class="form-control" required="required" name="iframesEmail" autocomplete="off" id="enviarEmailEmail">
        </div>
      </div>

      <div class="form-group">
        <label for="enviarEmailMensaje" class="col-sm-3 control-label">Mensaje:</label>
        
        <div class="col-sm-12">
          <textarea style="height: 200px;" class="form-control wysihtml5" data-stylesheet-url="assets/css/wysihtml5-color.css" name="enviarEmailMensaje" id="enviarEmailMensaje"></textarea>
        </div>
      </div>
      <br>
      <br>
      <br>
      <br>
      <br>
      <center><i>La(s) factura(s) será(n) enviada(s) como un adjunto en el correo electrónico.</i></center>
      
      <div class="row" id="barraPro" style="display: none">
      <hr>
        <div class="col-md-12">

          Generando las facturas. 
          <div class="progress">
            <div id="progreso" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
              <span class="sr-only">35% Complete (success)</span>
            </div>
          </div>
        </div>
          
      </div>


      </div>
      
      <div class="modal-footer">
        <table width="100%">
          <tr>
            <td><button onclick="enviarEmail()" target="_blank" class="btn btn-green"><i class="entypo-direction"></i> Enviar</button></td>
            <td width="80"><button type="button" class="btn btn-default" data-dismiss="modal" id="cancelarResponsive">Cancelar</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-8">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Productos de factura</h4>
        </div>
        
        <div class="modal-body">
        
          <iframe src="" id="frameCuentasPagar" frameborder="0" width="100%" height="400"></iframe>
          
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal" id="cerrarFrame">Cerrar</button>
        </div>
      </div>
    </div>
</div>


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

<div id="iframes" style="visibility: hidden; height: 0px;"></div>


<div class="modal fade" id="modalMostrarCerrar" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Anular factura</h4>
      </div>
      <div class="modal-body" div="contenidoFacturaFE">  
     A partir de la implementación de Facturación Electrónica, el proceso de anulación de la Factura, requiere de la creación de una Nota de Crédito Electrónica. El sistema lo hará de forma automática, pero es necesario que nos provea los siguientes datos: <br><br>

          <form role="form" action="" class="form-horizontal" method="post" name="formulario" id="formulario">
              Tipo de documento de referencia: 
              <div id="tipoDocRef" style="font-size: 18px; text-align: center; width: 100%; font-weight: bold;"></div>
              <div id="text" id="tipoDoc" value=""></div>
          </form>
          <button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" style="visibility: hidden; height: 0px"><i class="entypo-cancel"></i>Cerrar ventana</button>
      <div class="modal-footer" id="botoneraFinalizar">
        <table width="100%">
          <tr>
            <button type="button" class="btn btn-red"  id="cancelarResponsiveProductos" onclick="anular($('#numeroFactura').val(),$('#idFactura').val(),1)">Anular</button>
            <button type="button" class="btn btn-default cerrarModal" data-dismiss="modal"><i class="entypo-cancel"></i>Cerrar ventana</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
</div>




<?php include("../includes/confirmaEliminar.php");?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>