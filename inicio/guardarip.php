<?php include("../conn/connpg.php");
	if($_GET['e']=="11"){
echo "Cargando...";
		$sql="INSERT INTO tinicio VALUES(null,'".$userid."','".$_GET['ip1']."','".$_GET['pin']."','".date('d/m/Y')."')";
		mysql_query($sql,$conn)or die(mysql_error());

		if($_GET['pin']!=""){
				$error=1;
		}else{
			$error=2;
		}
		//header("Location: ../inicio/?pin=".$error);
		print "<script>document.location.href='../inicio/?pin=".$error."';</script>";    
  		exit(); 

	}
?>
<style type="text/css">
	.btng{padding: 20px}
	.btn{
      margin-bottom: 2px;
   }
</style>
<script type="text/javascript">
	var vlpin="";
	var numpin=0;
	function pin(val){
		numpin++;
		
		vlpin+=val;
		$("#txtpin").val(vlpin);

		if(numpin==4){
			if(confirm("Su número de acceso rápido pin es:\n"+vlpin)==true){
				
				enviar(vlpin);	
			}else{
				borrar();
			}
		}
	}

	function enviar(pin2){

		$('#modalcf').load('../inicio/guardarip.php?ip1=<?php echo $_GET['ip1'];?>&ip2=<?php echo $_GET['ip2'];?>&pin='+pin2+"&e=11");
	}
	function borrar(){
		vlpin="";
		numpin=0;
		$("#txtpin").val("");	
	}
</script>
<center>
	<h4>Desea guardar esta conexión como segura y configurar el pin de acceso</h4>
	<input type="text" name="txtpin" id="txtpin" class="form-control" placeholder="Pin" disabled />
<hr>
<table cellpadding="3" cellspacing="3">
	<tr>
		<td>
<input type="button" name="" onclick="pin(this.value)" value="7" class="btn btn-primary btng">
<input type="button" name="" onclick="pin(this.value)" value="8" class="btn btn-primary btng">
<input type="button" name="" onclick="pin(this.value)" value="9" class="btn btn-primary btng">
		</td>
	</tr>
	<tr>
		<td>
<input type="button" name="" onclick="pin(this.value)"  value="4" class="btn btn-primary btng">
<input type="button" name="" onclick="pin(this.value)" value="5" class="btn btn-primary btng">
<input type="button" name="" onclick="pin(this.value)" value="6" class="btn btn-primary btng">
		</td>
	</tr>
	<tr>
		<td>
<input type="button" name="" onclick="pin(this.value)" value="1" class="btn btn-primary btng">
<input type="button" name="" onclick="pin(this.value)" value="2" class="btn btn-primary btng">
<input type="button" name="" onclick="pin(this.value)" value="3" class="btn btn-primary btng"> 
		</td>
	</tr>
	<tr>
		<td>
<input type="button" name="" onclick="pin(this.value)" value="0" class="btn btn-primary btng">
<input type="button" name="" onclick="borrar();" value="Borrar" class="btn btn-primary btng" >
		</td>
	</tr>
</table>
<hr>
<button type="button" class="btn btn-danger" onclick="javascript: enviar('');">No deseo guardar esta conexión como segura</button>
</center>