<?php  include ("../conn/connpg.php");

controlAcceso('C-0');

decode_get2($_SERVER["REQUEST_URI"]); 
$error = 0;

$idasiento = $_GET['idasiento'];

$estado = $_GET['estado'];


if (isset($_GET['idCuenta'])){

    $sql = "SELECT * FROM clineasasiento WHERE idasiento  = '".$_GET['idasiento']."' ORDER BY numero ASC ";
        $query = mysql_query($sql, $conn);
        $numero=0;
        while($rowN=mysql_fetch_assoc($query)){
          $numero=$rowN['numero'];
        }

    $sql="INSERT INTO clineasasiento VALUES(
    null,
    '".$_GET['idasiento']."',
    '".$_GET['idCC']."',
    '".$_GET['idCuenta']."',
    '".$_GET['idAux']."',
    '".$_GET['debe']."',
    '".$_GET['haber']."',
    '".$numero."'
  )";
  mysql_query($sql,$conn)or die(mysql_error());

}


if (isset($_GET['del'])){
  
  $sql2 = "DELETE FROM clineasasiento WHERE id  = '".$_GET['idDel']."' ";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());
}


?>
<?php  include("../includes/headerLimpio.php");?>  
<script src="../assets/js/jquery-1.11.3.min.js"></script>     
<?php  include("../includes/alerts.php");?>       

    <link rel="stylesheet" href="style.css">
<style type="text/css">
  .form-control:focus{
    border:1px solid #FF0000;
  }
</style>

  <?php  
	$sql = "SELECT * FROM clineasasiento WHERE idasiento = '$idasiento' ORDER BY numeroLinea DESC";
  $query = mysql_query($sql, $conn)or die(mysql_error());
	$numProductos = mysql_num_rows($query);

?>
<div class="panel" >

    <table width="100%" class="table table-bordered responsive grande">     
      <tr style="background: #DADADA;font-weight: 600;text-align:center">
        <td></td>
        <td width="110px">Centro Costo</td>
        <td>Cuenta</td>
        <td>(Aux)</td>
        <td width="115px">DEBE</td>
        <td width="115px">HABER</td>       
      </tr>
<?php if($estado==1){?>
      <form action="" id="Nformulario" method="get" autocomplete="off">
        <input type="hidden" name="idasiento" value="<?php echo $idasiento?>"/>
        <input type="hidden" name="estado" value="<?php echo $estado?>"/>
      <tr style="background: #84BB5F">
        <td></td>
        <td><input type="hidden" name="idCC" id="0-1id" value="<?php echo $_GET['idCC']?>" />
           <input type="text" name="periodo" id="0-1" value="<?php echo $_GET['periodo']?>" class="form-control" onkeyup="controlfocus2(0,1,event)" ondblclick="parent.cargarCentro(2)" />
        </td>
        
        <td>
          <input type="text" class="form-control" name="idCuenta" id="0-2" onkeyup="controlfocus2(0,2,event)"  ondblclick="parent.cargarCuenta(2)" name="idpersona"  style="display: inline-block;width: 15%" >
          <input type="text" id="0-2nombre" class="form-control" disabled style="display: inline-block;width: 80%">
        </td>
        <td>
          <input type="text" class="form-control" name='idAux' id="0-3" onkeyup="controlfocus2(0,3,event)" ondblclick="parent.verpersona(4)" name="idpersona" style="display: inline-block;width: 15%"  >
           <input type="text" class="form-control" name='nombre' id="0-3Nombre" disabled style="display: inline-block;width: 80%">
        </td>
        <td>
          <input type="text" name="debe" id="0-4" class="form-control" value="0" onkeyup="controlfocus2(0,4,event)" data-mask="fdecimal" data-dec=" " data-rad="," maxlength="15" >
        </td>
        <td>
          <input type="text" name="haber" id="0-5" class="form-control" value="0"  onkeyup="controlfocus2(0,5,event)" data-mask="fdecimal" data-dec=" " data-rad="," maxlength="15" >
        </td>
      </tr>
      </form>
<?php }?>

      <?php  
	  	$totaldebe=0;
      $totalhaber=0;
      $cont=0;
        while ($row=mysql_fetch_assoc($query)) {
        $cont++; 
              ?>
          <tr id="tr<?php echo $cont;?>">
          <td>
          <?php if($estado==1){?>
            <a type="button" class="btn btn-xs btn-danger" href="lineasAsiento.php?del=1&idDel=<?php echo $row['id']?>&idasiento=<?php echo $idasiento?>&estado=<?php echo $estado;?>"><i class="entypo-trash"></i></a>
          <?php }?>
          </td>
          <td>
           <?php 
            $sqlCC = "SELECT * FROM ccostoscentro WHERE id = '".$row['idcentrocosto']."' ";
            $queryCC=mysql_query($sqlCC,$conn)or die(mysql_error());
            while($rowCC=mysql_fetch_assoc($queryCC)){
                echo $rowCC['nombre'];
            }
           ?>
          </td>
          <td>
  			    <?php 
            $sqlCC = "SELECT * FROM ccatalogocuentas WHERE id = '".$row['idcuenta']."' ";
            $queryCC=mysql_query($sqlCC,$conn)or die(mysql_error());
            while($rowCC=mysql_fetch_assoc($queryCC)){
              $codigo=$rowCC['codigo1']."-".$rowCC['codigo2']."-".$rowCC['codigo3']."-".$rowCC['codigo4'];
                echo $rowCC['descripcion']." <i>(".$codigo.")</i>";
            }
           ?>
          </td>
          <td>
          <?php 
            $sqlCC = "SELECT * FROM tpersonas WHERE id = '".$row['idpersona']."' ";
            $queryCC=mysql_query($sqlCC,$conn)or die(mysql_error());
            while($rowCC=mysql_fetch_assoc($queryCC)){
              
                echo $rowCC['nombre']." <i>(".$rowCC['identificacion'].")</i>";
            }
           ?>
          </td>
          <td>
            <?php echo $row['debe'];
            $debe = str_replace(' ', '', $row['debe']);
            $debe = str_replace(',', '.', $debe);
            $totaldebe+=$debe;
            ?>
          </td>
          <td valign="middle">
      			<?php echo $row['haber'];
            $haber = str_replace(' ', '', $row['haber']);
            $haber = str_replace(',', '.', $haber);
            $totalhaber+=$haber;
            ?>
          </td>
          
        </tr>   
  <?php  
   
}


//----------- editar

?>

 <input type="hidden" name="" id="cantRG" value="<?php echo $cont;?>"> 
 <?php 
 $balance="border:2px solid #770000";
if($totaldebe==$totalhaber){
  $balance="border:2px solid #008820";
}
 ?>


<tr style='text-align: right;font-weight: 600;font-size: 15px; '>
  <td colspan="3">

  </td>
  <td >
    Total
  </td>
  <td style="<?php echo $balance?>"><?php echo number_format($totaldebe,2,',',' ')?></td>
  <td style="<?php echo $balance?>"><?php echo number_format($totalhaber,2,',',' ')?></td>
</tr>
<tr style='text-align: right;font-weight: 600;font-size: 15px'>
  <td colspan="3">

  </td>
  <td>
    Balance
  </td>
  <td colspan="2" style="<?php echo $balance?>"><?php echo number_format($totaldebe-$totalhaber,2,',',' ')?></td>

</tr>
 
  		
    </table>    

   <?php 


    
if ($myError == 1){
  echo '<script type="text/javascript">miAlerta("'.$mensaje.'",1);</script>';
}
?>

</div>
<script type="text/javascript">


function cargarOnload(){

  if(parent.$("#no").val()!=""){
    $("#0-1").focus();
  }else{
    parent.$("#no").focus().select();
  }
 
  var idpersona=parent.$("#idpersona").val();
  var persona=parent.$("#persona").val();

  $("#0-3").val(idpersona);
  $("#0-3Nombre").val(persona);

  if(<?php echo $totaldebe?>==<?php echo $totalhaber?>){
    parent.$("#btnFinalizar").removeAttr("disabled");
  }else{
    parent.$("#btnFinalizar").prop('disabled', true);
  }

}



function controlfocus2(id,num,event){
   var tecla =event.which || event.keyCode;

    if(tecla==13 || tecla == 39){
      if(num==5){
        guardarform();
      }else{
        num=parseInt(num)+1;
        $("#"+id+"-"+num).focus().select();
      }
  }else if(tecla==37){
    if(num==1){
      if(id==0){
        parent.$(".3-1").focus().select();
      }else{
        id=id-1;
        controlfocus2(id,4,event);
      }
        
    }else{
        num=parseInt(num)-1;
        $("#"+id+"-"+num).focus().select();
      }
  }else if(id==0 && num==1){
    //0-1 funcion escribir
      parent.cargarCentro(2);
  }else if(id==0 && num==2){
    //0-2 funcion escribir
     parent.cargarCuenta(2);
  }else if(id==0 && num==3){
    //0-2 funcion escribir
     parent.verpersona(4);
  }

}

function guardarform(){
  var idCC=$("#0-1id").val();
  var idCuenta=$("#0-2").val();
  var idAux=$("#0-3").val();
  var debe=$("#0-4").val();
  var haber=$("#0-5").val();
    if(idCC!="" && idCuenta!="" && (debe!=0 || haber!=0)){
      $("#Nformulario").submit();
    }else{
      miAlerta("Faltan datos",1)
      var idCC=$("#0-1").focus().select();
    }
}


function guardar(idTxt,num){
  
 
   $("#cargador").html('<img src="../assets/images/cargando.gif" width="15px">');
    var idProforma="<?php echo $idProforma?>";
      var id=$("#id-"+num).val();
      var codigo=$("#2-"+num).val();
      var cantidad=$("#1-"+num).val();
      var numero=$("#numero-"+num).val();
      var codigo2=$("#codigo2-"+num).val();
      var descuento=$("#4-"+num).val();
      var html=$("#tr"+num).html();

      $.post( "funcionesAJAXgrid.php", { f: 'CAMBIAR-PRODUCTO',idProforma:idProforma,id:id,codigo:codigo,cantidad:cantidad,numero:numero,codigo2:codigo2,descuento:descuento,num:num})
      .done(function(data) {
       //alert(data);
        if(data==3){
          $("#tr"+num).html(html);
          $("#cargador").html("<span style='color:#14AA00'>No se encontro producto.</span>");
          $("#2-"+num).focus().select();
        }else if(data==4){
          $("#tr"+num).html(html);
          $("#cargador").html("<span style='color:#14AA00'>Ya existe una línea con ese código.</span>");
          $("#2-"+num).focus().select();
        }else if(data==5){
          //borrar
          $("#tr"+num).html("");
          $("#cargador").html("");
            $("#cantidad2").focus().select();
        }else{
            $("#tr"+num).html(data);
          $("#cargador").html("");
          idTxt=parseInt(idTxt)+1;
          $("#"+idTxt+"-"+num).focus().select();
          precios();
        }
        


          })  .fail(function() {
        alert("Error al cargar cambio de producto");
        document.reload();
    });

      
    
}



</script>

<!-- FIN CONTENIDO -->
 


<?php  include("../includes/footerLimpio.php");?>