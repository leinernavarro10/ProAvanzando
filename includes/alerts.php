<script type="text/javascript">
function miAlerta(msg,tipo = 1){
	switch(tipo){
		case 1:{
			jQuery('#modal-1A').addClass("alert-danger");
		break;}
	}
	jQuery('#btnMensaje').html(msg);
	jQuery('#modal-1A').slideDown();
	setTimeout(function() {cerrarMiAlerta();}, 3000);
}

function cerrarMiAlerta(){
	jQuery('#modal-1A').slideUp();
}

function mensajeExito(msg,tipo){
	switch(tipo){
		case 1:
			jQuery('#modal-1A').addClass("alert-success");
			jQuery('#modal-1A').removeClass('alert-danger');
		break;
		case 2:
			jQuery('#modal-1A').removeClass("alert-success");
			jQuery('#modal-1A').addClass("alert-danger");
		break;
	}
	jQuery('#btnMensaje').html(msg);
	jQuery('#modal-1A').slideDown();
	setTimeout(function() {cerrarMiAlerta();}, 3000);
}

function cerrarMiAlerta(){
	jQuery('#modal-1A').slideUp();
}
</script>

<div id="modal-1A" class="alert" style="display: none"><center><strong>Atención: </strong><span id="btnMensaje"></span></center></div>