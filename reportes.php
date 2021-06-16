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
    </div>
  </div>
</div>

<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-8">
<div style="display: flex; align-items: center; justify-content: center;">
    </div>
    <div class="row">
    <div class="col">
    <label for=""><b>Zona</b></label><br>
    <select name="" id="selZona" class="form-control"></select> 
    <br></div>
    <div class="col">
    <label for=""><b>Punto de Venta</b></label><br>
    <select name="" id="selPunVenta" class="form-control"></select>
    <br></div>
    </div>
    <div class="row">
    <div class="col">
    <label for=""><b>Vendedor</b></label><br>
    <select name="" id="selVendedor" class="form-control"></select> 
    <br></div>
    <div class="col">
    <label for=""><b>Sucursal</b></label><br>
    <select name="" id="selSucursal" class="form-control"></select>
    <br></div>
    </div>
    <div class="row">
    <div class="col">
    <label for=""><b>Segmento</b></label><br>
    <select name="" id="selSegmento" class="form-control">
    <option selected="true" disabled="disabled">Seleccione</option>
    <option value="Alto">Alto</option>
    <option value="Medio">Medio</option>
    <option value="Bajo">Bajo</option>
    </select>
    <br></div>
    <div class="col">
    <label for=""><b>Procedencia</b></label><br>
    <select name="" id="selProcedencia" class="form-control">
    <option selected="true" disabled="disabled">Seleccione</option>
    <option value="Propio">Propio</option>
    <option value="Competencia">Competencia</option>
    </select>
    </div>
    <div class="col">
    <label for=""><b>Categoría</b></label><br>
    <select name="" id="selCategoria" class="form-control"></select>
    <br></div>
    </div>
    <div class="row">
    <div class="col">
    <label for=""><b>Fecha</b></label><br>
    </div>
    </div>
    <div class="row">
    <div class="col">
    <p>Desde:</p><input type="date" id="selFechaIni" class="form-control"></input>
    <br></div>
    <div class="col">
    <p>Hasta:</p><input type="date" id="selFechaFin" class="form-control"></input> 
    <br></div>
    </div>
    <button class="btn btn-secondary" data-id="" id="btnDescarga"><i class="fas fa-download"></i> Descargar</button>
    <br>
    <!-- Descarga en pantalla -->
    <div id="descargaBoton" style="display:none;"></div> 
   </div>
</div>
</div>
<?php include ('includes/footer.php')?>

<script>
    getVendedor();
    getZona();
    getPunVenta();
    getCategoria();

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

function getCategoria(){
  var parametros = {
    "action": "getCategoria"
  }
  $.ajax({
    url: 'reportesAjax.php',
    data: parametros,
    success: function (data){
      console.log(data);
      $("#selCategoria").html(data);
    }
  });
}

$(document).ready(function(){
    $('#selPunVenta').change(function(){
      var pv = $("#selPunVenta").val();
      getSucursal(pv);
    });
})

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

function getSucursal(pv){
  var parametros = {
    "action": "getSucursal",
    "pv":pv
  }
  $.ajax({
    url: 'reportesAjax.php',
    data: parametros,
    success: function (data){
      console.log(data);
      $("#selSucursal").html(data);
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
    var selCategoria = $("#selCategoria").val();
    var selSegmento = $('#selSegmento').val();
    var selProcedencia = $("#selProcedencia").val();
    var selSucursal = $("#selSucursal").val();

  var parametros = {
            "action": "imprimirReporte",
            "idVendedor": selVendedor,
            "idZona" : selZona,
            "idPuntoVenta" : selPunVenta,
            "idInicio": inicio,
            "idFin": fin,
            "idCategoria": selCategoria,
            "segmento": selSegmento,
            "idProcedencia": selProcedencia,
            "idSucursal": selSucursal
        }
        $.ajax({
            data: parametros,
            url: "reportesAjax.php",
            success: function(data) {
                $('#selVendedor').val("");
                $('#selZona').val("");
                $('#selPunVenta').val("");
                $('#selSucursal').val("");
                $('#selFechaIni').val("");
                $('#selFechaFin').val("");
                $('#selCategoria').val("");
                $('#selSegmento').val("Seleccione");
                $('#selProcedencia').val("Seleccione");
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
