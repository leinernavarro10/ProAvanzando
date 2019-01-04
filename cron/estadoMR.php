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

function sedmail($clave,$consecutivoReceptor,$idSistema,$conn){

                 // Enable verbose debug output

	$sql = "SELECT * FROM tparametros WHERE id = '".$idSistema."' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		$sender = $row['emailEnvio'];
		$nombreEmpresa=$row['nombre'];
		$nombreEmpresa = $row['nomComercial'];
		$facturaElectronica = $row['facturaElectronica'];
	}

		$asunto = "Compra Registrada / ".$nombreEmpresa;
	

	$mail = new PHPMailer;
	$mail->SMTPDebug = 3;  
	$mail->IsMAIL();            
	   // Add a recipient

				$sql2 = "SELECT * FROM tmensajereceptorfe WHERE clave = '".$clave."' AND consecutivoReceptor='".$consecutivoReceptor."' AND sistema = '".$idSistema."'";
				$query2 = mysql_query($sql2, $conn);

				while($row2=mysql_fetch_assoc($query2)){
					$id=$row2['id'];

						$cedulaEmisor = preg_replace('/^0+/', '', $row2['cedulaEmisor']); 
					  	$sql2c = "SELECT * FROM tclientes WHERE identificacion = '".$cedulaEmisor."' AND usuario='".$idSistema."' ";
					  	$query2c = mysql_query($sql2c, $conn);
					 	while ($row2c=mysql_fetch_assoc($query2c)) {
					 		$email=$row2c['email'];
					    	//echo $row2c['emailEnvio'];
					   	}
					
					
					//$mail->addAttachment('../facturas/exports/f'.$row2['id'].'.png','FacGrafica'.$row2['numero'].'.png');
					
					if (file_exists("../parametros/archivos/firmados/MReceptor-".$consecutivoReceptor."-".$idSistema."-firmada.xml")){
						$mail->addAttachment('../parametros/archivos/firmados/MReceptor-'.$consecutivoReceptor.'-'.$idSistema.'-firmada.xml','MReceptor-'.$consecutivoReceptor.'-'.$nombreEmpresa.'-firmada.xml');
					}
					if($row2['estado']=='1' OR $row2['estado']=='3'){
						if (file_exists("../parametros/archivos/respuestas/MReceptor-".$consecutivoReceptor."-".$idSistema."-resp.xml")){
							$mail->addAttachment("../parametros/archivos/respuestas/MReceptor-".$consecutivoReceptor."-".$idSistema."-resp.xml",'MReceptor-'.$consecutivoReceptor.'-'.$nombreEmpresa.'-resp.xml');
						}
					}

		if($email!=""){				
			$mensaje = "Saludos. <br>Hemos recibido el siguiente comprobante electronico:<br>
			Clave: ".$clave."<br>
			Nº Consecutivo: ".$consecutivoReceptor."<br>
			Fecha Emisión: ".$row2['fechaEmision']."<br>
			Total Imp.: ".$row2['totalImpuesto']."<br>
			Total del Comprobante: ".$row2['totalFactura']."<br>

			El comprobante fue: ";
					if($row2['mensaje'] == '1'){
				        	$mensaje .=  "Aceptado";
				        }else if($row2['mensaje']== '3'){
				        	$mensaje .= "Rechazado";
				        }
				        $mensaje .= " por nosotros ante hacienda. <br><br>--Gracias por su compra<br>
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
			    echo 'Error: ' . $mail->ErrorInfo;
			} else {
				
				
				
			   // echo 'El mensaje fue enviado correctamente.';
			}
		}
	}
	

}


function cambiarEstado($clave,$consecutivoReceptor,$idSistema,$conn,$response){
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
		
		$consecutivo = $consecutivoReceptor;
		$estadoFE = $estado;
		$xmlRespuesta = $response;

		$sql = "UPDATE tmensajereceptorfe SET estado = '$estadoFE' WHERE consecutivoReceptor = '$consecutivo' AND sistema = '".$idSistema."'";
		$query = mysql_query($sql, $conn);

		$textoXML = base64_decode($xmlRespuesta);

		$file=fopen("../parametros/archivos/respuestas/MReceptor-".$consecutivo."-".$idSistema."-resp.xml","w+"); 
		fwrite ($file,$textoXML); 
		fclose($file);
		if($estado==1 OR $estado==3){
			sedmail($clave,$consecutivoReceptor,$idSistema,$conn);
		}
	}
	
}



function verEstado($clave,$consecutivoReceptor,$idSistema,$conn){
	$clave = $clave;
	$consecutivo = $consecutivoReceptor;
	$sql = "SELECT * FROM tparametros WHERE id = '".$idSistema."' ";
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

	$curl = curl_init();

	if ($tipoFE == 0){
		$url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion";
	}else{
		$url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion";
	}
	//echo "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/".$key;

  	curl_setopt_array($curl, array(
      CURLOPT_URL => $url."/".$clave."-".$consecutivo."",
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$authToken ,
        "content-type: application/json"
      ),
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_CONNECTTIMEOUT => 10,
	  CURLOPT_RETURNTRANSFER => 1,
	  CURLOPT_TIMEOUT => 60
    ));

  	try {
  		//print($curl);
  		$response = curl_exec($curl);
  		$info = curl_getinfo($curl);

  		cambiarEstado($clave,$consecutivoReceptor,$idSistema,$conn,$response);
  	} catch (Exception $e) {
  		//echo $e;
  	}
  	curl_close($curl);
}


$sql = "SELECT * FROM tmensajereceptorfe WHERE estado='0' ORDER by id DESC"; 
$query=mysql_query($sql,$conn)or die(mysql_error());
while ($row=mysql_fetch_assoc($query)) {
	$clave = $row['clave'];
	$consecutivoReceptor= $row['consecutivoReceptor'];
	$sql2 = "SELECT * FROM tparametros WHERE id = '".$row['sistema']."' ";
	$query2 = mysql_query($sql2, $conn);
	while ($row2=mysql_fetch_assoc($query2)){
		$tipoFE = $row2['produccion'];
	}
//echo $clave."<br>";
	$archivoFirmado="../parametros/archivos/firmados/MReceptor-".$consecutivoReceptor."-".$row['sistema']."-firmada.xml";
	if(file_exists($archivoFirmado)){
	//echo $numeroFactura."<br>";
	
	if($row['estado']==0){
		verEstado($clave,$consecutivoReceptor,$row['sistema'],$conn);
	}
	
//-----Cierra if
}
//------Cierra While
}


?>