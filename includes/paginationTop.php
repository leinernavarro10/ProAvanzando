<?php  
$pagi = $_GET['pagi']; 
$contar_pagi = (strlen($pagi));    // Contamos el numero de caracteres 
// Numero de registros por pagina 
$numer_reg = 25; 
// Contamos los registros totales 
//$sql = "SELECT * FROM tclientes WHERE estado = '1' $busqueda";

$query0 = $sql;
$result0 = mysql_query($query0, $conn);  
$numero_registros0 = mysql_num_rows($result0);  
$numpaginas = ceil($numero_registros0/$numer_reg);
############################################## 
// ----------------------------- Pagina anterior 
$prim_reg_an = $numer_reg - $pagi; 
$prim_reg_ant = abs($prim_reg_an);        // Tomamos el valor absoluto 

if ($pagi <> 0)  
{  
$pag_anterior = "<a href='".$_SERVER['PHP_SELF']."?".$busquedas."pagi=0'><i class=\"entypo-to-start\" style=\"font-size:18px\"></i></a><a href='".$_SERVER['PHP_SELF']."?".$busquedas."pagi=$prim_reg_ant'><i class=\"entypo-fast-backward\" style=\"font-size:18px\"></i></a>"; 
} 
// ----------------------------- Pagina siguiente 
$prim_reg_sigu = $numer_reg + $pagi; 

if ($pagi < $numero_registros0 - ($numer_reg - 1))  
{  
$ultimapagina = ($numpaginas*$numer_reg)-$numer_reg;
$pag_siguiente = "<a href='".$_SERVER['PHP_SELF']."?".$busquedas."pagi=$prim_reg_sigu'><i class=\"entypo-fast-forward\" style=\"font-size:18px\"></i></a><a href='".$_SERVER['PHP_SELF']."?".$busquedas."pagi=$ultimapagina'><i class=\"entypo-to-end\" style=\"font-size:18px\"></i></a>"; 
} 
// ----------------------------- Separador 
//$separador = (($pagi/$numer_reg)+1)." de ".$numpaginas;

if ($numpaginas == 1){
$separador = 'Página 1 de 1';
}else{
$separador = '<select style="width: 60px; height: 30px;" onchange="document.location.href=\''.$_SERVER['PHP_SELF'].'?'.$busquedas.'pagi=\'+this.value">';
for ($x=1; $x <= $numpaginas; $x++) {
$selected = '';
if ((($x*$numer_reg+1)-$numer_reg) == $pagi){
$selected = 'selected="selected" class="selected"';
}
$separador .= '<option value="'.(($x*$numer_reg+1)-$numer_reg).'" '.$selected.'>'.$x.'</option>'; 
}
$separador .= '</select>';
}

// Creamos la barra de navegacion 
if ($numpaginas == 1){
$pagi_navegacion = "$separador"; 
}else{

$pagi_navegacion = '
	'.$pag_anterior.'
	'.$separador.'
	'.$pag_siguiente.'
';
//$pagi_navegacion = "$pag_anterior $separador $pag_siguiente"; 
}
// ----------------------------- 
############################################## 
if ($contar_pagi > 0)  
{  
// Si recibimos un valor por la variable $page ejecutamos esta consulta 
$query = $sql." LIMIT $pagi,$numer_reg"; 
}  
else  
{  
// Si NO recibimos un valor por la variable $page ejecutamos esta consulta 
$query = $sql." LIMIT 0,$numer_reg"; 
}  
$result = mysql_query($query, $conn);  
$numero_registros = mysql_num_rows($result); 

?>