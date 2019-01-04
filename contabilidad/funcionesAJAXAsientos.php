<?php 


include ("../conn/connpg.php");



function normaliza($cadena) {
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

$f = $_POST['f'];

//////GUARDAR FACTURA
if ($f == 'GUARDAR-ASIENTO'){
	$idAsiento = $_POST['idAsiento'];
	$periodo = $_POST['periodo'];
	$moneda = $_POST['moneda'];
	$fechadoc = $_POST['fechadoc'];
	$no = $_POST['no'];
	$documento = $_POST['documento'];
	$idpersona = $_POST['idpersona'];
	$descripcion = $_POST['descripcion'];
	

	
	$sql = "UPDATE casientos SET 
	idperiodo = '$periodo',
	idmoneda = '$moneda',
	iddocumento = '$documento',
	idpersona = '$idpersona',
	numero = '$no',
	fechadoc = '$fechadoc', 
	descripcion = '$descripcion'

	WHERE id = '$idAsiento' ";
	$query = mysql_query($sql, $conn);
}

//BUSCAR CENTRO COSTO
if ($f == 'BUSCAR-CC'){
	$txtBuscar = $_POST['txtBuscar'];
	$sql = "SELECT * FROM ccostoscentro WHERE estado = 1  LIMIT 10";
	   	

		//$sql = "SELECT * FROM tproductos WHERE (FULLTEXT(name, description) nombre like '%$txtBuscar%' OR descripcion like '%$txtBuscar%' OR codigo like '%$txtBuscar%' OR id like '%$txtBuscar%') AND estado = 1 AND sistema = '".$cuentaid."' LIMIT 5";
		$query = mysql_query($sql, $conn)or die(mysql_error());
		if (mysql_num_rows($query) == 0){
			echo '<center><i class="entypo-info"></i><br>No se encuentra lo que estas buscando.</center>';
		}else{ ?>
			<table width="100%" class="table table-bordered">
			<thead>
			<tr>
				
				<th>Nombre</th>
				<th>Detalle</th>
				
			</tr>

			</thead>
			<tbody>
			<?php  
			$connt1=0;
				while ($row=mysql_fetch_assoc($query)) {
					$connt1++;
					$nombre=$row['nombre'];
					$tdbselect="";
					if(strlen($txtBuscar)>=3){
						if(preg_match('/'.$txtBuscar.'/i', $nombre)){
					    	$tdbselect= 'tdbselect';
						}
					}
					
					?>
					
					<tr id="trb<?php echo $connt1?>" class="trb <?php echo $tdbselect;?>" onclick="agregarCC(<?php echo $row['id']?>,'<?php echo $nombre?>')">
						
						<td><?php echo $row['nombre']?></td>
						<td><?php echo $row['detalle']?></td>
						
					</tr>
			<?php	}
			?>
			</tbody>
			</table>
			 <input type="hidden" name="" id="cantCC" value="<?php echo $connt1;?>">
		<?php  
		}	
	
}

//BUSCAR CENTRO COSTO
if ($f == 'BUSCAR-CUENTA'){
	$txtBuscar = $_POST['txtBuscar'];
	$id = $_POST['txtBuscar'];
	$codigo = $_POST['txtBuscar'];
	if ($txtBuscar == ""){
		echo '<center><i class="entypo-info"></i><br>Por favor digite el codigo o descripción de la cuenta.</center>';
	}else{
		$txtBuscar=explode(' ',$txtBuscar);
		foreach ($txtBuscar as $valor) {
	   		$buscar.=" descripcion LIKE '%$valor%' AND";
		}
			$buscar3=explode('-',$codigo);
			if(is_numeric($buscar3[0])){
	        	if($buscar3[0]!="") {
	        		$buscar=" codigo1 LIKE '".$buscar3[0]."%' AND ";
	        	}
	        	if($buscar3[1]!="") {
	        		$buscar.=" codigo2 LIKE '".$buscar3[1]."%' AND ";
	        	}
	        	if($buscar3[2]!="") {
	        		$buscar.=" codigo3 LIKE '".$buscar3[2]."%' AND ";
	        	}
	        	if($buscar3[3]!="") {
	        		$buscar.=" codigo4 LIKE '".$buscar3[3]."%' AND ";
	        	}
	        }

		if(is_numeric($id)){
			$sql = "SELECT * FROM ccatalogocuentas WHERE id = '$id' AND estado = 1  LIMIT 5";
		}else{
			$sql = "SELECT * FROM ccatalogocuentas WHERE $buscar estado = 1  LIMIT 5";
		}
		
	}

		
		$query = mysql_query($sql, $conn)or die(mysql_error());
		if (mysql_num_rows($query) == 0){
			echo '<center><i class="entypo-info"></i><br>No se encuentra lo que estas buscando.</center>';
		}else{ ?>
			<table width="100%" class="table table-bordered">
			<thead>
			<tr>
				
				<th>Codigo</th>
				<th>descripcion</th>
				
			</tr>

			</thead>
			<tbody>
			<?php  
			$connt1=0;
				while ($row=mysql_fetch_assoc($query)) {
					$connt1++;
					$codigo=$row['codigo1']."-".$row['codigo2']."-".$row['codigo3']."-".$row['codigo4'];
					$descripcion=$row['descripcion']." (".$codigo.")";

					$tdbselect="";
					
						if($row['id']==$id){
					    	$tdbselect= 'tdbselect';
						}
					

					?>
					
					<tr id="trbCU<?php echo $connt1?>" class="trbCU <?php echo $tdbselect;?>" onclick="agregarCuenta(<?php echo $row['id']?>,'<?php echo $descripcion?>')">
						
						<td><?php echo $codigo?></td>
						<td><?php echo $row['descripcion'];?></td>
						
					</tr>
			<?php	}
			?>
			</tbody>
			</table>
			 <input type="hidden" name="" id="cantCuenta" value="<?php echo $connt1;?>">
		<?php  
		}	
	
}
?>
