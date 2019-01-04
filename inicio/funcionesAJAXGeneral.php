<?php 

include("../conn/connpg.php");

if($_POST['f']=="NUEVA-NOTA"){
	$sql = "INSERT INTO tnotas VALUES( 
		null, 
		'".$userid."',
    '".$_POST['titulo']."',
    '".$_POST['descripcion']."',
		'".$_POST['contenido']."',
		'".date('d/m/Y')."', 
		'1'
		)";
		$query = mysql_query($sql,$conn)or die(mysql_error());
		echo mysql_insert_id();
}

if($_POST['f']=="MODIFICAR-NOTA"){
	
	$sql = "UPDATE tnotas SET 
			titulo = '".$_POST['titulo']."', 
			descripcion = '".$_POST['descripcion']."', 
			contenido = '".$_POST['contenido']."', 
			fecha = '".date('d/m/Y')."'
			WHERE id = '".$_POST['id']."'";	
		
		$query = mysql_query($sql,$conn)or die(mysql_error());

	
}

if($_POST['f']=="DELET-NOTA"){
	
	$sql = "UPDATE tnotas SET 
			estado = '2' 
			WHERE id = '".$_POST['id']."'";	
		
		$query = mysql_query($sql,$conn)or die(mysql_error());


}



?>