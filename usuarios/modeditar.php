<?php include ("../conn/connpg.php");

controlAcceso('U-1');

if($_GET['eliini']!=""){
	$sqlD="DELETE FROM tinicio WHERE id ='".$_GET['eliini']."' ";
	mysql_query($sqlD,$conn)or die(mysql_error());


		print "<meta http-equiv=\"refresh\" content=\"0;URL=../usuarios?i=".$_POST['nombre']."\">";
		exit();
}


if (isset($_POST['guardar'])){
	
	if ($_POST['password'] == $_POST['password2']){	
		
		

	
		if ($_POST['password'] != ''){
			$sql = "UPDATE tusuarios SET 
			usuario = '".$_POST['usuario']."', 
			pass = '".sha1($_POST['password'])."',
			estado='".$_POST['estado']."',
			idUsuariotipo='".$_POST['userTipo']."'
			WHERE id = '".$_POST['id']."'";
		}else{
			$sql = "UPDATE tusuarios SET 
			usuario = '".$_POST['usuario']."', 
			estado='".$_POST['estado']."',
			idUsuariotipo='".$_POST['userTipo']."'
			WHERE id = '".$_POST['id']."'";	
		}
		$query = mysql_query($sql,$conn)or die(mysql_error());
		$error="1";	
		$target_path = "image/";
		$target_path = $target_path.rand().".jpg"; 

		if(move_uploaded_file($_FILES['imgSU']['tmp_name'], $target_path)) { 
			
			$sql = "UPDATE tpersonas SET 
			foto = '".$target_path."' 
			WHERE id = '".$_POST['idPersona']."'";	
			$query = mysql_query($sql,$conn)or die(mysql_error());
			unlink($_POST['fotoAnt']);	


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
<style>
	#pswd_info {
	  position: absolute;
	  bottom: -15px;
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

    	<?php $sql = "SELECT * FROM tusuarios WHERE id = '".$_GET['id']."'";
		$query = mysql_query($sql,$conn);
		while ($row=mysql_fetch_assoc($query)){
		?>
<form action="modeditar.php" method="post" name="f1" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
		<input type="hidden" name="idPersona" value="<?php echo $row['idPersona']?>" />
<div class="row">
			<div class="col-md-6">
				<div class="panel panel-dark" id="charts_env">
					<div class="panel-heading">
						<div class="panel-title">Información usuario</div>
					</div>
					<div class="panel-body">
						<div class="tab-content">
		             
              Usuario: <br />
              <input type="text" name="usuario" id="usuario" class="form-control" value="<?php echo $row['usuario']?>"/>
              <br />
               
              Contraseña: <br />
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
              <p>Por favor repita contraseña: <br />
                <input type="password" name="password2" id="password2" class="form-control"/>
                <span id="ErrorRec"></span>
                <br />                
              </p>
             <?php if(revisarPrivilegios('U-1')){?>
              <p>Estado Usuario: <br />
                <select name="estado" class="form-control">
                	<option value="1" <?php if ($row['estado'] == 1){echo 'selected="selected"';}?>>Activo</option>
                	<option value="2" <?php if ($row['estado'] == 2){echo 'selected="selected"';}?>>Desactivado</option>
                	<option value="3" disabled <?php if ($row['estado'] == 3){echo 'selected="selected"';}?>> Pendiente de activacion</option>
                </select>
                <br />                
              </p>              
             <?php }?>
             
 
		
						</div>
					</div>
				</div>

			<div class="panel panel-dark" id="charts_env">
					<div class="panel-heading">
						<div class="panel-title">PC guardadas</div>
					</div>
					<div class="panel-body">
						<?php 
							$sql2="SELECT * FROM tinicio WHERE id_usuario='".$_GET['id']."' ";
							$query2=mysql_query($sql2,$conn)or die(mysql_error());
							if(mysql_num_rows($query2)>0){
								?>
								<ul class="list-group">
									<?php while($row2=mysql_fetch_assoc($query2)){?>
										<li class="list-group-item">
											
											<span class="badge"><a href="modeditar.php?id=<?php echo $_GET['id']?>&eliini=<?php echo $row2['id'];?>" class="entypo-cancel-circled"></a></span>

											<?php echo "PCIP: ".$row2['ip']." :: Codigo: ".$row2['codigo']." :: Fecha ".$row2['fecha'];?>
											
										</li>
									<?php }?>	
									</ul>
								<?php	
							}else{
								?>
								<center><strong>No hay datos guardados</strong></center>
								<?php 
							}
						?>
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
									<?php 
										$sqlF = "SELECT foto FROM tpersonas WHERE id = '".$row['idPersona']."'";
										$queryF = mysql_query($sqlF,$conn);
										while ($rowF=mysql_fetch_assoc($queryF)){
											$foto=$rowF['foto'];
										}
									?>
									<div class="fileinput fileinput-new" data-provides="fileinput"><input type="hidden">
										<div class="fileinput-new thumbnail" style="width: 200px; height: 200px;" data-trigger="fileinput">
											<?php if($foto==""){ ?>
											<img src="http://placehold.it/200x200" alt="...">
											<?php }else{
												?>
											<img src="<?php echo $foto;?>" alt="...">
												<?php
											}?>
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
										<input type="hidden" name="fotoAnt" value="<?php echo $foto;?>">
									</div>
						 </center>
						
				</div>
		
			</div>
		</div>


			<div class="col-md-6">
		
				<div class="panel panel-dark">
					<div class="panel-heading">
						<div class="panel-title">
							Privilegios
						</div>
					</div>
					<div class="panel-body ">
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
				          <input type="button" class="btn btn-red" value="Cancelar" onClick="document.location.href='../usuarios'" />
				      	</center>
					</div>
		
			</div>
		
		</div>
</div>

 
      
	</form>
    	<?php } ?>

<script src="../includes/script.js"></script>
