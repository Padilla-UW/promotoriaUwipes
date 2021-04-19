<?php
session_start();

include('includes/menu.php');
include('includes/header.php');

if($_SESSION["tipoUsuario"]!="Administrador"){
  echo '<script type="text/javascript">alert("Inicie sesión nuevamente.");</script>';
  echo '<script type="text/javascript">onload=window.location="index.php";</script>';
}else{
?>
<!-- Encabezado -->
<div class="container">
  <div class="row">
    <div class="col">
      <h2>DASHBOARD</h2>
    </div>
  </div>
</div>

<!-- Tablas Productos -->
<div class="container">
<br>
  <div class="row">
    <div class="col">
      <table id="tablaPropio" class="table table-info" style="margin-top:5px;">
        <thead>
          <tr>
            <th>PRODUCTOS + BARATOS PROPIOS</th>
          </tr>
        </thead>
        <tbody id="tablaProdPropio">
        </tbody>
      </table>
    </div>
    <div class="col">
    <table id="tablaCompetencia" class="table table-warning" style="margin-top:5px;">
        <thead>
          <tr>
            <th>PRODUCTOS + BARATOS COMPETENCIA</th>
          </tr>
        </thead>
        <tbody id="tablaProdCompetencia">
        </tbody>
      </table>
    </div>
    <div class="col">
    <table id="tablaAccesible" class="table table-success" style="margin-top:5px;">
        <thead>
          <tr>
            <th>PRODUCTOS + ACCESIBLES</th>
          </tr>
        </thead>
        <tbody id="tablaProdAccesible">
        </tbody>
      </table>
    </div>
  </div>
  </div>
<br>

<!-- Gráfica -->
<div class="container">
<div class="row">
<div class="col-4">
<label for="">Paso 1 - Punto de Venta</label>
<select class="form-control" name="grafica" id="selGrafica"></select>
</div>
<div class="col-4">
<label for="">Paso 2 - Fecha de inicio de Semana</label><br>
<input class="form-control" type="date" id="fechaSemana" onchange="getGrafica()">
</div>
<div id="btnLimpiarG" class="col-4">
<label for="">Paso 3 - Borrar para volver a insertar</label>
<button style="margin1; border-radius:5px;" class="form-control btn-light" onclick="limpiarGrafica()">Borrar Contenido en Gráfica</button>
</div>
</div>
<br>
<br>
<div>
  <canvas id="myChart"></canvas>
</div>
</div>

<?php include ('includes/footer.php')?>

<script>
  $(document).ready(function(){
    getPVenta();
    getTopProdPropio();
    getTopProdCompetencia();
    getTopProdAccesible();
    $('#btnLimpiarG').hide();
});

//GRÁFICA
function getGrafica(){
  var selGrafica = $("#selGrafica").val();
  var fechaSemana = $("#fechaSemana").val();
  
  var parametros = {
    "action": "getGrafica",
    "selGrafica": selGrafica,
    "fechaSemana": fechaSemana
  }
  $('#btnLimpiarG').show();
    $.ajax({
        data:parametros,
        url: "dashboardAjax.php",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        method: "GET",
        success: function(data) {
            var precio = [];
            var nombre = [];
            console.log(data);
 
            for (var i in data) {
                precio.push(data[i].precio);
                nombre.push(data[i].nombre);  
            }

            const chartdata = {
                labels: nombre,
                datasets: [{
                    label: 'Precio ',
                    backgroundColor: '#ff6384a8', //color puntos
                    borderColor: 'rgb(255, 99, 132)', //color línea
                    data: precio, //datos Y
                },
                ]
            };

            // configuración
            const config = {
                type: 'bar', //tipo de gráfica
                data: chartdata,
                options: {
                    responsive: true,
                    plugins: { //texto 
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Gráfica Precios Productos'
                        }
                    }
                },
            };

            // lanzamos gráfica
            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        },
        error: function(data) {
            console.log('error');
        }
    });
};

//Select automáticos
function getPVenta(){
  var parametros = {
    "action": "getPVenta"
  }
  $.ajax({
    url: 'dashboardAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#selGrafica").html(data);
    }
  });
}

//Tablas
function getTopProdPropio(){
  var parametros = {
    "action": "getPrecioProdPropio"
  }
  $.ajax({
    data: parametros,
    url: 'dashboardAjax.php',
    success: function (data) {
        data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaProdPropio").html(data.producto);
    }
  });
}

function getTopProdCompetencia(){
  var parametros = {
    "action": "getPrecioProdCompetencia"
  }
  $.ajax({
    data: parametros,
    url: 'dashboardAjax.php',
    success: function (data) {
        data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaProdCompetencia").html(data.producto);
    }
  });
}

function getTopProdAccesible(){
  var parametros = {
    "action": "getPrecioProdAccesible"
  }
  $.ajax({
    data: parametros,
    url: 'dashboardAjax.php',
    success: function (data) {
        data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaProdAccesible").html(data.producto);
    }
  });
}

function limpiarGrafica(){
  location.reload();
}

</script>

<?php
} //cierre llave else SESSION
?>
