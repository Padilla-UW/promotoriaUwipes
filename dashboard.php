<?php
session_start();

include('includes/menu.php');
include('includes/header.php');
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
<div class="col-4">
<select class="form-control" name="grafica" id="selGrafica">
</select></div>
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
    getGrafica();
});

//GRÁFICA
function getGrafica(){
  var selGrafica = $("#selGrafica").val();
  var parametros = {
    "action": "getGrafica",
    "selGrafica": selGrafica
  }
    $.ajax({
        data:parametros,
        url: "dashboardAjax.php",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        method: "GET",
        success: function(data) {
            var fecha = [];
            var precio = [];
            var nombre = [];
            console.log(data);
 
            for (var i in data) {
                fecha.push(data[i].fecha);
                precio.push(data[i].precio);
                nombre.push(data[i].nombre);  
            }

            const chartdata = {
                labels: fecha,
                datasets: [{
                    label: 'Precio ' + (nombre),
                    backgroundColor: '#ff6384a8', //color puntos
                    borderColor: 'rgb(255, 99, 132)', //color línea
                    data: precio, //datos Y
                },
                ]
            };

            // configuración
            const config = {
                type: 'line', //tipo de gráfica
                data: chartdata,
                options: {
                    responsive: true,
                    plugins: { //texto 
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Gráfica Precios'
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
</script>