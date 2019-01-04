		
				<!-- Modal 2 (Custom Width)-->
  


		<!-- lets do some work here... -->
		<!-- Footer -->
		<footer class="main">
			
			&copy; 2018 <strong>Coopeavanzando Juntos R.L</strong>
			</a>
		
<?php 
$mi_temporizador = microtime();
$partes_de_la_hora_actual = explode(' ', $mi_temporizador);
$hora_actual = $partes_de_la_hora_actual[1] + $partes_de_la_hora_actual[0];
$hora_al_terminar = $hora_actual;
$tiempo_total_en_segundos = round(($hora_al_terminar - $hora_al_empezar), 4);
echo 'La página fue generada en '.$tiempo_total_en_segundos.' segundos.';
?>

		</footer>
	</div>






</div>




<script src="../includes/script.js"></script>

	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="../assets/css/cssGeneralBOTTOM.css">

	<link rel="stylesheet" href="../assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="../assets/js/daterangepicker/daterangepicker-bs3.css">
	<link rel="stylesheet" href="../assets/js/icheck/skins/minimal/_all.css">
	<link rel="stylesheet" href="../assets/js/icheck/skins/square/_all.css">
	<link rel="stylesheet" href="../assets/js/icheck/skins/flat/_all.css">
	<link rel="stylesheet" href="../assets/js/icheck/skins/futurico/futurico.css">
	<link rel="stylesheet" href="../assets/js/icheck/skins/polaris/polaris.css">
	<link rel="stylesheet" href="../assets/css/custom.css">

		<!-- Bottom scripts (common) -->

<script src="../assets/js/toastr.js"></script>

	<script src="../assets/js/gsap/TweenMax.min.js"></script>
	<script src="../assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="../assets/js/bootstrap.js"></script>
	<script src="../assets/js/joinable.js"></script>
	<script src="../assets/js/resizeable.js"></script>
	<script src="../assets/js/neon-api.js"></script>

	<!-- Imported scripts on this page -->
	
	<script src="../assets/js/moment.min.js"></script>
	<script src="../assets/js/daterangepicker/daterangepicker.min.js"></script>

	<script src="../assets/js/bootstrap-datepicker.js"></script>


 <script src="../assets/js/neon-chat.js"></script>
	<!-- JavaScripts initializations and stuff -->
	<script src="../assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="../assets/js/neon-demo.js"></script>
	

<style type="text/css">
	body {
		font-size: 12px;
	}	

	input {
		font-size: 12px;

	}	
	.form-control{
		border: 1px solid #565F63;
		margin: 1px;
	}
	
</style>

</body>
</html>


<?php mysql_close($conn);
?>

<script type="text/javascript">
      //obtiene la direccion IP:
    function getIPs(callback){
    	
            $.post('../inicio/ajax/ip.php', {rip: '<?php echo $_COOKIE['x1242pcx']?>'}, function(data) {
                  if(data==0){
                    $('#modal_conf').modal('show');
                    $('#modalcf').load('../inicio/guardarip.php?ip1=<?php echo $_COOKIE['x1242pcx']?>');
                  }
                });
            }
	getIPs();

				var opts = {
						"closeButton": true,
						"debug": false,
						"positionClass": "toast-bottom-right",
						"onclick": null,
						"showDuration": "300",
						"hideDuration": "1000",
						"timeOut": "5000",
						"extendedTimeOut": "1000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					};


</script>