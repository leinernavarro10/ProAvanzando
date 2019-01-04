<?php 
include ("../../conn/connpg.php");
$arr_priuser[] = null;
$sql = "SELECT * FROM tprivilegios WHERE idUsuariotipo = '".$_POST['id']."'";
$query = mysql_query($sql,$conn);
while ($row=mysql_fetch_assoc($query)){
    array_push($arr_priuser, $row['numero']);
}

?>
<center>
	<h2><?php echo $_POST['nombre']?></h2>
</center>
<table class="table table-condensed table-bordered table-hover table-striped">
	<thead>
		<tr>
			<th width="15px"></th>
			<th>Privilegio</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			
			<td colspan="2"><strong><center>Configuración general </center></strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="PA-1" value="PA-1" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("PA-1",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="PA-1"> Cambiar parámetros</label></td>
		</tr>

		<tr>
			
			<td colspan="2"><strong><center>Usuarios</center></strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="U-1" value="U-1" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("U-1",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="U-1"> Editar Usuarios</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="U-2" value="U-2" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)" 
				<?php if (in_array("U-2",$arr_priuser)){ echo "checked";}?>></td>
			<td><label for="U-2"> Agregar Usuarios</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="U-3" value="U-3" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)" 
				<?php if (in_array("U-3",$arr_priuser)){ echo "checked";}?>></td>
			<td><label for="U-3"> Eliminar Usuarios</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="U-4" value="U-4" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)" 
				<?php if (in_array("U-4",$arr_priuser)){ echo "checked";}?>></td>
			<td><label for="U-4"> Ver Usuarios</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="U-5" value="U-5" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)" 
				<?php if (in_array("U-5",$arr_priuser)){ echo "checked";}?>></td>
			<td><label for="U-5"> Modificar privilegios </label></td>
		</tr>

		<tr>
			<td colspan="2"><strong><center>Personas</center></strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="P-0" value="P-0" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("P-0",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="P-0"> Ver</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="P-1" value="P-1" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("P-1",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="P-1"> Editar</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="P-2" value="P-2" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("P-2",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="P-2"> Nueva</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="P-3" value="P-3" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("P-3",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="P-3"> Cambiar Tipo Persona</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="P-4" value="P-4" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("P-4",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="P-4"> Editar Contacto</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="P-5" value="P-5" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("P-5",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="P-5"> Agregar Contacto</label></td>
		</tr>

		<tr>
			<td colspan="2"><strong><center>Contabilidad</center></strong></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-0" value="C-0" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-0",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-0"> Asientos Contables</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-5" value="C-5" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-5",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-5"> Cuenta por pagar(CXP)</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-4" value="C-4" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-4",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-4"> Reportes</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-6" value="C-6" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-6",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-6"> Bancos</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-1" value="C-1" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-1",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-1"> Periodos</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-2" value="C-2" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-2",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-2"> Catalogo Contable</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-3" value="C-3" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-3",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-3"> Centro de Costo</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-7" value="C-7" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-7",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-7"> Tipos Documentos</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-8" value="C-8" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-8",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-8"> Monedas</label></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="pri[]" id="C-9" value="C-9" onchange="cambiarEstado(this,<?php echo $_POST['id']?>)"
				<?php if (in_array("C-9",$arr_priuser)){ echo "checked";}?> ></td>
			<td><label for="C-9"> Editar asientos aplicados</label></td>
		</tr>
		
		

	</tbody>
</table>