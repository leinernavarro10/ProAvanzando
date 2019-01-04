<?php include ("../conn/connpg.php");

controlAcceso('C-1');

if($_GET['e']==1 OR $_GET['e']==2){

    $sql = "UPDATE cperiodos SET 
    estado = '".$_GET['e']."' 
    WHERE id = '".$_GET['id']."' ";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-periodos.php?i1=".$periodo."\">";
    exit();
}


if (isset($_POST['nuevo'])){
  
  $periodo=$_POST['mes'].'-'.$_POST['ano'];

    $sqlVER="SELECT * FROM cperiodos WHERE periodo='".$periodo."' ";
    $queryVER=mysql_query($sqlVER,$conn)or die(mysql_error());
    if(mysql_num_rows($queryVER)==0){
      $sql3 = "INSERT INTO cperiodos VALUES( 
        null, 
        '".$periodo."',
        '".$_POST['descripcion']."',
        '1'
        )";
      $query3 = mysql_query($sql3,$conn)or die(mysql_error());
      print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-periodos.php?i=".$periodo."\">";
    }else{
      print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-periodos.php?i2=".$periodo."\">";
    }

    exit();

}

?>


<?php
$pagina="Periodos";
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
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;El periodo <strong><?php echo $_GET['i']?></strong> se guardó correctamente.</span>
    </div>
  <?php }?>

  <?php if (isset($_GET['i2'])){ ?>
    <div class="alert alert-danger">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;El periodo <strong><?php echo $_GET['i2']?></strong> no se puede guardar porque ya existe uno igual</span>
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
            <div class="col-sm-5">
                <select name="mes" class="form-control" required>
                  <option value="">Seleccione...</option>
                 <option value="01">Enero</option>
                 <option value="02">Febrero</option>
                 <option value="03">Marzo</option>
                 <option value="04">Abril</option>
                 <option value="05">Mayo</option>
                 <option value="06">Junio</option>
                 <option value="07">Julio</option>
                 <option value="08">Agosto</option>
                 <option value="09">Septiembre</option>
                 <option value="10">Octubre</option>
                 <option value="11">Noviembre</option>
                 <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="col-sm-1">
              --
            </div>
            <div class="col-sm-5">
                <select name="ano" class="form-control" required>
                  <option value="">Seleccione...</option>
                  <?php for($ano=date('Y')-1;$ano<=date('Y')+1;$ano++){?>
                    <option value="<?php echo $ano;?>"><?php echo $ano;?></option>
                  <?php }?>
                </select>
            </div> 
          </div><br>
          <hr>
          <div class="form-group"> 
              <input type="text" class="form-control" name="descripcion" placeholder="Descripción"> 
          </div>
          <center>
            <button type="submit" name="nuevo" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-danger" onclick="document.location.href='conf-periodos.php'">Limpiar</button>
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
          $sql="SELECT * FROM cperiodos ORDER BY id DESC";
          $query=mysql_query($sql,$conn);
          if(mysql_num_rows($query)){
            ?><hr>
            <table class="table table-condensed table-bordered table-hover table-striped"> 
                <thead> 
                  <tr > 
                    <th style="text-align: center;">Periodo</th> 
                    <th >Descripción</th>
                    <th width="125px">Estado</th>
                  </tr> 
                </thead> 
                <tbody> 
              <?php
              while($row=mysql_fetch_assoc($query)){
              
              ?>
                  <tr class="hover" style="text-align: center;">
                    <td style="text-align: center;"><?php echo $row['periodo'];?></td> 
                    <td><?php echo $row['detalle'];?></td> 
                    <td style="text-align: center;"><?php switch ($row['estado']) {
                      case 1:
                        echo "Activo :: ";?>
                          <a href="conf-periodos.php?id=<?php echo $row['id']?>&e=2" class="btn btn-xs btn-red" title='Desactivar'><i class="glyphicon glyphicon-remove"></i></a>
                      <?php
                        break;
                      case 2:
                        echo "Desactivado :: ";
                        ?>
                          <a href="conf-periodos.php?id=<?php echo $row['id']?>&e=1" class="btn btn-xs btn-success" title='Activar'><i class="glyphicon glyphicon-ok"></i></a>
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