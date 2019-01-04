<?php include ("../conn/connpg.php");

controlAcceso('C-8');

if($_GET['e']==1 OR $_GET['e']==2){

    $sql = "UPDATE cmonedas SET 
    estado = '".$_GET['e']."' 
    WHERE id = '".$_GET['id']."' ";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-monedas.php?i1=".$periodo."\">";
    exit();
}


if (isset($_POST['nuevo'])){
  
  

    $sqlVER="SELECT * FROM cmonedas WHERE nombre='".$_POST['nombre']."' ";
    $queryVER=mysql_query($sqlVER,$conn)or die(mysql_error());
    if(mysql_num_rows($queryVER)==0){
      $sql3 = "INSERT INTO cmonedas VALUES( 
        null, 
        '".$_POST['nombre']."',
        '".$_POST['codigo']."',
        '".$_POST['valor']."',
        '2'
        )";
      $query3 = mysql_query($sql3,$conn)or die(mysql_error());
      print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-monedas.php?i=".$_POST['nombre']."\">";
    }else{
      print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-monedas.php?i2=".$_POST['nombre']."\">";
    }

    exit();

}

?>


<?php
$pagina="Monedas";
$skin='facebook';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>
<style type="text/css">
  .hover:hover{
    cursor: pointer;
    color: #959595;
    background: #C7C6C6;
  }
</style>
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
  <?php if (isset($_GET['i'])){ ?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;El centro de costo <strong><?php echo $_GET['i']?></strong> se guardó correctamente.</span>
    </div>
  <?php }?>

  <?php if (isset($_GET['i2'])){ ?>
    <div class="alert alert-danger">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;El centro de costo <strong><?php echo $_GET['i2']?></strong> no se puede guardar porque ya existe uno igual</span>
    </div>
  <?php }?>

  <?php if (isset($_GET['i1'])){ ?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;Se realizo el cambio de estado correctamente.</span>
    </div>
  <?php }?>
 <h2><?php echo $pagina?></h2>
<div class="row"> 
  <div class="col-sm-5"> 
    <div class="panel panel-default panel-shadow" data-collapsed="0">
      <div class="panel-heading"> 
        <div class="panel-title">Nuevo <?php echo $pagina?></div> 

      </div>

      <div class="panel-body"> 
        <form action="" method="post">
          <div class="form-group"> 
             <input type="text" class="form-control" name="nombre" placeholder="Nombre" required> 
          </div>
          <div class="form-group"> 
              <input type="text" class="form-control" name="codigo" placeholder="Codigo"> 
          </div>
          <div class="form-group"> 
              <input type="text" class="form-control" name="valor" placeholder="Valor en colones"> 
          </div>
          <center>
            <button type="submit" name="nuevo" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-danger" onclick="document.location.href='conf-centrocostos.php'">Limpiar</button>
          </center>
        </form>



      </div>
    </div>
  </div>
  <div class="col-sm-7">
      <div class="panel panel-default panel-shadow" data-collapsed="0">
      <div class="panel-heading"> 
        <div class="panel-title"><?php echo $pagina?></div> 

      </div>

      <div class="panel-body" style="height: 500px;overflow-y:scroll"> 
        
        <?php 
          $sql="SELECT * FROM cmonedas ORDER BY id ASC";
          $query=mysql_query($sql,$conn);
          if(mysql_num_rows($query)){
            ?><hr>
            <table class="table table-condensed table-bordered table-hover table-striped"> 
                <thead> 
                  <tr > 
                    <th></th>
                    <th style="text-align: center;">Nombre</th> 
                    <th >Código</th>
                    <th >Valor</th>
                    <th width="125px">Estado</th>
                  </tr> 
                </thead> 
                <tbody> 
              <?php
              while($row=mysql_fetch_assoc($query)){
              
              ?>
                  <tr class="hover" style="text-align: center;">
                    <td style="text-align: center;"><?php echo $row['id'];?></td> 
                    <td style="text-align: center;"><?php echo $row['nombre'];?></td>
                    <td style="text-align: center;"><?php echo $row['codigo'];?></td>  
                    <td><?php echo $row['valor'];?></td> 
                    <td style="text-align: center;"><?php switch ($row['estado']) {
                      case 1:
                        echo "Por defecto :: ";?>
                          <a href="conf-monedas.php?id=<?php echo $row['id']?>&e=2" class="btn btn-xs btn-success" title='Desactivar'><i class="glyphicon glyphicon-ok"></i></a>
                      <?php
                        break;
                      case 2:
                        echo "";
                        ?>
                          <a href="conf-monedas.php?id=<?php echo $row['id']?>&e=1" class="btn btn-xs btn-red" title='Activar'><i class="glyphicon glyphicon-remove"></i></a>
                      <?php
                        break;
                    }?>

                      
                    </td>
                  </tr>     
              <?php
              }?>
                </tbody> 
              </table>  
            <?php
          }else{
            echo "<center>No se encontraron datos</center>";
          }
        ?>

      </div>
    </div>

  </div>

</div>
  
  

 <!-- FIN CONTENIDO -->
<?php
include("../includes//footer.php");
?>