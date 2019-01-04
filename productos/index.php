<?php include("../conn/connpg.php");?> 
<?php 


if (isset($_GET['id'])){
    $idProduto = $_GET['id'];
    $nombre = html_entity_decode($_GET['nombre']);
    $sql = "UPDATE tproductos SET 
    estado = 2 
    WHERE id = '$idProduto' AND sistema = '".$cuentaid."'";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    //registrarBitacora($userid, "Eliminar producto: (".$idProducto.")".$nombre, $conn);   
}
?>
<?php $pagina="Productos"; include("../includes/header.php");?>       
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
  <li><a href="../inicio/"><i class="fa-home"></i>Inicio</a></li>
  <li><a href="#">Productos</a></li>
</ol>

<h2>Productos

<a href="nuevoProducto.php" class="btn btn-primary pull-right">
      <i class="entypo-plus"></i>
      
    </a>
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
    <br><br>
    <?php 
    if (isset($_GET['bNombre'])){
      if ($_GET['bNombre'] != ""){
        $bNombre = utf8_decode($_GET['bNombre']);
        $sql = "SELECT * FROM tproductos WHERE (nombre like '%$bNombre%' OR id = '$bNombre' OR codigo = '$bNombre') AND  estado = 1 AND sistema = '".$cuentaid."' ";  
        $mostrando = 'Mostrando el resultado de la búsqueda por: <b>'.utf8_encode($bNombre).'</b> :: <a href="productos.php"><i class="entypo-cw"></i>Restablecer</a>'; 
      }else{
        $sql = "SELECT * FROM tproductos WHERE estado = 1 AND sistema = '".$cuentaid."' "; 
      }
    }else{
      $sql = "SELECT * FROM tproductos WHERE estado = 1 AND sistema = '".$cuentaid."' ";  
    }
    $query = mysql_query($sql, $conn);

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i><br>
        No hay productos registrados.
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


<table width="100%" class="table table-bordered responsive tablaGrande" >
<thead>
<tr>
<th width="100"></th>
<th width="100">Código</th> 
<th>Nombre</th>
<th width="75">Exist.</th>  
<th width="150">Precio Venta</th>
<th width="150">Tipo.</th>  
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
  
    <a href="editarProducto.php?<?php echo $busqueypagi.'&id='.$row['id']?>" style="margin-right: 3px; margin-bottom: 3px;" class="btn btn-sm btn-default pull-left"><i class="entypo-pencil"></i></a>
  
    <a href="javascript:;" onclick="confirmBorrar('../productos/?<?php echo 'id='.$row['id'].'&nombre='.$row['nombre']?>','<?php echo $row['nombre']?>');" class="btn btn-sm btn-danger pull-left"><i class="entypo-trash"></i></a>
   
  </td>
  <td><?php echo $row['codigo']?></td>
  <td><?php echo $row['nombre']?></td>
  <td><?php echo $row['existencia']?></td>
  <td><?php
        if ($row['excento'] == 1){
          $precioVenta=$row['precioVenta'];
        }else{
          $precioVenta=($row['precioVenta']*0.13)+$row['precioVenta'];
        }



  $precioVenta = $precioVenta+$row['transporte']; 
  echo number_format($precioVenta,2)?></td>
  
  <td><?php 
  if ($row['tipo'] == 0){echo 'Prod.';}else{echo 'Serv.';}?></td>                 
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
<?php include("../includes/confirmaEliminar.php");?>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>