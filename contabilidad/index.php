
<?php include("../conn/connpg.php");

$pagina="Contabilidad";
$skin='facebook';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");

function getRealIP() {
if (!empty($_SERVER['HTTP_CLIENT_IP']))
return $_SERVER['HTTP_CLIENT_IP'];

if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
return $_SERVER['HTTP_X_FORWARDED_FOR'];

return $_SERVER['REMOTE_ADDR'];
}


?>


		
<?php 
if($_GET['pin']==1){  ?>
<div class="alert alert-success"><strong>Exito!</strong> Se registró el pin con éxito.</div>
  <?php } ?>

<?php 
if($_GET['pin']==2){  ?>
<div class="alert alert-warning"><strong>Alerta!</strong> Esta red no es segura. No tiene pin de acceso rápido.</div>
  <?php }?>
<style type="text/css">
  .well {
    background-image: url("../assets/images/abajoConta.png") ;
    background-position: bottom right;
    background-repeat: no-repeat;
}

</style>


    <div class="row">
      <div class="col-sm-12">
        <div class="well">
          <h3><?php echo date("d")?> de <?php 
            $m = date("m");
            switch ($m) {
              case '01':
                echo 'Enero';
              break;
              case '02':
                echo 'Febrero';
              break;
              case '03':
                echo 'Marzo';
              break;
              case '04':
                echo 'Abril';
              break;
              case '05':
                echo 'Mayo';
              break;
              case '06':
                echo 'Junio';
              break;
              case '07':
                echo 'Julio';
              break;
              case '08':
                echo 'Agosto';
              break;
              case '09':
                echo 'Septiembre';
              break;
              case '10':
                echo 'Octubre';
              break;
              case '11':
                echo 'Noviembre';
              break;
              case '12':
                echo 'Diciembre';
              break;
            }


            ?>, <?php echo date("Y")?></h3>
          <h4>Bienvenido(a) a <strong><?php echo $nombreProyecto?></strong></h4>
        </div>
      </div>
    </div>

<hr>
  <h3>Bienvenido al módulo de contabilidad</h3>
<hr>

<div class="row"> 
  <div class="col-sm-3"> 
    <div class="tile-title tile-primary"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-list-alt"></i> 
      </div> 
      <div class="title"> 
        <h3>Asientos Contables</h3> 
        <p></p>
      </div> 
    </div> 
  </div> 



  <div class="col-sm-3"> 
    <a href="../contabilidad">
    <div class="tile-title tile-primary"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-briefcase"></i> 
      </div> 
      <div class="title"> 
        <h3>Cuentas por pagar</h3> 
        <p></p> 
      </div> 
    </div> 
  </a>
  </div> 

  <div class="col-sm-3"> 
    <div class="tile-title tile-primary"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-bitcoin"></i> 
      </div> 
      <div class="title"> 
        <h3>Bancos</h3> 
        <p></p> 
      </div> 
    </div> 
  </div> 
  <div class="col-sm-3"> 
    <div class="tile-title tile-primary"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-save-file"></i> 
      </div> 
      <div class="title"> 
        <h3>Reportes</h3> 
        <p></p> 
      </div> 
    </div> 
  </div> 
</div>
<!-- FIN CONTENIDO -->



<?php 

 
include("../includes/footer.php");
?>


