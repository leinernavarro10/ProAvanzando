<?php include("../conn/connpg.php");
controlAcceso('C-0');
$idAsiento = $_GET['id'];
if(isset($_GET['estado'])){

  $sql2 = "UPDATE casientos SET estado  = '".$_GET['estado']."' WHERE id = '$idAsiento' ";
  $query2 = mysql_query($sql2, $conn) or die(mysql_error());

  echo "<script type='text/javascript'>
  parent.location.href = 'editarasientos.php?id=".$_GET['id']."';
  </script>";
  break;
}


?>  
<?php
$pagina="Editar Asientos";
$skin='facebook';
 include("../includes/header.php");
 ?>

 <?php
 include("../includes/sidebar.php");
 include("includes/menu.php");
 include("../includes/body.php");?>
<script type="text/javascript">
  function resizeIframe(obj){
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>

<div class="row">
<a href="asientos.php" class="btn btn-red pull-right" style="margin-right: 17px; margin-bottom: 10px;"><i class="entypo-cancel-circled"></i></a>
<a href="asientos.php?n=1" class="btn btn-primary pull-right" style="margin-right: 17px; margin-bottom: 10px;"><i class="entypo-plus"></i> Nuevo Asientos</a>
</div>
<!-- INICIA CONTENIDO -->
<?php include("../includes/alerts.php");?>

<style type="text/css">
  .btn {
    margin-top: 3px;
  }
  .tr2{
        background-color: #C5C5C5;
        color: #000000;
    } 

    .trb:hover td{
    
    cursor: pointer;
    }
    
    
    .custom-width2{
      width: 85%;
    }
    @media(max-width : 794px){
    .custom-width2{
        width: 97%;
    }
    .esconderPR{
      display: none;
    }   
}

.form-control:focus{
  border:1px solid #FF0000;
}


</style>

<div class="row">

      <?php 
      $sql = "SELECT * FROM casientos WHERE id = '$idAsiento'  ";
      $query = mysql_query($sql, $conn);
      if(mysql_num_rows($query)>0){
      while ($row=mysql_fetch_assoc($query)) {
        $estadoFE = $row['estadoFE'];

        $estado = $row['estado'];
        if ($estado != 1){
          $disabled = 'disabled="disabled"';
          $disabled2 = '';
        }else{
          $disabled = '';
          $disabled2 = 'disabled="disabled"';
        }
        if ($row['id'] == ''){
              $numeroAsiento = 0; 
            }else{
              $numeroAsiento = $row['id'];
            }
            
      ?>
        
        
      <div id="datosCliente" class="col-md-12" >
      
        <div class="panel panel-dark">
          <!-- panel head -->
          <div class="panel-heading" style="background: #D35E00;color: #FFFFFF">
            <div class="panel-title">Datos asientos</div>
            <div class="panel-options">
              <!--<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>-->
            </div>
          </div>
          <!-- panel body -->
          <div class="panel-body">
          
           <form role="form" action="" class="form-horizontal" method="post" name="formulario" id="formulario" >
              <input type="hidden" name="idFactura" id="idAsiento" value="<?php echo $row['id']?>">
            <table class="table table-condensed">
              <tr>
                <td>
                  <label for="id" class="col-sm-1 control-label">ID:</label>
                </td>
                <td>
                  <input type="text" class="form-control" disabled="" value="<?php echo addZero($numeroAsiento)?>" name="id" id="id" style='width: 75px' readonly >
                </td>
                 <td>
                  <label for="periodo" class="col-sm-1 control-label">Periodo:</label>
                </td>
                <td>
                  <select class="form-control 1-1" onkeyup="controlfocus(1,1,event)" name="periodo" id="periodo" <?php echo $disabled?> style='width: 100px' >
                    <?php 
                      $sqlP="SELECT * FROM cperiodos WHERE estado='1' OR id='".$row['idperiodo']."' ORDER BY id DESC";
                      $queryP=mysql_query($sqlP,$conn);
                      while($rowP=mysql_fetch_assoc($queryP)){
                    ?>
                    <option value="<?php echo $rowP['id']?>" title='<?php echo $rowP['detalle']?>' <?php if( $rowP['id']==$row['idperiodo']){echo "selected";}?>>
                      <?php echo $rowP['periodo']?>
                    </option>
                    <?php }?>
                  </select>
                </td>
                <td>
                  <label for="moneda" class="col-sm-1 control-label">Moneda:</label>
                </td>
                <td>
                  <select class="form-control 1-2" onkeyup="controlfocus(1,2,event)" name="moneda" id="moneda" <?php echo $disabled?> >
                    <?php 
                      $sqlP="SELECT * FROM cmonedas ORDER BY id ASC";
                      $queryP=mysql_query($sqlP,$conn);
                      while($rowP=mysql_fetch_assoc($queryP)){
                    ?>
                    <option value="<?php echo $rowP['id']?>" title='<?php echo $rowP['nombre']?>' <?php if( $rowP['id']==$row['idmoneda']){echo "selected";}?>>
                      <?php echo $rowP['codigo']?>
                    </option>
                    <?php }?>
                  </select>
                </td>
                <td>
                  <label for="fecha" class="col-sm-1 control-label">Fecha:</label>
                </td>
                <td>
                 
                  <div class="input-group"> 
                    <input type="text" class="form-control datepicker piso 1-3" onkeyup="controlfocus(1,3,event)" data-format="dd/mm/yyyy" <?php echo $disabled?> value="<?php echo $row['fechadoc']?>" name="fecha" id="fecha" style='z-index: 15'><div class="input-group-addon"> <a href="#"><i class="entypo-calendar"></i></a> </div> 
                  </div>
                </td>

                <td rowspan="3">
                  <button id="btnGuardar" type="button" class="btn btn-primary" <?php echo $disabled?> name="guardar" onclick="guardarAsiento()"><i class="entypo-floppy"></i></button><br>
                  <?php if($estado==1){?>
                  <button id="btnFinalizar" onclick="aplicar(2)" type="button" class="btn btn-primary" disabled name="finalizar"><i class="entypo-check"></i></button><br>
                  <?php }else{?>
                        <?php  if(revisarPrivilegios('C-9')){?>
                          <button id="btnFinalizar" onclick="aplicar(1)" type="button" class="btn btn-red" disabled name="finalizar"><i class="entypo-pencil"></i></button><br>
                        <?php }?>
                    <?php }?>
                  <button id="btnImprimir" onclick="imprimir()" type="button" class="btn btn-primary" <?php echo $disabled2?> ><i class="entypo-print"></i></button><br>
                  <button id="btnCerrar" type="button" class="btn btn-red" onclick="document.location.href='../contabilidad/asientos.php'"><i class="entypo-cancel-circled"></i></button>
                </td>
              </tr>

              <tr>
               
                <td>
                  <label for="no" class="col-sm-1 control-label">NO°:</label>
                </td>
                <td>
                  <input type="text" class="form-control 2-1" value='<?php echo $row['numero']?>' <?php echo $disabled?>  onkeyup="controlfocus(2,1,event)" name="no" id="no" >
                </td>
                 <td>
                  <label for="documento" class="col-sm-1 control-label">Documento:</label>
                </td>
                 <td>
                  <select class="form-control 2-2" onkeyup="controlfocus(2,2,event)" name="documento" id="documento" <?php echo $disabled?> style='width: 140px'  size="1">
                    <?php 
                      $sqlP="SELECT * FROM cdocumentos WHERE estado='1' OR id='".$row['iddocumento']."' ORDER BY id ASC";
                      $queryP=mysql_query($sqlP,$conn);
                      while($rowP=mysql_fetch_assoc($queryP)){
                    ?>
                    <option value="<?php echo $rowP['id']?>" title='<?php echo $rowP['detalle']?>'>
                      <?php echo $rowP['nombre']?>
                    </option>
                    <?php }?>
                  </select>
                </td>
                 <td>
                  <label for="idpersona" class="col-sm-1 control-label">Persona:</label>
                </td>
                <td colspan="3">
                  <input type="text" class="form-control 2-3" onkeyup="controlfocus(2,3,event)" onblur="mostrarBusqueda(3)" ondblclick="verpersona(3)" name="idpersona"  id="idpersona" <?php echo $disabled?> style="display: inline-block;width: 15%" value='<?php echo $row['idpersona']?>' >
                  <?php 
                    $sqlP="SELECT nombre FROM tpersonas WHERE id='".$row['idpersona']."' ";
                    $queryP=mysql_query($sqlP,$conn);
                    while($rowP=mysql_fetch_assoc($queryP)){
                      $nombrePersona=$rowP['nombre'];
                    }
                  ?>
                  <input type="text" class="form-control 2-4" name="persona" id="persona" value='<?php echo $nombrePersona?>' disabled style="display: inline-block;width: 80%">
                </td>
              </tr>

              <tr>
                <td><label for="descripcion" class="col-sm-1 control-label">Descripción:</label></td>
                <td colspan="7"><input type="text" class="form-control 3-1" onkeyup="controlfocus(3,1,event)" name="descripcion" id="descripcion" <?php echo $disabled?> value="<?php echo $row['descripcion']?>" ></td>
              </tr>
            </table>
          </form>
            
            <iframe id="productosProforma" src="lineasAsiento.php?idasiento=<?php echo $idAsiento?>&estado=<?php echo $estado?>" frameborder="0" style="width: 100%;height: auto" scrolling="no" onload="resizeIframe(this)" ></iframe>
          </div>
          
        </div>
    
            
        </div>
  
      <?php }

    }else{
      echo "<center>No se encuentra la factura</center>";
    }

       ?>
      
      
    
    </div>



<script type="text/javascript">


  var cantidadCks = 1;
  var cantidadGenerados = 0;
  var myInterval = "";
 $(".page-container").addClass("sidebar-collapsed");

function controlfocus(id,num,event){
   var tecla =event.which || event.keyCode;

    if(tecla==13){
      if(num==3){
        id=id+1;
        controlfocus(id,0,event);
      }else if(num==1 && id==3){
        $("#productosProforma").contents().find("#0-1").focus().select();
        guardarAsiento();
      }else{
        num=parseInt(num)+1;
        $("."+id+"-"+num).focus().select();
      }
  }else if(tecla==37){
    if(num==1){
        id=id-1;
        controlfocus(id,4,event);
      }else{
        num=parseInt(num)-1;
        $("."+id+"-"+num).focus().select();
      }
  }else if(id==2 && num==3){
    verpersona(3);
    
  }
}
 
  function mostrarBusqueda(val){
    var persona=$("#persona").val();
    if(persona==""){
      verpersona(val);
    }
  }


  function cargarCliente(id,nombre){
    $("#idpersona").val(id);
    $("#persona").val(nombre);
    var cuentaper=$("#productosProforma").contents().find("#0-3").val();

    if(cuentaper==""){
      $("#productosProforma").contents().find("#0-3").val(id);
      $("#productosProforma").contents().find("#0-3Nombre").val(nombre);
    }
   
    $(".3-1").focus().select();
  }

  function cargarCliente2(id,nombre){

      $("#productosProforma").contents().find("#0-3").val(id);
      $("#productosProforma").contents().find("#0-3Nombre").val(nombre);
    
   
    $("#productosProforma").contents().find("#0-4").focus().select();
  }
  //---------- CC


function cargarCentro(num){
  var cc=$("#productosProforma").contents().find("#0-1id").val();
 
    
      $('#modalBuscarCC').modal('show');
        $('#modalBuscarCC').on('shown.bs.modal', function () {
           $(this).find('#txtCC').focus();
           var txt=$("#productosProforma").contents().find("#0-1").val();
           $(this).find('#txtCC').val(txt);
           buscarCentroCosto('');
    });
  
}
var numcc=0;
  function buscarCentroCosto(event){

    var tecla =event.which || event.keyCode;

    if(tecla==40){
      //abajo
      if(numcc<$("#cantCC").val()){
        numcc++;
        $(".tdbselect").removeClass('tdbselect');
        $("#trb"+numcc).addClass('tdbselect');
      }
    }else if(tecla==38){
      //arriba
      
      if(numcc>1){
        numcc--;
        $(".tdbselect").removeClass('tdbselect');
        $("#trb"+numcc).addClass('tdbselect');
      }
    }else if(tecla==13){
      $(".tdbselect").click();
    }else{
      numcc=0;
      var txtBuscar = $("#txtCC").val();
      $.post( "funcionesAJAXAsientos.php", {f: 'BUSCAR-CC', txtBuscar: txtBuscar})
      .done(function( data ) {
         jQuery('#resultadoBusquedaCC').html(data);
      }); 
  }
  }
function agregarCC(id,nombre){
  $('#cancelarResponsivecc').click();
  $("#productosProforma").contents().find("#0-1id").val(id);
  $("#productosProforma").contents().find("#0-1").val(nombre);
   $("#productosProforma").contents().find("#0-2").focus().select();
}

// ------- cuentas



function cargarCuenta(num){
  var cc=$("#productosProforma").contents().find("#0-2").val();
      $('#modalBuscarCuentas').modal('show');
        $('#modalBuscarCuentas').on('shown.bs.modal', function () {
          var txt=$("#productosProforma").contents().find("#0-2").val();
           $(this).find('#txtCuenta').focus();
           $(this).find('#txtCuenta').val(txt);
           buscarCuenta('');
    });
  
}
var numCuenta=0;
  function buscarCuenta(event){

    var tecla =event.which || event.keyCode;

    if(tecla==40){
      //abajo
      if(numCuenta<$("#cantCuenta").val()){
        numCuenta++;
        $("#trbCU"+(numCuenta-1)).removeClass('tdbselect');
        $("#trbCU"+numCuenta).addClass('tdbselect');
      }
    }else if(tecla==38){
      //arriba
      
      if(numCuenta>1){
        numCuenta--;
        $("#trbCU"+(numCuenta+1)).removeClass('tdbselect');
        $("#trbCU"+numCuenta).addClass('tdbselect');
      }
    }else if(tecla==13){
      $(".tdbselect").click();
    }else{
      numCuenta=0;
      var txtBuscar = $("#txtCuenta").val();
      $.post( "funcionesAJAXAsientos.php", {f: 'BUSCAR-CUENTA', txtBuscar: txtBuscar})
      .done(function( data ) {
         jQuery('#resultadoBusquedaCuenta').html(data);
      }); 
  }
  }
function agregarCuenta(id,nombre){
  $('#cancelarResponsiveCuentas').click();
  $("#productosProforma").contents().find("#0-2").val(id);
  $("#productosProforma").contents().find("#0-2nombre").val(nombre);
   $("#productosProforma").contents().find("#0-3").focus().select();
}

</script>
<div class="modal fade" id="modalBusqueda" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Buscar cliente</h4>
      </div>
        <div class="modal-body">        
          <div class="form-group">
        <div class="col-sm-12">
        
            <input type="text" onkeyup="buscarCliente(event)" id="txtBuscar" placeholder="Buscar cliente" class="form-control" autocomplete="false">
        
        </div>
        <br>
        <br>
        <br>
        <div id="resultadoBusqueda">
          <center><i class="entypo-info"></i><br>Por favor digite el nombre de un cliente.</center>
        </div>
      </div>
        </div>
      
      <div class="modal-footer">
        <table width="100%">
          <tr>
           
            <td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsive">Cancelar</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalBuscarCC" data-backdrop="static">
  <div class="modal-dialog"  >
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Buscar Centro Costo</h4>
      </div>
          <div class="panel-body">
            <div class="tab-content">
              <div class="tab-pane active" id="profile-1">
                <div class="form-group">
                <div class="col-sm-12">
                    <input type="text" onkeyup="buscarCentroCosto(event)" id="txtCC" placeholder="Buscar Centro de Costo" class="form-control" autocomplete="false">
                </div>
                <br>
                <br>
                <br>
                <div id="resultadoBusquedaCC">
                  <center><i class="entypo-info"></i><br>Por favor digite el nombre o descripción del centro de costo</center>
                </div>
              </div>

              </div>
              
              
            </div>
            
          </div>
        
      <div class="modal-footer">
        <table width="100%">
          <tr>
            <td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsivecc">Cancelar</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalBuscarCuentas" data-backdrop="static">
  <div class="modal-dialog"  >
    <div class="modal-content">
      
    <div class="modal-header">
        <h4 class="modal-title" id="tablaResponsiveNombre">Buscar Cuentas</h4>
      </div>
          <div class="panel-body">
            <div class="tab-content">
              <div class="tab-pane active" id="profile-1">
                <div class="form-group">
                <div class="col-sm-12">
                    <input type="text" onkeyup="buscarCuenta(event)" id="txtCuenta" placeholder="Buscar Catalogo Cuentas" class="form-control" autocomplete="false">
                </div>
                <br>
                <br>
                <br>
                <div id="resultadoBusquedaCuenta">
                  <center><i class="entypo-info"></i><br>Por favor digite el nombre o descripción del centro de costo</center>
                </div>
              </div>

              </div>
              
              
            </div>
            
          </div>
        
      <div class="modal-footer">
        <table width="100%">
          <tr>
            <td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsiveCuentas">Cancelar</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>


<div class="modal custom-width fade" id="modalImprimir" data-backdrop="static">
  <div class="modal-dialog" style="width: 900px" >
    <div class="modal-content">
      

          <div class="panel-body" id="divimprimir">
              <center>Cargando ...</center>
          </div>
        
      <div class="modal-footer">
        <table width="100%">
          <tr>
            <td width="80"><button type="button" class="btn btn-default cerrarModal" data-dismiss="modal" id="cancelarResponsiveCuentas">Cancelar</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="../assets/js/jquery.hotkeys.js"></script>
<script type="text/javascript">

function guardarAsiento(){
  var idAsiento = $("#idAsiento").val();
  var periodo = $("#periodo").val();
  var moneda = $("#moneda").val();
  var fechadoc = $("#fecha").val();
  var no = $("#no").val();
  var documento = $("#documento").val();
  var idpersona = $("#idpersona").val();
  var descripcion = $("#descripcion").val();
  
  if(no!=""){
  $.post( "funcionesAJAXAsientos.php", { f: 'GUARDAR-ASIENTO', idAsiento: idAsiento, periodo: periodo, moneda: moneda, fechadoc: fechadoc, no: no, documento: documento, idpersona: idpersona, descripcion: descripcion})
      .done(function( data ) {
       mensajeExito("El asiento se guardó exitosamente.",tipo = 1);
    });
  }else{
      mensajeExito("Digite el número de documento para continuar.",tipo = 2);
      $(".2-1").focus().select();
  } 
}


function aplicar(val){
  document.location.href="editarasientos.php?bNombre=<?php echo $_GET['bNombre']?>&pagi=<?php echo $_GET['pagi']?>&id=<?php echo $_GET['id']?>&estado="+val;
}

function imprimir(){
 $('#modalImprimir').modal('show');
 $('#modalImprimir').on('shown.bs.modal', function () {
          $("#divimprimir").load("imprimirAsientos.php?id=<?php echo $_GET['id']?>");
  });
}


</script>

<!-- FIN CONTENIDO -->
<?php include("../includes/footer.php");?>