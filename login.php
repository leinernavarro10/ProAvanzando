<?php 
  
include("conn/connpg.php");
$cambiarPass = 0;
$codigoCambiar = 0;
$passNoIgual = 0;
$errorLogin = 0;
$denegada = 0;
$motivo = "";

if (isset($_POST['entrar']) OR $_POST['txtpin'] !=""){
  
   $error = "";
   if (isset($_POST['user']) and $_POST['user']){
      $user = $_POST['user'];
   }else{
      $error .= "Por favor digite el nombre de usuario<br>";
      $errorLogin = 1;
   }
   if (isset($_POST['pass']) and $_POST['pass']){
      $pass = $_POST['pass'];
   }else{
      $error .= "Por favor digite la contraseña<br>";
      $errorLogin = 1;
   }
 
$sqlpin="SELECT id_usuario FROM tinicio WHERE codigo ='".mysql_real_escape_string($_POST['txtpin'])."' AND ip='".$_POST['idPcPro1']."' ";
$queryPin=mysql_query($sqlpin,$conn);
while($rowpin=mysql_fetch_assoc($queryPin)){
$idpin=$rowpin['id_usuario']; 
}

   
   $sql = "SELECT * FROM tusuarios WHERE ((usuario like '".mysql_real_escape_string($user)."' and pass like '".mysql_real_escape_string(sha1($pass))."') OR id='".$idpin."')  ";
   $query = mysql_query($sql, $conn);
   $result = mysql_num_rows($query);
  
   if ($result > 0) {
      while ($row = mysql_fetch_assoc($query)) {         
            $idPersona = $row['idPersona'];
            $usuario = $row['usuario'];
            $id = $row['id'];
         
         if($row['estado']==1){
                  setcookie("x1242pcx", "".$_POST['idPcPro1']."");
                  setcookie("z24lpcx", "".$idPersona."");
                  setcookie("zw18k722", "".$id."");
          
                    print "<meta http-equiv=\"refresh\" content=\"0;URL=index.php\">";
                    exit();
         }else if($row['estado']==2){
               $error .= "Ha ocurrido un problema.<br>El usuario se encuentra desactivado.";
               $errorLogin = 1;
         }else if($row['estado']==3){
               $error .= "EL usuario se encuentra en proceso de activación, se enviar un correo cuando el mismo sea activado.";
               $errorLogin = 1;
         }
   }

   }else{
      if ($error == ""){
         $error .= "El usuario y la contraseña no coinciden o bien el nombre/código de usuario que digitó no esta registrado.";
         $errorLogin = 1;
      }
   }
}

?>
<!DOCTYPE html>

 <html lang="en"> 
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta name="description" content="Facturación" />
   <meta name="author" content="Coopeavanzando Juntos R.L" />
	<title>Login | ProAvanzando 0.0.1</title>
<link rel="shortcut icon" href="assets/images/favicon.ico" >
<link rel="stylesheet" href="login/login.css">

<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
   <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
   <link rel="stylesheet" href="assets/css/cssGeneralTOP.css">


<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<script src="assets/js/jquery-1.11.3.min.js"></script>

<script src="login/login.js"></script>


<script type="text/javascript">
      //obtiene la direccion IP:
    function getIPs(){
       if(localStorage.getItem('idPcPro')==null){
            var id=Math.floor((Math.random()*9999999)+1);

            localStorage.setItem('idPcPro',id);
       }
         var ip_addr=localStorage.getItem('idPcPro');
         
         $(".idPcPro1").val(ip_addr);
     $.post('inicio/ajax/ip2.php', {rip: ip_addr}, function(data) {
      
                  if(data>=1){

                     $("#fr1").hide('fast');
                     $("#fr2").show('slow');
                  }
           
                });
    }
    

   
               var vlpin="";
               var numpin=0;
               function pin(val){
                  numpin++;
                  
                  vlpin+=val;
                  $("#txtpin").val(vlpin);
                  $("#hipin").val(vlpin);
                  if(numpin==4){
                        enviar(vlpin);
                  }
               }

               function enviar(){
                  $("#txtpin").val(vlpin);
                  $("#hipin").val(vlpin);
                  $("#form_login2").submit();
                  
               }
               function borrar(){
                  vlpin="";
                  numpin=0;
                  $("#txtpin").val("");   
               }

</script>
<style type="text/css">
   .esconder{
      display: none;
   }

   .btng{padding: 20px}
   .btn{
      margin-bottom: 2px;
   }
</style>

</head>
<body>
<div class="materialContainer">


   <div class="box">
      <div id="fr1">
      <div class="title">LOGIN</div>
<form action="" method="post" role="form" id="form_login">
   <input type="hidden" name="idPcPro1" class="idPcPro1">
      <div class="input">
         <label for="name">Usuario</label>
         <input type="text" name="user" id="name">
         <span class="spin"></span>
      </div>

      <div class="input">
         <label for="pass">Contraseña</label>
         <input type="password" name="pass" id="pass">
         <span class="spin"></span>
      </div>

      <div class="button login">
         <button type="submit" name="entrar"><span>Entrar</span> <i class="fa fa-check"></i></button>
      </div>
</form>
      
      </div>
      

      <div class="login-form esconder" id="fr2">
      
      <div class="login-content">
         
         <div class="esconder">
            <h3>Error</h3>
            <p>El <strong>usuario</strong>/<strong>contraseña</strong> no son correctos.</p>
         </div>
         
         <form action="" method="post" id="form_login2">
            <input type="hidden" name="idPcPro1" class="idPcPro1">
            <div class="title">LOGIN</div>
            <center>
      <input type="password" name="txtpin" id="txtpin" class="form-control" placeholder="Pin" disabled />
      <input type="hidden" name="txtpin" id="hipin">
      <br>
         <table cellpadding="3" cellspacing="3">
            <tr>
               <td>
         <input type="button" name="7" onclick="pin(this.value)" value="7" class="btn btng btn-green">
         <input type="button" name="8" onclick="pin(this.value)" value="8" class="btn btng btn-green">
         <input type="button" name="9" onclick="pin(this.value)" value="9" class="btn btng btn-green">
               </td>
            </tr>
            <tr>
               <td>
         <input type="button" name="4" onclick="pin(this.value)"  value="4" class="btn btng btn-green">
         <input type="button" name="5" onclick="pin(this.value)" value="5" class="btn btng btn-green">
         <input type="button" name="6" onclick="pin(this.value)" value="6" class="btn btng btn-green">
               </td>
            </tr>
            <tr>
               <td>
         <input type="button" name="1" onclick="pin(this.value)" value="1" class="btn btng btn-green">
         <input type="button" name="2" onclick="pin(this.value)" value="2" class="btn btng btn-green">
         <input type="button" name="3" onclick="pin(this.value)" value="3" class="btn btng btn-green"> 
               </td>
            </tr>
            <tr>
               <td>
         <input type="button" name="0" onclick="pin(this.value)" value="0" class="btn btng btn-green">
         <input type="button" name="" onclick="borrar();" value="Borrar" class="btn btng btn-green">
               </td>
            </tr>
         </table>
      <br><br>
        
               <a href="javascript:void()" onclick='$("#fr2").hide("fast");$("#fr1").show("fast");' class="pass-forgot">Usuario y Password</a>
         
            <br><br>
            </center>        
         </form>       
      </div>
   </div>



   </div>



</div>

   <!-- Bottom scripts (common) -->
   <script src="assets/js/gsap/TweenMax.min.js"></script>
   <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
   <script src="assets/js/bootstrap.js"></script>
   <script src="assets/js/joinable.js"></script>
   <script src="assets/js/resizeable.js"></script>
   <script src="assets/js/neon-api.js"></script>
   <script src="assets/js/jquery.validate.min.js"></script>
   <!--<script src="../assets/js/neon-login.js"></script>-->


   <!-- JavaScripts initializations and stuff -->
   <script src="assets/js/neon-custom.js"></script>


   <!-- Demo Settings -->
   <script src="assets/js/neon-demo.js"></script>

<!-- Modal 2 (Custom Width)-->
  <div class="modal fade custom-width" id="modal-2">
    <div class="modal-dialog" style="width: 60%;">
            
        <div class="modal-body" id="modal">
         Cargando...       
        </div>
   
    </div>
  </div> 

<?php if ($passNoIgual == 1){ ?>
<script type="text/javascript">
   alert('No se pudo realizar el cambio de contraseña por que no eran iguales.\nPor favor intentelo de nuevo.');
</script>
<?php }
if ($muyCorta == 1){ ?>
<script type="text/javascript">
   alert('No se pudo realizar el cambio de contraseña por que era demasiado pequeña.\nPor favor cree una contraseña de 6 caracteres o más.');
</script>
<?php }
 ?>



<script type="text/javascript">
function ckrepet(reregpass){
   if(reregpass!=$("#regpass").val()){
         $('#modal-2').modal('show');
         $('#modal').html("<div class='alert alert-danger'><strong>Ha ocurrido un problema.</strong> Las contraseñas no son iguales.</div>");
         $("#reregpass").val("");
   }
}

$(document).ready(function() {
  var longitud=true;
  $('#regpass').keyup(function() {
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
      $("#regpass").val("");
    }
  
  });


});

function ckcorreo(email){

    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

    if (caract.test(email) == false){
         $('#modal-2').modal('show');
         $('#modal').html('<div class="alert alert-danger"><strong>Ha ocurrido un problema.</strong> El corre <strong>'+email+'</strong> no cuenta con los requisitos necesarios para realizar el registro.<div>');
         $("#regname").val("");
    }
}


getIPs();
$(document).ready(function(){

   <?php if ($cambiarPass == 1){ ?>
      $('#modal-2').modal('show');
      $('#modal').load('cambiarPass.php?cd=<?php echo $codigoCambiar?>');
   <?php } ?>

   <?php 
if ($errorLogin == 1){ ?>
   $('#modal-2').modal('show');
   $('#modal').html("<div class='alert alert-danger'><strong>Ha ocurrido un problema.</strong> <?php echo $error?></div>");
<?php } 
if ($denegada == 1){ ?>
   $('#modal-2').modal('show');
   $('#modal').load('afiliacionDenegada.php?motivo=<?php echo $motivo?>');
<?php } ?>

});

</script>


</body>
</html>