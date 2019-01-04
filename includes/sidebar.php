
    <header class="navbar navbar-fixed-top">
        <!-- set fixed position by adding class "navbar-fixed-top" --> 
        <div class="navbar-inner"> <!-- logo -->
            <div class="navbar-brand"> 
                <a href="../inicio/"> 
                    <img src="../assets/images/coopeBlanco.png" alt="" width="88">
                </a> 
            </div> 

        <!-- main menu --> 
        <ul class="navbar-nav"> 
            <?php if(revisarPrivilegios('P-0') OR revisarPrivilegios('P-1') OR revisarPrivilegios('P-2')){?>
            <li class="has-sub root-level"> 
                <a href="javascript: void(0)"><i class="entypo-user"></i><span class="title">Personas</span></a>
                <ul> 
                      <?php if(revisarPrivilegios('P-0') OR revisarPrivilegios('P-1')){?>
                    <li> 
                        <a href="javascript: verpersona(2)"><span class="title">Editar Personas</span></a>
                    </li>
                      <?php }
                      if(revisarPrivilegios('P-2')){?>
                    <li> 
                        <a href="javascript: nuevapersona()"><span class="title">Nueva Personas</span></a>
                    </li>
                    <?php }?> 
                       
                </ul>
            </li>
            <?php }?>
            <?php if(revisarPrivilegios('PA-1')){?>
                <li class="has-sub root-level"> 
                    <a href="../parametros/"><i class="entypo-cog"></i><span class="title">Par. del sistema</span></a>
                </li>
            <?php }?>

          </ul> <!-- notifications and other links --> 

          <ul class="nav navbar-right pull-right"> <!-- dropdowns --> 
            <li class="dropdown"> 
                <a href="javascript: notas()" ><i class="entypo-doc-text-inv"></i></a> 
            </li> 
             <li> 
                <a href="javascript: void(0)"> <i class="entypo-calendar"></i> <?php echo fecha_larga();?> </a> 
            </li> 
             <li class="sep"></li> 
             <li> 
                <a href="../logout.php">
                    Log Out <i class="entypo-logout right"></i> 
                </a> 
            </li>
        </ul> 
    </div> 
</header>

    <div class="sidebar-menu ">
        <div class="sidebar-menu-inner">
            <div class="sidebar-user-info">
                <div class="sui-normal">
                    <a href="#" class="user-link">
                        <?php if($foto==""){?>
                        <img src="../assets/images/thumb.png" width="55" alt="" class="img-circle" />
                        <?php }else{?>
                        <img  src="../usuarios/<?php echo $foto;?>" class="img-circle"  style='height:55px;width:55px' alt=""  />
                        <?php }?>
                        <span style="text-transform: capitalize;"><?php echo $usuario;?></span>
                        <strong><?php echo $nombreUsuario;?></strong>
                    </a>
                </div>
                <div class="sui-hover inline-links animate-in"><!-- You can remove "inline-links" class to make links appear vertically, class "animate-in" will make A elements animateable when click on user profile -->
                    <a href="../usuarios/editar.php?id=<?php echo $userid?>">
                        <i class="entypo-pencil"></i>
                        Perfil
                    </a>
                    <a href="../logout.php">
                        <i class="entypo-lock"></i>
                        Cerrar sesión
                    </a>
                    <span class="close-sui-popup">&times;</span>  </div>
            </div>
<ul id="main-menu" class="main-menu">
				<li class="">
					<a href="../inicio">
						<i class="entypo-gauge"></i>
						<span class="title">Dashboard</span>
					</a>
				</li>
            
           
