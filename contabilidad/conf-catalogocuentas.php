<?php include ("../conn/connpg.php");

controlAcceso('C-2');

if($_GET['e']==1 OR $_GET['e']==2){

    $sql = "UPDATE ccatalogocuentas SET 
    estado = '".$_GET['e']."' 
    WHERE id = '".$_GET['id']."' ";
    $query = mysql_query($sql, $conn)or die(mysql_error());

    print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-catalogocuentas.php?i1=".$_GET['codigo']."\">";
    exit();
}


if (isset($_POST['nuevo'])){
  
  $codigo=$_POST['codigo1']."-".$_POST['codigo2']."-".$_POST['codigo3']."-".$_POST['codigo4'];

    $sqlVER="SELECT * FROM ccatalogocuentas WHERE codigo1='".$_POST['codigo1']."' AND codigo2='".$_POST['codigo2']."' AND codigo3='".$_POST['codigo3']."'AND codigo4='".$_POST['codigo4']."'  ";
    $queryVER=mysql_query($sqlVER,$conn)or die(mysql_error());
    if(mysql_num_rows($queryVER)==0){
      $sql3 = "INSERT INTO ccatalogocuentas VALUES( 
        null, 
        '".$_POST['codigo1']."',
        '".$_POST['codigo2']."',
        '".$_POST['codigo3']."',
        '".$_POST['codigo4']."',
        '".$_POST['descripcion']."',
        '1'
        )";
      $query3 = mysql_query($sql3,$conn)or die(mysql_error());
      print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-catalogocuentas.php?i=".$codigo."\">";
    }else{
      print "<meta http-equiv=\"refresh\" content=\"0;URL=conf-catalogocuentas.php?i2=".$codigo."\">";
    }

    exit();

}

?>


<?php
$pagina="Catalogo de cuentas";
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
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;La cuenta codigo <strong><?php echo $_GET['i']?></strong> se guardó correctamente.</span>
    </div>
  <?php }?>

  <?php if (isset($_GET['i2'])){ ?>
    <div class="alert alert-danger">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;La cuenta codigo <strong><?php echo $_GET['i2']?></strong> no se puede guardar porque ya existe uno igual</span>
    </div>
  <?php }?>

  <?php if (isset($_GET['i1'])){ ?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;Se realizo el cambio de estado correctamente.</span>
    </div>
  <?php }?>
 <h2><?php echo $pagina?></h2>
<div class="row"> 
  
  <div class="col-sm-8">
      <div class="panel panel-default panel-shadow" data-collapsed="0">
      <div class="panel-heading"> 
        <div class="panel-title"><?php echo $pagina?></div> 

      </div>


      
      <div class="panel-body" > 
      
      <input type="text" name="buscar" id="buscar" placeholder="buscar" class="form-control" onkeyup="vercatalogo(this.value)">

        <div style="height: 485px;overflow-y:scroll" id='idcatalogo'>
          <center><img src='../assets/images/cargando.gif' width=''>Cargando...</center>
        </div>
      </div>


    </div>

  </div>
  
  
  <div class="col-sm-4"> 
    <div class="panel panel-dark panel-shadow" data-collapsed="0">
      <div class="panel-heading" style="background: "> 
        <div class="panel-title">Nuevo <?php echo $pagina?></div> 

      </div>

      <div class="panel-body"> 
        <form action="" method="post">
          <div class="form-group"> 
               <div class="col-sm-3">
                <input type="text" class="form-control" name="codigo1" id="codigo1" placeholder="000" maxlength="3" required onkeyup="vercuentaCO()"  autocomplete="off">
               </div>
               <div class="col-sm-3">
                <input type="text" class="form-control" name="codigo2" id="codigo2"  placeholder="000" maxlength="3" required onkeyup="vercuentaCO()"  autocomplete="off">
               </div>
               <div class="col-sm-3">
                <input type="text" class="form-control" name="codigo3" id="codigo3"  placeholder="000" maxlength="3" required onkeyup="vercuentaCO()"  autocomplete="off">
               </div>
               <div class="col-sm-3">
                <input type="text" class="form-control" name="codigo4" id="codigo4"  placeholder="000" maxlength="3" required onkeyup="vercuentaCO()"  autocomplete="off">
               </div>

          </div>
          <div id="VerCuenta" style="font-weight: 700">
            <center>
              <i class="entypo-info-circled"></i> Esperando datos.
            </center>
          </div>
          <div class="form-group"> 
              <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required> 
          </div>
          <center>
            <button type="submit" name="nuevo" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-danger" onclick="document.location.href='conf-centrocostos.php'">Limpiar</button>
          </center>
        </form>



      </div>
    </div>
  </div>


</div>
  
<script type="text/javascript">
  function vercuentaCO(){
      var codigo1=$("#codigo1").val();
      var codigo2=$("#codigo2").val();
      var codigo3=$("#codigo3").val();
      var codigo4=$("#codigo4").val();

        $.post("ajax/vercuentas.php", {codigo1:codigo1,codigo2:codigo2,codigo3:codigo3,codigo4:codigo4}).done(function(data) {
         if(data!=""){
          $("#VerCuenta").html(data);
         }
          
        });
  }
  


  function vercatalogo(val){
    $("#idcatalogo").html("<center><img src='../assets/images/cargando.gif' width=''>Cargando...</center>");
        $.post("ajax/vercatalogo.php", {val:val}).done(function(data) {
        
          $("#idcatalogo").html(data);
        
          
        });
  }
  vercatalogo('');
  $("#buscar").focus();
</script>


 <!-- FIN CONTENIDO -->
<?php
include("../includes//footer.php");
?>