<?php 
namespace App\Library;
require '../parametros/firmadocr.php';
$firmador = new Firmadocr();

include ("../conn/connpg.php");

require '../assets/libs/PHPMailer/PHPMailerAutoload.php';

function normaliza($cadena) {
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

$f = $_POST['f'];

//////GUARDAR FACTURA
if ($f == 'GUARDAR-FACTURA'){
	$idFactura = $_POST['idFactura'];
	$fecha = date("d/m/Y");
	$idCliente = $_POST['idCliente'];
	$cliente = $_POST['cliente'];
	$email = $_POST['email'];
	$descuento = $_POST['descuento'];
	$vendedor = $_POST['vendedor'];
	$tipoFactura = $_POST['tipoFacturaText'];
	$observacion = $_POST['observacion'];
	$exonerar = $_POST['exonerar'];
	$caja = $_POST['caja'];
	$plazo = $_POST['plazo'];

	if ($observacion != ""){
		$sql = "SELECT * FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
		$query = mysql_query($sql, $conn);
		if (mysql_num_rows($query) == 0){
			$sql = "INSERT INTO tobservacionesfactura VALUES (null, '$idFactura','$observacion')";
			$query = mysql_query($sql, $conn);
		}else{
			$sql = "UPDATE tobservacionesfactura SET observacion = '$observacion' WHERE idFactura = '$idFactura'";
			$query = mysql_query($sql, $conn);
		}
	}else{
		$sql = "DELETE FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
		$query = mysql_query($sql, $conn);
	}

	$sql = "UPDATE tfacturas SET 
	idCliente = '$idCliente',
	nombreCliente = '$cliente',
	email = '$email',
	exonerar = '$exonerar',
	descuento = '$descuento',
	idVendedor = '$vendedor', 
	idCaja = '$caja', 
	plazo = '$plazo' 
	WHERE id = '$idFactura' AND sistema = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
}

//////GUARDAR FACTURA
if ($f == 'GUARDAR-OBSERVACION'){
	$idFactura = $_POST['idFactura'];
	$observacion = $_POST['observacion'];

	if ($observacion != ""){
		$sql = "SELECT * FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
		$query = mysql_query($sql, $conn);
		if (mysql_num_rows($query) == 0){
			$sql = "INSERT INTO tobservacionesfactura VALUES (null, '$idFactura','$observacion')";
			$query = mysql_query($sql, $conn);
		}else{
			$sql = "UPDATE tobservacionesfactura SET observacion = '$observacion' WHERE idFactura = '$idFactura'";
			$query = mysql_query($sql, $conn);
		}
	}else{
		$sql = "DELETE FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
		$query = mysql_query($sql, $conn);
	}
}

//////FINALIZAR FACTURA
if ($f == 'FINALIZAR-FACTURA'){

	$idFactura = $_POST['idFactura'];

	$sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idFactura' ";
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) != 0){

		$fecha = date("d/m/Y");
		$idCliente = $_POST['idCliente'];
		$cliente = $_POST['cliente'];
		$email = $_POST['email'];
		$descuento = $_POST['descuento'];
		$vendedor = $_POST['vendedor'];
		$tipoFactura = $_POST['tipoFactura'];
		$observacion = $_POST['observacion'];
		$exonerar = $_POST['exonerar'];
		$finalizar = $_POST['finalizar'];
		$caja = $_POST['caja'];
		$plazo = $_POST['plazo'];
		$formaPago = $_POST['formaPago'];

		$tipoDoc = $_POST['tipoDoc'];
		$ref = $_POST['ref'];

		$contingencia = $_POST['contingencia'];
		$sininternet = $_POST['sininternet'];

		if ($contingencia == 1){
			if ($sininternet == 1){
				$situacionDoc = 3;
			}else{
				$situacionDoc = 2;
			}
		}else{
			$situacionDoc = 1;
		}

		
		$caja = $_POST['caja'];

		$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
		$query = mysql_query($sql, $conn);
		while ($row=mysql_fetch_assoc($query)){
		      $cedulaEmpresa = $row['cedula'];
		}

		if ($observacion != ""){
			$sql = "SELECT * FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
			$query = mysql_query($sql, $conn);
			if (mysql_num_rows($query) == 0){
				$sql = "INSERT INTO tobservacionesfactura VALUES (null, '$idFactura','$observacion')";
				$query = mysql_query($sql, $conn);
			}else{
				$sql = "UPDATE tobservacionesfactura SET observacion = '$observacion' WHERE idFactura = '$idFactura'";
				$query = mysql_query($sql, $conn);
			}
		}else{
			$sql = "DELETE FROM tobservacionesfactura WHERE idFactura = '$idFactura'";
			$query = mysql_query($sql, $conn);
		}

		$sql = "UPDATE tfacturas SET 
		idCliente = '$idCliente',
		nombreCliente = '$cliente',
		email = '$email',
		exonerar = '$exonerar',
		descuento = '$descuento',
		idVendedor = '$vendedor',
		idCaja = '$caja', 
		plazo = '$plazo', 
		estadoFE='0'
		WHERE id = '$idFactura' AND sistema = '".$cuentaid."'";
		$query = mysql_query($sql, $conn);

		if($finalizar == 1){

			$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
			$query = mysql_query($sql, $conn);
			while ($row=mysql_fetch_assoc($query)){
			      $facturaElectronica = $row['facturaElectronica'];
			}

			$sql2 = "SELECT * FROM tfacturas WHERE id = '$idFactura' AND sistema = '".$cuentaid."' ";
			$query2 = mysql_query($sql2, $conn);
			while ($row2=mysql_fetch_assoc($query2)) {
				$estado = $row2['estado'];
				$codigoSeguridad = $row2['codigoSeguridad'];
				$caja=$row2['idCaja'];
				if ($estado == 1){
					$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
					$query = mysql_query($sql, $conn)or die(mysql_error());
					while ($row=mysql_fetch_assoc($query)) {
						$numeroFactura = $row['factura']+1;
						$sql4 = "UPDATE tparametros SET factura = '$numeroFactura' WHERE id = '".$cuentaid."' ";
						$query4 = mysql_query($sql4, $conn);
					}


					$sql = "SELECT * FROM tcajas WHERE id = '$caja' AND sistema = '".$cuentaid."' ";
					$query = mysql_query($sql, $conn);
					while ($row=mysql_fetch_assoc($query)) {
						$numeroCaja = $row['numero'];
						$idSucursal = $row['idSucursal'];
						$ultimaFE = $row['ultimaFE'];
						$ultimaTE = $row['ultimaTE'];
					}

					$sql = "SELECT * FROM tsucursales WHERE id = '$idSucursal'  ";
					$query = mysql_query($sql, $conn);
					while ($row=mysql_fetch_assoc($query)) {
						$numeroSucursal = $row['numero'];
					}


					if ($facturaElectronica == 1){
						
						if ($tipoDoc == "01"){
							$numeroFacturaFE = $ultimaFE+1;
							$sql4 = "UPDATE tcajas SET ultimaFE = '$numeroFacturaFE' WHERE id = '$caja' AND sistema = '".$cuentaid."'";
							$query4 = mysql_query($sql4, $conn);
							/*$sql = "SELECT * FROM tparametros WHERE id = 33";
							$query = mysql_query($sql, $conn)or die(mysql_error());
							while ($row=mysql_fetch_assoc($query)) {
								$numeroFacturaFE = $row['valor']+1;
								$sql4 = "UPDATE tparametros SET valor = '$numeroFacturaFE' WHERE id = 33";
								$query4 = mysql_query($sql4, $conn);
							}*/
						}else if ($tipoDoc == "04"){
							$numeroFacturaFE = $ultimaTE+1;
							$sql4 = "UPDATE tcajas SET ultimaTE = '$numeroFacturaFE' WHERE id = '$caja' AND sistema = '".$cuentaid."'";
							$query4 = mysql_query($sql4, $conn);
							/*$sql = "SELECT * FROM tparametros WHERE id = 36";
							$query = mysql_query($sql, $conn)or die(mysql_error());
							while ($row=mysql_fetch_assoc($query)) {
								$numeroFacturaFE = $row['valor']+1;
								$sql4 = "UPDATE tparametros SET valor = '$numeroFacturaFE' WHERE id = 36";
								$query4 = mysql_query($sql4, $conn);
							}*/
						}
					}

					$consecutivo = "";
					if ($facturaElectronica == 1){
						$consecutivo = zeroFill(3,$numeroSucursal);
						$consecutivo .= zeroFill(5,$numeroCaja);
						$consecutivo .= $tipoDoc;
						//$consecutivo .= zeroFill(2,1);
						$consecutivo .= zeroFill(10,$numeroFacturaFE);
					}

					$cedulaEmpresa = str_replace("-", "", str_replace(" ", "", $cedulaEmpresa));

					$clave = "";
					if ($facturaElectronica == 1){
						$clave = '506';
						$clave .= date("d");
						$clave .= date("m");
						$clave .= date("y");
						$clave .= zeroFill(12, $cedulaEmpresa);
						$clave .= zeroFill(20, $consecutivo);
						$clave .= $situacionDoc;
						$clave .= $codigoSeguridad;
					}

					/*
					$fechaFE = date("Y-m-d");
					$fechaFE .= 'T';
					$fechaFE .= date("h:m:s-0600");*/


					$fechaFE = date('Y-m-d\TH:i:sP');


					if ($tipoFactura == 0){
						$sql3 = "UPDATE tfacturas SET numero = '$numeroFactura', fechaCierre = '$fecha', estado = 2, claveFE = '$clave', consecutivoFE = '$consecutivo', fechaFE = '$fechaFE' WHERE id = '$idFactura' AND sistema = '".$cuentaid."' ";
						//ESTADO = 2
						$query3 = mysql_query($sql3, $conn);
					}else if ($tipoFactura == 1){
					  $sql = "SELECT * FROM tfacturas WHERE id = '$idFactura' AND sistema = '".$cuentaid."' ";
					  $query = mysql_query($sql, $conn);
					  while ($row=mysql_fetch_assoc($query)) {
					    $monto = $row['monto'];

					    $sql = "INSERT INTO tcuentascobrar VALUES (null, '$numeroFactura', '$idCliente', '$cliente', '$fecha', '$monto', 1,'".$cuentaid."')";
					    $query = mysql_query($sql, $conn)or die(mysql_error());

					    $sql = "UPDATE tfacturas SET 
					    numero = '$numeroFactura', fechaCierre = '$fecha', estado = 4, claveFE = '$clave', consecutivoFE = '$consecutivo', fechaFE = '$fechaFE'
					    WHERE id = '$idFactura' AND sistema = '".$cuentaid."'";
					    //ESTADO = 4
					    $query = mysql_query($sql, $conn);
					    $mensajeCC = 1;
					  }

					}
				}
			}
		}
		
		if ($facturaElectronica == 1){//////INICIO CONDICION FACTURA ELECTRONICA. TODO EL PROCESO SE REALIZA SOLO SI ESTÁ ACTIVA LA FACTURA ELECTRÓNICA
			echo $tipoFactura.'-'.$numeroFactura;
		}else{
			echo $tipoFactura.'-'.'0';
		}
	}else{
		echo "error-Por favor agregue al menos un producto a la factura.";
	}
}

///GENERAR ARCHIVO XML
if ($f == 'GENERAR-XML-FE'){
	
	$idFactura = $_POST['idFactura'];
	$fecha = date("d/m/Y");
	$idCliente = $_POST['idCliente'];
	$cliente = $_POST['cliente'];
	$email = $_POST['email'];
	$descuento = $_POST['descuento'];
	$vendedor = $_POST['vendedor'];
	$tipoFactura = $_POST['tipoFactura'];
	$observacion = $_POST['observacion'];
	$exonerar = $_POST['exonerar'];
	$finalizar = $_POST['finalizar'];
	$numeroFactura = $_POST['numeroFactura'];
	$tipoDoc = $_POST['tipoDoc'];

	$contingencia = $_POST['contingencia'];
	$sininternet = $_POST['sininternet'];
	$referencia = $_POST['referencia'];
	$fechaReferencia = $_POST['fechaReferencia'];

	$PlazoCredito = $_POST['plazoCredito'];

	$sql2 = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."' LIMIT 1";
	$query2 = mysql_query($sql2, $conn)or die(mysql_error());
	while ($row2=mysql_fetch_assoc($query2)) {
		$estado = $row2['estado'];
		$consecutivoFE = $row2['consecutivoFE'];
		$claveFE = $row2['claveFE']; 
		$fechaFE = $row2['fechaFE'];
		$plazo = $row2['plazo'];
		$exonerar = $row2['exonerar'];
	}

	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	      $nombreEmpresa = normaliza($row['nombre']);
	      $nombreComercial = normaliza($row['nomComercial']);
	      $direccion = $row['otrasSenas'];
	      $web = "";
	      $emailEmpresa = $row['email'];
	      $telefonoEmpresa = $row['telefono'];
	      $tributacion = $row['leyendaTributaria'];
	      $cedulaEmpresa = $row['cedula'];
	      $tipoCedula = $row['tipocedula'];
	      $observaciones = "";
	      $imprimirCopia = $row['imprimirCopia'];
	      $facturaElectronica = $row['facturaElectronica'];
		  $provincia = $row['provincia'];
		  $canton = $row['canton'];
		  $distrito = $row['distrito'];
		  $barrio = $row['barrio'];
		  $OtrasSenas = normaliza($row['otrasSenas']);
	}




	if ($tipoFactura == 0){
		$CondicionVenta = "01";
	}else{
		$CondicionVenta = "02";
	}	
	
	$tipoCedulaCLI = '01';
	$cedulaCLI = '000000000';
	$nombreCliente = 'Cliente de contado';
	$provinciaCLI = $provincia;
	$cantonCLI = $canton;
	$distritoCLI = $distrito;
	$barrioCLI = $barrio;
	$direccionCLI = '';
	$telefonoCLI = $telefonoEmpresa;
	$emailCLI = 'mail@mail.com';

	$sql2 = "SELECT * FROM tclientes WHERE id = '$idCliente' ";
	$query2 = mysql_query($sql2, $conn)or die(mysql_error());
	while ($row2=mysql_fetch_assoc($query2)) {
		$tipoCedulaCLI = $row2['tipoIdentificacion'];
		$cedulaCLI = $row2['identificacion'];
		$nombreCliente = normaliza($row2['nombre']);
		$provinciaCLI = $row2['idProvincia'];
		$cantonCLI = $row2['idCanton'];
		$distritoCLI = $row2['idDistrito'];
		$barrioCLI = $row2['idBarrio'];
		$direccionCLI = normaliza($row2['direccion']);
		$telefonoCLI = $row2['telefono1'];
		$emailCLI = $row2['email'];
	}

	/////INICIO CREACION XML
	if ($tipoDoc == "01"){
		$textoXML = '<?xml version="1.0" encoding="utf-8"?>
		<FacturaElectronica xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica" xsi:schemaLocation="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica.xsd">';
	}else if ($tipoDoc == "04") {
		$textoXML = '<?xml version="1.0" encoding="utf-8"?>
		<TiqueteElectronico xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/tiqueteElectronico" xmlns:xsd="http://www.w3.org/2001/XMLSchema" 	     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
	}

		$textoXML .= '
		<Clave>'.$claveFE.'</Clave>
		<NumeroConsecutivo>'.$consecutivoFE.'</NumeroConsecutivo>
		<FechaEmision>'.$fechaFE.'</FechaEmision>
		<Emisor>
			<Nombre>'.$nombreEmpresa.'</Nombre>
			<Identificacion>
				<Tipo>'.zeroFill(2,$tipoCedula).'</Tipo>
				<Numero>'.str_replace("-", "", str_replace(" ", "", $cedulaEmpresa)).'</Numero>
			</Identificacion>
			<NombreComercial>'.$nombreComercial.'</NombreComercial>
			<Ubicacion>
				<Provincia>'.zeroFill(1,$provincia).'</Provincia>
				<Canton>'.zeroFill(2,$canton).'</Canton>
				<Distrito>'.zeroFill(2,$distrito).'</Distrito>
				<Barrio>'.zeroFill(2,$barrio).'</Barrio>
				<OtrasSenas>'.$direccion.'</OtrasSenas>
			</Ubicacion>
			<Telefono>
				<CodigoPais>506</CodigoPais>
				<NumTelefono>'.str_replace("-", "", str_replace(" ", "", $telefonoEmpresa)).'</NumTelefono>
			</Telefono>
			<CorreoElectronico>'.$emailEmpresa.'</CorreoElectronico>
		</Emisor>';
		if ($idCliente != 0){
			$textoXML .= '
			<Receptor>
				<Nombre>'.$nombreCliente.'</Nombre>';
				
				if ($cedulaCLI != ""){
					$textoXML .= '
					<Identificacion>
						<Tipo>'.zeroFill(2,$tipoCedulaCLI).'</Tipo>
						<Numero>'.str_replace("-", "", str_replace(" ", "", $cedulaCLI)).'</Numero>
					</Identificacion>';
				}
				if ($provinciaCLI != ""){
					$textoXML .= '
					<Ubicacion>
						<Provincia>'.zeroFill(1,$provinciaCLI).'</Provincia>
						<Canton>'.zeroFill(2,$cantonCLI).'</Canton>
						<Distrito>'.zeroFill(2,$distritoCLI).'</Distrito>
						<Barrio>'.zeroFill(2,$barrioCLI).'</Barrio>
						<OtrasSenas>'.$direccionCLI.'</OtrasSenas>
					</Ubicacion>';
				}
				if ($telefonoCLI != ""){
					$textoXML .= '
					<Telefono>
						<CodigoPais>506</CodigoPais>
						<NumTelefono>'.str_replace("-", "", str_replace(" ", "", $telefonoCLI)).'</NumTelefono>
					</Telefono>';
				}
				if ($emailCLI != ""){
					$textoXML .= '
					<CorreoElectronico>'.$emailCLI.'</CorreoElectronico>';
				}
			$textoXML .= '
			</Receptor>';
		}
		$textoXML .= '
		<CondicionVenta>'.$CondicionVenta.'</CondicionVenta>
		<PlazoCredito>'.$plazo.'</PlazoCredito>
		<MedioPago>01</MedioPago>
		<DetalleServicio>';

	$TotalDescuentos = 0;
	$TotalImpuesto = 0;
	$serviciosExcentos = 0;
	$serviciosGravados = 0;
	$TotalExento = 0;
	$productosExcentos = 0;
	$productosGravados = 0;
	$TotalGravado = 0;
	$totalVenta = 0;

	$sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idFactura' ";
	$query = mysql_query($sql, $conn);
	$a = 0;
	while ($row=mysql_fetch_assoc($query)) { 
		$impuesto = 0;
		$a++;
		$descuento = 0;
		if ($row['descuento'] != 0){
			$descuento += ($row['cantidad']*$row['precio'])*($row['descuento']/100);
			$TotalDescuentos += $descuento;
		}
		$textoXML .= '
		<LineaDetalle>
			<NumeroLinea>'.$a.'</NumeroLinea>
			<Codigo>
				<Tipo>04</Tipo>
				<Codigo>'.$row['idProducto'].'</Codigo>
			</Codigo>
			<Cantidad>'.number_format($row['cantidad'],3,'.','').'</Cantidad>
			<UnidadMedida>Unid</UnidadMedida>
			<UnidadMedidaComercial/>
			<Detalle>'.normaliza($row['nombre']).'</Detalle>
			<PrecioUnitario>'.number_format($row['precio'],5,'.','').'</PrecioUnitario>
			<MontoTotal>'.number_format($row['cantidad']*$row['precio'],5,'.','').'</MontoTotal>';
			if ($descuento != 0){
				$textoXML .= '
					<MontoDescuento>'.number_format($descuento,5,'.','').'</MontoDescuento>
					<NaturalezaDescuento>Descuento general</NaturalezaDescuento>
				';
			}/*else{
				$textoXML .= '
					<MontoDescuento>0.00000</MontoDescuento>
					<NaturalezaDescuento>Descuento general</NaturalezaDescuento>
				';
			}*/

			$SubTotal = ($row['cantidad']*$row['precio'])-$descuento;
			$SubTotalNoDesc = $row['cantidad']*$row['precio'];
			$textoXML .= '<SubTotal>'.number_format($SubTotal,5,'.','').'</SubTotal>';



			if ($row['tipo'] == 0){  //producto
				if ($row['impuesto'] == 0){ //producto excento
					$productosExcentos += number_format($SubTotalNoDesc,5,'.','');
					//$productosExcentos += $SubTotalNoDesc;
				}else if ($row['impuesto'] == 1){ //producto gravado
					if ($exonerar == 0){
						$impuesto = $SubTotalNoDesc*0.13;
						$impuesto = number_format($impuesto,5,'.','');
						$sumaImpuestos+=$impuesto;
						$textoXML .='
						<Impuesto>
							<Codigo>01</Codigo>
							<Tarifa>'.number_format(13,4,'.','').'</Tarifa>
							<Monto>'.$impuesto.'</Monto>
						</Impuesto>
						';
					}
					
					$productosGravados += number_format($SubTotalNoDesc,5,'.','');
					//$productosGravados += $SubTotalNoDesc;
				} 
			}else if ($row['tipo'] == 1){  //servicio
				if ($row['impuesto'] == 0){ //servicio excento
					//$serviciosExcentos += $SubTotalNoDesc;
					$serviciosExcentos += number_format($SubTotalNoDesc,5,'.','');
				}else if ($row['impuesto'] == 1){ //servicio gravado
					if ($exonerar == 0){
						$impuesto = $SubTotalNoDesc*0.13;
						$impuesto = number_format($impuesto,5,'.','');
						$sumaImpuestos+=$impuesto;
						$textoXML .='
						<Impuesto>
							<Codigo>01</Codigo>
							<Tarifa>'.number_format(13,4,'.','').'</Tarifa>
							<Monto>'.number_format($SubTotalNoDesc*0.13,5,'.','').'</Monto>
						</Impuesto>
						';
					}
					$serviciosGravados += number_format($SubTotalNoDesc,5,'.','');
					//$serviciosGravados += $SubTotalNoDesc;
				} 
			}

			$MontoTotalLinea = $SubTotal+$impuesto;
			$textoXML .='<MontoTotalLinea>'.number_format($MontoTotalLinea,5,'.','').'</MontoTotalLinea>
		</LineaDetalle>
		';

	}

	$TotalGravado = $productosGravados+$serviciosGravados;
	$TotalExento = $productosExcentos+$serviciosExcentos;


	$totalVenta += $TotalGravado+$TotalExento;
	//$TotalImpuesto = $TotalGravado*0.13;
		$TotalImpuesto = $sumaImpuestos;

	if ($exonerar == 1){
		$TotalImpuesto = 0;
	}

	$textoXML .= '

		</DetalleServicio>
		<ResumenFactura>
			<CodigoMoneda>CRC</CodigoMoneda>
			<TotalServGravados>'.number_format($serviciosGravados,5,'.','').'</TotalServGravados>
			<TotalServExentos>'.number_format($serviciosExcentos,5,'.','').'</TotalServExentos>
			<TotalMercanciasGravadas>'.number_format($productosGravados,5,'.','').'</TotalMercanciasGravadas>
			<TotalMercanciasExentas>'.number_format($productosExcentos,5,'.','').'</TotalMercanciasExentas>
			<TotalGravado>'.number_format($TotalGravado,5,'.','').'</TotalGravado>
			<TotalExento>'.number_format($TotalExento,5,'.','').'</TotalExento>
			<TotalVenta>'.number_format($totalVenta,5,'.','').'</TotalVenta>
			<TotalDescuentos>'.number_format($TotalDescuentos,5,'.','').'</TotalDescuentos>
			<TotalVentaNeta>'.number_format($totalVenta-$TotalDescuentos,5,'.','').'</TotalVentaNeta>
			<TotalImpuesto>'.number_format($TotalImpuesto,5,'.','').'</TotalImpuesto>
			<TotalComprobante>'.number_format(($totalVenta-$TotalDescuentos)+$TotalImpuesto,5,'.','').'</TotalComprobante>
		</ResumenFactura>';

		if ($sininternet == 1){
			$razon = 'Documento por contingencia sin internet';
		}else{
			$razon = 'Documento por contingencia';
		}

		if ($contingencia == 1){
			$explode = explode("/", $fechaReferencia);
			$fechaReferencia = $explode[2].'-'.$explode[1].'-'.$explode[0].'T12:00:00-06:00';

			$textoXML .= '
			<InformacionReferencia>
				<TipoDoc>08</TipoDoc>
		        <Numero>'.$referencia.'</Numero>
		        <FechaEmision>'.$fechaReferencia.'</FechaEmision>
		        <Codigo>05</Codigo>
			    <Razon>'.$razon.'</Razon>
		    </InformacionReferencia>';
	    }

	$textoXML .= '
		<Normativa>
			<NumeroResolucion>DGT-R-48-2016</NumeroResolucion>
            <FechaResolucion>07-10-2016 08:00:00</FechaResolucion>
		</Normativa>
		';

		if ($tipoDoc == "01"){
			$textoXML .= '</FacturaElectronica>';
		}else if ($tipoDoc == "04") {
			$textoXML .= '</TiqueteElectronico>';
		}

	$file=fopen("../parametros/archivos/noFirmados/".$numeroFactura."-".$cuentaid.".xml","w+"); 
	fwrite ($file,$textoXML); 
	fclose($file);
	//// FIN CREACION XML

	echo $numeroFactura;

}


if ($f == "FIRMAR-XML"){
	$numeroFactura = $_POST['numeroFactura'];
	$tipoDoc = $_POST['tipoDoc'];

	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)){
	     $pinFE = trim($row['pinFE']);
	    $firmadorPK=$row['firmador'];
	    $nombreComercial=$row['nomComercial'];
	}

	//echo $numeroFactura."--".$tipoDoc;
//echo $pinFE;
	$xml = base64_encode(file_get_contents("../parametros/archivos/noFirmados/".$numeroFactura."-".$cuentaid.".xml")); 
	$textoXML = $firmador->firmar("../parametros/archivos/".$firmadorPK.".p12", $pinFE, $xml, $tipoDoc);
	

	$file=fopen("../parametros/archivos/firmados/".$numeroFactura."-".$cuentaid."-firmada.xml","w+"); 
	fwrite ($file,base64_decode($textoXML)); 
	fclose($file);
}

if ($f == "OBTENER-TOKEN"){
	if(isset($_POST['sistema'])){
		$sql = "SELECT * FROM tparametros WHERE id = '".$_POST['sistema']."' ";
	}else{
		$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
	}
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

    $result = file_get_contents($url, false, $context);
    //$result = get_url_contents($url);

    if ($result === FALSE) { echo $result; }
    $token = json_decode($result); //get a token object
    //devuelvo el token
    echo $token->access_token;
}


if ($f == 'ENVIAR-FACTURA'){
	$numeroFactura = $_POST['numeroFactura'];
	$authToken = $_POST['token'];

	///////CONVERTIR FACTURA EN BASE 64
	if(isset($_POST['sistema'])){
		$sql = "SELECT * FROM tparametros WHERE id = '".$_POST['sistema']."' ";
	}else{
		$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
	}
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){  
	  	$nombreComercial=$row['nomComercial']; 
	  	$cedulaEmpresa = $row['cedula'];
	    $tipoCedula = $row['tipocedula'];
	    $tipoFE = $row['produccion'];
	}
	if(isset($_POST['sistema'])){
		$invoice = file_get_contents("../parametros/archivos/firmados/".$numeroFactura."-".$_POST['sistema']."-firmada.xml");
	}else{
		$invoice = file_get_contents("../parametros/archivos/firmados/".$numeroFactura."-".$cuentaid."-firmada.xml");
	}
    $invoice = base64_encode($invoice);

    if(isset($_POST['sistema'])){
    	$sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$_POST['sistema']."' ";
    }else{
    	$sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."' ";
    }
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $consecutive = $row['consecutivoFE'];
	  $key = $row['claveFE'];
	  $fecha = $row['fechaFE'];
	  $idCliente = $row['idCliente'];
	  $idFactura = $row['id'];
	}

	

	$tipoCedulaCLI = "";
	$cedulaCliente = "";
	$sql = "SELECT * FROM tclientes WHERE id = '$idCliente'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	      if ($row['tipoIdentificacion'] == ""){
	      	$tipoCedulaCLI = "00";
	      }else{
	      	$tipoCedulaCLI = $row['tipoIdentificacion'];
	      }
	      $cedulaCliente = $row['identificacion'];
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

	if ($info['http_code'] == '202'){

////validar
		$curl = curl_init();

		//echo "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/".$key;

	  	curl_setopt_array($curl, array(
	      CURLOPT_URL => $url."/".$key."",
	      CURLOPT_HTTPHEADER => array(
	        "authorization: Bearer ".$authToken ,
	        "content-type: application/json"
	      ),
	    ));

	  $response = curl_exec($curl);
	  $info = curl_getinfo($curl);
	  //print_r($info);
	  curl_close($curl);

	  //echo "Validacion";

	  //print_r($info);


	  }else{
	  	echo 'error';
	  }	
}

if ($f == 'VERIFICAR'){
	$numeroFactura = $_POST['numeroFactura'];
	if(isset($_POST['sistema'])){
		$sql = "SELECT * FROM tparametros  WHERE id = '".$_POST['sistema']."'";
	}else{
		$sql = "SELECT * FROM tparametros  WHERE id = '".$cuentaid."'";
	}
	$query = mysql_query($sql, $conn)or die(mysql_error());
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
    
    if(isset($_POST['sistema'])){
   		$sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$_POST['sistema']."' ";
    }else{
    	$sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."' ";
    }
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

  	try {
  		//print($curl);
  		$response = curl_exec($curl);
  		$info = curl_getinfo($curl);	
  		print curl_error($curl);

  		print_r($response);
  	} catch (Exception $e) {
  		echo $e;
  	}
  	curl_close($curl);
}

if ($f == 'VERIFICAR-RECEPTOR'){
	$key = $_POST['clave'];

	$sql = "SELECT * FROM tparametros  WHERE id = '".$cuentaid."'";
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

  	try {
  		//print($curl);
  		$response = curl_exec($curl);
  		$info = curl_getinfo($curl);	
  		print curl_error($curl);

  		print_r($response);
  	} catch (Exception $e) {
  		echo $e;
  	}
  	curl_close($curl);
}

if ($f == 'VERIFICAR-NC'){
	$numeroFactura = $_POST['numeroFactura'];

	$sql = "SELECT * FROM tnotasCreditoFE WHERE numeroFactura = '$numeroFactura' AND sistema = '".$cuentaid."' ORDER BY id DESC";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $consecutive = $row['consecutivoNC'];
	  $key = $row['claveNC'];
	  $fecha = $row['fechaNC'];
	}

	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
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

    $sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."' ";
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
  		print curl_error($curl);

  		print_r($response);
  	} catch (Exception $e) {
  		echo $e;
  	}
  	curl_close($curl);
}

if ($f == 'CAMBIARESTADO'){
	$numeroFactura = $_POST['numeroFactura'];
	$estadoFE = $_POST['estado'];
	$xmlRespuesta = $_POST['xmlRespuesta'];


	if(isset($_POST['sistema'])){
		$sql = "SELECT * FROM tparametros WHERE id = '".$_POST['sistema']."'";
	}else{
		$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	}
	
	
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
		$nombreEmpresa=$row['nomComercial'];
	}

	if(isset($_POST['sistema'])){
		$sql = "UPDATE tfacturas SET estadoFE = '$estadoFE' WHERE numero = '$numeroFactura' AND sistema='".$_POST['sistema']."'";
	}else{
		$sql = "UPDATE tfacturas SET estadoFE = '$estadoFE' WHERE numero = '$numeroFactura' AND sistema='".$cuentaid."'";
	}
	$query = mysql_query($sql, $conn);
	
	$textoXML = base64_decode($xmlRespuesta);
	if(isset($_POST['sistema'])){
		$file=fopen("../parametros/archivos/respuestas/".$numeroFactura."-".$_POST['sistema']."-resp.xml","w+"); 
	}else{
		$file=fopen("../parametros/archivos/respuestas/".$numeroFactura."-".$cuentaid."-resp.xml","w+");	
	}
	fwrite ($file,$textoXML); 
	fclose($file);
}


///GENERAR XML NOTA DE CREDITO
if ($f == 'GENERAR-XML-NC'){
	$numeroFactura = $_POST['numeroFactura'];
	$idFactura = $_POST['idFactura'];
	$tipoDoc = $_POST['tipoDoc'];

	$sql = "SELECT * FROM tnotascreditofe WHERE numeroFactura = '$numeroFactura' AND sistema = '".$cuentaid."' ORDER BY id DESC";
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) == 0){

		$sql2 = "SELECT * FROM tfacturas WHERE id = '$idFactura' AND sistema = '".$cuentaid."' LIMIT 1";
		$query2 = mysql_query($sql2, $conn)or die(mysql_error());
		while ($row2=mysql_fetch_assoc($query2)) {
			$estado = $row2['estado'];
			$consecutivoFE = $row2['consecutivoFE'];
			$claveFE = $row2['claveFE']; 
			$fechaFE = $row2['fechaFE'];
			$plazo = $row2['plazo'];
			$exonerar = $row2['exonerar'];
			$caja = $row2['idCaja'];
			$codigoSeguridad = $row2['codigoSeguridad'];
			$idCliente = $row2['idCliente'];
			$idFactura = $row2['id'];
			$estado = $row2['estado'];

			if ($estado == 4){
				$tipoFactura = 1;
			}else{
				$tipoFactura == 0;
			}
		}

		$tipoDocFE = substr($consecutivoFE,8,2);

		if ($estado != 1 and $claveFE != ''){

			$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
			$query = mysql_query($sql, $conn);
			while ($row=mysql_fetch_assoc($query)){
			      $nombreEmpresa = normaliza($row['nombre']);
			      $nombreComercial = normaliza($row['nomComercial']);
			      $direccion = $row['otrasSenas'];
			      $web = "";
			      $emailEmpresa = $row['email'];
			      $telefonoEmpresa = $row['telefono'];
			      $tributacion = $row['leyendaTributaria'];
			      $cedulaEmpresa = $row['cedula'];
			      $tipoCedula = $row['tipocedula'];
			      $observaciones = "";
			      $imprimirCopia = $row['imprimirCopia'];
			      $facturaElectronica = $row['facturaElectronica'];
				  $provincia = $row['provincia'];
				  $canton = $row['canton'];
				  $distrito = $row['distrito'];
				  $barrio = $row['barrio'];
				  $OtrasSenas = normaliza($row['otrasSenas']);
			}


			if ($tipoFactura == 0){
				$CondicionVenta = "01";
			}else{
				$CondicionVenta = "02";
			}	
			
			$tipoCedulaCLI = '01';
			$cedulaCLI = '000000000';
			$nombreCliente = 'Cliente de contado';
			$provinciaCLI = $provincia;
			$cantonCLI = $canton;
			$distritoCLI = $distrito;
			$barrioCLI = $barrio;
			$direccionCLI = '';
			$telefonoCLI = $telefonoEmpresa;
			$emailCLI = 'mail@mail.com';

			$sql2 = "SELECT * FROM tclientes WHERE id = '$idCliente'";
			$query2 = mysql_query($sql2, $conn)or die(mysql_error());
			while ($row2=mysql_fetch_assoc($query2)) {
				$tipoCedulaCLI = $row2['tipoIdentificacion'];
				$cedulaCLI = $row2['identificacion'];
				$nombreCliente = normaliza($row2['nombre']);
				$provinciaCLI = $row2['idProvincia'];
				$cantonCLI = $row2['idCanton'];
				$distritoCLI = $row2['idDistrito'];
				$barrioCLI = $row2['idBarrio'];
				$direccionCLI = normaliza($row2['direccion']);
				$telefonoCLI = $row2['telefono1'];
				$emailCLI = $row2['email'];
			}

			$sql = "SELECT * FROM tcajas WHERE id = '$caja'";
			$query = mysql_query($sql, $conn);
			while ($row=mysql_fetch_assoc($query)) {
				$numeroCaja = $row['numero'];
				$idSucursal = $row['idSucursal'];
				$ultimaNC = $row['ultimaNC'];
			}

			$sql = "SELECT * FROM tsucursales WHERE id = '$idSucursal'";
			$query = mysql_query($sql, $conn);
			while ($row=mysql_fetch_assoc($query)) {
				$numeroSucursal = $row['numero'];
			}

			$numeroNotaCreditoFE = $ultimaNC+1;
			$sql4 = "UPDATE tcajas SET ultimaNC = '$numeroNotaCreditoFE' WHERE id = '$caja'";
			$query4 = mysql_query($sql4, $conn);

			$consecutivoNC = zeroFill(3,$numeroSucursal);
			$consecutivoNC .= zeroFill(5,$numeroCaja);
			$consecutivoNC .= zeroFill(2,3);
			$consecutivoNC .= zeroFill(10,$numeroNotaCreditoFE);

			$cedulaEmpresa = str_replace("-", "", str_replace(" ", "", $cedulaEmpresa));

			$claveNC = '506';
			$claveNC .= date("d");
			$claveNC .= date("m");
			$claveNC .= date("y");
			$claveNC .= zeroFill(12, $cedulaEmpresa);
			$claveNC .= zeroFill(20, $consecutivoNC);
			$claveNC .= "1";
			$claveNC .= $codigoSeguridad;

			$fechaNC =  date('Y-m-d\TH:i:sP');
			
			$sql2 = "INSERT INTO tnotasCreditoFE VALUES (null, '$numeroFactura', '$claveNC', '$consecutivoNC', '$fechaNC', '$claveFE', '$consecutivoFE', '$tipoDoc', '$codigoDoc', '$razonDoc', 1,'".$cuentaid."')";
			$query2 = mysql_query($sql2, $conn)or die(mysql_error());
			/////INICIO CREACION XML
			$textoXML = '<?xml version="1.0" encoding="utf-8"?>
			<NotaCreditoElectronica xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/notaCreditoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
				<Clave>'.$claveNC.'</Clave>
				<NumeroConsecutivo>'.$consecutivoNC.'</NumeroConsecutivo>
				<FechaEmision>'.$fechaNC.'</FechaEmision>
				<Emisor>
					<Nombre>'.$nombreEmpresa.'</Nombre>
					<Identificacion>
						<Tipo>'.zeroFill(2,$tipoCedula).'</Tipo>
						<Numero>'.str_replace("-", "", str_replace(" ", "", $cedulaEmpresa)).'</Numero>
					</Identificacion>
					<NombreComercial>'.$nombreComercial.'</NombreComercial>
					<Ubicacion>
						<Provincia>'.zeroFill(1,$provincia).'</Provincia>
						<Canton>'.zeroFill(2,$canton).'</Canton>
						<Distrito>'.zeroFill(2,$distrito).'</Distrito>
						<Barrio>'.zeroFill(2,$barrio).'</Barrio>
						<OtrasSenas>'.$direccion.'</OtrasSenas>
					</Ubicacion>
					<Telefono>
						<CodigoPais>506</CodigoPais>
						<NumTelefono>'.str_replace("-", "", str_replace(" ", "", $telefonoEmpresa)).'</NumTelefono>
					</Telefono>
					<CorreoElectronico>'.$emailEmpresa.'</CorreoElectronico>
				</Emisor>';
				if ($idCliente != 0){
				$textoXML .= '
				<Receptor>
					<Nombre>'.$nombreCliente.'</Nombre>';
					
					if ($cedulaCLI != ""){
						$textoXML .= '
						<Identificacion>
							<Tipo>'.zeroFill(2,$tipoCedulaCLI).'</Tipo>
							<Numero>'.str_replace("-", "", str_replace(" ", "", $cedulaCLI)).'</Numero>
						</Identificacion>';
					}
					if ($provinciaCLI != ""){
						$textoXML .= '
						<Ubicacion>
							<Provincia>'.zeroFill(1,$provinciaCLI).'</Provincia>
							<Canton>'.zeroFill(2,$cantonCLI).'</Canton>
							<Distrito>'.zeroFill(2,$distritoCLI).'</Distrito>
							<Barrio>'.zeroFill(2,$barrioCLI).'</Barrio>
							<OtrasSenas>'.$direccionCLI.'</OtrasSenas>
						</Ubicacion>';
					}
					if ($telefonoCLI != ""){
						$textoXML .= '
						<Telefono>
							<CodigoPais>506</CodigoPais>
							<NumTelefono>'.str_replace("-", "", str_replace(" ", "", $telefonoCLI)).'</NumTelefono>
						</Telefono>';
					}
					if ($emailCLI != ""){
						$textoXML .= '
						<CorreoElectronico>'.$emailCLI.'</CorreoElectronico>';
					}
					$textoXML .= '
					</Receptor>';
				}
			
				$textoXML .= '
				<CondicionVenta>'.$CondicionVenta.'</CondicionVenta>
				<PlazoCredito>'.$plazo.'</PlazoCredito>
				<MedioPago>01</MedioPago>
				<DetalleServicio>';

			$TotalDescuentos = 0;
			$TotalImpuesto = 0;
			$serviciosExcentos = 0;
			$serviciosGravados = 0;
			$TotalExento = 0;
			$productosExcentos = 0;
			$productosGravados = 0;
			$TotalGravado = 0;
			$totalVenta = 0;

			$sql = "SELECT * FROM tproductosfactura WHERE idCotizacion = '$idFactura' ";
			$query = mysql_query($sql, $conn);

			if (mysql_num_rows($query) == 0){
				$textoXML .= '
				<LineaDetalle>
					<NumeroLinea>1</NumeroLinea>
					<Codigo>
						<Tipo>04</Tipo>
						<Codigo>NC00</Codigo>
					</Codigo>
					<Cantidad>1.000</Cantidad>
					<UnidadMedida>Unid</UnidadMedida>
					<UnidadMedidaComercial/>
					<Detalle>ANULACION DE FACTURA ELECTRONICA</Detalle>
					<PrecioUnitario>0.00000</PrecioUnitario>
					<MontoTotal>0.00000</MontoTotal>
					<SubTotal>0.00000</SubTotal>
					<MontoTotalLinea>0.00000</MontoTotalLinea>
				</LineaDetalle>';
			}else{
				$a = 0;
				while ($row=mysql_fetch_assoc($query)) { 
					$impuesto = 0;
					$a++;
					$descuento = 0;
					if ($row['descuento'] != 0){
						$descuento += ($row['cantidad']*$row['precio'])*($row['descuento']/100);
						$TotalDescuentos += $descuento;
					}
					$textoXML .= '
					<LineaDetalle>
						<NumeroLinea>'.$a.'</NumeroLinea>
						<Codigo>
							<Tipo>04</Tipo>
							<Codigo>'.$row['idProducto'].'</Codigo>
						</Codigo>
						<Cantidad>'.number_format($row['cantidad'],3,'.','').'</Cantidad>
						<UnidadMedida>Unid</UnidadMedida>
						<UnidadMedidaComercial/>
						<Detalle>'.normaliza($row['nombre']).'</Detalle>
						<PrecioUnitario>'.number_format($row['precio'],5,'.','').'</PrecioUnitario>
						<MontoTotal>'.number_format($row['cantidad']*$row['precio'],5,'.','').'</MontoTotal>';
						if ($descuento != 0){
							$textoXML .= '
								<MontoDescuento>'.number_format($descuento,5,'.','').'</MontoDescuento>
								<NaturalezaDescuento>Descuento general</NaturalezaDescuento>
							';
						}/*else{
							$textoXML .= '
								<MontoDescuento>0.00000</MontoDescuento>
								<NaturalezaDescuento>Descuento general</NaturalezaDescuento>
							';
						}*/

						$SubTotal = ($row['cantidad']*$row['precio'])-$descuento;
						$SubTotalNoDesc = $row['cantidad']*$row['precio'];
						$textoXML .= '<SubTotal>'.number_format($SubTotal,5,'.','').'</SubTotal>';



						if ($row['tipo'] == 0){  //producto
							if ($row['impuesto'] == 0){ //producto excento
								//$productosExcentos += $SubTotalNoDesc;
								$productosExcentos += number_format($SubTotalNoDesc,5,'.','');
							}else if ($row['impuesto'] == 1){ //producto gravado
								if ($exonerar == 0){
									$impuesto = $SubTotalNoDesc*0.13;
									$impuesto = number_format($impuesto,5,'.','');
									$sumaImpuestos+=$impuesto;
									$textoXML .='
									<Impuesto>
										<Codigo>01</Codigo>
										<Tarifa>'.number_format(13,4,'.','').'</Tarifa>
										<Monto>'.$impuesto.'</Monto>
									</Impuesto>
									';
								}
								$productosGravados += number_format($SubTotalNoDesc,5,'.','');
								//$productosGravados += $SubTotalNoDesc;
							} 
						}else if ($row['tipo'] == 1){  //servicio
							if ($row['impuesto'] == 0){ //servicio excento
								//$serviciosExcentos += $SubTotalNoDesc;
								$serviciosExcentos += number_format($SubTotalNoDesc,5,'.','');
							}else if ($row['impuesto'] == 1){ //servicio gravado
								if ($exonerar == 0){
									$impuesto = $SubTotalNoDesc*0.13;
									$impuesto = number_format($impuesto,5,'.','');
									$sumaImpuestos+=$impuesto;
									$textoXML .='
									<Impuesto>
										<Codigo>01</Codigo>
										<Tarifa>'.number_format(13,4,'.','').'</Tarifa>
										<Monto>'.number_format($SubTotalNoDesc*0.13,5,'.','').'</Monto>
									</Impuesto>
									';
								}
								$serviciosGravados += number_format($SubTotalNoDesc,5,'.','');
								//$serviciosGravados += $SubTotalNoDesc;
							} 
						}

						$MontoTotalLinea = $SubTotal+$impuesto;
						$textoXML .='<MontoTotalLinea>'.number_format($MontoTotalLinea,5,'.','').'</MontoTotalLinea>
					</LineaDetalle>
					';

				}
			}

			$TotalGravado = $productosGravados+$serviciosGravados;
			$TotalExento = $productosExcentos+$serviciosExcentos;


			$totalVenta += $TotalGravado+$TotalExento;
			$TotalImpuesto = $sumaImpuestos;


			if ($exonerar == 1){
				$TotalImpuesto = 0;
			}

			$textoXML .= '

				</DetalleServicio>
				<ResumenFactura>
					<CodigoMoneda>CRC</CodigoMoneda>
					<TotalServGravados>'.number_format($serviciosGravados,5,'.','').'</TotalServGravados>
					<TotalServExentos>'.number_format($serviciosExcentos,5,'.','').'</TotalServExentos>
					<TotalMercanciasGravadas>'.number_format($productosGravados,5,'.','').'</TotalMercanciasGravadas>
					<TotalMercanciasExentas>'.number_format($productosExcentos,5,'.','').'</TotalMercanciasExentas>
					<TotalGravado>'.number_format($TotalGravado,5,'.','').'</TotalGravado>
					<TotalExento>'.number_format($TotalExento,5,'.','').'</TotalExento>
					<TotalVenta>'.number_format($totalVenta,5,'.','').'</TotalVenta>
					<TotalDescuentos>'.number_format($TotalDescuentos,5,'.','').'</TotalDescuentos>
					<TotalVentaNeta>'.number_format($totalVenta-$TotalDescuentos,5,'.','').'</TotalVentaNeta>
					<TotalImpuesto>'.number_format($TotalImpuesto,5,'.','').'</TotalImpuesto>
					<TotalComprobante>'.number_format(($totalVenta-$TotalDescuentos)+$TotalImpuesto,5,'.','').'</TotalComprobante>
				</ResumenFactura>

				<InformacionReferencia>
					<TipoDoc>'.$tipoDocFE.'</TipoDoc>
			        <Numero>'.$claveFE.'</Numero>
			        <FechaEmision>'.$fechaFE.'</FechaEmision>
			        <Codigo>01</Codigo>
				    <Razon>Nota de credito en punto de venta.</Razon>
			    </InformacionReferencia>

				<Normativa>
					<NumeroResolucion>DGT-R-48-2016</NumeroResolucion>
		            <FechaResolucion>07-10-2016 08:00:00</FechaResolucion>
				</Normativa>
			</NotaCreditoElectronica>';

			$file=fopen("../parametros/archivos/noFirmados/NCredito-".$numeroFactura."-".$cuentaid.".xml","w+"); 
			fwrite ($file,$textoXML); 
			fclose($file);

			echo "1";
		}else{
			$sql = "UPDATE tfacturas SET estado = '3' WHERE numero = '$numeroFactura' AND sistema = '".$cuentaid."' ";
			$query = mysql_query($sql, $conn);
			echo "2";
		}
	}else{
		echo "1";
	}
}

if ($f == 'ENVIAR-FACTURA-NC'){
	$numeroFactura = $_POST['numeroFactura'];
	$authToken = $_POST['token'];

	///////CONVERTIR FACTURA EN BASE 64
	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	      $cedulaEmpresa = $row['cedula'];
	      $tipoCedula = $row['tipocedula'];
		  $tipoFE = $row['produccion'];
		  $nombreComercial=$row['nomComercial'];
	}

	$invoice = file_get_contents("../parametros/archivos/firmados/NCredito-".$numeroFactura."-".$cuentaid."-firmada.xml");

    $invoice = base64_encode($invoice);

    $sql = "SELECT * FROM tfacturas WHERE numero = '$numeroFactura' AND sistema = '".$cuentaid."'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $idCliente = $row['idCliente'];
	  $idFactura = $row['id'];
	}

	$sql = "SELECT * FROM tnotasCreditoFE WHERE numeroFactura = '$numeroFactura' AND sistema = '".$cuentaid."'  ORDER BY id DESC";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $consecutive = $row['consecutivoNC'];
	  $key = $row['claveNC'];
	  $fecha = $row['fechaNC'];
	}

	

	$tipoCedulaCLI = "";
	$cedulaCliente = "";
	$sql = "SELECT * FROM tclientes WHERE id = '$idCliente'";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	      if ($row['tipoIdentificacion'] == ""){
	      	$tipoCedulaCLI = "00";
	      }else{
	      	$tipoCedulaCLI = $row['tipoIdentificacion'];
	      }
	      $cedulaCliente = $row['identificacion'];
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

	if ($info['http_code'] == '202'){

////validar
		$curl = curl_init();

		//echo "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/".$key;

	  	curl_setopt_array($curl, array(
	      CURLOPT_URL => $url."/".$key."",
	      CURLOPT_HTTPHEADER => array(
	        "authorization: Bearer ".$authToken ,
	        "content-type: application/json"
	      ),
	    ));

	  $response = curl_exec($curl);
	  $info = curl_getinfo($curl);
	  //print_r($info);
	  curl_close($curl);

	  //echo "Validacion";

	  //print_r($info);
	  }else{
	  	echo 'error';
	  }	
}


if ($f == "FIRMAR-XML-NC"){
	$numeroFactura = $_POST['numeroFactura'];

	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)){
	     $pinFE = trim($row['pinFE']);
	    $firmadorPK=$row['firmador'];
	    $nombreComercial=$row['nomComercial'];
	}

	//echo $numeroFactura."--".$tipoDoc;
//echo $pinFE;
	$xml = base64_encode(file_get_contents("../parametros/archivos/noFirmados/NCredito-".$numeroFactura."-".$cuentaid.".xml")); 

	//$xml = base64_encode(file_get_contents("archivos/noFirmados/NCredito-".$numeroFactura.".xml")); 
	$textoXML = $firmador->firmar("../parametros/archivos/".$firmadorPK.".p12", $pinFE, $xml, "03");

	$file=fopen("../parametros/archivos/firmados/NCredito-".$numeroFactura."-".$cuentaid."-firmada.xml","w+"); 
	fwrite ($file,base64_decode($textoXML)); 
	fclose($file);
}

	
if ($f == 'ELIMINARFACTURA'){

	$numeroFactura = $_POST['numeroFactura'];
	$estadoFE = $_POST['estado'];
	$xmlRespuesta = $_POST['xmlRespuesta'];
	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."'";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)){
	    $nombreComercial=$row['nomComercial'];
	}

	$sql = "UPDATE tnotasCreditoFE SET estadoFE = '$estadoFE' WHERE numeroFactura = '$numeroFactura' AND sistema = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);

	$sql = "UPDATE tfacturas SET estado = '3' WHERE numero = '$numeroFactura' AND sistema = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);

	$textoXML = base64_decode($xmlRespuesta);

	$file=fopen("../parametros/archivos/respuestas/NCredito-".$numeroFactura."-".$cuentaid."-resp.xml","w+"); 
	fwrite ($file,$textoXML); 
	fclose($file);
}



////////////////////////////ACEPTACION DE FACTURA 


if ($f == 'GUARDAR-PROVEEDOR'){
	$identificacion = $_POST['identificacion'];
	$tipo = $_POST['tipo'];
	$nombre = $_POST['nombre'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$direccion = $_POST['direccion'];
	$contacto = $_POST['contacto'];
	$tipoMercaderia = $_POST['tipoMercaderia'];

	$sql = "SELECT * FROM tclientes WHERE identificacion = '$identificacion' AND usuario='$cuentaid' ";
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) == 0){
		$sql = "INSERT INTO tclientes VALUES (null,'$tipo','$identificacion', '$nombre','','$direccion', '$telefono', '', '$email', '', '".date('d/m/Y')."', 1,'','','','','0','2','".$cuentaid."')";
		$query = mysql_query($sql, $conn);		
	}

	$sql = "SELECT * FROM tclientes WHERE identificacion = '$identificacion' AND usuario='$cuentaid' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) { ?>
		<input type="hidden" name="idProveedor" value="<?php echo $row['id']?>">
		<table width="100%" class="table table-bordered responsive tablaGrande">
			<tr>
				<td style="font-weight: bold;">Identificación:</td>
				<td><?php echo $row['identificacion']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Nombre:</td>
				<td><?php echo $row['nombre']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold;" width="150px">Teléfono:</td>
				<td><?php echo $row['telefono1']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold;" width="150px">Email:</td>
				<td><?php echo $row['email']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold;" width="150px">Dirección:</td>
				<td><?php echo $row['direccion']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold;" width="150px">Contacto:</td>
				<td><?php echo $row['contacto']?></td>
			</tr>
			<tr>
				<td style="font-weight: bold;" width="150px">Tipo de mercadería:</td>
				<td><?php echo $row['tipoMercaderia']?></td>
			</tr>
    	</table>		
	<?php }
}

if ($f == 'GUARDAR-CUENTA-PAGAR'){
	
	$numeroConsecutivo = $_POST['numeroConsecutivo'];
	$clave = $_POST['clave'];
		$sql2 = "UPDATE tmensajereceptorfe SET tipoFA='1' WHERE consecutivoFE = '$numeroConsecutivo' AND clave='$clave' AND sistema = '".$cuentaid."' ";
        $query2 = mysql_query($sql2, $conn) or die(mysql_error());

		echo 1;		
	
}

if ($f == 'GUARDAR-COMPRA-PROVEEDOR'){
	$idProveedor = $_POST['idProveedor'];
	$nombre = $_POST['nombre'];
	$fechaEmision = $_POST['fechaEmision'];
	$numeroConsecutivo = $_POST['numeroConsecutivo'];
	$totalComprobante = $_POST['totalComprobante'];

	$fecha = explode("T", $fechaEmision);

	$fecha = explode("-", $fecha[0]);
	$fechaEmision = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];

	$sql = "SELECT * FROM tcomprasproveedores WHERE idProveedor = '$idProveedor' AND numeroFactura = '$numeroConsecutivo' AND estado = 1 AND sistema ='$cuentaid' ";
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) == 0){
		$sql = "INSERT INTO tcomprasproveedores VALUES (null, '$idProveedor', '$numeroConsecutivo', '$fechaEmision', '$totalComprobante', '', 1,'$cuentaid')";
		$query = mysql_query($sql, $conn);
		echo 1;		
	}else{
		echo 0;
	}
}

if ($f == 'GUARDAR-PRODUCTO'){
	$cantidad = $_POST['cantidad'];
	$impuesto = $_POST['impuesto'];
	$codigoInterno = $_POST['codigoInterno'];
	$precioInterno = $_POST['precioInterno'];
	$utilidad = $_POST['utilidad'];
	$precioVenta = $_POST['precioVenta'];
	$idProveedor = $_POST['idProveedor'];
	$nombre = $_POST['nombre'];

	if ($impuesto == "" or $impuesto == 0){
		$excento = 1;
	}else{
		$excento = 0;
	}

	$sql = "SELECT * FROM tproductos WHERE id = '$codigoInterno' AND estado = 1"; 
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) == 0){
		$sql = "INSERT INTO tproductos VALUES ('$codigoInterno', '', '$nombre', '', '$idProveedor', '1', '$precioInterno', '$precioVenta', '$excento', '$utilidad', '0', '$cantidad', '0', '0', 1, 1)";
		$query = mysql_query($sql, $conn)or die(mysql_error());
		echo 1;
	}else{
		echo 0;
	}
}

if ($f == 'MODIFICAR-PRODUCTO'){
	$cantidad = $_POST['cantidad'];
	$impuesto = $_POST['impuesto'];
	$codigoInterno = $_POST['codigoInterno'];
	$precioInterno = $_POST['precioInterno'];
	$utilidad = $_POST['utilidad'];
	$precioVenta = $_POST['precioVenta'];
	$idProveedor = $_POST['idProveedor'];
	$nombre = $_POST['nombre'];

	if ($impuesto == "" or $impuesto == 0){
		$excento = 1;
	}else{
		$excento = 0;
	}

	$sql = "SELECT * FROM tproductos WHERE id = '$codigoInterno' AND estado = 1"; 
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)) {
		$cantidadOld = $row['existencia'];
	}
	$nuevaCantidad = $cantidadOld + $cantidad;

	$sql = "UPDATE tproductos SET 
	nombre = '$nombre', 
	proveedor = '$idProveedor', 
	costoUnitario = '$precioInterno', 
	precioVenta = '$precioVenta',
	porcentajeUtilidad = '$utilidad',
	existencia = '$nuevaCantidad' 
	WHERE id = '$codigoInterno'";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	echo 1;
}

///GENERAR XML NOTA DE CREDITO
if ($f == 'GENERAR-XML-MR'){
	$clave = $_POST['clave'];
	$cedulaEmisor = $_POST['cedulaEmisor'];
	//$fechaEmision = $_POST['fechaEmision'];
	$consecutivoFE=$_POST['consecutivoFE'];
	$fechaEmision = date('Y-m-d\TH:i:sP');

	$totalImpuesto = $_POST['totalImpuesto'];
	$tipoIdentificacionEmisor = $_POST['tipoIdentificacionEmisor'];
	$TotalFactura = $_POST['TotalFactura'];
	$mensaje = $_POST['mensaje'];
	$detalleMensaje = $_POST['detalleMensaje']; 

	$sql = "SELECT * FROM tmensajereceptorfe WHERE clave = '$clave' AND sistema='".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	if (mysql_num_rows($query) == 0){

		$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
		$query = mysql_query($sql, $conn);
		while ($row=mysql_fetch_assoc($query)){
		    $cedulaReceptor = $row['cedula'];
		}

		$cedulaEmisor = zeroFill(12,$cedulaEmisor);

		$cedulaReceptor = str_replace("-", "", str_replace(" ", "", $cedulaReceptor));
		$cedulaReceptor = zeroFill(12,$cedulaReceptor);

					$sql = "SELECT * FROM tsucursales WHERE numero = '1' AND sistema = '".$cuentaid."' ";
					$query = mysql_query($sql, $conn);
					while ($row=mysql_fetch_assoc($query)) {
						$numeroSucursal = $row['numero'];
						$idSucursal= $row['id'];
					}

					$sql = "SELECT * FROM tcajas WHERE idSucursal='$idSucursal' AND numero = '1' AND sistema = '".$cuentaid."' ";
					$query = mysql_query($sql, $conn);
					while ($row=mysql_fetch_assoc($query)) {
						$numeroMR = $row['ultimaMR'];
						$caja=$row['id'];
					}

						$numeroMR = $numeroMR+1;
						$sql4 = "UPDATE tcajas SET ultimaMR = '$numeroMR' WHERE id = '$caja' AND sistema = '".$cuentaid."'";
						$query4 = mysql_query($sql4, $conn);





		if ($mensaje == 1){
			$tipoComprobante = 5;
		}else if ($mensaje == 3){
			$tipoComprobante = 7;
		}

		$consecutivoReceptor = zeroFill(3,1);
		$consecutivoReceptor .= zeroFill(5,1);
		$consecutivoReceptor .= zeroFill(2,$tipoComprobante);
		$consecutivoReceptor .= zeroFill(10,$numeroMR);

		$sql2 = "INSERT INTO tmensajereceptorfe VALUES (null, '$clave','$consecutivoFE','$tipoIdentificacionEmisor', '$cedulaEmisor', '$fechaEmision', '$totalImpuesto', '$TotalFactura','0','$mensaje', '$detalleMensaje', '$cedulaReceptor', '$consecutivoReceptor', 0,'".$cuentaid."')";
		$query2 = mysql_query($sql2, $conn)or die(mysql_error());
		/////INICIO CREACION XML
		$textoXML = '<?xml version="1.0" encoding="UTF-8"?>
		<MensajeReceptor xmlns="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/mensajeReceptor" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
		  <Clave>'.$clave.'</Clave>
		  <NumeroCedulaEmisor>'.$cedulaEmisor.'</NumeroCedulaEmisor>
		  <FechaEmisionDoc>'.$fechaEmision.'</FechaEmisionDoc>
		  <Mensaje>'.$mensaje.'</Mensaje>
		  <DetalleMensaje>'.$detalleMensaje.'</DetalleMensaje>
		  <MontoTotalImpuesto>'.$totalImpuesto.'</MontoTotalImpuesto>
		  <TotalFactura>'.$TotalFactura.'</TotalFactura>
		  <NumeroCedulaReceptor>'.$cedulaReceptor.'</NumeroCedulaReceptor>
		  <NumeroConsecutivoReceptor>'.$consecutivoReceptor.'</NumeroConsecutivoReceptor>
		</MensajeReceptor>';

		$file=fopen("../parametros/archivos/noFirmados/MReceptor-".$consecutivoReceptor."-".$cuentaid.".xml","w+"); 
		fwrite ($file,$textoXML); 
		fclose($file);

		echo $consecutivoReceptor;
	}else{
		echo "0";	
	}
}

if ($f == "FIRMAR-XML-MR"){
	$consecutivo = $_POST['consecutivo'];

	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)){
	    $pinFE = trim($row['pinFE']);
	    $firmadorPK=$row['firmador'];
	    $nombreComercial=$row['nomComercial'];
	}
	
	//$textoXML = $firmador->firmar("archivos/firmaDigital.p12", $pinFE,"archivos/noFirmados/NCredito-".$numeroFactura.".xml","archivos/firmados/NCredito-".$numeroFactura."-firmada.xml");

	$xml = base64_encode(file_get_contents("../parametros/archivos/noFirmados/MReceptor-".$consecutivo."-".$cuentaid.".xml")); 
	$textoXML = $firmador->firmar("../parametros/archivos/".$firmadorPK.".p12", $pinFE, $xml, "05");

	$file=fopen("../parametros/archivos/firmados/MReceptor-".$consecutivo."-".$cuentaid."-firmada.xml","w+"); 
	fwrite ($file,base64_decode($textoXML)); 
	fclose($file);
}

if ($f == 'ENVIAR-FACTURA-MR'){
	$consecutivo = $_POST['consecutivo'];
	$authToken = $_POST['token'];

	///////CONVERTIR FACTURA EN BASE 64

	$invoice = file_get_contents("../parametros/archivos/firmados/MReceptor-".$consecutivo."-".$cuentaid."-firmada.xml");

    $invoice = base64_encode($invoice);

	$sql = "SELECT * FROM tmensajereceptorfe WHERE consecutivoReceptor = '$consecutivo' AND sistema = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn);
	while ($row=mysql_fetch_assoc($query)){
	  $clave = $row['clave'];
	  $tipoIdentificacionEmisor = $row['tipoIdentificacionEmisor'];
	  $cedulaEmisor = $row['cedulaEmisor'];
	  $fechaEmision = $row['fechaEmision'];
	  $consecutivoReceptor = $row['consecutivoReceptor'];
	}
	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
	$query = mysql_query($sql, $conn)or die(mysql_error());
	while ($row=mysql_fetch_assoc($query)){
	    $cedulaReceptor = trim($row['cedula']);
	    $tipoCedulaReceptor=$row['tipocedula'];
	    $tipoFE=$row['produccion'];
	}
	
	if ($tipoFE == 0){
		$url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion";
	}else{
		$url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion";
	}
    $curl = curl_init();



	$cedulaEmisor = zeroFill(12,$cedulaEmisor);

	$cedulaReceptor = str_replace("-", "", str_replace(" ", "", $cedulaReceptor));
	$cedulaReceptor = zeroFill(12,$cedulaReceptor);

    $fields = "{\n\t\"clave\": \"$clave\","
        . "\n\t\"fecha\": \"".$fechaEmision."\","
        . "\n\t\"emisor\": {\n\t\t\"tipoIdentificacion\": \"".zeroFill(2,$tipoIdentificacionEmisor)."\",\n\t\t\"numeroIdentificacion\": \"".$cedulaEmisor."\"\n\t},";
        if ($cedulaReceptor != ""){
        	$fields .= "\n\t\"receptor\": {\n\t\t\"tipoIdentificacion\": \"".zeroFill(2,$tipoCedulaReceptor)."\",\n\t\t\"numeroIdentificacion\": \"".$cedulaReceptor."\"\n\t},";
    	}
    $fields .= "\n\t\"consecutivoReceptor\": \"".$consecutivoReceptor."\","
        . "\n\t\"comprobanteXml\": \"$invoice\"\n}";

       // echo $fields;
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

	print_r($info);

	echo curl_error($curl);
	curl_close($curl);

	if ($info['http_code'] == '202'){

////validar
		$curl = curl_init();

		echo "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/".$clave;

	  	curl_setopt_array($curl, array(
	      CURLOPT_URL => $url."/".$clave."",
	      CURLOPT_HTTPHEADER => array(
	        "authorization: Bearer ".$authToken ,
	        "content-type: application/json"
	      ),
	    ));

	  $response = curl_exec($curl);
	  $info = curl_getinfo($curl);
	  //print_r($info);
	  curl_close($curl);

	  //echo "Validacion";

	  //print_r($info);
	  }else{
	  	echo 'error';
	  }	
}


if ($f == 'VERIFICAR-MR'){
	$clave = $_POST['clave'];
	$consecutivo = $_POST['consecutivo'];
	$sql = "SELECT * FROM tparametros WHERE id = '".$cuentaid."' ";
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
  		print curl_error($curl);

  		print_r($response);
  	} catch (Exception $e) {
  		echo $e;
  	}
  	curl_close($curl);
}


if ($f == 'CAMBIARESTADO-MR'){
	$consecutivo = $_POST['consecutivo'];
	$estadoFE = $_POST['estado'];
	$xmlRespuesta = $_POST['xmlRespuesta'];

	$sql = "UPDATE tmensajereceptorfe SET estado = '$estadoFE' WHERE consecutivoReceptor = '$consecutivo' AND sistema = '".$cuentaid."'";
	$query = mysql_query($sql, $conn);

	$textoXML = base64_decode($xmlRespuesta);

	$file=fopen("../parametros/archivos/respuestas/MReceptor-".$consecutivo."-".$cuentaid."-resp.xml","w+"); 
	fwrite ($file,$textoXML); 
	fclose($file);
}



?>
