<script type="text/javascript">
  function confirmBorrar(url, nombre){
    jQuery('#urlBorrar').attr("href",url);
    jQuery('#nombreEliminar').html(nombre);
    jQuery('#modal-4').modal('show', {backdrop: 'static'});
  }
</script>
<div class="modal fade" id="modal-4" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h4 class="modal-title">Ateción por favor.</h4>
      </div>
      
      <div class="modal-body">
      
        ¿Está seguro(a) que desea eliminar a <span id="nombreEliminar" style="font-weight: bold"></span>?
        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <a id="urlBorrar" href=""><button type="button" class="btn btn-danger">Eliminar</button></a>
      </div>
    </div>
  </div>
</div>