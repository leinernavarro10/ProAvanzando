<?php include ("../conn/connpg.php");

controlAcceso('U-2');

if (isset($_POST['guardar'])){
	if ($_POST['password'] == $_POST['password2']){	
    
		$sql = "INSERT INTO tusuarios VALUES( 
		null, 
		'".$_POST['id']."',
		'".$_POST['usuario']."',
		'".sha1($_POST['password'])."', 
		'".$_POST['estado']."',
		'".$_POST['userTipo']."'
		)";
		$query = mysql_query($sql,$conn)or die(mysql_error());
		$idU=mysql_insert_id();

	  $target_path = "image/";
    $target_path = $target_path.rand().".jpg"; 

    if(move_uploaded_file($_FILES['imgSU']['tmp_name'], $target_path)) { 
      $sql = "UPDATE tpersonas SET 
      foto = '".$target_path."' 
      WHERE id = '".$_POST['id']."'";  
      $query = mysql_query($sql,$conn)or die(mysql_error());
    }

    


		print "<meta http-equiv=\"refresh\" content=\"0;URL=../usuarios?i=".$_POST['nombre']."\">";
		exit();
	}else{
		?>
			<script type="text/javascript">
            	alert('Las contraseñas no son iguales');
				history.go(-1);
            </script>
		<?php 	
	}
}
?>


<?php $pagina="Nuevo Usuario"; 
$skin='green';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>
<style>
  #pswd_info {
    position: absolute;
    top: 30px;
    right: 0px;
    width: 250px;
    padding: 15px;
    background: #fefefe;
    font-size: .875em;
    border-radius: 5px;
    box-shadow: 0 1px 3px #ccc;
    border: 1px solid #ddd;
    z-index: 1500;
  }
  #pswd_info h4 {
    margin: 0 0 10px 0;
    padding: 0;
    font-weight: normal;
  }
  #pswd_info::before {
    content: "\25B2";
    position: absolute;
    top: -12px;
    left: 45%;
    font-size: 14px;
    line-height: 14px;
    color: #ddd;
    text-shadow: none;
    display: block;
  }
  .invalid {
   
    padding-left: 22px;
    line-height: 24px;
    color: #ec3f41;
  }
  .valid {
    
    padding-left: 22px;
    line-height: 24px;
    color: #3a7d34;
  }
  #pswd_info {
    display: none;
  }  
</style>
  <ol class="breadcrumb bc-2">
  <li>
    <a href="<?php echo base_url();?>"><i class="fa-home"></i>Home</a>
  </li>
   <li>
    <a href="../usuarios"><i class="fa-home"></i>Usuarios </a>
  </li>
  <li class="active">
    <strong>Nuevo Usuario</strong>
  </li>
</ol>  
  <h2><?php echo $pagina?></h2>
      <form action="" method="post" name="f1" enctype="multipart/form-data">
<div class="row">
      <div class="col-md-6">
        <div class="panel panel-dark" id="charts_env">
          <div class="panel-heading">
            <div class="panel-title">Información usuario</div>
          </div>
          <div class="panel-body">

              <div class="form-group"> 
                <label for="usuario" class="col-sm-3 control-label">Nombre:</label> 
                <div class="col-sm-9">
                  <input type="hidden" name="id" id="id"/> 
                  <input type="text" name="nombre" id="nombre" readonly="" class="form-control" onclick="verpersona(1);" />
                </div> 
              </div>

              <div class="form-group"> 
                <label for="usuario" class="col-sm-3 control-label">Usuario:</label> 
                <div class="col-sm-9"> 
                  <input type="text" name="usuario" id="usuario" class="form-control"/>
                </div> 
              </div>

              <div class="form-group"> 
                <label for="password" class="col-sm-3 control-label">Contraseña:</label> 
                <div class="col-sm-9"> 
                  <input type="password" name="password" id="password" class="form-control"/>
                     <div id="pswd_info">
                      <h4>La contraseña debe cumplir los siguientes requisitos:</h4>
                      <ul>
                        <li id="letter" class="invalid">Letras <strong>minuscula</strong>
                        </li>
                        <li id="capital" class="invalid">Letras <strong>mayuscula</strong>
                        </li>
                        <li id="number" class="invalid"><strong>Numeros</strong>
                        </li>
                        <li id="length" class="invalid">Mas de <strong>8 caracteres</strong>
                        </li>
                      </ul>
                    </div>
                </div> 
              </div>
              
               <div class="form-group"> 
                <label for="password2" class="col-sm-3 control-label">Repita la contraseña:</label> 
                <div class="col-sm-9"> 
                   <input type="password" name="password2" id="password2" class="form-control"/>
                 <span id="ErrorRec"></span>
                </div> 
              </div>
      .        
<hr>
              <?php if(revisarPrivilegios('U-1')){?>
                <div class="form-group"> 
                  <label for="estado" class="col-sm-3 control-label">Estado Usuario:</label> 
                  <div class="col-sm-9"> 
                      <select name="estado" id="estado" class="form-control">
                        <option value="1">Activo</option>
                        <option value="2">Desactivado</option>
                        <option value="3" disabled > Pendiente de activacion</option>
                      </select>
                  </div> 
                </div>
              <?php }?>
            
          </div>
        </div>
      </div>
    
    <div class="col-md-6">
    
        <div class="panel panel-dark">
          <div class="panel-heading">
            <div class="panel-title">
              Foto
            </div>
          </div>
    
          <div class="panel-body ">
            
                <center>
                  
                  <div class="fileinput fileinput-new" data-provides="fileinput"><input type="hidden">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
                     
                      <img src="http://placehold.it/200x200" alt="...">
                      
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 10px;"></div>
                    <div>
                      <span class="btn btn-white btn-file">
                        <span class="fileinput-new">Select image</span>
                        <span class="fileinput-exists">Change</span>
                        <input name="imgSU" accept="image/*" type="file">
                      </span>
                      <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                  </div>
             </center>
        </div>
      </div>

        <div class="panel panel-dark">
          <div class="panel-heading">
            <div class="panel-title">
              Privilegios 
            </div>
          </div>
    
          <div class="panel-body">
            <?php 
                  $sqlt="SELECT * FROM tusuariostipos ORDER BY id ASC";
                  $queryt=mysql_query($sqlt,$conn);
                  if(mysql_num_rows($queryt)){
                    ?>
                    <table class="table responsive"> 
                        <thead> 
                          <tr> 
                            <th></th> 
                            <th>Nombre</th> 
                            <th>Descripción</th>
                          </tr> 
                        </thead> 
                        <tbody> 
                      <?php
                      while($rowt=mysql_fetch_assoc($queryt)){?>
                          <tr>
                            <td><input type="radio" name="userTipo" id="<?php echo $rowt['id'];?>" value="<?php echo $rowt['id'];?>" <?php if ($rowt['id']==$row['idUsuariotipo']){ echo "checked";}?>></td> 
                            <td>
                              <label for="<?php echo $rowt['id'];?>">
                                <?php echo $rowt['nombre'];?>
                        </label>
                      </td> 
                            <td><?php echo $rowt['descripcion'];?></td> 
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

                <center>
          <input type="submit" name="guardar" class="btn btn-success" value="Guardar" />
          <input type="button" class="btn btn-red" value="Cancelar" onClick="document.location.href='usuarios.php'" /></center>
        </div>
      </div>
    </div>
</div>   
	</form>
    <script type="text/javascript">
      

      function agregarNuevoUsuario(id,nombre){
        $("#id").val(id);
        $("#nombre").val(nombre);
      }
    </script>
  

<script src="../assets/js/fileinput.js"></script>
<?php
include("../includes/footer.php");
?>