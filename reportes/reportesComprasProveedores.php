<?php $pagina="Reporte Compras"; include("../conn/connpg.php");?>	
<?php include("../includes/header.php");?>				
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");

if (!isset($_GET['tipo'])){
	$_GET['tipo'] = 1;
}

if (isset($_GET['m'])){
	$m = $_GET['m'];
}else{
	$m = date('m', strtotime('-1 month'));
}

if (isset($_GET['y'])){
	$y = $_GET['y'];
}else{
	$y = date('Y', strtotime('-1 month'));
}

?>
<!-- INICIA CONTENIDO -->
<ol class="breadcrumb" >
	<li><a href="#"><i class="fa-home"></i>Reportes</a></li>
	<li><a href="#">Reporte de compras</a></li>
</ol>
<h2>Reporte de compras</h2>
<br />

<hr>
OPCIONES
<hr> 
<form action="comprasProveedores.php" method="get" target="_blank">
<label><input type="radio" onclick="rMes()" name="tipo" value="1" <?php if ($_GET['tipo'] == 1){echo 'checked="checked"';}?>> Reporte por mes</label>
<select class="text" name="m" style="padding: 6px">
    <option value="01" <?php if ($m == '01'){echo 'selected="selected"';} ?>>Enero</option>
    <option value="02" <?php if ($m == '02'){echo 'selected="selected"';} ?>>Febrero</option>
    <option value="03" <?php if ($m == '03'){echo 'selected="selected"';} ?>>Marzo</option>
    <option value="04" <?php if ($m == '04'){echo 'selected="selected"';} ?>>Abril</option>
    <option value="05" <?php if ($m == '05'){echo 'selected="selected"';} ?>>Mayo</option>
    <option value="06" <?php if ($m == '06'){echo 'selected="selected"';} ?>>Junio</option>
    <option value="07" <?php if ($m == '07'){echo 'selected="selected"';} ?>>Julio</option>
    <option value="08" <?php if ($m == '08'){echo 'selected="selected"';} ?>>Agosto</option>
    <option value="09" <?php if ($m == '09'){echo 'selected="selected"';} ?>>Septiembre</option>
    <option value="10" <?php if ($m == '10'){echo 'selected="selected"';} ?>>Octubre</option>
    <option value="11" <?php if ($m == '11'){echo 'selected="selected"';} ?>>Noviembre</option>
    <option value="12" <?php if ($m == '12'){echo 'selected="selected"';} ?>>Diciembre</option>
  </select> 
Año: 
  <select class="text" name="y" style="padding: 6px">
    <?php 
    for ($a = $y-1; $a <= date('Y'); $a++){ ?>
      <option value="<?php echo $a?>" <?php if ($y == $a){echo 'selected="selected"';} ?>><?php echo $a?></option>
    <?php } ?>
  </select>

<br><br>
<label><input type="radio" id="tipoRango" name="tipo" value="2" <?php if ($_GET['tipo'] == 2){echo 'checked="checked"';}?>> Reporte por rango de fecha</label>
<input type="text" name="rango" id="rango" value="<?php echo $_GET['rango']?>" data-format="DD/MM/YYYY" class="text daterange" style="width: 160px; padding: 6px;" />
<br><br>
Tipo de facturas: 
<select class="text" name="tipoFE" style="padding: 6px">
    <option value="">Todo</option>
    <option value="0">Compras en efectivo</option>
    <option value="1">Cuentas por pagar</option>
   <option value="2">Facturas canceladas</option>
  </select> 
<br><br>
Proveedor: 
<select name="idProveedor" id="idProveedor" class="form-control">
<option value="0">Todos los proveedores</option>
<?php
$sql2 = "SELECT * FROM tclientes WHERE estado = '1' AND tipo = '2' AND usuario ='".$cuentaid."' ";
$query2 = mysql_query($sql2,$conn)or die(mysql_error());
while($row2=mysql_fetch_assoc($query2)){ ?>
<option value="<?php echo $row2['id']?>"><?php echo $row2['nombre']?></option>
<?php } ?>
</select>

<br><br>
<input type="button" value="Generar" style="padding: 4px" onclick="generarReporte()">
<input type="submit" id="generar" value="Generar" style="display: none;">
</form>
<hr>

<script type="text/javascript">
function generarReporte(){
	if (document.getElementById("tipoRango").checked){
		if (document.getElementById("rango").value==""){
			alert("Por favor seleccione el rango de fechas.");
		}else{
			document.getElementById("generar").click();	
		}	
	}else{
		document.getElementById("generar").click();
	}
}

</script>
<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>