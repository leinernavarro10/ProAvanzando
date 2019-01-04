<?php 
include("conn.php");
require '../assets/libs/PHPMailer/PHPMailerAutoload.php';
function get_url_contents($url){  
	  if (function_exists('file_get_contents')) {  
	    $result = @file_get_contents($url);  
	  }  
	  if ($result == '') {  
	    $ch = curl_init();  
	    $timeout = 30;  
	    curl_setopt($ch, CURLOPT_URL, $url);  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
	    $result = curl_exec($ch);  
	    curl_close($ch);  
	  }  

	  return $result;  
	}

function sedmail($numeroFactura,$idSistema,$conn,$estado){

                 // Enable verbose debug output

	$sql = "SELECT * FROM tparametros WHERE id = '".$idSistema."' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		$sender = $row['emailEnvio'];
		$nombreEmpresa=$row['nombre'];
		$nombreEmpresa = $row['nomComercial'];
		$facturaElectronica = $row['facturaElectronica'];
	}

		$asunto = "Nota de Credito / ".$nombreEmpresa;
	

	$mail = new PHPMailer;
	$mail->SMTPDebug = 3;  
	$mail->IsMAIL();            
	   // Add a recipient

				
				$sql2 = "SELECT * FROM tfacturas WHERE numero = '".$numeroFactura."' AND email !='' AND sistema = '".$idSistema."'";
				$query2 = mysql_query($sql2, $conn);

				while($row2=mysql_fetch_assoc($query2)){
					$id=$row2['id'];
					$email=$row2['email'];
					if (file_exists('../facturas/exports/f'.$row2['id'].'.png')){
					$mail->addAttachment('../facturas/exports/f'.$row2['id'].'.png','NotaCreGrafica'.$row2['numero'].'.png');
					}  
					if (file_exists('../parametros/archivos/firmados/NCredito-'.$row2['numero'].'-'.$idSistema.'-firmada.xml')){
						$mail->addAttachment('../parametros/archivos/firmados/NCredito-'.$row2['numero'].'-'.$idSistema.'-firmada.xml','NCredito-'.$row2['numero'].'-'.$nombreEmpresa.'-firmada.xml');
					}
						if($row2['estadoFE']=='1' OR $row2['estadoFE']=='3'){
							if (file_exists('../parametros/archivos/respuestas/NCredito-'.$row2['numero'].'-'.$idSistema.'-resp.xml')){
								$mail->addAttachment('../parametros/archivos/respuestas/NCredito-'.$row2['numero'].'-'.$idSistema.'-resp.xml','NCredito-'.$row2['numero'].'-'.$nombreEmpresa.'-resp.xml');
							}
						}
$mensaje = "Saludos. Adjunto encontrará los documentos relacionados con la Nota de Credito que anula la Factura con el número: ".$numeroFactura."<br>Estado de la factura: ";
		if($estado == '1'){
	        	$mensaje .=  "Aceptado";
	        }else if($estado== '3'){
	        	$mensaje .= "Rechazado";
	        }
	        $mensaje .= "<br><br>
Nota de credito generada por el servidor de <strong>www.coopeavanzando.com</strong>
";
	$body = $mensaje;
	$mail->setFrom($sender,'Facturas '.$nombreEmpresa);
	$mail->From = $sender;
	$mail->FromName = $nombreEmpresa;
	$mail->addAddress($email);  

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $asunto;
	$mail->Body    = $body;
	$mail->AltBody = strip_tags($body);

	$mail->CharSet = 'UTF-8';

	if(!$mail->send()) {
	    //echo 'Error: ' . $mail->ErrorInfo;
	} else {
		
		if (file_exists('../facturas/exports/f'.$id.'.png')){
				unlink('../facturas/exports/f'.$id.'.png');
		}
		
	   // echo 'El mensaje fue enviado correctamente.';
		}

	}
	

}


function cambiarEstado($numeroFactura,$idSistema,$conn,$response){
//	echo $response;
$string = $response;
$resultado = json_decode($string, true);

	
$estado=0; 	

			if($resultado['ind-estado'] == 'aceptado'){
	        	$estado = 1;
	        }else if($resultado['ind-estado'] == 'rechazado'){
	        	$estado = 3;
	        }else{
	        	$estado = 2;
	        }


	if($estado==0){
		//echo $response;
	}else{
		//echo "Estado: ".$estado."<br>";
		
		$numeroFactura = $numeroFactura;
		$estadoFE = $estado;
		$xmlRespuesta = $response;
		$sql = "SELECT * FROM tparametros WHERE id = '".$idSistema."'";
		$query = mysql_query($sql, $conn)or die(mysql_error());
		while ($row=mysql_fetch_assoc($query)){
		    $nombreComercial=$row['nomComercial'];
		}

		$sql = "UPDATE tnotasCreditoFE SET estadoFE = '$estadoFE' WHERE numeroFactura = '$numeroFactura' AND sistema = '".$idSistema."' ";
		$query = mysql_query($sql, $conn);

		$sql = "UPDATE tfacturas SET estado = '3' WHERE numero = '$numeroFactura' AND sistema = '".$idSistema."' ";
		$query = mysql_query($sql, $conn);

		$textoXML = base64_decode($xmlRespuesta);

		$file=fopen("../parametros/archivos/respuestas/NCredito-".$numeroFactura."-".$idSistema."-resp.xml","w+"); 
		fwrite ($file,$textoXML); 
		fclose($file);

		if($estado==1 OR $estado==3){
			sedmail($numeroFactura,$idSistema,$conn,$estado);
		}
	}
	
}



function verEstado($numeroFactura,$idSistema,$conn){
	$numeroFactura = $numeroFactura;

	$sql = "SELECT * FROM tnotasCreditoFE WHERE numeroFactura = '$numeroFactura' AND sistema = '".$idSistema."' ORDER BY id DESC";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $consecutive = $row['consecutivoNC'];
	  $key = $row['claveNC'];
	  $fecha = $row['fechaNC'];
	}

	$sql = "SELECT * FROM tparametros WHERE id = '".$idSistema."'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	      $cedulaEmpresa = $row['cedula'];
	      $tipoCedula = $row['tipocedula'];
		  $usuarioFE = trim($row['usuarioFE']);
		  $passwordFE = trim($row['passFE']);
		  $tipoFE = $row['produccion'];
	}

	if ($tipoFE == 0){
		$client_id = "api-stag";
		$url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token";
	}else{
		$client_id = "api-prod";
		$url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut/protocol/openid-connect/token/";
	}
	    
    $data = array('client_id' => $client_id,//Test: 'api-stag' Production: 'api-prod'
                  'client_secret' => '',//always empty
                  'grant_type' => 'password', //always 'password'
                  //go to https://www.hacienda.go.cr/ATV/login.aspx to generate a username and password credentials
                  'username' => $usuarioFE, 
                  'password' => $passwordFE, 
                  'scope' =>'');//always empty

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) { echo $result; }
    $token = json_decode($result); //get a token object
    //devuelvo el token
    $authToken = $token->access_token;

    $sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$idSistema."' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $idCliente = $row['idCliente'];
	}

	$curl = curl_init();

	if ($tipoFE == 0){
		$url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion";
	}else{
		$url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion";
	}
	//echo "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/".$key;

  	curl_setopt_array($curl, array(
      CURLOPT_URL => $url."/".$key."",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$authToken ,
        "content-type: application/json"
      ),
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_CONNECTTIMEOUT => 10,
	  CURLOPT_RETURNTRANSFER => 1,
	  CURLOPT_TIMEOUT => 60
    ));

  	//echo $url."/".$key."";

  	try {
  		//print($curl);
  		$response = curl_exec($curl);
  		$info = curl_getinfo($curl);	
  		cambiarEstado($numeroFactura,$idSistema,$conn,$response);
  	} catch (Exception $e) {
  		//echo $e;
  	}
  	curl_close($curl);
}


$sql = "SELECT * FROM tnotasCreditoFE WHERE estadoFE = 2  ORDER by id DESC"; 
$query=mysql_query($sql,$conn)or die(mysql_error());
while ($row=mysql_fetch_assoc($query)) {
	$numeroFactura = $row['numeroFactura'];

	$sql2 = "SELECT * FROM tparametros WHERE id = '".$row['sistema']."' ";
	$query2 = mysql_query($sql2, $conn);
	while ($row2=mysql_fetch_assoc($query2)){
		$tipoFE = $row2['produccion'];
	}
//echo $clave."<br>";
	$archivoFirmado="../parametros/archivos/firmados/NCredito-".$numeroFactura."-".$row['sistema']."-firmada.xml";
	if(file_exists($archivoFirmado)){
	//echo $numeroFactura."<br>";
	
	
		verEstado($numeroFactura,$row['sistema'],$conn);
	
	
//-----Cierra if
		}
//------Cierra While
}


?>