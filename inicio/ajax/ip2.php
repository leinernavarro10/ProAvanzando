<?php 
include("../../conn/connpg.php");

$sql="SELECT * FROM tinicio WHERE ip='".$_POST['rip']."' AND codigo !='' AND codigo!='0'";
$query=mysql_query($sql,$conn)or die(mysql_error());

echo mysql_num_rows($query);


?>