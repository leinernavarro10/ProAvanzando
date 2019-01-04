
<?php include("../conn/connpg.php");

$pagina="Inicio";
$skin='green';
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
    background-image: url("../assets/images/abajo.png") ;
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

<div class="row"> 
  <div class="col-sm-3 col-xs-6"> 
    <div class="tile-stats tile-red"> 
      <div class="icon"><i class="entypo-users"></i></div> 
      <div class="num" data-start="0" data-end="83" data-postfix="" data-duration="1500" data-delay="0">83</div> 

      <h3>Registered users</h3>
        <p>so far in our blog, and our website.</p> 
    </div> 
  </div> 

  <div class="col-sm-3 col-xs-6"> 
    <div class="tile-stats tile-green"> 
      <div class="icon"><i class="entypo-chart-bar"></i></div> 
      <div class="num" data-start="0" data-end="135" data-postfix="" data-duration="1500" data-delay="600">135</div> 
      <h3>Daily Visitors</h3> 
      <p>this is the average value.</p> 
    </div> 
  </div> 
  <div class="clear visible-xs"></div>

  <div class="col-sm-3 col-xs-6"> 
    <div class="tile-stats tile-aqua"> 
      <div class="icon"><i class="entypo-mail"></i></div> 
      <div class="num" data-start="0" data-end="23" data-postfix="" data-duration="1500" data-delay="1200">23</div>
      <h3>New Messages</h3> 
      <p>messages per day.</p> 
    </div> 
  </div> 
  <div class="col-sm-3 col-xs-6"> 
    <div class="tile-stats tile-blue"> 
      <div class="icon"><i class="entypo-rss"></i></div> 
      <div class="num" data-start="0" data-end="52" data-postfix="" data-duration="1500" data-delay="1800">52</div> 
      <h3>Subscribers</h3> 
      <p>on our site right now.</p> 
    </div> 
  </div> 
</div>
<hr>
  <h3>Menú</h3>
<hr>
<div class="row"> 
  <div class="col-sm-3"> 
    <div class="tile-title tile-primary"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-shopping-cart"></i> 
      </div> 
      <div class="title"> 
        <h3>Facturación</h3> 
        <p></p>
      </div> 
    </div> 
  </div> 
<?php if(revisarPrivilegios('C-0') OR revisarPrivilegios('C-5') OR revisarPrivilegios('C-6') OR revisarPrivilegios('C-4')){?>
  <div class="col-sm-3"> 
    <a href="../contabilidad">
    <div class="tile-title tile-blue"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-signal"></i> 
      </div> 
      <div class="title"> 
        <h3>Contabilidad</h3> 
        <p></p> 
      </div> 
    </div> 
  </a>
  </div> 
<?php }?>
  <div class="col-sm-3"> 
    <div class="tile-title tile-pink"> 
      <div class="icon"> 
        <i class="glyphicon glyphicon-briefcase"></i> 
      </div> 
      <div class="title"> 
        <h3>Proveeduría</h3> 
        <p></p> 
      </div> 
    </div> 
  </div> 
  <div class="col-sm-3"> 
    <div class="tile-title tile-plum"> 
      <div class="icon"> 
        <i class="entypo-traffic-cone"></i> 
      </div> 
      <div class="title"> 
        <h3>Producción</h3> 
        <p></p> 
      </div> 
    </div> 
  </div> 
</div>
<!-- FIN CONTENIDO -->



<?php 

 
include("../includes/footer.php");
?>


