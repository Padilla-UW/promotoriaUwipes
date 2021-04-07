<?php
include('includes/menu.php');
include('includes/header.php');
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
      <h2>Zonas</h2>
    </div>
  </div>
</div>

<!-- Botón Lanza Modal -->
<div class="container">
  <div class="row">
    <div class="col">
      <button type="button" class="btn btn-light" style="margin-top:5px;" data-toggle="modal"
        data-target="#modalNvaZona" id="btnAgregar">
        Agregar <i class="fas fa-plus"></i></button><br>
    </div>
  </div>
</div>
<!--Fin Lanza Modal -->

<!-- Filtrados Búsqueda -->
<div class="container" style="margin-top:10px">
  <div class="row justify-content-between">
    <div class="col-6 col-lg-5" id="filtros"></div>
    <div class="col-6 col-lg-4">
      <input type="text" onkeyup="load()" class="form-control" id="busquedaNombre" placeholder="Nombre">
    </div>
  </div>
</div>
<br>
<!--Fin Filtrados Búsqueda -->

<!-- Tabla zona con campos principales-->
<div class="container">
  <div class="row">
    <div class="col">
      <table id="myTable" class="table" style="margin-top:10px;">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody id="tablaZonas">
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
<!-- FIN Tabla Zona -->

<!-- Modal nueva Zona-->
<div class="modal fade" id="modalNvaZona" tabindex="-1" aria-labelledby="modalNvaZonaLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNvaZonaLabel" style="color:#607d8b">Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarAdd">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="formNuevaZona">
          <label for="nombre"><b>DATOS</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreAdd" type="text" placeholder="Nombre" name="nombre" required>
          <br>
          <div id="avisoAgregar"> </div>
          <br>
          <button class="btn btn-light"
            style="margin:1%; border-color:#607d8b; color: black; background-color:#607d8b57;" type="button" data-id=""
            id="btnNuevaZona">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal nueva zona-->

<!-- Modal Editar-->
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
        <form id="formEditar">
          <label for="nombre"><b>DATOS</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreEdit" type="text" placeholder="Nombre" name="nombre" required>
          <br>
          <div id="avisoEditar"> </div>
          <br>
          <button type="button" class="btn btn-light"
            style="margin:1%; border-color:#607d8b; color: black; background-color:#607d8b57;" data-id=""
            id="btnEditarZona">Guardar Cambios </button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Editar-->

<?php include ('includes/footer.php')?>

<script>
$(document).ready(function () {
  load('');
});

//función para mostrar datos en el tbody de la tabla
//llamamos paginación y filtros
function load(page, busquedaNombre) {
  var busquedaNombre = $("#busquedaNombre").val();

  var parametros = {
    "action": "getZona",
    "page": page,
    "busquedaNombre": busquedaNombre
  }
  $.ajax({
    data: parametros,
    url: 'zonasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaZonas").html(data.zonas);
      $("#pagUsuario").html(data.pagination);
    }
  });
}

//AGREGAR
$("#btnNuevaZona").click(function () {
  var nombreAdd = $("#nombreAdd").val();

  if (nombreAdd != "") {
    var parametros = {
      "action": "agregarZona",
      "nombreAdd": nombreAdd
    }
    $.ajax({
      url: "zonasAjax.php",
      data: parametros,
      success: function (data) {
        console.log(data);
        load();
        if (data == 1) {
          $('#btnNuevaZona').hide();
          $('#avisoAgregar').html("<i class='far fa-save'></i> Agregado con Éxito").css("color", "#0f5132");
        }else if(data==0){
                $('#avisoAgregar').html("<i class='bi bi-x-square'></i> Dato Duplicado").css("color", "red");
              } 
              $('#nombreAdd').val("");
      }
    });
  }else{
    $('#avisoAgregar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//EDITAR función para obtener datos
$(document).on("click", "#btnEditModal", function () {
  var idZona = $(this).attr('data-id');
  var parametros = {
    "action": "getDatosZona",
    "idZona": idZona
  };
  $.ajax({
    data: parametros,
    url: 'zonasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#nombreEdit").val(data.nombre);
      $("#btnEditarZona").attr("data-id", data.idZona);
    }
  })
});

//EDITAR función para obtener datos de campos editar y realizar validaciones
$("#btnEditarZona").click(function btnEditarZona(idZona, nombreEdit) {
  var idZona = $("#btnEditarZona").attr('data-id');
  var nombreEdit = $("#nombreEdit").val();

  if (nombreEdit != "") {
    var parametros = {
      "action": "editarZona",
      "idZona": idZona,
      "nombreEdit": nombreEdit
    }
    $.ajax({
      url: "zonasAjax.php",
      data: parametros,
      success: function (data) {
        load();
        if (data == 1) {
          $('#btnEditarZona').hide();
          $('#avisoEditar').html("<i class='far fa-save'></i> Guardado con Éxito").css("color", "#0f5132");
        }else if(data==0){
                $('#avisoEditar').html("<i class='bi bi-x-square'></i> Dato Duplicado").css("color", "red");
              }
      }
    });
  } else {
    $('#avisoEditar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

// limpiar avisos
$(document).on("click", "#btnCerrarAdd", function () {
  $('#avisoAgregar').html("");
  $('#btnNuevaZona').show();
});
$(document).on("click", "#btnCerrarEdit", function () {
  $('#avisoEditar').html("");
  $('#btnEditarZona').show();
});
</script>
