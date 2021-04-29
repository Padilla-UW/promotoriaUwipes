<?php
session_start();
include('includes/header.php');
include('includes/menu.php');

if($_SESSION["tipoUsuario"]!="Administrador"){
  echo '<script type="text/javascript">alert("Inicie sesión nuevamente.");</script>';
  echo '<script type="text/javascript">onload=window.location="index.php";</script>';
}else{
?>

<style>
#btnDescarga{
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 6px;
}

#btnDescarga:hover{
  opacity: 0.8;
}
</style>

<!-- Encabezado -->
<div class="container">
  <div class="row">
    <div class="col">
      <h2>Reportes</h2>
      <p>Reporte en Excel con los siguientes datos: Fecha, Zona, Punto de Venta y Vendedor.</p>
    </div>
  </div>
</div>

<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-6">
<div style="display: flex; align-items: center; justify-content: center;">
    </div>
    <label for=""><b>Vendedor</b></label><br>
    <select name="" id="selVendedor" class="form-control"></select> 
    <br>
    <label for=""><b>Zona</b></label><br>
    <select name="" id="selZona" class="form-control"></select> 
    <br>
    <label for=""><b>Punto de Venta</b></label><br>
    <select name="" id="selPunVenta" class="form-control"></select> 
    <br>
    <label for=""><b>Fecha</b></label>
    <br>
    <p>Desde:</p><input type="date" id="selFechaIni" class="form-control"></input>
    <br>
    <p>Hasta:</p><input type="date" id="selFechaFin" class="form-control"></input> 
    <br>
    <button class="btn btn-secondary" data-id="" id="btnDescarga"><i class="fas fa-download"></i> Descargar</button>
    <br>
    <!-- Descarga en pantalla -->
    <div id="descargaBoton" style="display:none;"></div> 
   </div>
</div>
</div>
<?php include ('includes/footer.php')?>

<script>
$(document).ready(function(){
    getVendedor();
    getZona();
    getPunVenta();
});

//selects automáticos
function getVendedor(){
  var parametros = {
    "action": "getVendedor"
  }
  $.ajax({
    url: 'reportesAjax.php',
    data: parametros,
    success: function (data){
      console.log(data);
      $("#selVendedor").html(data);
    }
  });
}

function getZona(){
  var parametros = {
    "action": "getZona"
  }
  $.ajax({
    url: 'reportesAjax.php',
    data: parametros,
    success: function (data){
      console.log(data);
      $("#selZona").html(data);
    }
  });
}

function getPunVenta(){
  var parametros = {
    "action": "getPunVenta"
  }
  $.ajax({
    url: 'reportesAjax.php',
    data: parametros,
    success: function (data){
      console.log(data);
      $("#selPunVenta").html(data);
    }
  });
}

//cacha datos de variables para botón descarga 
$("#btnDescarga").click(function() {
    var selVendedor = $("#selVendedor").val();
    var selZona = $("#selZona").val();
    var selPunVenta = $("#selPunVenta").val();
    var inicio = $("#selFechaIni").val();
    var fin = $("#selFechaFin").val();
  var parametros = {
            "action": "imprimirReporte",
            "idVendedor": selVendedor,
            "idZona" : selZona,
            "idPuntoVenta" : selPunVenta,
            "idInicio": inicio,
            "idFin": fin
        }
        $.ajax({
            data: parametros,
            url: "reportesAjax.php",
            success: function(data) {
                $('#selVendedor').val("");
                $('#selZona').val("");
                $('#selPunVenta').val("");
                $('#selFechaIni').val("");
                $('#selFechaFin').val("");
                console.log(data);
                // Descarga en pantalla
                $("#descargaBoton").html(data);
                desc();
        }

    });
});

//Descargar archivo en pantalla
function desc() {
        var e = jQuery.Event("click");
        $("#buttonDescargar").trigger(e);
    }
</script>

<?php
} //cierre llave else SESSION
?>
