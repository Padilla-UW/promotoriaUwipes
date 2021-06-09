<?php
session_start();
include('includes/header.php');
include('includes/menu.php');

if($_SESSION["tipoUsuario"]!="Vendedor"){
  echo '<script type="text/javascript">alert("Inicie sesión nuevamente.");</script>';
  echo '<script type="text/javascript">onload=window.location="index.php";</script>';
}else{
?>

<style>
#btnVisita{
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 6px;
}

#btnVisita:hover{
  opacity: 0.8;
}
</style>

<!-- selects visita principal -->
<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-6">
<div style="display: flex; align-items: center; justify-content: center;">
    </div>
    <br>
    <label for=""><b>Punto de Venta</b></label><br>
    <select name="" id="selPuntoV" class="form-control"></select> 
    <br>
    <label for=""><b>Sucursal</b></label><br>
    <select name="" id="selSucursal" class="form-control"></select>
    <br>
    <div id="mns"></div>
    <br>
    <button class="btn btn-secondary" data-id="" id="btnVisita"><i class="fas fa-user-check"></i> Visita</button>
   </div>
</div>
</div>
<!--Fin selects visita principal -->
<?php include ('includes/footer.php')?>

<script>
$(document).ready(function(){
  getPVenta(); 
});

//Select automáticos
$(document).ready(function(){
    $('#selPuntoV').change(function(){
      var pv = $("#selPuntoV").val();
      getSucursal(pv);
    });
  })

function getPVenta() {
  var parametros = {
    "action": "getPVenta"
  }
  $.ajax({
    url: 'visitasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#selPuntoV").html(data);
    }
  });
}

function getSucursal(pv) {
  var parametros = {
    "action": "getSucursal",
    "pv": pv
  }
  $.ajax({
    url: 'visitasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#selSucursal").html(data);
    }
  });
}

//Botón visita valida o redirecciona
$("#btnVisita").click(function(){
  var selSucursal = $('#selSucursal').val();
  var selPuntoV = $('#selPuntoV').val();
 
  if(selSucursal != "" && selPuntoV != "") {
    window.location="ficheroSeleccion.php";
    var parametros = {
        "action": "entrarVisita",
        "selPuntoV": selPuntoV,
        "selSucursal": selSucursal
      };
}else{
  $("#mns").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> Completar Campos Vacíos");
  }$.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
          console.log(data);
        }
      });
    });

</script>

<?php
} //cierre llave else SESSION
?>
