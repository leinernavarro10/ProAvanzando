                <?php  if(revisarPrivilegios('C-0')){?>
				<li class="">
					<a href="../contabilidad/asientos.php">
						<i class="glyphicon glyphicon-list-alt"></i>
						<span class="title">Asientos Contables</span>
					</a>
				</li>
                <?php }?>
                 <?php  if(revisarPrivilegios('C-5')){?>
				<li class="">
					<a href="../usuarios">
						<i class="glyphicon glyphicon-briefcase"></i>
						<span class="title">Cuentas por pagar</span>
					</a>
				</li>
                <?php }?>
                <?php  if(revisarPrivilegios('C-6')){?>
				<li class="">
					<a href="../usuarios">
						<i class="glyphicon glyphicon-bitcoin"></i>
						<span class="title">Bancos</span>
					</a>
				</li>
                <?php }?>
                 <?php  if(revisarPrivilegios('C-4')){?>
				<li class="">
					<a href="../usuarios">
						<i class="glyphicon glyphicon-save-file"></i>
						<span class="title">Reportes</span>
					</a>
				</li>
                <?php }?>
                <?php  if(revisarPrivilegios('C-1') OR revisarPrivilegios('C-2') OR revisarPrivilegios('C-3') ){?>
				
                <li class="has-sub">
                        <a href="#">
                            <i class="entypo-tools"></i>
                            <span class="title">Configuración</span>
                        </a>
                    <ul class="">
						<?php  if(revisarPrivilegios('C-1')){?>
                        	<li class="root-level"><a href="../contabilidad/conf-periodos.php"><span class="title">Periodos</span></a></li>
						<?php }?>
						<?php  if(revisarPrivilegios('C-2')){?>
                        	<li class="root-level"><a href="../contabilidad/conf-catalogocuentas.php"><span class="title">Catalogo Cuentas</span></a></li>
						<?php }?>	
						<?php  if(revisarPrivilegios('C-3')){?>
                        	<li class="root-level"><a href="../contabilidad/conf-centrocostos.php"><span class="title">Centro de costo</span></a></li>
						<?php }?>
						<?php  if(revisarPrivilegios('C-7')){?>
                        	<li class="root-level"><a href="../contabilidad/conf-documentos.php"><span class="title">Tipos Documentos</span></a></li>
						<?php }?>
						<?php  if(revisarPrivilegios('C-8')){?>
                        	<li class="root-level"><a href="../contabilidad/conf-monedas.php"><span class="title">Monedas</span></a></li>
						<?php }?>			
                    </ul>
                </li>
                <?php }?>
			</ul>
		</div>
	</div>