<?php  include ("../conn/connpg.php");



?>

<?php  include("../includes/headerLimpio.php");?>       
<?php  include("../includes/alerts.php");?>       

  <style type="text/css">
    body {
      margin: 0px;
      padding: 0px;
      overflow: auto;
    }    
  </style>
<?php 
   $xml = simplexml_load_file($_GET['xml']);
   if($_GET['tipo']==1 OR $_GET['tipo']==3){
    if($_GET['tipo']==1){
      $tipoFE="FE";
      echo "<center><strong><h4>Factura Electronica</h4></strong></center>";
    }else{
       $tipoFE="NC";
      echo "<center><strong><h4>Nota de credito</h4></strong></center>";
    }
    
    echo "<center><strong>Clave:</strong> ".$xml->Clave;
    echo "<br><strong>NumeroConsecutivo:</strong> ".$xml->NumeroConsecutivo."</center>";
    echo "<br><strong>Emisor:</strong>";
    echo "<br>** <strong>Nombre:</strong> ".$xml->Emisor->Nombre;
    echo "<br>** <strong>NombreComercial:</strong> ".$xml->Emisor->NombreComercial;
    echo "<br>***** <strong>Tipo ID:</strong> ".$xml->Emisor->Identificacion->Tipo;
    echo "<br>***** <strong>Identificacion:</strong> ".$xml->Emisor->Identificacion->Numero;
    echo "<br>** <strong>Ubicacion:</strong> Provincia: '".$xml->Emisor->Ubicacion->Provincia."' Canton: '".$xml->Emisor->Ubicacion->Provincia."' Distrito '".$xml->Emisor->Ubicacion->Distrito."' OtrasSenas: '".$xml->Emisor->Ubicacion->OtrasSenas."'" ;
    echo "<br>** <strong>Telefono:</strong> ".$xml->Emisor->Telefono->CodigoPais." ".$xml->Emisor->Telefono->NumTelefono;
    echo "<hr>";
    if(isset($xml->Receptor)){
      echo "<strong>Receptor:</strong>";
      echo "<br>** <strong>Nombre:</strong> ".$xml->Receptor->Nombre;
      echo "<br>***** <strong>Tipo ID:</strong> ".$xml->Receptor->Identificacion->Tipo;
      echo "<br>***** <strong>Identificacion:</strong> ".$xml->Receptor->Identificacion->Numero;
      echo "<br>** <strong>Ubicacion:</strong> Provincia: '".$xml->Receptor->Ubicacion->Provincia."' Canton: '".$xml->Receptor->Ubicacion->Provincia."' Distrito '".$xml->Receptor->Ubicacion->Distrito."' OtrasSenas: '".$xml->Receptor->Ubicacion->OtrasSenas."'" ;
      echo "<br>** <strong>Telefono:</strong> ".$xml->Receptor->Telefono->CodigoPais." ".$xml->Receptor->Telefono->NumTelefono;
      echo "<hr>";
    }

    if(isset($xml->DetalleServicio)){?>
        
<div style="width: 100%;max-width: 100%;overflow-x:scroll ">
      <table width="100%" class="table table-bordered responsive tablaGrande">
            <tr>
              <td colspan="10" style="text-align: center; font-weight: bold;">Productos.</td>
            </tr>
            <tr>
              <td style="font-weight: bold;"></td>
              <td style="font-weight: bold;">Cant</td>
              <td style="font-weight: bold;">Cód. Prov</td>
              <td style="font-weight: bold;">Descripción</td>
              <td style="font-weight: bold;">Precio Prov</td>
              <td style="font-weight: bold;">MontoTotal</td>
              <td style="font-weight: bold;">SubTotal</td>
              <td style="font-weight: bold;">Impuesto</td>
              <td style="font-weight: bold;" >MontoTotalLinea</td>
            </tr>
    
            <?php 
            $mayor = 0;
            foreach ($xml->DetalleServicio->LineaDetalle as $linea) {
              $mayor++; 
               ?>
              <tr>
                <td><?php echo $numeroLinea = $linea->NumeroLinea?></td>
                <td><?php echo $linea->Cantidad?></td>
                <td><?php echo $codigo = $linea->Codigo->Codigo?></td>
                <td><?php echo $linea->Detalle?></td>
                <td><?php echo $linea->PrecioUnitario;?></td>
                <td><?php echo $linea->MontoTotal;?></td>
                <td><?php echo $linea->SubTotal;?></td>
                <td><?php if(isset($linea->Impuesto)){
                  echo $linea->Impuesto->Monto;
                }else{echo "EXE";}?></td>
                <td><?php echo $linea->MontoTotalLinea;?></td>
               
              </tr> 
            <?php 
            } 
            ?>

          </table>
        </div>
   <?php  }
echo "<strong>ResumenFactura</strong>";
echo "<br>CodigoMoneda: ".$xml->ResumenFactura->CodigoMoneda;
echo "<br>TotalServGravados: ".$xml->ResumenFactura->TotalServGravados;
echo "<br>TotalServExentos: ".$xml->ResumenFactura->TotalServExentos;
echo "<br>TotalMercanciasGravadas: ".$xml->ResumenFactura->TotalMercanciasGravadas;
echo "<br>TotalMercanciasExentas: ".$xml->ResumenFactura->TotalMercanciasExentas;
echo "<br>TotalGravado: ".$xml->ResumenFactura->TotalGravado;
echo "<br>TotalExento: ".$xml->ResumenFactura->TotalExento;
echo "<br>TotalVenta: ".$xml->ResumenFactura->TotalVenta;
echo "<br>TotalDescuentos: ".$xml->ResumenFactura->TotalDescuentos;
echo "<br>TotalVentaNeta: ".$xml->ResumenFactura->TotalVentaNeta;
echo "<br>TotalImpuesto: ".$xml->ResumenFactura->TotalImpuesto;
echo "<br>TotalComprobante: ".$xml->ResumenFactura->TotalComprobante;
echo "<br>";
        

    $nombre=$tipoFE.''.$xml->NumeroConsecutivo."-".$xml->Emisor->NombreComercial.".xml";
   }else if($_GET['tipo']==2){
    echo "<center><strong><h4>Mensaje Respuesta</h4></strong>";
    echo "<strong>Clave:</strong> ".$xml->Clave."</center>";;
    echo "<br><strong>NombreEmisor:</strong> ".$xml->NombreEmisor;
    echo "<br><strong>NumeroCedulaEmisor:</strong> ".$xml->NumeroCedulaEmisor;
    echo "<br><strong>Mensaje:</strong> ".$xml->Mensaje;
    echo "<hr>DetalleMensaje: <strong><br>".$xml->DetalleMensaje."</strong>";
    echo "<hr><strong>MontoTotalImpuesto:</strong> ".$xml->MontoTotalImpuesto;
    echo "<br><strong>TotalFactura:</strong> ".$xml->TotalFactura;
    $nombre='RESHAC-'.$xml->Clave."-".$xml->NombreEmisor.".xml";
   }else if($_GET['tipo']==4){
      echo "<center><strong><h4>RECEPCIÓN FACTURA COMPRA</h4></strong>";
      echo "<strong>Clave:</strong> ".$xml->Clave."</center>";
      echo "<br><strong>NumeroCedulaEmisor:</strong> ".$xml->NumeroCedulaEmisor;
      echo "<br><strong>FechaEmisionDoc:</strong> ".$xml->FechaEmisionDoc;
      echo "<br><strong>Mensaje:</strong> ".$xml->Mensaje;
      echo "<hr>DetalleMensaje: <strong><br>".$xml->DetalleMensaje."</strong>";
      echo "<hr><strong>MontoTotalImpuesto:</strong> ".$xml->MontoTotalImpuesto;
      echo "<br><strong>TotalFactura:</strong> ".$xml->TotalFactura;

      echo "<hr><strong>NumeroCedulaReceptor:</strong> ".$xml->NumeroCedulaReceptor;
      echo "<br><strong>NumeroConsecutivoReceptor:</strong> ".$xml->NumeroConsecutivoReceptor;
      
      $nombre='FACCOMPRA-'.$xml->Clave."-".$xml->NombreEmisor.".xml";
   }
  
?>
<br>
<a href="<?php echo $_GET['xml'];?>" title='Descargar' download="<?php echo $nombre;?>" class="btn btn-sm btn-success" style="margin-left: 3px;"><i class="entypo-download"></i></a>
<?php 
include("../includes/footerLimpio.php");?>