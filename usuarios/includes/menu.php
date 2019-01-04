                <?php  if(revisarPrivilegios('U-4')){?>
				<li class="">
					<a href="../usuarios">
						<i class="entypo-users"></i>
						<span class="title">Usuarios</span>
					</a>
				</li>
                <?php  
                }
                if(revisarPrivilegios('U-2')){?>
                <li class="">
                    <a href="../usuarios/nuevo.php">
                        <i class="entypo-list-add"></i>
                        <span class="title">Nuevo Usuario</span>
                    </a>
                </li>
                <?php }
                if(revisarPrivilegios('U-5')){
                ?>
        
                <li class="has-sub">
                        <a href="#">
                            <i class="entypo-tools"></i>
                            <span class="title">Configuración</span>
                        </a>
                    <ul class="">
                        <li class="root-level"><a href="../usuarios/privilegios.php"><span class="title">Privilegios</span></a></li>
                    </ul>
                </li>
                <?php }?>
			</ul>
		</div>
	</div>