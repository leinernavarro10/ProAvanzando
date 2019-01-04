<?php  
include ("../conn/connpg.php");



$f = $_POST['f'];

//CARGAR CANTONES
if ($f == 'CARGAR-PROVINCIA-CLI'){
	$provincia = $_POST['idProvincia'];
	$idCantonCLI = $_POST['idCantonCLI'];
	?>
	<select name="idCanton" id="idCanton" class="form-control" onchange="cambiarCanton()" required>
		<option value="">Cantón...</option>
	  <?php  
	  $sql2 = "SELECT * FROM tubicacionfe WHERE provincia = '$provincia' GROUP BY canton ORDER BY canton ASC";
	  $query2 = mysql_query($sql2, $conn);
	  while ($row2=mysql_fetch_assoc($query2)) {
	  ?>
      <option value="<?php echo $row2['canton']?>" <?php  if ($row2['canton'] == $idCantonCLI){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreCanton'])?></option>
      <?php  } ?>
    </select>
	<?php  
}

//CARGAR CANTONES
if ($f == 'CARGAR-DISTRITO-CLI'){
	$provincia = $_POST['idProvincia'];
	$canton = $_POST['idCanton'];
	$idDistritoCLI = $_POST['idDistritoCLI'];
	?>
	<select name="idDistrito" id="idDistrito" class="form-control" onchange="cambiarDistrito()" required>
	  <option value="">Distrito...</option>
	  <?php  
	  $sql2 = "SELECT * FROM tubicacionfe WHERE provincia = '$provincia' AND canton = '$canton' GROUP BY distrito ORDER BY distrito ASC";
	  $query2 = mysql_query($sql2, $conn);
	  while ($row2=mysql_fetch_assoc($query2)) {
	  ?>
      <option value="<?php echo $row2['distrito']?>" <?php  if ($row2['distrito'] == $idDistritoCLI){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreDistrito'])?></option>
      <?php  } ?>
    </select>
	<?php  
}

//CARGAR CANTONES
if ($f == 'CARGAR-BARRIO-CLI'){
	$provincia = $_POST['idProvincia'];
	$canton = $_POST['idCanton'];
	$distrito = $_POST['idDistrito'];
	$idBarrioCLI = $_POST['idBarrioCLI'];
	?>
	<select name="idBarrio" id="idBarrio" class="form-control">
	  <option value="">Barrio...</option>
	  <?php  
	  $sql2 = "SELECT * FROM tubicacionfe WHERE provincia = '$provincia' AND canton = '$canton' AND distrito = '$distrito' GROUP BY barrio ORDER BY barrio ASC";
	  $query2 = mysql_query($sql2, $conn)or die(mysql_error());
	  while ($row2=mysql_fetch_assoc($query2)) {
	  ?>
      <option value="<?php echo $row2['barrio']?>" <?php  if ($row2['barrio'] == $idBarrioCLI){echo 'selected="selected"';} ?>><?php echo utf8_encode($row2['nombreBarrio'])?></option>
      <?php  } ?>
    </select>
	<?php  
}

     if ($f == 'GUARDAR-PERSONA'){
		$idCliente = $_POST['idCliente'];
		$tipoIdentificacion = $_POST['tipoIdentificacion'];
		$identificacion = $_POST['identificacion']; 
		$nombre = $_POST['nombre'];
		$nomComercial = $_POST['nomComercial']; 
		$tipoPersona = $_POST['tipoCliente'];
		$idProvincia = $_POST['idProvincia']; 
		$idCanton = $_POST['idCanton']; 
		$idDistrito = $_POST['idDistrito']; 
		$idBarrio = $_POST['idBarrio']; 
		$direccion = $_POST['direccion'];
		$sexo= $_POST['sexo'];
		$nacionalidad= $_POST['nacionalidad'];
		$fecha= $_POST['fecha'];

		$sqlVER="SELECT * FROM tpersonas WHERE identificacion='".$identificacion."' AND id !='".$idCliente."' ";
		$queryVER=mysql_query($sqlVER,$conn);
		if(mysql_num_rows($queryVER)==0){

				$sql = "UPDATE tpersonas SET 
				tipoIdentificacion = '$tipoIdentificacion', 
			  	identificacion = '$identificacion', 
			  	nombre = '$nombre', 
			  	nomComercial = '$nomComercial', 
				tipoPersona = '$tipoPersona', 
			  	idProvincia = '$idProvincia', 
			  	idCanton = '$idCanton', 
			  	idDistrito = '$idDistrito', 
			  	idBarrio = '$idBarrio',
			  	direccion = '$direccion',
				sexo = '$sexo',
				nacionalidad = '$nacionalidad',
				fechaNac='$fecha'
			  	WHERE id = '$idCliente' ";
			  $query = mysql_query($sql, $conn)or die(mysql_error());
		}else{
			echo "Ya se encuentra una persona con el mismo número de cedula.\nLos cambios no fueron realizados";
		}


     }



     if ($f == 'GUARDAR-CONTACTO'){
     	$sql="INSERT INTO tcontacto VALUES(null,'".$_POST['idCliente']."','".$_POST['dato']."','".$_POST['detalle']."','".$_POST['tipoConta']."','".$_POST['estado']."')";
     	mysql_query($sql,$conn)or die(mysql_error());
     }


     if ($f == 'EDITAR-CONTACTO'){

     	$sql = "UPDATE tcontacto SET 
			dato='".$_POST['dato']."',
			detalle='".$_POST['detalle']."',
			estado='".$_POST['estado']."'
		  	WHERE id = '".$_POST['idCont']."' ";
		  $query = mysql_query($sql, $conn)or die(mysql_error());
     }

     if ($f == 'BORRAR-CONTACTO'){
     	$sql="DELETE FROM tcontacto WHERE id = '".$_POST['idCont']."' ";
     	mysql_query($sql,$conn)or die(mysql_error());
     }



      if ($f == 'GUARDAR-NUEVA-PERSONA'){

      	$tipoIdentificacion = $_POST['tipoIdentificacion'];
		$identificacion = $_POST['identificacion']; 
		$nombre = $_POST['nombre'];
		$nomComercial = $_POST['nomComercial']; 
		$tipoPersona = $_POST['tipoCliente'];
		$idProvincia = $_POST['idProvincia']; 
		$idCanton = $_POST['idCanton']; 
		$idDistrito = $_POST['idDistrito']; 
		$idBarrio = $_POST['idBarrio']; 
		$direccion = $_POST['direccion'];
		$sexo= $_POST['sexo'];
		$nacionalidad= $_POST['nacionalidad'];
		$fecha= $_POST['fecha'];
		$sqlVER="SELECT * FROM tpersonas WHERE identificacion='".$identificacion."' ";
		$queryVER=mysql_query($sqlVER,$conn);
		if(mysql_num_rows($queryVER)==0){
	     	$sql="INSERT INTO tpersonas VALUES(null,'".$tipoIdentificacion."','".$identificacion."','".$nombre."','".$nomComercial."','".$tipoPersona."','".$idProvincia."','".$idCanton."','".$idDistrito."','".$idBarrio."','".$direccion."','".$sexo."','".$nacionalidad."','".$fechaNac."','".date('d/m/Y')."','','1','".$userid."')";
	     	mysql_query($sql,$conn)or die(mysql_error());
	     	echo mysql_insert_id();
	    }else{
	    	echo "Error";
	    }
     }

     if($f == 'VER-PERSONA'){
		$identificacion = $_POST['ced']; 
      	
		$sqlVER="SELECT * FROM tpersonas WHERE identificacion='".$identificacion."' ";
		$queryVER=mysql_query($sqlVER,$conn);
		if(mysql_num_rows($queryVER)>=1){
	     	echo "Error";
	    }
	    
	    	
     }
?>
