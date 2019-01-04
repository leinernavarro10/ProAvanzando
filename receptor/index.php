<?php include("../conn/connpg.php");?>	
<?php 
//controlAcceso(55);
?>
<?php $pagina="Mensaje Receptor";  include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<style type="text/css" media="screen">


@media(max-width: 758px){
  .esconder2{
    display: none;
  }
  .flechas{
     display: initial;
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
<ol class="breadcrumb" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
  <li><a href="">Compras</a></li>
	
</ol>

<h2>Mensaje receptor
  <?php if ($_GET['n']!=""){ ?>
    <a href="../receptor/" style="margin-right: 5px" class="btn btn-blue pull-right">
      <i class="entypo-eye"></i>
      Restaurar vista
    </a> 
  <?php }else{?>
     <a href="../receptor/?n=1" style="margin-right: 5px" class="btn btn-orange pull-right">
      <i class="entypo-eye"></i>
      Mostrar montos
    </a> 
  <?php }?>
<a href="aceptacionFE.php" style="margin-right: 5px" class="btn btn-primary pull-right">
			<i class="entypo-plus"></i> Nueva Compra
			
		</a></h2>
<br />
<form autocomplete="off" action="" method="get">
  <input type="hidden" name="n" value="<?php echo $_GET['n']?>">
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
    
      if ($_GET['bNombre'] != ""){
        $bNombre = utf8_decode($_GET['bNombre']);
        $sql = "SELECT * FROM tmensajereceptorfe WHERE (fechaEmision like '%$bNombre%') AND sistema='$cuentaid' ORDER by id DESC";  
        $mostrando = 'Mostrando el resultado de la búsqueda por: <b>'.utf8_encode($bNombre).'</b> :: <a href="../receptor/"><i class="entypo-cw"></i>Restablecer</a>'; 
      
    }else{
      $sql = "SELECT * FROM tmensajereceptorfe WHERE sistema='$cuentaid' ORDER by id DESC";  
    }
    $query = mysql_query($sql, $conn);

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i><br>
        No hay mensajes de receptor.
      </center>
      <?php
    }else{
      //mostramos la tabla con las editoriales 
      ?>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}
        if ($bError != ''){echo '<div class="bError">'.$bError.'</div><hr>';}?>
<center>
  <div class="flechas"><i class="entypo-switch"></i></div>
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">







<?php if($_GET['n']==""){?>


<table width="100%" class="table table-bordered responsive tablaGrande" >
<thead>
<tr>
<th width="100">Consecutivo</th> 
<th width="100">Fecha Emisión</th> 
<th width="50" class="esconder2">Clave</th>
<th>Emisor</th> 
<th width="20">Estado</th> 
<th width="50">Archivos</th> 
<th width="50">Tipo</th> 
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
    <a href="javascript: void(0);" onclick="verificarFE('<?php echo $row['clave']?>', '<?php echo $row['consecutivoReceptor']?>')"><?php echo $row['consecutivoReceptor']?></a>
  </td>
  <td><?php $fecha = explode("T",$row['fechaEmision']); $fechanormal = explode("-", $fecha[0]); echo $fechanormal[2].'/'.$fechanormal[1].'/'.$fechanormal[0];?></td>
  <td class="esconder2"><?php echo $row['clave']?></td>  
  <td><?php 
  $cedulaEmisor = preg_replace('/^0+/', '', $row['cedulaEmisor']); 
  $sql2 = "SELECT * FROM tclientes WHERE identificacion = '".$cedulaEmisor."' AND usuario='".$cuentaid."' ";
  $query2 = mysql_query($sql2, $conn);
  while ($row2=mysql_fetch_assoc($query2)) {
     echo $row2['nombre'];
   } ?></td>   
   <td><span id="estadoFE-<?php echo $row['consecutivoReceptor']?>">
    <?php if ($row['estado'] == 4){
            echo 'No enviada';
          }else if ($row['estado'] == 1){
            echo 'Aceptada';
          }else if($row['estado'] == 0){
            echo 'Procesando';
          }else if($row['estado'] == 3){
            echo 'Rechazada';
          }?>
    </span></td>
    <td width="110">
      <?php
      $nombre_fichero="../parametros/archivos/firmados/MReceptor-".$row['consecutivoReceptor']."-".$cuentaid."-firmada.xml";
      if(file_exists($nombre_fichero)){?>
      <a href="javascript:void(0)" onclick="previewXML('<?php echo $nombre_fichero?>','4')" class="btn btn-sm btn-success pull-left" style="margin-left: 3px;"><i class="entypo-download"></i></a>
    <?php }?>
    <?php 
      $nombre_fichero="../parametros/archivos/respuestas/MReceptor-".$row['consecutivoReceptor']."-".$cuentaid."-resp.xml";
      if(file_exists($nombre_fichero)){?>
      <a href="javascript:void(0)" onclick="previewXML('<?php echo $nombre_fichero?>','2')" class="btn btn-sm btn-success pull-left" style="margin-left: 3px;"><i class="entypo-info"></i>
      </a>
      <?php }?>
     </td> 
     <td>
       <?php if ($row['tipoFA'] == 0){echo 'Compra';}else if ($row['tipoFA'] == 1){echo '<span title="Cuentas por pagar">CXP</span>';}else if ($row['tipoFA'] == 2){echo '<span title="Cuentas por pagar">Pago Realizado</span>';};?>
     </td>
</tr>
<?php
}
?>
</tbody>
</table>

<?php }else{?>


<table width="100%" class="table table-bordered responsive tablaGrande" >
<thead>
<tr>
  <th>Emisor</th> 
<th>Factura</th> 
<th>Fecha Emisión</th> 
<th>Estado</th> 
<th>Monto</th> 
<th>Tipo</th> 
<th></th>
</tr>
</thead>
<tbody>
<?php  

$busquedas = 'bNombre='.$bNombre.'&';
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
include("../includes/paginationTop.php"); 
while ($row = mysql_fetch_array($result)){ ?>

<tr>

  <td><?php 
  $cedulaEmisor = preg_replace('/^0+/', '', $row['cedulaEmisor']); 
  $sql2 = "SELECT * FROM tclientes WHERE identificacion = '".$cedulaEmisor."' AND usuario='".$cuentaid."'  ";
  $query2 = mysql_query($sql2, $conn);
  while ($row2=mysql_fetch_assoc($query2)) {
     echo $row2['nombre'];
   } ?>
  </td> 
  <td>
   <?php echo substr($row['consecutivoFE'], -5);?>
  </td>
  <td><?php $fecha = explode("T",$row['fechaEmision']); $fechanormal = explode("-", $fecha[0]); echo $fechanormal[2].'/'.$fechanormal[1].'/'.$fechanormal[0];?></td>
   <td><span id="estadoFE-<?php echo $row['consecutivoReceptor']?>"><?php if ($row['estado'] == 4){echo 'No enviada';}else if ($row['estado'] == 1){echo 'Aceptada';}else if($row['estado'] == 0){echo 'Procesando';}else if($row['estado'] == 3){echo 'Rechazada';}?></span></td>
  <td width="110">
    <?php echo number_format($row['totalFactura'],2,'.','');?>
  </td> 
  <td>
    <?php if ($row['tipoFA'] == 0){echo 'Compra';}else if ($row['tipoFA'] == 1){echo '<span title="Cuentas por pagar">CXP</span>';}else if ($row['tipoFA'] == 2){echo '<span title="Cuentas por pagar">Pago Realizado</span>';};?>
  </td>
  <td>
    <?php if ($row['tipoFA'] == 1 OR $row['tipoFA'] == 2){?>
    <a href="pagarcxp.php?id=<?php echo $row['id']?>" class="btn btn-sm btn-orange pull-left" style="margin-left: 3px;"><i class="entypo-list-add"></i>
    </a>
    <?php } ?>
  </td>
</tr>
<?php
}
?>
</tbody>
</table>




<?php }?>

</div>
Simbología: <i class="entypo-pencil"></i> Modificar <i class="entypo-trash"></i> Eliminar</center>
<?php
include("../includes/paginationBottom.php");
}
?>
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
  
  document.getElementById("frameXML").src = '../facturas/previewXML.php?xml='+xml+'&tipo='+tipo;
}

  function verificarFE(clave, consecutivo){
  $("#estadoFE-"+consecutivo).html("<img src='../assets/images/cargando.gif'> VERIFICANDO...");
  jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'VERIFICAR-MR', clave: clave, consecutivo: consecutivo})
      .done(function( data ) {
        //console.log(data);
        if (data.indexOf('404</h2>') >= 0){
          $("#estadoFE-"+consecutivo).html('No enviada');
        }else{
          var estado = "";
          var resp = JSON.parse(data);
          if (resp['ind-estado'] == 'aceptado'){
            $("#estadoFE-"+consecutivo).html('Aceptada');
            estado = 1;
          }else if (resp['ind-estado'] == 'rechazado'){
            $("#estadoFE-"+consecutivo).html('Rechazada');
            estado = 3;
          }else{
            $("#estadoFE-"+consecutivo).html('Procesando');
            estado = 2;
          }

          jQuery.post( "../facturas/funcionesAJAXFacturas.php", { f: 'CAMBIARESTADO-MR', consecutivo: consecutivo, estado: estado, xmlRespuesta: resp['respuesta-xml']})
          .done(function( data ) {
            console.log(data);
          });
        }
    });
}

</script>

<?php include("../includes/confirmaEliminar.php");?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>