<?php include ("../../conn/connpg.php");

		if($_POST['val']==""){
          $sql="SELECT * FROM ccatalogocuentas ORDER BY codigo1,codigo2,codigo3,codigo4 ASC";
        }else{
        	$buscar=explode('-',$_POST['val']);
        	if($buscar[0]!="") {
        		$buscar2=" OR codigo1 LIKE '".$buscar[0]."%' ";
        	}
        	if($buscar[1]!="") {
        		$buscar2.=" AND codigo2 LIKE '".$buscar[1]."%' ";
        	}
        	if($buscar[2]!="") {
        		$buscar2.=" AND codigo3 LIKE '".$buscar[2]."%' ";
        	}
        	if($buscar[3]!="") {
        		$buscar2.=" AND codigo4 LIKE '".$buscar[3]."%' ";
        	}
        	$sql="SELECT * FROM ccatalogocuentas WHERE (descripcion LIKE '".$_POST['val']."%') ".$buscar2." ORDER BY codigo1,codigo2,codigo3,codigo4 ASC";

        }

          $query=mysql_query($sql,$conn);
          if(mysql_num_rows($query)){
            ?><hr>
            <table class="table table-condensed table-bordered table-hover table-striped"> 
                <thead> 
                  <tr > 
                    <th style="text-align: center;">Cuentas</th> 
                    <th>Descripción</th>
                    <th width="125px">Estado</th>
                  </tr> 
                </thead> 
                <tbody> 
              <?php
              while($row=mysql_fetch_assoc($query)){
              $codigo=$row['codigo1']."-".$row['codigo2']."-".$row['codigo3']."-".$row['codigo4'];
              $este="";
                
              $color="color: #000000;";
              if($row['codigo4']!='0000'){
                  $color='color:#0B469E;';
              }else if($row['codigo3']!='0000'){
                $color='color:#770202;';
              }else if($row['codigo2']!='0000'){
                $color='color:#038E03;';
              }else if($row['codigo1']!='0000'){
                $color='color:#000000';}
              
              ?>
                  <tr class="hover" style="<?php echo $color;?><?php echo $este;?>"  >
                    <td>
                      <?php echo $codigo;?>
                    </td> 
                    <td>
                      <?php echo $row['descripcion'];?>
                    </td> 
                    <td style="text-align: center;">
                      <?php 
                      switch ($row['estado']) {
                        case 1:
                          echo "Activo :: ";?>
                            <a href="conf-catalogocuentas.php?id=<?php echo $row['id']?>&e=2&codigo=<?php echo $codigo?>" class="btn btn-xs btn-red" title='Desactivar'><i class="glyphicon glyphicon-remove"></i></a>
                          <?php
                        break;
                        case 2:
                          echo "Desactivado :: ";
                          ?>
                          <a href="conf-catalogocuentas.php?id=<?php echo $row['id']?>&e=1&codigo=<?php echo $codigo?>" class="btn btn-xs btn-success" title='Activar'><i class="glyphicon glyphicon-ok"></i></a>
                        <?php
                        break;
                      }?>
                    </td>
                  </tr>     

              <?php
              
              //codigo1
              }?>
                </tbody> 
              </table>  
            <?php
          }else{
            echo "<center>No se encontraron datos</center>";
          }
        ?>