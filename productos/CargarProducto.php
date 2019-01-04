
<?php include("../conn/connpg.php");

if($_GET['estado']=="1"){
  $sql = "UPDATE tproductos SET 
      estado='1'
      WHERE vendedor = '".$_GET['id']."' AND estado='0'";
      mysql_query($sql,$conn)or die(mysql_error());
} 




 $pagina="Nuevo Producto"; include("../includes/header.php");?>        
<?php include("../includes/sidebar.php");?>
<?php include("../includes/body.php");?>



<style type="text/css">
   .cars{
        
        position: absolute;
        background:#001E4F;
        color: #FFFFFF;
    }
        
    .esconder{
        display: none;
    }

</style>


  


<ol class="breadcrumb">
	<li>
		<a href="<?php echo base_url();?>"><i class="fa-home"></i>Home</a>
	</li>
	
</ol>
<a href="PlantillaProductosCarne.xlsx" download="PlantillaProductosCarne"><i class="entypo-download"></i> Descargar Plantilla</a>
<div class="row">



    <div class="col-md-4">
        <div class="panel panel-dark" id="charts_env">
          <div class="panel-heading">
            <div class="panel-title">Subir Productos</div>
          </div>
          <div class="panel-body">
<form action="" method="post" onsubmit="javascript: $('#sub').removeClass('esconder');" name="f1" enctype="multipart/form-data"> 
              
<div id="errorverificar" style="text-align: center;"></div>
               
    
             
        
                  <div class="fileinput fileinput-new" id="dvdfile" data-provides="fileinput">
                    <input type="hidden">
                    <div class="input-group">
                      <div class="form-control uneditable-input" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                      </div>
                      <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">Seleccione el archivo</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="excel">
                      </span>
                      
                    </div>
                  </div>

              <br>
             
        
                  <input type='submit' name='enviar'  value="Importar" id="btnfile" class="btn btn-success "  />
                  <input type="hidden" value="upload" name="action" />
                </form>       

<div class="progress progress-striped active esconder" id="sub">
     <div class="progress-bar progress-bar-success"  style="width: 100%">
        <span class="sr-only">Subiendo…</span>
      </div>
</div>
        
         <?php
    extract($_POST);
    if ($action == "upload") {
        //cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo bak_ 
        $archivo = $_FILES['excel']['name'];
        $tipo = $_FILES['excel']['type'];
        $nombre = date('dmY')."".$archivo;

        $destino = $nombre;

        if (copy($_FILES['excel']['tmp_name'], $destino)){?>
          <hr>
             <div class="alert alert-success">
              <strong>Success!</strong> Archivo Cargado Con Éxito</div>
                
        <?php }
        else{
            ?>
            <hr>
            <div class="alert alert-danger"><strong>Error</strong> Al Cargar el Archivo.</div>
                       
        <?php 
    }
        if (file_exists($destino)) {
          
require_once '../../carneslogin/configuracion/PHPExcel/Classes/PHPExcel.php';
$archivo = $destino;
$inputFileType = PHPExcel_IOFactory::identify($archivo);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($archivo);
$sheet = $objPHPExcel->getSheet(0); 



$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();



echo "<div class='progress progress-striped active' id='sub'>
          <div class='progress-bar progress-bar-success'  style='width: 100%''>
        <span class='sr-only'>Cargando...</span>
        </div>
</div>";

$delete="DELETE FROM tproductos  ";
mysql_query($delete,$conn);

for ($row = 4; $row <= ($row+2) ; $row++){  
                 
                    /*$color=$objPHPExcel->getActiveSheet()
                            ->getStyle('A' . $row)
                            ->getFont()
                            
                            ->getColor()
                            ->getRGB();
                    */
                    $codigo   =$sheet->getCell('B' . $row)->getValue();
                    $nombre   =$sheet->getCell('E' . $row)->getValue();
                    $exento      =$sheet->getCell('F' . $row)->getValue();
                    //$costoUnitario   =$sheet->getCell('E' . $row)->getValue();
                    $precioVenta   =$sheet->getCell('G' . $row)->getValue();
                    $cantidad =$sheet->getCell('A' . $row)->getValue();
                    
                    if($exento=='Exe'){
                        $exento=1;
                    }else{
                        $exento=0;
                    }
                  
                    //$noches=$sheet->getCell('AB' . $row)->getCalculatedValue();
        if($codigo==""){
            echo "<div class='cars'>".round((100*$row)/$row)."%  </div>";
            break;
        }else{
            echo "<div class='cars'>".round((100*$row)/$highestRow)."%  </div>";
        }

       if($cantidad>0 OR $cantidad!=""){
          $sadatos .= "(null,'".$codigo."','".$nombre."','','','1','','".$precioVenta."','".$exento."','','0','".$cantidad."','','','0','1', '".$cuentaid."'),";
          
        }
 }

$sadatos=substr($sadatos, 0, -1);

       $sqlN = "INSERT INTO tproductos VALUES ".$sadatos;
          mysql_query($sqlN, $conn)or die(mysql_error());
        

?>
<br>
<hr>
  <div class="alert alert-warning">
    <strong>Success!</strong> 
           Los datos se cargaron con éxito 
  </div>

<?php
        echo "<script>document.location.href='../productos?id=".$_POST['id']."'</script>";   
            
        }
        //si por algo no cargo el archivo bak_ 
        else {?>
            <div class="alert alert-error alert-block">
              <h4 class="alert-heading">Error!</h4>
              Necesitas primero importar el archivo</div>

        <?php
            
        }
   
    }
     ?>



          </div>
        </div>
        <?php if($_GET['id']!=""){?>
        <strong>
        <i>Si los productos no están correctos vuelva a subir el archivo </i>
        </strong>
        <?php }?>
      </div>

      

      




<!-- FIN CONTENIDO -->
  <script src="../assets/js/fileinput.js"></script>
<?php 


include("../includes/footer.php");
?>

 
