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
      <h2>Sucursales</h2>
    </div>
  </div>
</div>

<!-- Botón Lanza Modal -->
<div class="container">
  <div class="row">
    <div class="col">
      <button type="button" class="btn btn-light" style="margin-top:5px;" data-toggle="modal"
        data-target="#modalNvaSuc" id="btnAgregar">
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
        <button id="filtroPVenta" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Punto de Venta
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroPuntoVenta">
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

<!-- Tabla sucursal con campos principales-->
<div class="container">
  <div class="row">
    <div class="col">
      <table id="myTable" class="table" style="margin-top:10px;">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Número</th>
            <th>Punto de Venta</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody id="tablaSucursal">
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
<!-- FIN Tabla Sucursal -->

<!-- Modal nueva sucursal-->
<div class="modal fade" id="modalNvaSuc" tabindex="-1" aria-labelledby="modalNvaSucLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNvaSucLabel" style="color:#607d8b">Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarAdd">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
          <label for="nombre"><b>DATOS</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreAdd" type="text" placeholder="Nombre" name="nombre" required>
          <br>
          <label for="numero"><b>Número</b></label><br>
          <input class="form-control" id="numeroAdd" type="text" placeholder="Número" name="numero">
          <br>
          <label for="pventa"><b>Punto de Venta</b></label><br>
          <select class="form-control" name="pventa" id="pventaAdd" required></select>
          <br>
          <div id="avisoAgregar"> </div>
          <br>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCerrarAdd">Cerrar</button>
          <button class="btn btn-outline-success" type="button" data-id="" id="btnNuevaSuc">Agregar 
          <i class="far fa-save"></i></button>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal nueva sucursal-->

<!-- Modal Editar sucursal-->
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
          <label for="numero"><b>Número</b></label><br>
          <input class="form-control" id="numeroEdit" type="text" placeholder="Número" name="numero">
          <br>
          <label for="pventa"><b>Punto de Venta</b></label><br>
          <select class="form-control" name="pventa" id="pventaEdit" required></select>
          <br>
          <div id="avisoEditar"> </div>
          <br>
          <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCerrarEdit">Cerrar</button>
          <button type="button" class="btn btn-outline-success" style="margin:1%;" data-id="" id="btnEditarSuc">
          Guardar <i class="far fa-save"></i></button>
          </div>
      </div>
    </div>
  </div>
</div>
<!-- FIN Editar sucursal-->

<?php include ('includes/footer.php')?>

<script>
getPVentaFiltro("#filtroPuntoVenta");
$(document).ready(function () {
  load('');
  getPVentaFiltro();
  getPVenta();
});

//función para mostrar datos en el tbody de la tabla
//llamamos paginación y filtros
function load(page, busquedaNombre, idPuntoVenta) {
  var idPuntoVenta = $("#filtroPVenta").attr("data-visitBusc");
  getPVentaFiltro(idPuntoVenta);
  var busquedaNombre = $("#busquedaNombre").val();

  var parametros = {
    "action": "getSucursal",
    "page": page,
    "idPuntoVenta": idPuntoVenta,
    "busquedaNombre": busquedaNombre
  }
  $.ajax({
    data: parametros,
    url: 'zonasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaSucursal").html(data.pventa);
      $("#pagUsuario").html(data.pagination);
    }
  });
}

//filtros de búsqueda
function getPVentaFiltro(filtro) {
  var parametros = {
    "action": "getPVentaFiltro"
  }
  $.ajax({
    url: "zonasAjax.php",
    data: parametros,
    success: function (data) {
      $(filtro).html(data);
    }
  });
}

//Aparece bolita de búsqueda en filtros
$(document).on("click", ".opcFilPunVenta", function () {
  var pVentaBusc = $(this).attr('data-id');
  var idPuntoVenta = $(this).attr('data-tipVisita');
  $("#filtroPVenta").attr("data-visitBusc", pVentaBusc);
  load();
  if ($("#buscPVenta").length) {
    $("#buscPVenta").remove();
  }
  if (pVentaBusc)
    $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscPVenta">' + idPuntoVenta + ' <i class="far fa-times-circle"></i></a>');
});

//AGREGAR
$("#btnNuevaSuc").click(function () {
  var nombreAdd = $("#nombreAdd").val();
  var numeroAdd = $("#numeroAdd").val();
  var pventaAdd = $("#pventaAdd").val();

  if (nombreAdd != "" && numeroAdd != "" && pventaAdd != "") {
    var parametros = {
      "action": "agregarSuc",
      "nombreAdd": nombreAdd,
      "numeroAdd": numeroAdd,
      "pventaAdd": pventaAdd
    }
    $.ajax({
      url: "zonasAjax.php",
      data: parametros,
      success: function (data) {
        console.log(data);
        load();
        if(data == 1) {
          $('#btnNuevaSuc').hide();
          $('#avisoAgregar').html("<i class='far fa-save'></i> Agregado con Éxito").css("color", "#0f5132");
          $('#nombreAdd').val("");
          $('#numeroAdd').val("");
          $('#pventaAdd').val("");
        }
      }
    });
  }else{
    $('#avisoAgregar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//EDITAR función para obtener datos
$(document).on("click", "#btnEditModalS", function () {
  var idSucursal = $(this).attr('data-id');
  var parametros = {
    "action": "getDatosSuc",
    "idSucursal": idSucursal
  };

  $.ajax({
    data: parametros,
    url: 'zonasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#nombreEdit").val(data.nombre);
      $("#numeroEdit").val(data.numero);
      $("#pventaEdit").val(data.idPuntoVenta);
      $("#btnEditarSuc").attr("data-id", data.idSucursal);
    }
  })
});

//EDITAR
$("#btnEditarSuc").click(function btnEditarSuc(idSucursal, nombreEdit, numeroEdit, pventaEdit) {
  var idSucursal = $("#btnEditarSuc").attr('data-id');
  var nombreEdit = $("#nombreEdit").val();
  var numeroEdit = $("#numeroEdit").val();
  var pventaEdit = $("#pventaEdit").val();
 
  if (nombreEdit != "" && numeroEdit != "" && pventaEdit != "") {
    var parametros = {
      "action": "editarSuc",
      "idSucursal": idSucursal,
      "nombreEdit": nombreEdit,
      "numeroEdit": numeroEdit,
      "pventaEdit": pventaEdit
    }
    $.ajax({
      url: "zonasAjax.php",
      data: parametros,
      success: function (data) {
        load();
        if (data == 1) {
          $('#btnEditarSuc').hide();
          $('#avisoEditar').html("<i class='far fa-save'></i> Guardado con Éxito").css("color", "#0f5132");
        }else{
          $('#avisoEditar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
        }
      }
    });
  } else {
    $('#avisoEditar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//limpiar avisos
$(document).on("click", "#buscPVenta", function() {
  $("#filtroPVenta").attr('data-visitBusc', '');
  $("#buscPVenta").remove();
  load();
});
$(document).on("click", "#btnCerrarAdd", function () {
  $('#avisoAgregar').html("");
  $('#btnNuevaSuc').show();
  $('#nombreAdd').val("");
  $('#numeroAdd').val("");
  $('#pventaAdd').val("");
});
$(document).on("click", "#btnCerrarEdit", function () {
  $('#avisoEditar').html("");
  $('#btnEditarSuc').show();
});

//Función para SELECT automático
function getPVenta() {
  var parametros = {
    "action": "getPVenta"
  }
  $.ajax({
    url: 'zonasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#pventaAdd").html(data);
      $("#pventaEdit").html(data);
    }
  });
}

</script>

<?php
} //cierre llave else SESSION
?>