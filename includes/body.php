	<div class="main-content">



		<div class="modal fade custom-width" id="modal-2">
    <div class="modal-dialog" style="width: 60%">
     
      
        
        <div class="modal-body" id="modal">
         Cargando...          
        </div>
        
     
     
    </div>
  </div>


<div class="modal fade" data-backdrop="static" id="modal_conf">
    <div class="modal-dialog">
      <div class="modal-content">
      
        
        <div class="modal-body" id="modalcf">
         Cargando...          
        </div>
        
        
      </div>
    </div>
  </div>

<div class="modal fade custom-width" id="modalNuevaPersonas" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog" style="width: 70%">
      <div class="modal-content">
        
        <div class="modal-body" id="resultadoNuevaPersona">
          <center><img src='../assets/images/cargando.gif' width='30px'>Cargando... </center>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>


<div class="modal fade custom-width" id="modalPersonas" data-backdrop="static" data-keyboard="false" >
		<div class="modal-dialog" style="width: 90%">
			<div class="modal-content">
				
				<div class="modal-body" id="modal3">
          
           <?php if(revisarPrivilegios('P-0') OR revisarPrivilegios('P-1')){?>
            <center><strong>Buscar persona</strong></center>
		        <div class="form-group">
              <div class="col-sm-11">
                 <input type="hidden" id="tipoBuscarP" >
                  <input type="text" onkeyup="buscarCliente(event)" id="txtBuscarP" placeholder="Buscar Persona" class="form-control" autocomplete="false">
              </div>
              <div class="col-sm-1"><span id="cargadorPersona" style="display: inline-block;"></span></div>
              <br>
              <br>
              <br>
              <div id="resultadoBusqueda">
                <center><i class="entypo-info"></i><br>Por favor digite el nombre de una persona.</center>
              </div>

            </div>
          <?php }else{
            ?>
              <center><i class="entypo-info"></i><br>No tiene acceso. Pregunta a su administrador del sistema </center>
              
            <?php 
          }?>
			 </div>
				<div class="modal-footer">
					<button type="button" id="closeModalPerson" class="btn btn-default" data-dismiss="modal">Close</button>
					
				</div>
			</div>
		</div>
	</div>
