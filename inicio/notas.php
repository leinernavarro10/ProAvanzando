<?php include("../conn/connpg.php");?>
<div class="row" >
 <div class="notes-env">
    
      <div class="notes-header">
        <h2 style="color: #FFFFFF">Notes</h2>
        
        <div class="right">
          <a class="btn btn-success btn-icon icon-left esconder" id="guardar-note">
            <i class="entypo-floppy"></i>
            Guardar Nota
          </a>

          <a class="btn btn-orange btn-icon icon-left" id="add-note">
            <i class="entypo-pencil"></i>
            Nueva Nota
          </a>
        </div>
      </div>
      
      
      <div class="notes-list">
      
        <ul class="list-of-notes">
        
          <!-- predefined notes -->
          <?php 
            $sql="SELECT * FROM tnotas WHERE estado='1' AND id_usuario ='".$userid."' ORDER BY id DESC ";
            $query=mysql_query($sql,$conn)or die(mysql_error());
            while($row=mysql_fetch_assoc($query)){
          ?>
          <li class="current"> <!-- class "current" will set as current note on Write Pad -->
            <a href="#">
              <strong><?php echo $row['titulo']?></strong>
              <span><?php echo $row['descripcion']?></span>
              <i style="display:none"><?php echo $row['id']?></i>
            </a>
            
            <button class="note-close">&times;</button>
            
            <div class="content"><?php echo $row['contenido']?></div>
          </li>
          <?php }?>
         
          
       
          
          
          
          <!-- this will be automatically hidden when there are notes in the list -->
          <li class="no-notes">
            Todavía no hay notas!
          </li>
        </ul>
        
        
        <div class="write-pad">
          <textarea class="form-control autogrow" disabled></textarea>
        </div>
        
      </div>
    </div>
</div>


 <script src="../assets/js/neon-notes.js"></script>
 