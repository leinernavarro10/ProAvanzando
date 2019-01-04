<?php 
include("../conn/connpg.php");

$email = $_POST['email'];
$pre = $_POST['pre'];
$ids = $_POST['id'];

$NC = $_POST['NC'];


require '../assets/libs/PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->SMTPDebug = 3;  
$mail->IsMAIL();                             // Enable verbose debug output
if(isset($_POST['sistema'])){
	$sql = "SELECT * FROM tparametros WHERE id = '".$_POST['sistema']."' ";
	$cuentaid=$_POST['sistema'];
}else{
	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
}
$query = mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($query)) {
	$sender = $row['emailEnvio'];
	$nombreEmpresa=$row['nombre'];
	$nombreEmpresa = $row['nomComercial'];
	$facturaElectronica = $row['facturaElectronica'];
}



if ($pre == "p"){
	$asunto = "Cotización (factura proforma)";
}else if ($pre == "f"){
	if ($NC == 1){
		$asunto = "Nota de Credito / ".$nombreEmpresa;
	}else{
		$asunto = "Factura /".$nombreEmpresa;
	}
}
$mail->setFrom($sender,'Facturas '.$nombreEmpresa);
$mail->From = $sender;
$mail->FromName = $nombreEmpresa;
$mail->addAddress($email);     // Add a recipient

$id = explode("-", $ids);
for ($i = 0; $i < count($id); $i++){
	if ($id[$i]!=""){
		$mail->addAttachment('exports/'.$pre.$id[$i].'.png');         // Add attachments

		if ($facturaElectronica == 1 and $pre == 'f'){
			$sql2 = "SELECT * FROM tfacturas WHERE id = '".$id[$i]."'";
			$query2 = mysql_query($sql2, $conn);

			while($row2=mysql_fetch_assoc($query2)){

				if ($NC == 1){
					$sql = "SELECT * FROM tnotasCreditoFE WHERE numeroFactura = '".$row2['numero']."' AND sistema='".$cuentaid."' "; 
					$query=mysql_query($sql,$conn)or die(mysql_error());
					while ($row=mysql_fetch_assoc($query)) {
						if($row2['estadoFE'] == '1'){
		        			$EstadoME=  "Aceptado";
				        }else if($row2['estadoFE']== '3'){
				        	$EstadoME= "Rechazado";
				        }
					}
				}else{
					if($row2['estadoFE'] == '1'){
		        		$EstadoME=  "Aceptado";
			        }else if($row2['estadoFE']== '3'){
			        	$EstadoME= "Rechazado";
			        }
			    }

				if ($NC == 1){
					$mail->addAttachment('../parametros/archivos/firmados/NCredito-'.$row2['numero'].'-'.$cuentaid.'-firmada.xml','NotadeCredito-'.$row2['numero'].'-'.$nombreEmpresa.'.xml'); 

					if($row2['estadoFE']=='1'){
					$mail->addAttachment('../parametros/archivos/respuestas/NCredito-'.$row2['numero'].'-'.$cuentaid.'-resp.xml','NotadeCredito-'.$row2['numero'].'-'.$nombreEmpresa.'-resp.xml');
					}
				}else{
					$mail->addAttachment('../parametros/archivos/firmados/'.$row2['numero'].'-'.$cuentaid.'-firmada.xml',''.$row2['numero'].'-'.$nombreEmpresa.'-firmada.xml');
					if($row2['estadoFE']=='1'){
					$mail->addAttachment('../parametros/archivos/respuestas/'.$row2['numero'].'-'.$cuentaid.'-resp.xml',''.$row2['numero'].'-'.$nombreEmpresa.'-resp.xml');
					}
					 
				}
			}
			
		}
	}
}
			
$mensaje = $_POST['mensaje'];
$body = $mensaje."<br>Estado de la factura: ".$EstadoME."";
if ($NC == 1){
	$body.="<br><br>Nota de credito generada por el servidor de <strong>www.coopeavanzando.com</strong>";
}else{
	$body.="<br><br>--Gracias por su compra<br>Factura generada por el servidor de <strong>www.coopeavanzando.com</strong>";
}

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $asunto;
$mail->Body    = $body;
$mail->AltBody = strip_tags($body);

$mail->CharSet = 'UTF-8';

if(!$mail->send()) {
    echo 'Error: ' . $mail->ErrorInfo;
} else {
	$id = explode("-", $ids);
	for ($i = 0; $i < count($id); $i++){
		if ($id[$i]!=""){
			unlink('exports/'.$pre.$id[$i].'.png');
		}
	}
    echo 'El mensaje fue enviado correctamente.';
}

?>