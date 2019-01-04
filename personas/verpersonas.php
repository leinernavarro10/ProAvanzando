<?php 
include ("../conn/connpg.php");


$txtBuscarP = $_POST['txtBuscarP'];
$tipoBuscarP = $_POST['tipoBuscarP'];
	
		$txtBuscarP=explode(' ',$txtBuscarP);
		foreach ($txtBuscarP as $valor) {
	   		$buscar.=" nombre LIKE '%$valor%' AND";
		}


		$sql = "SELECT * FROM tpersonas WHERE id like '%".$_POST['txtBuscarP']."%' OR identificacion like '%".$_POST['txtBuscarP']."%' OR $buscar estado = 1  ";

		if($tipoBuscarP==1){
			$sql.=" AND tipoPersona = 3 OR tipoPersona = 4";
		}

	
	//echo $sql;
		$query = mysql_query($sql." LIMIT 7", $conn);
		if (mysql_num_rows($query) == 0){
			echo '<center><i class="entypo-info"></i><br>No se encuentra lo que estas buscando.</center>';
		}else{ ?>
			<table width="100%" class="table table-bordered responsiv tbperson">
			<thead>
			<tr>
				<th>Codigo</th>
				<th>Cliente</th>
				<th>Cedula</th>
				<th>Tipo</th>
			</tr>
			</thead>
			<tbody>
			<?php  
			$connt1=0;
				while ($row=mysql_fetch_assoc($query)) {
					$connt1++;
					$nombre = $row['nombre'];
					
					?>
					<tr id="trCL<?php echo $connt1?>" onclick="procesarPersona(<?php echo $row['id']?>,'<?php echo $nombre?>',<?php echo $tipoBuscarP?>)" >	
						<td><?php echo $row['id']?></td>	
						<td><?php echo $nombre?></td>
						<td><?php echo $row['identificacion']?></td>
						<td><?php switch ($row['tipoPersona']) {
							case 0:
								echo "Cliente";
								break;
							case 1:
								echo "Proveedor";
								break;
							case 2:
								echo "Socios";
								break;
							case 3:
								echo "Usuario del sistema";
								break;
							case 4:
								echo "Empleado";
								break;

						}?>
						</td>
					</tr>
			<?php
				}
			?>
			</tbody>
			</table>
			<input type="hidden" name="" id="cantRG2" value="<?php echo $connt1;?>">
		<?php  
		}

?>