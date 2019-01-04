<?php include ("../conn/connpg.php");

controlAcceso('U-1');
?>

<?php 
$pagina="Editar Usuario";
$skin='green';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>
  <ol class="breadcrumb bc-2">
  <li>
    <a href="<?php echo base_url();?>"><i class="fa-home"></i>Home</a>
  </li>
   <li>
    <a href="../usuarios"><i class="fa-home"></i>Usuarios </a>
  </li>
  <li class="active">
    <strong>Editar Usuario</strong>
  </li>
</ol>
 <h2><?php echo $pagina?></h2>

    <div class="alert alert-warning">
        <span><strong>!INFO!</strong> &nbsp;&nbsp;Para modificar sus datos personales diríjase al módulo <strong>PERSONAS</strong>.</span>
    </div>
	<div id="frameEdit">
		
	</div>

<script type="text/javascript">
	function frameEdit(){
		$("#frameEdit").load("modeditar.php?id=<?php echo $_GET['id']?>");
	}
	frameEdit()
</script>
 <script src="../assets/js/fileinput.js"></script>
 <?php
include("../includes/footer.php");
?>