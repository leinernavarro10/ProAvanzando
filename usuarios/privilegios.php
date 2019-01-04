<?php include ("../conn/connpg.php");

controlAcceso('U-5');

if (isset($_POST['nuevo'])){
	

    $sql3 = "INSERT INTO tusuariostipos VALUES( 
      null, 
      '".$_POST['nombre']."',
      '".$_POST['descripcion']."'
      )";
    $query3 = mysql_query($sql3,$conn)or die(mysql_error());

		print "<meta http-equiv=\"refresh\" content=\"0;URL=privilegios.php?i=".$_POST['nombre']."\">";
		exit();
}
?>


<?php
$pagina="Privilegios";
$skin='green';
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
    <a href="../usuarios">Usuarios </a>
  </li>
   
  <li class="active">
    <strong>Privilegios</strong>
  </li>
</ol>  
  <?php if (isset($_GET['i'])){ ?>
    <div class="alert alert-success">
        <span><strong>!Éxito!</strong> &nbsp;&nbsp;El tipo de privilegio <strong><?php echo $_GET['i']?></strong> se guardó correctamente.</span>
    </div>
  <?php }?>
 <h2><?php echo $pagina?></h2>
<div class="row"> 
  <div class="col-sm-5"> 
    <div class="panel panel-default panel-shadow" data-collapsed="0">
      <div class="panel-heading"> 
        <div class="panel-title">Tipos Usuarios</div> 

      </div>

      <div class="panel-body"> 
        <form action="" method="post">
          <div class="form-group"> 
              <input type="text" class="form-control" name="nombre" placeholder="Nombre" required> 
          </div>
          <div class="form-group"> 
              <input type="text" class="form-control" name="descripcion" placeholder="Descripción"> 
          </div>
          <button type="submit" name="nuevo" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-danger" onclick="document.location.href='privilegios.php'">Limpiar</button>
        </form>

        <?php 
          $sql="SELECT * FROM tusuariostipos ORDER BY id ASC";
          $query=mysql_query($sql,$conn);
          if(mysql_num_rows($query)){
            ?><hr>
            <table class="table responsive"> 
                <thead> 
                  <tr > 
                    <th>Nombre</th> 
                    <th>Descripción</th>
                  </tr> 
                </thead> 
                <tbody> 
              <?php
              while($row=mysql_fetch_assoc($query)){
                $nombre=$row['nombre'];
              ?>
                  <tr class="hover" onclick="cargarPrivilegios(<?php echo $row['id'];?>,'<?php echo $nombre;?>');"> 
                    <td><?php echo $row['nombre'];?></td> 
                    <td><?php echo $row['descripcion'];?></td> 
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
  <div class="col-sm-7">
      <div class="panel panel-default panel-shadow" data-collapsed="0">
      <div class="panel-heading"> 
        <div class="panel-title">Privilegios <span id="privilegiosCArgador" style="display: inline-block;"></span></div> 

      </div>

      <div class="panel-body" style="height: 500px;overflow-y:scroll"> 
        <div id="privilegiosCArgador"></div>
         <div id="privilegios"></div>

      </div>
    </div>

  </div>

</div>
  
  <script type="text/javascript">
      function cargarPrivilegios(id,nombre){
        $("#privilegiosCArgador").html("<center><img src='../assets/images/cargando.gif' width='15px'></center>");
        $.post("ajax/verprivilegios.php", {id: id,nombre:nombre}).done(function(data) {
          $("#privilegios").html(data);

        $("#privilegiosCArgador").html("");
        });
      }

      function cambiarEstado(val,id){
        $("#privilegiosCArgador").html("<center><img src='../assets/images/cargando.gif' width='15px'></center>");

        if($(val).prop('checked')) {
          var f="Nuevo";
        }else{
          var f="Borrar";
        }
        var valor=$(val).val();

         $.post("ajax/guardarprivilegio.php", {f:f,id:id,valor:valor}).done(function(data) {
         // $("#privilegios").html(data);
         console.log(data);
          $("#privilegiosCArgador").html("");
        });
      }

  </script>


 <!-- FIN CONTENIDO -->
<?php
include("../includes//footer.php");
?>