<?php 

$mi_temporizador = microtime();
$partes_de_la_hora_actual = explode(' ', $mi_temporizador);
$hora_actual = $partes_de_la_hora_actual[1] + $partes_de_la_hora_actual[0];
$hora_al_empezar = $hora_actual;

date_default_timezone_set('America/Costa_Rica');

ini_set('display_errors', 0);

$server = 'localhost'; //remplace por la dirección del servidor
$user = 'root'; //remplace por el usuario de la base de datos
$password = ''; //remplace por la contraseña de la base de datos
$database = 'bd_proava'; //remplace por el nombre de la base de datos



$conn = mysql_connect($server, $user, $password);
mysql_select_db($database, $conn);

function base_url(){
	return "http://192.168.1.235/proavanzando/";	
}

$keycode = "X01M3mYeCRh01xjfmI9riEN8Q3ODyEuc9IjA8upTMicZQsIXssaV8AXA";
//echo $_COOKIE['userPro'];
if ($_COOKIE['zw18k722'] == "" ){
if ((basename($_SERVER['REQUEST_URI']) != "login.php" and basename($_SERVER['REQUEST_URI']) != "ip2.php" and strpos(basename($_SERVER['REQUEST_URI']),"cambiarPass.php") == FALSE) and basename($_SERVER['REQUEST_URI']) != "registro.php"){
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
	}
}else{

  $userid = $_COOKIE['zw18k722'];
  $personaid = $_COOKIE['z24lpcx'];

  function conexion(){
  	$conn = mysql_connect($server, $user, $password);
  	mysql_select_db($database, $conn);	
  	return $conn;
  }
  $nombreProyecto = "proavanzando 0.0.1";

  //echo $_COOKIE['idUserPro']."aqui";


  if ($userid  == "" ){
      if (basename($_SERVER['REQUEST_URI']) != "login.php" and basename($_SERVER['REQUEST_URI']) != "ip2.php"){
          echo "<script type='text/javascript'>
          parent.location.href = 'login.php';
          </script>";
          exit();
      }
  }else{


      $sql = "SELECT nombre,foto FROM tpersonas WHERE id = '".$personaid."' ";
      $query = mysql_query($sql, $conn)or die(mysql_error());
      if (mysql_num_rows($query) == 0){
      echo "<script type='text/javascript'>
      //parent.location.href = 'logout.php';
      </script>";
      exit();
      }else{
          while ($row=mysql_fetch_assoc($query)) {
              $nombreUsuario = $row['nombre'];
              $foto=$row['foto'];
          }
          $sql = "SELECT * FROM tusuarios WHERE idPersona = '".$personaid."' ";
          $query = mysql_query($sql, $conn)or die(mysql_error());
          while ($row=mysql_fetch_assoc($query)) {
              $usuario = $row['usuario'];
              $estado = $row['estado'];
              $idUsuariotipo = $row['idUsuariotipo'];
          }
          
           if($estado!=1){
            echo "
                <html>
                <meta>
                <script type=\"text/javascript\">
                //alert(parent.location.href);
                document.location.href = '".base_url()."logout.php';
    </script>
                </head>
                <body></body>
                ";
            exit();
           }
      }
  }
    
}

function controlAcceso($numero){
  $idUsuariotipo=$GLOBALS['idUsuariotipo'];
  $sql = "SELECT * FROM tprivilegios WHERE idUsuariotipo = '$idUsuariotipo' AND numero = '$numero'";
    $query = mysql_query($sql, $GLOBALS['conn'])or die(mysql_error());
    if (mysql_num_rows($query) == 0){
    echo "<script type='text/javascript'>
    parent.location.href = '../noAuth.php';
    </script>";
    exit();
    }
}

function revisarPrivilegios($numero){
  $idUsuariotipo=$GLOBALS['idUsuariotipo'];
  $sql = "SELECT * FROM tprivilegios WHERE idUsuariotipo = '$idUsuariotipo' AND numero = '$numero'";
    $query = mysql_query($sql, $GLOBALS['conn'])or die(mysql_error());
    if (mysql_num_rows($query) == 0){
      return false;
    }else{
      return true;
    }
}


function zeroFill($len, $num){
   $largo = strlen($num);
   $resta = $len-$largo;
   for ($a = 1; $a<=$resta;$a++){
      $num = '0'.$num;
   }
   return $num;
}


function addZero($num){
   $largo = strlen($num);
   $resta = 5-$largo;
   for ($a = 1; $a<=$resta;$a++){
      $num = '0'.$num;
   }
   return $num;
}

function encode_this($string)
{
$string = utf8_encode($string);
$control = "controlVariable"; //defino la llave para encriptar la cadena, cambiarla por la que deseamos usar
$string = $control.$string.$control; //concateno la llave para encriptar la cadena
$string = base64_encode($string);//codifico la cadena
return($string);
}

function getSubString($string, $length=NULL){
    //Si no se especifica la longitud por defecto es 50
    if ($length == NULL)
        $length = 50;
    //Primero eliminamos las etiquetas html y luego cortamos el string
    $stringDisplay = substr(strip_tags($string), 0, $length);
    //Si el texto es mayor que la longitud se agrega puntos suspensivos
    if (strlen(strip_tags($string)) > $length)
        $stringDisplay .= ' ...';
    return $stringDisplay;
}

function parseFecha($fecha){
  $fecha = explode("-",$fecha);
  return $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
}

function decode_get2($string)
{
$cad = explode("?",$string); //separo la url desde el ?
$string = $cad[1]; //capturo la url desde el separador ? en adelante
$string = base64_decode($string); //decodifico la cadena
$control = "controlVariable"; //defino la llave con la que fue encriptada la cadena,, cambiarla por la que deseamos usar
$string = str_replace($control, "", "$string"); //quito la llave de la cadena

//procedo a dejar cada variable en el $_GET
$cad_get = explode("&",$string); //separo la url por &
foreach($cad_get as $value)
{
$val_get = explode("=",$value); //asigno los valosres al GET
$_GET[$val_get[0]]=utf8_decode($val_get[1]);
}
}


function num2letras($num, $fem = true, $dec = true) { 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'uno'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
}

function fecha_larga(){
$dia = date("D");
$mes = date("M");
$mes_arr_ori = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
$mes_arr_nue = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$dia_arr_ori = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
$dia_arr_nue = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "S&aacute;bado");

$dia = str_replace($dia_arr_ori, $dia_arr_nue, $dia);
$mes = str_replace($mes_arr_ori, $mes_arr_nue, $mes);

$fecha_larga = $dia." ".date("d")." de ".$mes." del ".date("Y");
return $fecha_larga;
}
?>
