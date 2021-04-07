<?php
include('includes/menu.php');
include('includes/header.php');
?>

<style>
.page-item.active .page-link {
    z-index: 3;
    color: #607d8b;
    background-color: #607d8b57;
    border-color: #607d8b;
}

.page-item .page-link {
    z-index: 2;
    color: #607d8b;
    background-color: white;
}
</style>

<!-- Encabezado -->
<div class="container">
  <div class="row">
    <div class="col">
      <h2>Usuarios</h2>
    </div>
  </div>
</div>

<!-- Botón Lanza Modal -->
<div class="container">
  <div class="row">
    <div class="col">
      <button type="button" class="btn btn-light" style="margin-top:5px;" data-toggle="modal"
        data-target="#modalNvoUsuario" id="btnAgregar">
        Agregar <i class="fas fa-plus"></i></button><br>
    </div>
  </div>
</div>
<!--Fin Lanza Modal -->

<!-- Filtrados Búsqueda -->
<div class="container" style="margin-top:10px">
  <div class="row justify-content-between">
    <div class="col-6 col-lg-5" id="filtros">
      <div class="btn-group" role="group">
        <button id="filtroTUsuario" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Tipo de Usuario
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroUsuario">
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

<!-- Tabla usuario con campos principales-->
<div class="container">
  <div class="row">
    <div class="col">
      <table id="myTable" class="table" style="margin-top:10px;">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Ciudad</th>
            <th>Editar</th>
          </tr>
        </thead>
        <tbody id="tablaUsuarios">
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
<!-- FIN Tabla Usuario -->

<!-- Modal nuevo usuario-->
<div class="modal fade" id="modalNvoUsuario" tabindex="-1" aria-labelledby="modalNvoUsuarioLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNvoUsuarioLabel" style="color:#607d8b">Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarAdd">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoUsuario">
          <label for="nombre"><b>DATOS PERSONALES</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreAdd" type="text" placeholder="Ingrese su Nombre" name="nombre" required>
          <br>
          <label for="apellidos"><b>Apellidos</b></label><br>
          <input class="form-control" id="apellidosAdd" type="text" placeholder="Ingrese sus Apellidos" name="apellidos"
            required>
          <br>
          <label for="telefono"><b>Teléfono</b></label><br>
          <input class="form-control" id="telAdd" type="text" placeholder="Ingrese su Teléfono" name="telefono"
            required>
          <br>
          <label for="ciudad"><b>Ciudad</b></label><br>
          <input class="form-control" id="ciudadAdd" type="text" placeholder="Ingrese su Ciudad" name="ciudad" required>
          <br><br>
          <label for="nombre"><b>DATOS CUENTA DE INGRESO</b></label>
          <br>
          <label for="correoUsuario"><b>Correo</b></label><br>
          <input class="form-control" id="correoAdd" type="text" placeholder="Ingrese su Correo" name="correoUsuario"
            required>
          <br>
          <label for="password"><b>Contraseña</b></label><br>
          <input class="form-control" id="passwordAdd" type="password" placeholder="Ingrese su Contraseña"
            name="password" required>
          <br>
          <label for="rol"><b>Tipo de Usuario</b></label><br>
          <select class="form-control" name="idRol" id="rolAdd" required></select>
          <br>
          <div id="avisoAgregar"> </div>
          <br>
          <button class="btn btn-light"
            style="margin:1%; border-color:#607d8b; color: black; background-color:#607d8b57;" type="button" data-id=""
            id="btnNuevoUsuario">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal nuevo usuario-->

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
          <label for="nombre"><b>DATOS PERSONALES</b></label>
          <br>
          <label for="nombre"><b>Nombre</b></label><br>
          <input class="form-control" id="nombreEdit" type="text" placeholder="Ingrese su Nombre" name="nombre"
            required>
          <br>
          <label for="apellidos"><b>Apellidos</b></label><br>
          <input class="form-control" id="apellidosEdit" type="text" placeholder="Ingrese sus Apellidos"
            name="apellidos" required>
          <br>
          <label for="telefono"><b>Teléfono</b></label><br>
          <input class="form-control" id="telEdit" type="text" placeholder="Ingrese su Teléfono" name="telefono"
            required>
          <br>
          <label for="ciudad"><b>Ciudad</b></label><br>
          <input class="form-control" id="ciudadEdit" type="text" placeholder="Ingrese su Ciudad" name="ciudad"
            required>
          <br>
          <label for="nombre"><b>DATOS CUENTA DE INGRESO</b></label>
          <br>
          <label for="correoUsuario"><b>Correo</b></label><br>
          <input class="form-control" id="correoEdit" type="text" placeholder="Ingrese su Correo" name="correoUsuario"
            required>
          <br>
          <label for="rol"><b>Tipo de Usuario</b></label><br>
          <select class="form-control" name="idRol" id="rolEdit" required></select>
          <br><br>
          <div id="avisoEditar"> </div>
          <br>
          <button type="button" class="btn btn-light"
            style="margin:1%; border-color:#607d8b; color: black; background-color:#607d8b57;" data-id=""
            id="btnEditarUsuario">Guardar Cambios </button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Editar-->

<!-- Modal Cambiar Contraseña-->
<div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="modalPasswordLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPasswordLabel" style="color:#607d8b">Actualizar Contraseña</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarPass">
          <span aria-hidden="true">&times;</span></button></button></button>
      </div>
      <div class="modal-body">
        <form id="formPassword">
          <label for="password"><b>Nueva Contraseña</b></label><br>
          <input class="form-control" id="passwordEdit" type="password" placeholder="Ingrese su Contraseña"
            name="password" required>
          <br>
          <div id="avisoPass"></div>
          <br>
          <button type="button" class="btn btn-light"
            style="margin:1%; border-color:#607d8b; color: black; background-color:#607d8b57;" data-id=""
            id="btnEditPassword">Cambiar Contraseña </button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Cambiar Contraseña-->

<?php include ('includes/footer.php')?>

<script>
getUsuarioFiltro("#filtroUsuario");

$(document).ready(function () {
  load('');
  getTipoUsuario();
  getEditUsuario();
  getUsuarioFiltro();
});

//función para mostrar datos en el tbody de la tabla
//llamamos paginación y filtros
function load(page, idTipoUsuario, busquedaNombre) {
  var idTipoUsuario = $("#filtroTUsuario").attr("data-userBusc");
  getUsuarioFiltro(idTipoUsuario);
  var busquedaNombre = $("#busquedaNombre").val();

  var parametros = {
    "action": "getUsuarios",
    "page": page,
    "idTipoUsuario": idTipoUsuario,
    "busquedaNombre": busquedaNombre
  }
  $.ajax({
    data: parametros,
    url: 'usuarioAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaUsuarios").html(data.usuarios);
      $("#pagUsuario").html(data.pagination);
    }
  });
}

//AGREGAR
$("#btnNuevoUsuario").click(function () {
  var idPersona = $("#btnNuevoUsuario").data('id');
  var nombreAdd = $("#nombreAdd").val();
  var apellidosAdd = $("#apellidosAdd").val();
  var telAdd = $("#telAdd").val();
  var ciudadAdd = $("#ciudadAdd").val();
  var rolAdd = $("#rolAdd").val();
  var correoAdd = $("#correoAdd").val();
  var passwordAdd = $("#passwordAdd").val();
  var caracter = ";";
  var validate = false;

  if (correoAdd.includes(caracter)) {
    validate = false;
  } else {
    validate = true;
  }

  if (nombreAdd != "" && apellidosAdd != "" && telAdd != "" && ciudadAdd != "" && correoAdd != "" && passwordAdd != "" && validate) {
    var parametros = {
      "action": "agregarUsuario",
      "idPersona": idPersona,
      "nombreAdd": nombreAdd,
      "apellidosAdd": apellidosAdd,
      "telAdd": telAdd,
      "ciudadAdd": ciudadAdd,
      "rolAdd": rolAdd,
      "correoAdd": correoAdd,
      "passwordAdd": passwordAdd
    }
    $.ajax({
      url: "usuarioAjax.php",
      data: parametros,
      success: function (data) {
        console.log(data);
        load();
        if (data == 1) {
          $('#btnNuevoUsuario').hide();
          $('#avisoAgregar').html("<i class='far fa-save'></i> Agregado con Éxito").css("color", "#0f5132");
        }
      $('#nombreAdd').val("");
      $('#apellidosAdd').val("");
      $('#telAdd').val("");
      $('#ciudadAdd').val("");
      $('#rolAdd').val("");
      $('#correoAdd').val("");
      $('#passwordAdd').val("");
      }
    });
  } else {
    $('#avisoAgregar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//EDITAR función para obtener datos
$(document).on("click", "#btnEditModal", function () {
  var idPersona = $(this).attr('data-id');
  var parametros = {
    "action": "getDatosUsuario",
    "idPersona": idPersona
  };

  $.ajax({
    data: parametros,
    url: 'usuarioAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#nombreEdit").val(data.nombre);
      $("#apellidosEdit").val(data.apellidos);
      $("#telEdit").val(data.telefono);
      $("#ciudadEdit").val(data.ciudad);
      $("#rolEdit").val(data.idTipoUsuario);
      $("#correoEdit").val(data.correo);
      $("#btnEditarUsuario").attr("data-id", data.idPersona);
    }
  })
});

//EDITAR función para obtener datos de campos editar y realizar validaciones
$("#btnEditarUsuario").click(function btnEditarUsuario(idPersona, nombreEdit, apellidosEdit, telEdit, ciudadEdit, rolEdit, correoEdit) {
  var idPersona = $("#btnEditarUsuario").attr('data-id');
  var nombreEdit = $("#nombreEdit").val();
  var apellidosEdit = $("#apellidosEdit").val();
  var telEdit = $("#telEdit").val();
  var ciudadEdit = $("#ciudadEdit").val();
  var rolEdit = $("#rolEdit").val();
  var correoEdit = $("#correoEdit").val();
  var caracter = ";";
  var validate = false;

  if (correoEdit.includes(caracter)) {
    validate = false;
  } else {
    validate = true;
  }

  if (nombreEdit != "" && apellidosEdit != "" && telEdit != "" && ciudadEdit != "" && correoEdit != "" && validate == true) {
    var parametros = {
      "action": "editarUsuario",
      "idPersona": idPersona,
      "nombreEdit": nombreEdit,
      "apellidosEdit": apellidosEdit,
      "telEdit": telEdit,
      "ciudadEdit": ciudadEdit,
      "rolEdit": rolEdit,
      "correoEdit": correoEdit
    }
    $.ajax({
      url: "usuarioAjax.php",
      data: parametros,
      success: function (data) {
        load();
        if (data == 1) {
          $('#btnEditarUsuario').hide();
          $('#avisoEditar').html("<i class='far fa-save'></i> Guardado con Éxito").css("color", "#0f5132");
        }
      }
    });
  } else {
    $('#avisoEditar').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//CONTRASEÑA función para obtener datos
$(document).on("click", "#btnPassModal", function () {
  var idPersona = $(this).attr('data-id');
  var parametros = {
    "action": "getDatosUsuario",
    "idPersona": idPersona
  };

  $.ajax({
    data: parametros,
    url: 'usuarioAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      $("#btnEditPassword").attr("data-id", data.idPersona);
    }
  })
});

//CONTRASEÑA función para obtener datos de campos editar y realizar validaciones
$("#btnEditPassword").click(function btnEditPassword(idPersona, passwordEdit) {
  var idPersona = $("#btnEditPassword").attr('data-id');
  var passwordEdit = $("#passwordEdit").val();

  if (passwordEdit != "") {
    var parametros = {
      "action": "editPassword",
      "idPersona": idPersona,
      "passwordEdit": passwordEdit
    }
    $.ajax({
      url: "usuarioAjax.php",
      data: parametros,
      success: function (data) {
        load();
        if (data == 1) {
          $('#btnEditPassword').hide();
          $('#avisoPass').html("<i class='far fa-save'></i> Datos Guardados").css("color", "#0f5132");
        }
        $('#passwordEdit').val("");
      }
    });
  } else {
    $('#avisoPass').html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos").css("color", "red");
    console.log("Existen campos vacios");
  }
});

//Función para SELECT automático
function getTipoUsuario() {
  var parametros = {
    "action": "getTipoUsuario"
  }
  $.ajax({
    url: 'usuarioAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#rolAdd").html(data);
    }
  });
}

function getEditUsuario() {
  var parametros = {
    "action": "getTipoUsuario"
  }
  $.ajax({
    url: 'usuarioAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#rolEdit").html(data);
    }
  });
}

// limpiar avisos y filtros
$(document).on("click", "#btnCerrarAdd", function () {
  $('#avisoAgregar').html("");
  $('#btnNuevoUsuario').show();
});
$(document).on("click", "#btnCerrarEdit", function () {
  $('#avisoEditar').html("");
  $('#btnEditarUsuario').show();
});
$(document).on("click", "#btnCerrarPass", function () {
  $('#avisoPass').html("");
  $('#btnEditPassword').show();
});
$(document).on("click", "#buscUsuario", function() {
  $("#filtroCateProd").attr('data-userBusc', '');
  $("#buscUsuario").remove();
  location.reload();
});

//filtros de búsqueda
function getUsuarioFiltro(filtro) {
  var parametros = {
    "action": "getUsuarioFiltro"
  }
  $.ajax({
    url: "usuarioAjax.php",
    data: parametros,
    success: function (data) {
      $(filtro).html(data);
    }
  });
}

//Aparece bolita de búsqueda en filtros
$(document).on("click", ".opcFilTipoUsu", function () {
  var tipoUsuarioBusc = $(this).attr('data-id');
  var idTipoUsuario = $(this).attr('data-tipUsuario');
  $("#filtroTUsuario").attr("data-userBusc", tipoUsuarioBusc);
  load();
  if ($("#buscUsuario").length) {
    $("#buscUsuario").remove();
  }
  if (tipoUsuarioBusc)
    $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscUsuario">' + idTipoUsuario + ' <i class="far fa-times-circle"></i></a>');
});

</script>
