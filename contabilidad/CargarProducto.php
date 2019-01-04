
<?php include("../conn/connpg.php");


$pagina="Cargar Catalogo";
$skin='facebook';
 include("../includes/header.php");
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>


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

                <form action="" method="post" name="f1" enctype="multipart/form-data"> 
              
               
                
                  <div class="fileinput fileinput-new " id="dvdfile" data-provides="fileinput">
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
             
        
                  <input type='submit' name='enviar'  value="Importar" id="btnfile" class="btn btn-success"  />
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

        $destino = "".$nombre;

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
          
require_once '../assets/PHPExcel/Classes/PHPExcel.php';
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



for ($row = 2; $row <= ($row+2) ; $row++){  
                 
                    /*$color=$objPHPExcel->getActiveSheet()
                            ->getStyle('A' . $row)
                            ->getFont()
                            
                            ->getColor()
                            ->getRGB();
                    */
                    $codigo   =$sheet->getCell('A' . $row)->getValue();
                    $nombre   =$sheet->getCell('B' . $row)->getValue();
                   
                      
                    //$noches=$sheet->getCell('AB' . $row)->getCalculatedValue();
                    
        if($codigo==""){
            echo "<div class='cars'>".round((100*$row)/$row)."%  </div>";
            break;
        }else{
            echo "<div class='cars'>".round((100*$row)/$highestRow)."%  </div>";
        }
         $codigo=explode('-',$codigo);
                    $codigo1=$codigo[0];
                    $codigo2=$codigo[1];
                    $codigo3=$codigo[2];
                    $codigo4=$codigo[3];

                    
       
          $sadatos .= "(null,'".$codigo1."','".$codigo2."','".$codigo3."','".$codigo4."','".$nombre."','1'),";
          
        
 }

$sadatos=substr($sadatos, 0, -1);
echo "<br><br>".$sadatos;
       $sqlN = "INSERT INTO ccatalogocuentas VALUES ".$sadatos;
          mysql_query($sqlN, $conn)or die(mysql_error());
        

?>
<br>
<hr>
  <div class="alert alert-warning">
    <strong>Success!</strong> 
           Los datos se cargaron con éxito 
  </div>

<?php
      
            
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
        
      </div>

      
      </div>

      




<!-- FIN CONTENIDO -->
  <script src="../assets/js/fileinput.js"></script>
<?php 


include("../includes/footer.php");
?>

 
