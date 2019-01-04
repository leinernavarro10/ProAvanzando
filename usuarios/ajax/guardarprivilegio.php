<?php include ("../../conn/connpg.php");
echo $_POST['f'];
if($_POST['f']=='Nuevo'){
	$sql="INSERT INTO tprivilegios VALUES(null,'".$_POST['id']."','".$_POST['valor']."')";
}
if($_POST['f']=='Borrar'){
	$sql="DELETE FROM tprivilegios WHERE idUsuariotipo = '".$_POST['id']."' AND numero ='".$_POST['valor']."' ";
}
mysql_query($sql,$conn)or die(mysql_error());
?>