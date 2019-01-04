<?php  

include("conf/conf.php"); 

$institucion = '';
$sql = "SELECT * FROM institucion";
$query = mysql_query($sql, $conn) or die(mysql_error());
$result = mysql_num_rows($query);
	
while ($row = mysql_fetch_assoc($query)) {
	$institucion = $row['institucion'];
}



if (isset($_POST['g'])){
	$sql = "SELECT * FROM usuarios WHERE usuario = '".$_POST['usuario']."'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
		if ($row['asociar'] != 0){
			$sql2 = "SELECT * FROM profesores WHERE id = '".$row['asociar']."'";
			$query2 = mysql_query($sql2, $conn);
			while ($row2=mysql_fetch_assoc($query2)){
				$mail = $row2['email'];
			}
		}else{
			$mail = $row['email'];
		}
		
		if ($mail == ''){		
		?><script type="text/javascript">
        alert('Su correo electrónico no ha sido configurado.\nPor favor consulte a su administrador de sistema.');
        </script><?php
		}else{
			
		
$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$cad = "";
for($i=0;$i<5;$i++) {
$contrasena .= substr($str,rand(0,62),1);
}	

			
$sql = "UPDATE usuarios SET 
pass = '".md5($contrasena)."',
bloq = 0
WHERE usuario = '".$_POST['usuario']."'";
$query = mysql_query($sql, $conn);
		
$destinatario = $mail; 
$asunto = "Recuperacion de Contrasena -- Registro Digital"; 
$cuerpo = ' 
<html> 
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"> 
   <title>Recuperacion de Contrasena -- Registro Digital</title> 
<link rel="shortcut icon" href="favicon.ico" >
</head> 
<body> 
<h1>Recuperacion de Contrasena -- Registro Digital</h1> 
<p> 
<b>
Estimado companero:

Alguien (problamente usted) ha solicitado el cambio de contrasena para el usuario <strong>'.$_POST['usuario'].'</strong><br><br>
Esta es su nueva informacion de acceso:<br>

Usuario: '.$_POST['usuario'].'<br>
Contraseña: '.$contrasena.'<br>
<br><br>
Recuerde la importancia de mantener su contrasena en secreto.<br />
<br />
Muchas gracias.
</p> 
</body> 
</html> 
'; 

//para el envío en formato HTML 
$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

//dirección del remitente 
$headers .= "From: Registro Digital <noreplay@programacionalternativa.com>\r\n"; 

//dirección de respuesta, si queremos que sea distinta que la del remitente 
$headers .= "Reply-To: noreplay@programacionalternativa.com\r\n"; 

//ruta del mensaje desde origen a destino 
$headers .= "Return-path: noreplay@programacionalternativa.com\r\n"; 

//direcciones que recibirán copia oculta 
$headers .= "Bcc: guimogra@gmail.com\r\n";


if (mail($destinatario,$asunto,$cuerpo,$headers)) {
  //echo 'mail() Success!' . "<br />\n";
}
else {
  //echo 'mail() Failure!' . "<br />\n";
}
			
		?><script type="text/javascript">
        alert('Revise su correo electrónico por favor.\nAhí encontrará su nueva información de acceso.');
        </script><?php			
		}
	}
}


if ($_POST['entrar'] != "" and isset($_POST['entrar'])){
	$error = "";
	if (isset($_POST['user']) and $_POST['user']){
		$user = $_POST['user'];
	}else{
		$error .= "Por favor digite el nombre de usuario.<br>";
	}
	
	if (isset($_POST['pass']) and $_POST['pass']){
		$pass = $_POST['pass'];
	}else{
		$error .= "Por favor digite la contraseña.<br>";
	}
	

	$sql = "SELECT * FROM usuarios WHERE usuario like '".$user."' and bloq = 3";
	$query = mysql_query($sql, $conn) or die(mysql_error());
	
	if (mysql_num_rows($query) > 0){
		$error = "Usted ha sobrepasado el número máximo de intentos.<br />
EL usuario <strong>".$user."</strong> HA SIDO BLOQUEADO. Haga click en \"Olvide mi contraseña\" para activarlo de nuevo.";
	}else{
	
	$sql = "SELECT * FROM usuarios WHERE usuario like '".$user."' and pass like '".md5($pass)."'";
	$query = mysql_query($sql, $conn) or die(mysql_error());
	$result = mysql_num_rows($query);
	
	if ($result > 0) {
	
			while ($row = mysql_fetch_assoc($query)) {
			$id = $row['id'];
			$user=$_POST['user'];
			$tipo=$row['tipo'];
			setcookie("user_registro", "".$_POST['user']."");
			setcookie("nombre", "".$row['nombre']." ".$row['apellido']."");
			setcookie("id_registro", "".$id."");
			setcookie("tipo_registro", "".$row['tipo']."");
			setcookie("iniciales_registro", "".$row['iniciales']."");
			setcookie("privilegios", "".$row['privilegios']."");
			setcookie("asociar", "".$row['asociar']."");
			
			$sql = "UPDATE usuarios SET 
			bloq = 0
		    WHERE usuario like '".$user."'";
			$query = mysql_query($sql, $conn) or die(mysql_error());
			
			if ($pass == 'cambiar'){
				print "<meta http-equiv=\"refresh\" content=\"0;URL=cambiar.php?id_editar=".$id."\">";		
				exit();
			}else{
				print "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";		
				exit();
			}
		}
		
	}else{
		if ($error == ""){
			$sql = "UPDATE usuarios SET 
			bloq = bloq+1
		    WHERE usuario like '".$user."'";
			$query = mysql_query($sql, $conn) or die(mysql_error());
			
			$sql = "SELECT * FROM usuarios WHERE usuario like '".$user."'";
			$query = mysql_query($sql, $conn) or die(mysql_error());
			while ($row=mysql_fetch_assoc($query)){
				$intentos = 3-$row['bloq'];
			}
			
			$sql = "SELECT * FROM usuarios WHERE usuario like '".$user."' and bloq = 3";
			$query = mysql_query($sql, $conn) or die(mysql_error());
			
			if (mysql_num_rows($query) > 0){
				$error = "Usted ha sobrepasado el número máximo de intentos.<br />
		EL usuario <strong>".$user."</strong> HA SIDO BLOQUEADO. Haga click en \"Olvide mi contraseña\" para activarlo de nuevo.";
			}else{
				$error .= "El usuario y la contraseña no coinciden o bien<br>el nombre de usuario que digitó no esta registrado.<br>Intentos restantes: <strong>".$intentos."</strong>";
			}
		}
	}	
	
	}
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bienvenid@</title>
<style type="text/css">
body {
background: url(img/login.jpg) repeat;
font-family: Georgia, "Times New Roman", Times, serif;
overflow: hidden;
margin: 0px;
height: 0px;}
#noscript {
display: block;
width: 100%;
background:  #ffffff;
height: 1000px;
overflow: hidden;	
}

#res {
display: block;
width: 100%;
background:  #ffffff;
height: 1000px;
overflow: hidden;	
}

.login{
-moz-border-radius: 70px 10px 70px 10px;
-webkit-border-radius: 70px 10px 70px 10px;
border-radius: 70px 10px 70px 10px;
height: 360px;
border: 1px solid #CCCCCC;
background-color:#FFF;
width: 400px;
padding:10px;
text-align:center;
font-family: Georgia, "Times New Roman", Times, serif;
display: block;
-webkit-box-shadow: 2px 2px 5px #999;
  -moz-box-shadow: 2px 2px 5px #999;
  filter: shadow(color=#999999, direction=135, strength=2);}

.login a{
color: #333333;
text-decoration: none;}

.login a:hover{
color: #666666;}

input {
-moz-border-radius: 10px;
-webkit-border-radius: 10px;
border-radius: 10px;
border: 1px solid #CCCCCC;
font-size: 22px;
text-align:center;
font-family: Georgia, "Times New Roman", Times, serif;
}

#error {
margin-top: 10px;
font-size: 12px;
color: #990000;
height: 50px;
}

.submit {
width: 48px;
height: 48px;
background: url(img/play_icon.png) no-repeat;
border: none;
text-indent: 9999px;}
.Estilo1 {font-size: 12px}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function () {
$(window).resize(function () {
document.getElementById('user').focus();
$('.login').css({
position: 'absolute',
display: 'inline',//mostramos el elemento
left: ($(window).width() - $('.login').outerWidth()) / 2,
top: ($(window).height() - $('.login').outerHeight()) / 2
});
});
// Corremos nuestra nueva funcion
$(window).resize();
$('#user input').focus(); 
});

</script>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/lightbox/thickbox.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>js/lightbox/thickbox.css" type="text/css" media="screen" />
<link rel="shortcut icon" href="favicon.ico" >
</head>

<body>
<noscript>
<div id="noscript">
<div class="alpha"><img src="img/warning.png" /><br />
Este sistema necesita tener Javascript activado en su navegador.<br /><br />

Por favor actívelo o utilice otro Navegador.
</div>
</div>
</noscript>

<div class="login">
<img src="logo.png" /><br /><div id="error"><?php echo $error?><?php if ($error == ''){echo '<span style="color:#088abc;">Bienvenid@ al Sistema de Registro Digital<br><i><strong>'.$institucion.'</strong></i></span>';}?></div>
<div style="width: 390px; border-bottom: 1px solid #CCCCCC; border-top: 1px solid #CCCCCC; padding: 5px; margin-bottom: 5px; margin-top: 5px;">
Base de datos: 
<select style="font-size: 24px;" onChange="document.location.href = this.value">
	<option value="http://ctpgevi.com/2011">2011</option>
    <option value="http://ctpgevi.com/2012" selected="selected">2012</option>
</select>
</div>
Usuario:<br /><form action="" method="post">
	<input name="user" type="text" id="user" tabindex="0" style="font-family: Century Gothic; font-size: 15px; height: 30px; width: 200px;"/><br />
    Contraseña:<br /><input name="pass" type="password" id="pass" style="width: 200px; height: 30px;"/>
    <br />
    <input type="submit" name="entrar" value="Entrar" class="submit" style="cursor: pointer;"/>
    <a href="olvide_mi_contrasena.php?keepThis=true&TB_iframe=true&height=300&width=400&h=0" title="¿Olvidó su contraseña?" class="thickbox Estilo1">Olvidé mi contraseña</a>
    </form>
</div>

<script type="text/javascript">
<!-- 
var navegador = navigator.appName 
if (navegador == "Microsoft Internet Explorer"){
direccion=("explorer.php"); 
window.location=direccion; 
}
//-->

</script>
</body>
</html>
