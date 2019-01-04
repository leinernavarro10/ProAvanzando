	<div class="main-content">

	<div class="row" style="display: none">
		
			<!-- Profile Info and Notifications -->
			<div class="col-md-6 col-sm-8 clearfix">
		
				<ul class="user-info pull-left pull-none-xsm">
		
					<!-- Profile Info -->
					<li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
		
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php 
							if (file_exists('profileImages/'.$idUsuario.'.jpg')){
								echo '<img src="profileImages/'.$idUsuario.'.jpg?'.rand().'" alt="" class="img-circle" width="44" />';
							}else{
								echo '<img src="profileImages/0.jpg?'.rand().'" alt="" class="img-circle" width="44" />';
							}
							?>
							<?php echo $nombreUsuario?>
						</a>
		
						<ul class="dropdown-menu">
		
							<!-- Reverse Caret -->
							<li class="caret"></li>
		
							<!-- Profile sub-links -->
							<li>
								<a href="perfil.php">
									<i class="entypo-user"></i>
									Editar perfil
								</a>
							</li>

							<li>
								<a href="notas.php">
									<i class="entypo-list-add"></i>
									Notas
								</a>
							</li>
						
							<!--
							<li>
								<a href="calendario.php">
									<i class="entypo-calendar"></i>
									Calendario
								</a>
							</li>
							-->
							<li>
								<a href="recordatorios.php">
									<i class="entypo-clock"></i>
									Recordatorios
								</a>
							</li>

							<li>
								<a href="logout.php">
									<i class="entypo-logout"></i>
									Cerrar sesión
								</a>
							</li>
						</ul>
					</li>
		
				</ul>
		
			</div>
		
		
			<!-- Raw Links -->
			<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
				<ul class="list-inline links-list pull-right">
		
					<li class="sep"></li>
		
					<li>
						<a href="logout.php">
							Cerrar sesión <i class="entypo-logout right"></i>
						</a>
					</li>
				</ul>
		
			</div>

		</div>

		<!--<hr>-->