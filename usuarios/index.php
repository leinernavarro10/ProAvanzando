<?php include ("../conn/connpg.php");

controlAcceso('U-4');
?>

<?php
$pagina="Usuarios";
$skin='green';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>

     <ol class="breadcrumb bc-2">
  <li>
    <a href="<?php echo base_url();?>"><i class="fa-home"></i>Home</a>
  </li>
  <li class="active">
    <strong>Usuarios</strong>
  </li>
</ol>  
    
     <?php if (isset($_GET['i'])){	?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;El usuario <strong><?php echo $_GET['i']?></strong> se guardó correctamente.</span>
    </div>
    <?php }
	if (isset($_GET['eli']) and $_GET['eli'] == 1){	?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;Se ha realizado la eliminación correctamente.</span>
    </div>
    <?php }
	if (isset($_GET['eli']) and $_GET['eli'] == 2){	?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;Se ha reseteado la contraseña del afiliado correctamente.</span>
    </div>
    <?php }?>
	
  <h2><?php echo $pagina?> <?php if(revisarPrivilegios('U-2')){ ?><a href="../usuarios/nuevo.php" class="btn btn-primary pull-right"><i class="entypo-plus"></i> Nuevo Usuario</a><?php }?></h2>
<br />

<form autocomplete="off" action="" method="get">
    <div class="form-group">
      <div class="col-md-3 pull-right">
        <div class="input-group">
          <input type="text" class="form-control" name="bNombre" placeholder="Buscar" required>
          <span class="input-group-btn">
            <button class="btn btn-blue" type="submit">Buscar</button>
          </span>
        </div>
      </div>
    </div>
</form>
 <?php 
    if ($_GET['bNombre']!=""){
        $bNombre = $_GET['bNombre'];
        $sql = "SELECT  tusuarios.id, tusuarios.usuario, tpersonas.nombre, tpersonas.identificacion FROM tusuarios, tpersonas WHERE tpersonas.id=tusuarios.idPersona AND (tpersonas.nombre like '%$bNombre%' OR tusuarios.usuario like '%$bNombre%' OR tpersonas.identificacion like '%$bNombre%') ORDER BY tpersonas.nombre ASC";  
        $mostrando = 'Mostrando el resultado de la búsqueda por: <b>'.$bNombre.'</b> :: <a href="../usuarios"><i class="entypo-cw"></i>Restablecer</a>'; 
    }else{
     $sql = "SELECT tusuarios.id, tusuarios.usuario, tpersonas.nombre, tpersonas.identificacion FROM tusuarios, tpersonas WHERE tpersonas.id=tusuarios.idPersona ORDER BY tpersonas.nombre ASC";
    }
    $query = mysql_query($sql, $conn)or die(mysql_error());

    if (mysql_num_rows($query) == 0){
      ?>
      <center><br>
        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}?>
        <i class="entypo-info-circled"></i>
        No hay usuarios registradas.
      </center>
      <?php
    }else{
      
      ?>

        <?php if ($mostrando != ''){echo '<div class="alert alert-info"><center><strong>Búsqueda:</strong>'.$mostrando.'</center></div>';}
        if ($bError != ''){echo '<div class="bError">'.$bError.'</div><hr>';}?>

<center>



  <div class="flechas"><i class="entypo-switch"></i></div>
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">

<table width="100%" class="table table-condensed table-bordered table-hover table-striped">
<thead>
<tr>
<th width="25px"></th>
<th>Identificacion</th>
<th>Nombre</th> 
<th>Usuario</th>
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
  <td><?php if(revisarPrivilegios('U-1')){?><a href="editar.php?<?php echo 'id='.$row['id']?>" style="margin-right: 3px; margin-bottom: 3px;" class="btn btn-sm btn-success"><i class="entypo-pencil"></i></a><?php }?>
  </td>
  <td>
  <?php echo $row['identificacion'];?>
  </td>
  <td>
  <?php echo $row['nombre'];?>
  </td>
  <td>
  <?php echo $row['usuario'];?>
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


  <?php
include("../includes/footer.php");
?>