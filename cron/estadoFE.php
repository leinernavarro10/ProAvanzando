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

function sedmail($numeroFactura,$idSistema,$conn){

                 // Enable verbose debug output

	$sql = "SELECT * FROM tparametros WHERE id = '".$idSistema."' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		$sender = $row['emailEnvio'];
		$nombreEmpresa=$row['nombre'];
		$nombreEmpresa = $row['nomComercial'];
		$facturaElectronica = $row['facturaElectronica'];
	}

		$asunto = "Factura / ".$nombreEmpresa;
	

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
					$mail->addAttachment('../facturas/exports/f'.$row2['id'].'.png','FacGrafica'.$row2['numero'].'.png');
					}  
					if (file_exists('../parametros/archivos/firmados/'.$row2['numero'].'-'.$idSistema.'-firmada.xml')){
						$mail->addAttachment('../parametros/archivos/firmados/'.$row2['numero'].'-'.$idSistema.'-firmada.xml',''.$row2['numero'].'-'.$nombreEmpresa.'-firmada.xml');
					}
						if($row2['estadoFE']=='1' OR $row2['estadoFE']=='3'){
							if (file_exists('../parametros/archivos/respuestas/'.$row2['numero'].'-'.$idSistema.'-resp.xml')){
								$mail->addAttachment('../parametros/archivos/respuestas/'.$row2['numero'].'-'.$idSistema.'-resp.xml',''.$row2['numero'].'-'.$nombreEmpresa.'-resp.xml');
							}
						}
$mensaje = "Saludos. Adjunto encontrará los documentos relacionados con el Documento Electrónico con el número: ".$numeroFactura."<br>Estado de la factura: ";
			if($row2['estadoFE'] == '1'){
	        	$mensaje .=  "Aceptado";
	        }else if($row2['estadoFE']== '3'){
	        	$mensaje .= "Rechazado";
	        }
	        $mensaje .= "<br><br>--Gracias por su compra<br>
Factura generada por el servidor de <strong>www.coopeavanzando.com</strong>
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
		$sql = "UPDATE tfacturas SET estadoFE = '".$estado."' WHERE numero = '".$numeroFactura."' AND sistema = '".$idSistema."' ";
		$query = mysql_query($sql, $conn);
			if($estado==1 OR $estado==3){
				$xmlRespuesta=$resultado['respuesta-xml'];
				$textoXML = base64_decode($xmlRespuesta);
				$file=fopen("../parametros/archivos/respuestas/".$numeroFactura."-".$idSistema."-resp.xml","w+"); 
				fwrite ($file,$textoXML); 
				fclose($file);

				sedmail($numeroFactura,$idSistema,$conn);

			}else if($estado==2){
				verificar($numeroFactura,$idSistema,$conn);
			}
	}
	
}

function verificar($numeroFactura,$idSistema,$conn){
	$sql = "SELECT * FROM tparametros  WHERE id = '".$idSistema."'";
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
     if($result==true){
    	$token = json_decode($result); //get a token object
    	$authToken = $token->access_token;
   
	    $sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$idSistema."' ";
		$query = mysql_query($sql, $conn);
		while ($row=mysql_fetch_assoc($query)){
		  $consecutive = $row['consecutivoFE'];
		  $key = $row['claveFE'];
		  $fecha = $row['fechaFE'];
		  $idCliente = $row['idCliente'];
		}
		$curl = curl_init();
		if ($tipoFE == 0){
			$url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion";
		}else{
			$url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion";
		}
	  	curl_setopt_array($curl, array(
	      CURLOPT_URL => $url."/".$key."",
	      CURLOPT_HTTPHEADER => array(
	        "authorization: Bearer ".$authToken ,
	        "content-type: application/json"
	      ),
	      CURLOPT_SSL_VERIFYPEER => false,
	      CURLOPT_CONNECTTIMEOUT => 10,
		  CURLOPT_RETURNTRANSFER=> true,
		  CURLOPT_TIMEOUT => 60
	    ));
	  	try {
	  		//print($curl);
	  		$response = curl_exec($curl);
	  		$info = curl_getinfo($curl);	
	  		
	  		cambiarEstado($numeroFactura,$idSistema,$conn,$response);
	  	} catch (Exception $e) {
	  		//cambiarEstado($numeroFactura,$idSistema,$conn,$e);
	  		
	  	}
	  	curl_close($curl);
	}
}

function verEstado($numeroFactura,$idSistema,$conn){
	$archivoFirmado="../parametros/archivos/firmados/".$numeroFactura."-".$idSistema."-firmada.xml";
	$sql2 = "SELECT * FROM tparametros WHERE id = '".$idSistema."' ";
	$query2 = mysql_query($sql2, $conn);
	while ($row2=mysql_fetch_assoc($query2)){
	    $cedulaEmpresa = $row2['cedula'];
	    $tipoCedula = $row2['tipocedula'];
		$usuarioFE = trim($row2['usuarioFE']);
		$passwordFE = trim($row2['passFE']);
		$tipoFE = $row2['produccion'];
		$nombreComercial=$row2['nomComercial'];  
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

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
   // echo "Error 2";
    $result = file_get_contents($url, false, $context);
    //$result = get_url_contents($url);
    if ($result === FALSE) { echo $result; }
    if($result==true){
   		$token = json_decode($result); //get a token object
    //devuelvo el token
    //
		$authToken = $token->access_token;
		//echo $authToken;

//-------------------------EnviarFactura
		$invoice = file_get_contents($archivoFirmado);
	    $invoice = base64_encode($invoice);
	   
		 $sql3 = "SELECT * FROM tfacturas WHERE numero = '".$numeroFactura."' AND sistema='".$idSistema."' ";
		$query3 = mysql_query($sql3, $conn);
		while ($row3=mysql_fetch_assoc($query3)){
		  $consecutive = $row3['consecutivoFE'];
		  $key = $row3['claveFE'];
		  $fecha = $row3['fechaFE'];
		  $idCliente = $row3['idCliente'];
		  $idFactura = $row3['id'];
		}

		$tipoCedulaCLI = "";
		$cedulaCliente = "";
		$sql4 = "SELECT * FROM tclientes WHERE id = '$idCliente'";
		$query4 = mysql_query($sql4, $conn);
		while ($row4=mysql_fetch_assoc($query4)){
		      if ($row4['tipoIdentificacion'] == ""){
		      	$tipoCedulaCLI = "00";
		      }else{
		      	$tipoCedulaCLI = $row4['tipoIdentificacion'];
		      }
		      $cedulaCliente = $row4['identificacion'];
		  }
		if ($tipoFE == 0){
			$url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion";
		}else{
			$url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion";
		}
	    $curl = curl_init();
	    $fields = "{\n\t\"clave\": \"$key\","
	        . "\n\t\"fecha\": \"".$fecha."\","
	        . "\n\t\"emisor\": {\n\t\t\"tipoIdentificacion\": \"".zeroFill(2,$tipoCedula)."\",\n\t\t\"numeroIdentificacion\": \"".str_replace("-", "", str_replace(" ", "", $cedulaEmpresa))."\"\n\t},";
	        if ($cedulaCliente != ""){
	        	$fields .= "\n\t\"receptor\": {\n\t\t\"tipoIdentificacion\": \"".zeroFill(2,$tipoCedulaCLI)."\",\n\t\t\"numeroIdentificacion\": \"".str_replace("-", "", str_replace(" ", "", $cedulaCliente))."\"\n\t},";
	    	}
	    $fields .= "\n\t\"callbackUrl\": \"https://example.com/invoiceView\","
	        . "\n\t\"comprobanteXml\": \"$invoice\"\n}";
	      curl_setopt_array($curl, array(
	      CURLOPT_URL => $url,
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_SSL_VERIFYPEER => false,
	      CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "POST",
	      CURLOPT_POSTFIELDS => $fields,
	      CURLOPT_COOKIE => "__cfduid=d73675273d6c68621736ad9329b7eff011507562303",
	      CURLOPT_HTTPHEADER => array(
	        "authorization: Bearer ".$authToken ,
	        "content-type: application/json"
	      ),
	    ));
		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		echo curl_error($curl);
		curl_close($curl);
	////validar

			$curl = curl_init();
			
		  	curl_setopt_array($curl, array(
		      CURLOPT_URL => $url."/".$key."",
		      CURLOPT_HTTPHEADER => array(
		        "authorization: Bearer ".$authToken ,
		        "content-type: application/json"
		      ),
		      CURLOPT_RETURNTRANSFER=> true
		    ));
		try {
		 	$response = curl_exec($curl);
		  	$info = curl_getinfo($curl);
			cambiarEstado($numeroFactura,$idSistema,$conn,$response);
		} catch (Exception $e) {
	  		//cambiarEstado($numeroFactura,$idSistema,$conn,$e);
	  		
	  	}  
	 
		  curl_close($curl);
	}  
}


$sql = "SELECT * FROM tfacturas WHERE (estadoFE = '2' OR estadoFE = '0')  AND claveFE!=''";
$query=mysql_query($sql,$conn)or die(mysql_error());
while ($row=mysql_fetch_assoc($query)) {
	$numeroFactura = $row['numero'];

	$sql2 = "SELECT * FROM tparametros WHERE id = '".$row['sistema']."' ";
	$query2 = mysql_query($sql2, $conn);
	while ($row2=mysql_fetch_assoc($query2)){
		$tipoFE = $row2['produccion'];
	}

	$archivoFirmado="../parametros/archivos/firmados/".$numeroFactura."-".$row['sistema']."-firmada.xml";
	if(file_exists($archivoFirmado)){
	//echo $numeroFactura."<br>";
	
	if($row['estadoFE']==0){
		verEstado($numeroFactura,$row['sistema'],$conn);
	}
	if($row['estadoFE']==2){
		verificar($numeroFactura,$row['sistema'],$conn);
	}
//-----Cierra if
}
//------Cierra While
}


?>