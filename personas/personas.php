<?php

$servidor = "papersonas.db.10377897.hostedresource.com";
$user = "papersonasro";
$pass = "RD1q27a@";
$database = "papersonas";

$conn = mysql_connect($servidor,$user,$pass);
mysql_select_db($database);

$cedula = $_POST['cedula'];

$sql = "SELECT * FROM tpersonapadron WHERE cedula like '$cedula'";
$query = mysql_query($sql, $conn)or die(mysql_error());

while ($row=  mysql_fetch_assoc($query)){
	//print_r($row);
    $nombre = str_replace("?", "Ñ", $row['nombre']);
    $primerApellido = str_replace("?", "Ñ", $row['primerApellido']);
    $segundoApellido = str_replace("?", "Ñ", $row['segundoApellido']);
    echo $row['cedula'].'||'.$nombre.'||'.$primerApellido.'||'.$segundoApellido.'||'.$row['sexo'].'||'.$row['fechaNacimiento'];
}
?>
