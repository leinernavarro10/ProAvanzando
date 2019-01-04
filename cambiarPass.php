<?php include("conn/db.php");?>


<style type="text/css">
	.input {
	padding:5px;
	border:1px solid #959595;
	font-family: Century Gothic;
	font-size: 12px;
	background-color:#eee;
	background: url(img/input.png);
	color:#333;
}


.button{
	border:1px solid #959595;
	font-family: Century Gothic;
	font-size: 12px;
	background:#484848;
	padding:5px 15px;
	color:#fff;
	cursor:pointer;
}
</style>



<?php



$sql2 = "SELECT * FROM tafiliados WHERE codigo LIKE '".$_GET['cd']."' ";
$query2 = mysql_query($sql2,$conn)or die(mysql_error());

while ($row=mysql_fetch_assoc($query2)){ ?>

<center>
<img src="assets/images/allinevo.png" height="50" />
</center><br />
<?php if ($row['genero'] == 1){?>
Bienvenida: 
<?php }else{ ?>
Bienvenido: 
<?php } ?>
<strong><?php echo $row['nombre'].' '.$row['apellidos']?></strong>
<hr />
Para poder continuar es necesario que cambies tu nueva contraseña..<br />
<form action="login.php" method="post" target="_parent">
<input type="hidden" name="codigo" value="<?php echo $row['codigo']?>"/>
<table width="100%" border="0">
  <tr>
    <td width="50%" valign="top"><p>
      <label for="textfield"></label>
      Contraseña nueva:<br />
      <input type="password" name="password1" id="password1" class="input" style="width:95%"/>
      </p>
      <p>        Repite la contraseña:<br />      
        <input type="password" name="password2" id="password2" class="input" style="width:95%"/></p>
      <p>
        <center>
          <input type="submit" name="cambiarPass" value="Cambiar contraseña" class="button" onclick="return validarLargo()"/><input type="button" value="Cancelar" class="button" onclick="cerrarVentana()"/>
        </center>
      </p></td>
    <td><center>
    <img src="img/info.png" width="24" height="24" /><br /> Algunas recomendaciones para una contraseña optima
</center>
<ul>
<li>Mínimo 6 caracteres.</li>
<li>Incluir al menos una letra mayuscula.</li>
<li>Incluir al menos un número.</li>
<li>Incluir al menos un caracter especial (@.,.#, entre otros).</li>
</ul></td>
  </tr>
  <tr>
    <td colspan="2">
    <center>
    Esta ventana se cerrará automáticamente en: <strong><span id="lblsegundos">90</span></strong> segundos por seguridad.
    </center></td>
  </tr>
</table>
</form>
<br />

<?php }?>
<script type="text/javascript">
function validarLargo(){
	if (document.getElementById('password1').value.length >= 5){
		if (document.getElementById('password1').value != document.getElementById('password2').value){
			alert('La contraseñas deben ser iguales.');
			return false;
		}
		return true;
	}else{
		alert('La contraseña debe tener como mínimo 6 caracteres.');
		return false;
	}
}

function cerrarVentana(){
	parent.location.href='index.php';
}
var seg = 90;
function segundos(){
	seg--;
	if (seg<10){
		document.getElementById('lblsegundos').innerHTML = '0'+seg;
	}else{
		document.getElementById('lblsegundos').innerHTML = seg;
	}	
	if (seg == 0){
		cerrarVentana();	
	}
}
setInterval("segundos()",1000);
</script>

