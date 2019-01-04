<?php include("../conn/connpg.php");?> 
<?php 

if($estado!=4){
  print "<meta http-equiv=\"refresh\" content=\"0;URL=../inicio\">";
  break;
}


?>
<?php
$pagina="Listas Facturas";
 include("../includes/header.php");
?>  
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
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
<h2>Facturas</h2>
<br />

<form autocomplete="off" action="" method="get">
    <div class="form-group">
      <div class="col-md-3 pull-right">
        <div class="input-group">
          <select name="bNombre" class="form-control">
            <option value="">Seleccione...</option>
             <option value="0" <?php if($_GET['bNombre']==0){echo "selected";}?> >Enviando</option>
            <option value="1" <?php if($_GET['bNombre']==1){echo "selected";}?>  >Aceptado</option>
            <option value="3" <?php if($_GET['bNombre']==3){echo "selected";}?>  >Rechazada</option>
            <option value="2" <?php if($_GET['bNombre']==2){echo "selected";}?>  >Procesando</option>
          </select>
          <span class="input-group-btn">
            <button class="btn btn-blue" type="submit">Buscar</button>
          </span>
        </div>
      </div>
    </div>
</form>

    <br><br>
    <?php 
    if ($_GET['bNombre']!=""){
       $bNombre = $_GET['bNombre'];
        $sql = "SELECT * FROM tfacturas WHERE estado != 3 AND estado != 5 AND estado != 1 AND estadoFE = '".$_GET['bNombre']."' AND claveFE!=''  ORDER by id DESC";  
      
    }else{
      $sql = "SELECT * FROM tfacturas WHERE estado != 3 AND estado != 5 AND estado != 1 AND claveFE!='' ORDER by id DESC";
    }
    $query = mysql_query($sql, $conn);

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i><br>
        No hay facturas registradas.
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

<table width="100%" class="table table-bordered responsive tablaGrande">
<thead>
<tr>
<th>Cliente</th> 
<th>Fecha de creación</th>
<th>Estado</th>  
<th>Estado FE</th>
<td></td>
<td>Sistema</td>
</tr>
</thead>
<tbody>
<?php  

$busquedas = 'bNombre='.$bNombre.'&';
$busqueypagi = $busquedas.'&pagi='.$_GET['pagi'];
include("../includes/paginationTop.php"); 
while ($row = mysql_fetch_array($result)){

?>

<tr>
  
    <input type="hidden" name="email<?php echo $row['numero']?>" id="email<?php echo $row['numero']?>" value="<?php echo $row['email']?>">
    <input type="hidden" name="idFactura<?php echo $row['numero']?>" id="idFactura<?php echo $row['numero']?>" value="<?php echo $row['id']?>">
   
  

  <td>
  
      <a href="javascript: void(0);" style="color: #610000;font-weight: 600;" onclick="verificarFE(<?php echo $row['numero']?>,<?php echo $row['sistema']?>)">(<?php echo addZero($row['numero'])?>)</a>
      <span id="idFactura<?php echo $row['numero']?>" style="visibility: hidden;">(<?php echo $row['id']?>)</span>
     
  

    <?php 
  echo $row['nombreCliente']; 
  if ($row['email'] != ''){
    echo ' (<span id="email'.$row['numero'].'">'.$row['email'].'</span>)';
  }
  ?></td>
  <td class="esconder2"><?php if ($row['estado'] == 1){echo $row['fechaCreacion'];}else if($row['estado'] == 2){echo $row['fechaCierre'];}?></td>

  <td><?php if ($row['estado'] == 1){echo 'Abierta';}else if($row['estado'] == 2){echo 'Cerrada';}else if($row['estado'] == 4){echo 'Cuenta por cobrar';}?></td>      

     
    <td>
      <span id="estadoFE-<?php echo $row['numero']?>">
      <?php 
      if ($row['claveFE'] == ""){
        echo 'No es F.E.';
      }else if ($row['estadoFE'] == 4){
        echo '<a href="javascript: void(0);" onclick="tokenR('.$row['numero'].','.$row['sistema'].')">No enviada</a>';
      }else if ($row['estadoFE'] == 1){
        echo 'Aceptada';
      }else if($row['estadoFE'] == 2){
        echo '<a href="javascript: void(0);" onclick="tokenR('.$row['numero'].','.$row['sistema'].')">Procesando</a>'; $listaProcesando .= $row['numero'].'-';
      }else if($row['estadoFE'] == 3){
        echo 'Rechazada';
      }else if($row['estadoFE']==0){
       echo '<a href="javascript: void(0);" onclick="tokenR('.$row['numero'].','.$row['sistema'].')">Enviando...</a>';$listaProcesando .= $row['numero'].'-';
     }?>
     </span>
   </td>

     <td width="110">
      <?php if ($row['claveFE'] != ''){
        $nombre_fichero="../parametros/archivos/firmados/".$row['numero']."-".$row['sistema']."-firmada.xml";
        if(file_exists($nombre_fichero)){
        ?>
      <a href="javascript:void(0)" onclick="javascript: previewXML('<?php echo $nombre_fichero?>','1')" class="btn btn-sm btn-success pull-left" style="margin-left: 3px;"><i class="entypo-download"></i></a>
      <?php }?>
      <?php  $nombre_fichero="../parametros/archivos/respuestas/".$row['numero']."-".$row['sistema']."-resp.xml";
        if(file_exists($nombre_fichero)){?>
      <a href="javascript:void(0)" onclick="previewXML('<?php echo $nombre_fichero?>','2')" class="btn btn-sm btn-success pull-left" style="margin-left: 3px;"><i class="entypo-info"></i></a>
      <?php }?>

      <?php } ?>
     </td> 
   
    <td>
      <?php
      $sql2 = "SELECT * FROM tparametros WHERE id = '".$row['sistema']."' ";
      $query2 = mysql_query($sql2, $conn);
      while($row2=mysql_fetch_assoc($query2)){
          if($row2['nomComercial']!=""){
            echo $row2['nomComercial'];
          }else{
            echo $row2['nombre'];
          }
      }
    ?>
    </td>

                 
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





<script type="text/javascript" src="facturasGeneral.js"></script>

<?php 
if ($listaProcesando != ''){ ?>
  <script type="text/javascript">
  <?php 
  $pendientes = explode("-", $listaProcesando);
  foreach ($pendientes as $pendiente) {
    if ($pendiente != ''){ ?>
      //tokenR('<?php echo $pendiente?>');
<?php }
  }  
  ?>



  </script>
  <?php 
}
?>


<script type="text/javascript">
  var ventana_ancho = $(window).width();
  if(ventana_ancho>=768){
    $(".page-container").addClass("sidebar-collapsed");
  }


function previewXML(xml,tipo){
  jQuery('#modal-9').modal('show', {backdrop: 'static'});
  
  document.getElementById("frameXML").src = 'previewXML.php?xml='+xml+'&tipo='+tipo;
}

</script>




<?php include("../includes/confirmaEliminar.php");?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>