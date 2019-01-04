<?php include("../conn/connpg.php");
//controlAcceso(39);
?>	
<?php 
if (isset($_GET['id'])){
  $id = $_GET['id'];
  $nombre = $_GET['nombre'];
  $sql = "SELECT * FROM tabonoscobrar WHERE numeroFactura = '$id' AND estado = 1";
  $query = mysql_query($sql, $conn);
  if (mysql_num_rows($query) == 0){
    
    $sql = "UPDATE tcuentascobrar SET 
    estado = 2 
    WHERE numero = '$id'";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    $sql = "UPDATE tfacturas SET 
    estado = 2 
    WHERE numero = '$id'";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    //registrarBitacora($userid, "Eliminar ceunta por cobrar: ".$nombre, $conn);   
  }else{
    $error1 = 1;
  }
}


?>
<?php $pagina="Cuentas por cobrar"; include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
	<li><a href="#">Cuentas por cobrar</a></li>
</ol>

<h2>Cuentas por cobrar
  <a href="cuentasCobrarCanceladas.php" class="btn btn-default pull-right"><i class="entypo-plus"></i>Ver cuentas por cobrar canceladas</a>
</h2>
<hr />

<?php include("../includes/alerts.php");
if ($error1 == 1){ ?>
<script type="text/javascript">
  miAlerta('No se puede eliminar la cuenta porque tiene abonos. Debe eliminar primero los abonos.');
</script>
<?php }?>


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
        $sql = "SELECT * FROM tcuentascobrar WHERE sistema='".$cuentaid."' AND (nombreCliente like '%$bNombre%' OR numero like '%$bNombre%') AND  estado = 1 ORDER BY id DESC";  
        $mostrando = 'Mostrando el resultado de la búsqueda por nombre: <b>'.utf8_encode($bNombre).'</b> :: <a href="cuentasCobrar.php"><i class="entypo-cw"></i>Restablecer</a>'; 
      }else{
        $sql = "SELECT * FROM tcuentascobrar WHERE sistema='".$cuentaid."' AND estado = 1 ORDER BY id DESC"; 
      }
    }else{
      $sql = "SELECT * FROM tcuentascobrar WHERE sistema='".$cuentaid."' AND estado = 1 ORDER BY id DESC";  
    }
    $query = mysql_query($sql, $conn);

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i><br>
        No hay cuentas por cobrar registradas.
      </center>
      <?php
    }else{
      //mostramos la tabla con las editoriales 
      ?>

        <?php if ($mostrando != ''){echo '
        <div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}
        if ($bError != ''){echo '<div class="bError">'.$bError.'</div><hr>';}?>

<center>

<script type="text/javascript">
  var pagina = 'cuentasCobrar.php';
</script>

<div class="flechas"><i class="entypo-switch"></i></div>
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">

<table width="100%" class="table table-bordered responsive tablaGrande" >
<thead>
<tr>
<th width="97"></th>
<th>Número factura</th> 
<th>Nombre cliente</th> 
<th width="150">Fecha</th> 
<th>Monto</th>   
<th>Saldo</th>   
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
  	<a href="agregarAbono.php?<?php echo $busqueypagi.'&id='.$row['id'].'&numero='.$row['numero']?>" style="margin-right: 3px; margin-bottom: 3px;" class="btn btn-sm btn-default pull-left"><i class="entypo-list-add"></i></a>


    <a href="javascript:;" onclick="confirmBorrar('cuentasCobrar.php?<?php echo 'id='.$row['numero'].'&nombre='.htmlentities($row['nombreCliente'])?>','<?php echo htmlentities($row['nombreCliente'])?>');" class="btn btn-sm btn-danger pull-left"><i class="entypo-trash"></i></a>
  </td>
  <td><?php $numeroFactura = $row['numero'];echo addZero($row['numero']);?></td>  
  <td><?php echo $row['nombreCliente'];?></td> 
  <td><?php echo $row['fechaFactura'];?></td>     
  <td><?php echo $row['monto'];?></td> 
  <td><?php 
  $montoAbonos = 0;
  $saldo = $row['monto'];
  $sql = "SELECT * FROM tabonoscobrar WHERE numeroFactura = '$numeroFactura' AND estado = 1";
  $query = mysql_query($sql, $conn);
  while ($row=mysql_fetch_assoc($query)) {
    $montoAbonos += $row['monto'];
  }

  echo $saldo = $saldo - $montoAbonos;
  ?></td> 
</tr>
<?php
}
?>
</tbody>
</table>
</div>
<i>Simbología: <i class="entypo-list-add"></i> Agregar un abono 
<?php
include("../includes/paginationBottom.php");
}
?>
<?php include("../includes/confirmaEliminar.php");?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>