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
                        <i class="entypo-basket"></i>
                        <span class="title">Facturación</span>
                    </a>
                </li>
                <?php }
                if(revisarPrivilegios('U-5')){
                ?>
                <li class="">
                    <a href="../contabilidad">
                        <i class="entypo-chart-bar"></i>
                        <span class="title">Contabilidad</span>
                    </a>
                </li>
                <?php }
                if(revisarPrivilegios('U-5')){
                ?>
                <li class="">
                    <a href="../usuarios/nuevo.php">
                        <i class="entypo-suitcase"></i>
                        <span class="title">Proveeduría</span>
                    </a>
                </li>
                <?php }
                if(revisarPrivilegios('U-5')){
                ?>
                <li class="">
                    <a href="../usuarios/nuevo.php">
                        <i class="entypo-traffic-cone"></i>
                        <span class="title">Producción</span>
                    </a>
                </li>
                <?php }
                if(revisarPrivilegios('U-5')){
                ?>
                <li class="">
                    <a href="../usuarios/nuevo.php">
                        <i class="entypo-trophy"></i>
                        <span class="title">Rec. Humanos</span>
                    </a>
                </li>
                <?php }?>
			</ul>
		</div>
	</div>