<?php 
include("conn/connpg.php");
if($_POST['regname']=="" AND $_POST['regpass']=="" AND $_POST['reregpass']==""){
    echo "
                <html>
                <meta>
                <script type=\"text/javascript\">
                //alert(parent.location.href);
                document.location.href = '".base_url()."login.php';
    </script>
                </head>
                <body></body>
                ";
    exit();
}else{
  if($_POST['user']!="" AND $_POST['nombre']!=""){
    if(!$_POST['g-recaptcha-response']){
       $errorLogin="Por favor verifica el captcha";
    }else{

$sqlCl="SELECT * FROM usuarios WHERE usuario='".$_POST['user']."' OR (nombre='".$_POST['user']."' AND apellido='".$_POST['apellido']."')";
$queryCL=mysql_query($sqlCl, $conn);
if(mysql_num_rows($queryCL)==0){

    $sql = "INSERT INTO usuarios VALUES( 
      null, 
      '".$_POST['nombre']."',
      '".$_POST['apellidos']."',
      '".$_POST['regname']."',
      '".$_POST['user']."',
      '".sha1($_POST['regpass'])."', 
      '3',
      ''
      )";
    $query = mysql_query($sql,$conn)or die(mysql_error());
    $idUser=mysql_insert_id();

    $sql3 = "INSERT INTO tparametros VALUES( 
      null, 
      '".$idUser."',
      '1','','".$_POST['apellidos']." ".$_POST['nombre']."','','0','0','0','0','','','','','noreply@coopeavanzando.com','0','0','','','','','','','','','','','','0'
      )";
    $query3 = mysql_query($sql3,$conn)or die(mysql_error());
    $idCuenta=mysql_insert_id();

    $sql3 = "INSERT INTO tcuentas VALUES( 
      null, 
      '".$idCuenta."',
      '".$idUser."'
      )";
    $query3 = mysql_query($sql3,$conn)or die(mysql_error());
    print "<meta http-equiv=\"refresh\" content=\"0;URL=login.php?etp=etp\">";
    exit(); 

}else{
  $errorLogin="Ya existe registrado una persona con el mismo nombre de usuario o nombre y apellidos.";
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
	<title>Registro - Facturación</title>
<link rel="shortcut icon" href="assets/images/favicon.ico" >
<link rel="stylesheet" href="login/login.css">

<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
   <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
   <link rel="stylesheet" href="assets/css/cssGeneralTOP.css">


<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<script src="assets/js/jquery-1.11.3.min.js"></script>

<script src="login/login.js"></script>


<style type="text/css">
   .esconder{
      display: none;
   }

   .btng{padding: 20px}
   .btn{
      margin-bottom: 2px;
   }
</style>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div class="materialContainer">


 
   <div class="box ">
      

      <div class="title">Registro</div>
      <form action="" method="post" accept-charset="utf-8">
      <input type="hidden" name="regname" value="<?php echo $_POST['regname']?>">
       <input type="hidden" name="regpass" value="<?php echo $_POST['regpass']?>">
       <input type="hidden" name="reregpass" value="<?php echo $_POST['reregpass']?>">

       <div class="input">
         <label for="user">Usuario</label>
         <input type="text" name="user" id="user" value="<?php echo $_POST['user']?>" required>
         <span class="spin"></span>
      </div>

      <div class="input">
         <label for="regname">Nombre</label>
         <input type="text" name="nombre" id="regname" value="<?php echo $_POST['nombre']?>" required>
         <span class="spin"></span>
      </div>
      <div class="input">
         <label for="apellidos">Apellidos</label>
         <input type="text" name="apellidos" id="apellidos" value="<?php echo $_POST['apellidos']?>"  required>
         <span class="spin"></span>
      </div>
      <br>
    <div class="title">  
<div class="g-recaptcha" data-sitekey="6LcUkHkUAAAAAG0trxL-Ypqmt3gjJMSRRKkTJjyY"></div>
   </div>  
<br>
      <div class="button login">
         <button type="submit"><span>Registrar</span></button>
      </div>

</form>
   </div>
 <a href="login.php" onclick='$("#fr2").hide("fast");$("#fr1").show("fast");' class="pass-forgot">Cancelar</a>

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
      <div class="modal-content">
       
        
        <div class="modal-body" id="modal">
         Cargando...       
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div> 
   <script type="text/javascript">
    
<?php 
if ($errorLogin !=""){ ?>
   alert("<?php echo $errorLogin?>");
<?php } 
?>
</script>
</body>
</html>

<?php }?>