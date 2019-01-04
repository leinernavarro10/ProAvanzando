<script type="text/javascript" src="../assets/js/html2canvas.js"></script>
<script type="text/javascript">
function capturar(pre, id){
  html2canvas(document.body, {
    onrendered: function(canvas) {
      //document.getElementById('canvas') = canvas;
      var myImage = canvas.toDataURL();

      jQuery.ajax({
        type: "POST",
        url: "scriptExport.php",
        data: { 
           imgBase64: myImage,
           id : pre+id 
        }
      }).done(function(msg) {
        parent.aumentarGenerados();
        /*jQuery.ajax({
          type: "POST",
          url: "sendEmail.php",
          data: { 
             id: id,
             email: email,
             mensaje: mensaje
          }
        }).done(function(msg) {
          alert(msg);
        });*/
      });
    }
  });  
}
</script>