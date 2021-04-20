<?php
session_start();
include('includes/menu.php');
include('includes/header.php');

if($_SESSION["tipoUsuario"]!="Administrador"){
  echo '<script type="text/javascript">alert("Inicie sesión nuevamente.");</script>';
  echo '<script type="text/javascript">onload=window.location="index.php";</script>';
}else{
?>

<style>
.page-item.active .page-link {
    z-index: 3;
    color: white;
    background-color: rgba(34,34,34,0.75);
    border-color: black;
}

.page-item .page-link {
    z-index: 2;
    color: black;
    background-color: white;
}
</style>

<!-- Encabezado -->
<div class="container">
  <div class="row">
    <div class="col">
      <h2>Puntos de Venta</h2>
    </div>
  </div>
</div>

<!-- Botón Lanza Modal -->
<div class="container">
  <div class="row">
    <div class="col">
      <button type="button" class="btn btn-light" style="margin-top:5px;" data-toggle="modal"
        data-target="#modalNvoPuntoV" id="btnAgregar">
        Agregar <i class="fas fa-plus"></i></button><br>
    </div>
  </div>
</div>
<!--Fin Lanza Modal -->

<!-- Filtrados Búsqueda -->
<div class="container" style="margin-top:10px">
  <div class="row justify-content-between">
    <div class="col-6 col-lg-7" id="filtros">
      <div class="btn-group" role="group">
        <button id="filtroTPuntoV" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Tipo
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <a class="dropdown-item opcFilPuntoV" data-puntoV="Mayoreo" href="#">Mayoreo</a>
          <a class="dropdown-item opcFilPuntoV" data-puntoV="Moderno" href="#">Moderno</a>
          <a class="dropdown-item opcFilPuntoV" data-puntoV="Conveniencia" href="#">Conveniencia</a>
        </div>
      </div>
      <div class="btn-group" role="group">
        <button id="filtroPZona" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Zona
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroZonaPV">
        </div>
      </div>
      <div class="btn-group" role="group">
        <button id="filtroPVendedor" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Vendedor
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroVendedorPV">
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-4">
      <input type="text" onkeyup="load()" class="form-control" id="busquedaNombre" placeholder="Nombre">
    </div>
  </div>
</div>
<br>
<!--Fin Filtrados Búsqueda -->

<!-- Tabla puntos venta con campos principales-->
<div class="container">
  <div class="row">
    <div class="col">
      <table id="myTable" class="table" style="margin-top:10px;">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Zona</th>
            <th>Vendedor</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody id="tablaPuntosV">
        </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div style="display: flex; align-items: center; justify-content: center;">
        <br><br>
        <nav aria-label="Page navigation example" id="pagUsuario"></nav>
      </div>
    </div>
  </div>
</div>
<!-- FIN Tabla Puntos Venta -->

<!-- Modal nuevo punto venta-->
<div class="modal fade" id="modalNvoPuntoV" tabindex="-1" aria-labelledby="modalNvoPuntoVLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNvoPuntoVLabel" style="color:#607d8b">Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarAdd">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
          <label for="nombre"><b>DATOS</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreAdd" type="text" placeholder="Nombre" name="nombre" required>
          <br>
            <label for="tipo"><b>Tipo</b></label>
            <select class="form-control" id="tipoAdd">
              <option value="0">Seleccione</option>
              <option value="Mayoreo">Mayoreo</option>
              <option value="Moderno">Moderno</option>
              <option value="Conveniencia">Conveniencia</option>
            </select>
          <br>
          <label for="zona"><b>Zona</b></label><br>
          <select class="form-control" name="idZona" id="zonaAdd" required></select>
          <br>
          <label for="vendedor"><b>Vendedor</b></label><br>
          <select class="form-control" name="idVendedor" id="vendedorAdd" required></select>
          <br>
          <div id="avisoAgregar"> </div>
          <br>
          <button class="btn btn-outline-success" type="button" data-id="" id="btnNuevoPuntoV">Agregar 
          <i class="far fa-save"></i></button>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal nuevo punto venta-->

<!-- Modal Editar punto venta-->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel" style="color:#607d8b">Edición</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarEdit">
          <span aria-hidden="true">&times;</span></button></button>
      </div>
      <div class="modal-body">
          <label for="nombre"><b>DATOS</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreEdit" type="text" placeholder="Nombre" name="nombre" required>
          <br>
            <label for="tipo"><b>Tipo</b></label>
            <select class="form-control" id="tipoEdit">
              <option value="0">Seleccione</option>
              <option value="Mayoreo">Mayoreo</option>
              <option value="Moderno">Moderno</option>
              <option value="Conveniencia">Conveniencia</option>
            </select>
          <br>
          <label for="zona"><b>Zona</b></label><br>
          <select class="form-control" name="idZona" id="zonaEdit" required></select>
          <br>
          <label for="vendedor"><b>Vendedor</b></label><br>
          <select class="form-control" name="idVendedor" id="vendedorEdit" required></select>
          <br>
          <div id="avisoEditar"> </div>
          <br>
          <button type="button" class="btn btn-outline-success" style="margin:1%;" data-id="" id="btnEditarPuntoV">
          Guardar <i class="far fa-save"></i></button>
      </div>
    </div>
  </div>
</div>
<!-- FIN Editar punto venta-->

<?php include ('includes/footer.php')?>

<script>
getZonaFiltro("#filtroZonaPV");
getVendedorFiltro("#filtroVendedorPV");
$(document).ready(function () {
  load('');
  getZonas();
  getVendedor();
  getZonaFiltro();
  getVendedorFiltro();
});

//función para mostrar datos en el tbody de la tabla
//llamamos paginación y filtros
function load(page, tipo, busquedaNombre, idZonaV, idVendedorV) {
  var idZonaV = $("#filtroPZona").attr("data-zonaV");
  getZonaFiltro(idZonaV);
  var idVendedorV = $("#filtroPVendedor").attr("data-vendedorV");
  getVendedorFiltro(idVendedorV);
  var tipo = $("#filtroTPuntoV").attr("data-puntoV");
  var busquedaNombre = $("#busquedaNombre").val();

  var parametros = {
    "action": "getPuntosV",
    "page": page,
    "tipo": tipo,
    "busquedaNombre": busquedaNombre,
    "idZonaV": idZonaV,
    "idVendedorV": idVendedorV
  }
  $.ajax({
    data: parametros,
    url: 'zonasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaPuntosV").html(data.pventa);
      $("#pagUsuario").html(data.pagination);
    }
  });
}

//AGREGAR
$("#btnNuevoPuntoV").click(function () {
  var nombreAdd = $("#nombreAdd").val();
  var tipoAdd = $("#tipoAdd").val();
  var zonaAdd = $("#zonaAdd").val();
  var vendedorAdd = $("#vendedorAdd").val();

  if (nombreAdd != "" && tipoAdd != "" && zonaAdd != "" && vendedorAdd != "") {
    var parametros = {
      "action": "agregarPuntoV",
      "nombreAdd": nombreAdd,
      "tipoAdd": tipoAdd,
      "zonaAdd": zonaAdd,
      "vendedorAdd": vendedorAdd
    }
    $.ajax({
      url: "zonasAjax.php",
      data: parametros,
      success: function (data) {
        console.log(data);
        load();
        if(data == 1) {
          $('#btnNuevoPuntoV').hide();
          $('#avisoAgregar').html("<i class='far fa-save'></i> Agregado con Éxito").css("color", "#0f5132");
          $('#nombreAdd').val("");
          $('#tipoAdd').val("0");
          $('#zonaAdd').val("");
          $('#vendedorAdd').val("");
        }else{
        $('#avisoAgregar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
        }
      }
    });
  }else{
    $('#avisoAgregar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//EDITAR función para obtener datos
$(document).on("click", "#btnEditModalP", function () {
  var idPuntoVenta = $(this).attr('data-id');
  var parametros = {
    "action": "getDatosPuntoV",
    "idPuntoVenta": idPuntoVenta
  };

  $.ajax({
    data: parametros,
    url: 'zonasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#nombreEdit").val(data.nombre);
      $("#tipoEdit").val(data.tipo);
      $("#zonaEdit").val(data.idZona);
      $("#vendedorEdit").val(data.idVendedor);
      $("#btnEditarPuntoV").attr("data-id", data.idPuntoVenta);
    }
  })
});

//EDITAR función para obtener datos de campos editar y realizar validaciones
$("#btnEditarPuntoV").click(function btnEditarPuntoV(idPuntoVenta, nombreEdit, tipoEdit, zonaEdit, vendedorEdit) {
  var idPuntoVenta = $("#btnEditarPuntoV").attr('data-id');
  var nombreEdit = $("#nombreEdit").val();
  var tipoEdit = $("#tipoEdit").val();
  var zonaEdit = $("#zonaEdit").val();
  var vendedorEdit = $("#vendedorEdit").val();
 
  if (nombreEdit != "" && tipoEdit != "" && zonaEdit != "" && vendedorEdit != "") {
    var parametros = {
      "action": "editarPuntoV",
      "idPuntoVenta": idPuntoVenta,
      "nombreEdit": nombreEdit,
      "tipoEdit": tipoEdit,
      "zonaEdit": zonaEdit,
      "vendedorEdit": vendedorEdit
    }
    $.ajax({
      url: "zonasAjax.php",
      data: parametros,
      success: function (data) {
        load();
        if (data == 1) {
          $('#btnEditarPuntoV').hide();
          $('#avisoEditar').html("<i class='far fa-save'></i> Guardado con Éxito").css("color", "#0f5132");
        }
      }
    });
  } else {
    $('#avisoEditar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//Función para SELECT automático
function getZonas() {
  var parametros = {
    "action": "getZonas"
  }
  $.ajax({
    url: 'zonasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#zonaAdd").html(data);
      $("#zonaEdit").html(data);
    }
  });
}

function getVendedor() {
  var parametros = {
    "action": "getVendedor"
  }
  $.ajax({
    url: 'zonasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#vendedorAdd").html(data);
      $("#vendedorEdit").html(data);
    }
  });
}

// limpiar avisos y filtros
$(document).on("click", "#btnCerrarAdd", function () {
  $('#avisoAgregar').html("");
  $('#btnNuevoPuntoV').show();
});
$(document).on("click", "#btnCerrarEdit", function () {
  $('#avisoEditar').html("");
  $('#btnEditarPuntoV').show();
});
$(document).on("click", "#buscPunto", function() {
  $("#filtroTPuntoV").attr('data-puntoV', '');
  $("#buscPunto").remove();
  load();
});
$(document).on("click", "#buscZona", function() {
  $("#filtroPZona").attr('data-zonaV', '');
  $("#buscZona").remove();
  load();
});
$(document).on("click", "#buscVendedor", function() {
  $("#filtroPVendedor").attr('data-vendedorV', '');
  $("#buscVendedor").remove();
  load();
});

//filtros búsqueda
$(".opcFilPuntoV").click(function() {
  var puntoBusc = $(this).attr('data-puntoV');
  $("#filtroTPuntoV").attr('data-puntoV', puntoBusc);
  load();
  if ($("#buscPunto").length) {
      $("#buscPunto").remove();
  }
  if (puntoBusc)
      $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscPunto">' + puntoBusc + ' <i class="far fa-times-circle"></i></a>');
});

  $(document).on("click", ".opcFilZonaV", function () {
  var zonaBusc = $(this).attr('data-id');
  var idZonaV = $(this).attr('data-zonaV');
  $("#filtroPZona").attr("data-zonaV", zonaBusc);
  load();
  if ($("#buscZona").length) {
    $("#buscZona").remove();
  }
  if (zonaBusc)
    $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscZona">' + idZonaV + ' <i class="far fa-times-circle"></i></a>');
});

$(document).on("click", ".opcFilVendedorV", function () {
  var vendedorBusc = $(this).attr('data-id');
  var idVendedorV = $(this).attr('data-vendedorV');
  $("#filtroPVendedor").attr("data-vendedorV", vendedorBusc);
  load();
  if ($("#buscVendedor").length) {
    $("#buscVendedor").remove();
  }
  if (vendedorBusc)
    $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscVendedor">' + idVendedorV + ' <i class="far fa-times-circle"></i></a>');
});

//filtros de búsqueda funciones
function getZonaFiltro(filtro) {
  var parametros = {
    "action": "getZonaFiltro"
  }
  $.ajax({
    url: "zonasAjax.php",
    data: parametros,
    success: function (data) {
      $(filtro).html(data);
    }
  });
}

function getVendedorFiltro(filtro) {
  var parametros = {
    "action": "getVendedorFiltro"
  }
  $.ajax({
    url: "zonasAjax.php",
    data: parametros,
    success: function (data) {
      $(filtro).html(data);
    }
  });
}

</script>

<?php
} //cierre llave else SESSION
?>
