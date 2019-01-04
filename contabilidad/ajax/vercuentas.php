<?php include ("../../conn/connpg.php");

	$sql = "SELECT descripcion FROM ccatalogocuentas WHERE codigo1 LIKE '".$_POST['codigo1']."'  AND codigo2 = '000' AND codigo3='000' AND  codigo4='000' ";
	$query = mysql_query($sql."LIMIT 1",$conn)or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)){
	    echo "<font color='#FF6C00'>".$row['descripcion']."</font><br>";
	    if($_POST['codigo2']!=""){
		    $sql = "SELECT descripcion FROM ccatalogocuentas WHERE codigo1 LIKE '".$_POST['codigo1']."' AND codigo2 LIKE '".$_POST['codigo2']."' AND codigo3='000' AND  codigo4='000' ";
			$query = mysql_query($sql."LIMIT 1",$conn)or die(mysql_error());
			while ($row=mysql_fetch_assoc($query)){
			    echo "<font color='#FF6C00'> * ".$row['descripcion']."</font><br>";
			}
		}
		if($_POST['codigo3']!=""){
			$sql = "SELECT descripcion FROM ccatalogocuentas WHERE codigo1 LIKE '".$_POST['codigo1']."' AND codigo2 LIKE '".$_POST['codigo2']."' AND codigo3 LIKE '".$_POST['codigo3']."' AND codigo4 ='000'  ";
			$query = mysql_query($sql."LIMIT 1",$conn)or die(mysql_error());
			while ($row=mysql_fetch_assoc($query)){
			    echo "<font color='#FF6C00'> ** ".$row['descripcion']."</font>";
			}
		}
	}


?>