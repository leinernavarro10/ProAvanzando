<?php include ("../conn/connpg.php");




if (isset($_GET['id'])){
    $idCliente = $_GET['id'];
    $nombre = $_GET['nombre'];
    $sql = "UPDATE tclientes SET 
    estado = 2 
    WHERE id = '$idCliente'";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    //registrarBitacora($userid, "Eliminar cliente: ".$nombre, $conn);   
}

?>

<?php

 
$pagina="Personas";
$skin='green';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");
?>
<style type="text/css">
   .cars{
        width: 100%;
        position: absolute;
        background:#001E4F;
        color: #FFFFFF;
    }
        
   
@media(max-width: 416px){
  .esconder2{display: none;}
}
</style>




<ol class="breadcrumb bc-2">
  <li>
    <a href="<?php echo base_url();?>"><i class="fa-home"></i>Home</a>
  </li>
  <li class="active">
    <strong>Clientes</strong>
  </li>
</ol>  
    <h2><?php echo $pagina?></h2>

   <div id="frameEdit">
    
  </div>

<script type="text/javascript">
  function frameEdit(){
    $("#frameEdit").load("verpersonas.php");
  }
  frameEdit()
</script>

<?php
include("../includes/footer.php");
?>