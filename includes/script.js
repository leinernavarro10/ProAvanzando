$(document).ready(function() {
  var longitud=true;
  $('#password').keyup(function() {
    var longitud=true;
    var pswd = $(this).val();
    if (pswd.length < 8) {
      $('#length').removeClass('valid').addClass('invalid');
      longitud = false;
    } else {
      $('#length').removeClass('invalid').addClass('valid');
  
    }

    //validate letter
    if (pswd.match(/[A-z]/)) {
      $('#letter').removeClass('invalid').addClass('valid');
    
    } else {
      $('#letter').removeClass('valid').addClass('invalid');
      longitud = false;
    }

    //validate capital letter
    if (pswd.match(/[A-Z]/)) {
      $('#capital').removeClass('invalid').addClass('valid');
   
    } else {
      $('#capital').removeClass('valid').addClass('invalid');
      longitud = false;
    }

    //validate number
    if (pswd.match(/\d/)) {
      $('#number').removeClass('invalid').addClass('valid');
     
    } else {
      $('#number').removeClass('valid').addClass('invalid');
      longitud = false;
    }

  }).focus(function() {
    $('#pswd_info').show();
  }).blur(function() {
    $('#pswd_info').hide();
   
    var divs = $(".invalid").toArray().length;
    if(divs>0){
      $("#password").val("");
      
    }
  
  });


});

$(document).ready(function() {
  var longitud=true;
  $('#password2').blur(function() {
      var pass=$('#password').val();
      var pass2 = $(this).val();
      if(pass!=pass2){
        $(this).val("");
         $("#ErrorRec").html("<font color='#760000'>Error. Las contraseñas no son correctas</font>");
      }else{
      $("#ErrorRec").html("");
    }
  }).keyup(function() {
       $("#ErrorRec").html("");
  });

});


var numPER=0;
function buscarCliente(event){
    var tecla =event.which || event.keyCode;
    
    if (event.ctrlKey && event.keyCode === 32) {
      $('#btnFinalizar').click();
     
    }else if(tecla==40){
      //abajo
      if(numPER<$("#cantRG2").val()){
        numPER++;

        $("#trCL"+(numPER-1)).removeClass('tdbselect2');
        $("#trCL"+numPER).addClass('tdbselect2');
      }
    }else if(tecla==38){
      //arriba
      
      if(numPER>1){
        numPER--;
        $("#trCL"+(numPER+1)).removeClass('tdbselect2');
        $("#trCL"+numPER).addClass('tdbselect2');
      }
    }else if(tecla==13){
      $(".tdbselect2").click();
    }else{
    buscarCliente2();
  }
}

function buscarCliente2(){
  numPER=0;

      var tipoBuscarP = $("#tipoBuscarP").val();
       var txtBuscarP = $("#txtBuscarP").val();

       if(txtBuscarP!=""){

        $("#cargadorPersona").html("<center><img src='../assets/images/cargando.gif'  width='20px'></center>");
        $.post( "../personas/verpersonas.php", {txtBuscarP: txtBuscarP,tipoBuscarP:tipoBuscarP})
        .done(function( data ) {
           $("#cargadorPersona").html("");
           jQuery('#resultadoBusqueda').html(data);
        });
      }else{
        jQuery('#resultadoBusqueda').html("<center><i class='entypo-info'></i><br>Por favor digite el nombre de una persona.</center>");
      }
}

function procesarPersona(id,nombre,tipoBuscarP){
  
    if(tipoBuscarP==1){
      //Agregar en nuevo usuario
      $('#modalPersonas').modal('toggle');
      agregarNuevoUsuario(id,nombre);
    }

    if(tipoBuscarP==2){
      //Agregar en nuevo usuario
        editarUsuario(id,nombre);
    }

     if(tipoBuscarP==3){
      //Agregar en nuevo usuario
      $('#closeModalPerson').click();
        cargarCliente(id,nombre);
    }

    if(tipoBuscarP==4){
      //Agregar en nuevo usuario
      $('#closeModalPerson').click();
        cargarCliente2(id,nombre);
    }
}

function verpersona(val){
    $("#resultadoBusqueda").html("<center><i class='entypo-info'></i><br>Por favor digite el nombre de una persona.</center>");
    $('#modalPersonas').modal();
    $('#modalPersonas').on('shown.bs.modal', function () {
        $(this).find('#txtBuscarP').removeAttr('disabled').val("").focus();
        $(this).find('#tipoBuscarP').val(val);
    });
}

function editarUsuario(id,nombre){
    $("#cargadorPersona").html("<center><img src='../assets/images/cargando.gif' width='20px'></center>");
     $.post( "../personas/editarPersona.php", {id:id})
      .done(function( data ) {
        $("#cargadorPersona").html("");
        jQuery('#resultadoBusqueda').html(data);
      });
}

function nuevapersona(){
    $('#modalNuevaPersonas').modal();
    
       $.post("../personas/nuevaPersona.php").done(function(data) {
           $('#resultadoNuevaPersona').html(data);
        });
    
}

function notas(){
  $('#modal-2').modal();
  $("#modal").load("../inicio/notas.php");
}