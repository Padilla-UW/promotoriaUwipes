<?php
include('includes/header.php');
?>

<style>
.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #607d8b99;
    border-color: #607d8b;
}
</style>

<!-- Botón Lanza Modal -->
<button type="button" class="btn btn-light" style="margin:1%; border-color:#607d8b; color: white; background-color:#607d8b99;" data-toggle="modal" data-target="#modalNvoUsuario" id="btnAgregar">
  Agregar
</button>
<!--Fin Botón Lanza Modal -->

<!-- Tabla usuario con campos principales-->
<div class="container">
<div class="row">
<div class="col">
<table class="table">
  <thead>
    <tr>
      <th>Nombre</th>
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
<nav aria-label="Page navigation example" id="pagUsuario"></nav></div>
</div>
</div>
</div>
<!-- FIN Tabla Usuario -->

<!-- Modal nuevo usuario-->
<div class="modal fade" id="modalNvoUsuario" tabindex="-1" aria-labelledby="modalNvoUsuarioLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
    <input class="form-control" id ="nombreAdd" type="text" placeholder="Ingrese su Nombre" name="nombre" required>
<br>
    <label for="apellidos"><b>Apellidos</b></label><br>
    <input class="form-control" id ="apellidosAdd" type="text" placeholder="Ingrese sus Apellidos" name="apellidos" required>
<br>
    <label for="telefono"><b>Teléfono</b></label><br>
    <input class="form-control" id ="telAdd" type="text" placeholder="Ingrese su Teléfono" name="telefono" required>
<br>
    <label for="ciudad"><b>Ciudad</b></label><br>
    <input class="form-control" id ="ciudadAdd" type="text" placeholder="Ingrese su Ciudad" name="ciudad" required>
<br><br>
    <label for="nombre"><b>DATOS CUENTA DE INGRESO</b></label>
<br>
    <label for="correoUsuario"><b>Correo</b></label><br>
    <input class="form-control" id ="correoAdd" type="text" placeholder="Ingrese su Correo" name="correoUsuario" required>
<br>
    <label for="password"><b>Contraseña</b></label><br>
    <input class="form-control" id ="passwordAdd" type="password" placeholder="Ingrese su Contraseña" name="password" required>
<br>
    <label for="rol"><b>Tipo de Usuario</b></label><br>
    <select class="form-control" name="idRol" id="rolAdd" required></select>
    <br>
    <div id="avisoAgregar"> </div>
    <br>
<button class="btn btn-light" style="margin:1%; border-color:#607d8b; color: white; background-color:#607d8b99;" type="button" data-id="" id="btnNuevoUsuario">Guardar</button>
</form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal nuevo usuario-->

<!-- Modal Editar-->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
    <input class="form-control" id ="nombreEdit" type="text" placeholder="Ingrese su Nombre" name="nombre" required>
<br>
    <label for="apellidos"><b>Apellidos</b></label><br>
    <input class="form-control" id ="apellidosEdit" type="text" placeholder="Ingrese sus Apellidos" name="apellidos" required>
<br>
    <label for="telefono"><b>Teléfono</b></label><br>
    <input class="form-control" id ="telEdit" type="text" placeholder="Ingrese su Teléfono" name="telefono" required>
<br>
    <label for="ciudad"><b>Ciudad</b></label><br>
    <input class="form-control" id ="ciudadEdit" type="text" placeholder="Ingrese su Ciudad" name="ciudad" required>
<br>
<label for="nombre"><b>DATOS CUENTA DE INGRESO</b></label>
<br>
    <label for="correoUsuario"><b>Correo</b></label><br>
    <input class="form-control" id ="correoEdit" type="text" placeholder="Ingrese su Correo" name="correoUsuario" required>
<br>
    <label for="rol"><b>Tipo de Usuario</b></label><br>
    <select class="form-control" name="idRol" id="rolEdit" required></select>
    <br><br>
    <div id="avisoEditar"> </div>
    <br>
<button type="button" class="btn btn-light" style="margin:1%; border-color:#607d8b; color: white; background-color:#607d8b99;" data-id="" id="btnEditarUsuario" >Guardar Cambios  </button>
</form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Editar-->

<!-- Modal Cambiar Contraseña-->
<div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="modalPasswordLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
    <input class="form-control" id ="passwordEdit" type="password" placeholder="Ingrese su Contraseña" name="password" required>
<br>
<div id="avisoPass"></div>
<br>
<button type="button" class="btn btn-light" style="margin:1%; border-color:#607d8b; color: white; background-color:#607d8b99;" data-id="" id="btnEditPassword">Cambiar Contraseña  </button>
</form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Cambiar Contraseña-->

<?php include ('includes/footer.php')?>

<script>
//función para
$(document).ready(function(){
  load('');
  getTipoUsuario();
  getEditUsuario();
});

//función para mostrar datos en el tbody de la tabla
//ponemos buscar por si el un futuro se necesita
function load(page){
  var parametros={
    "action":"getUsuarios",
    "page":page
  }
  $.ajax({
    data:parametros,
    url:'adminAjax.php',
    success: function(data){
      data= JSON.parse(data);
      console.log(data);
      $("#tablaUsuarios").html(data.usuarios);
      $("#pagUsuario").html(data.pagination);
    }
  });
}

//AGREGAR función para JALA
$(document).on("click", "#modalNvoUsuario", function() {
      var idPersona=$(this).data('id');
      var idUsuario=$(this).data('id');
      var parametros = {
        "action": "getDatosUsuario",
        "idPersona": idPersona,
        "idUsuario": idUsuario
      };

      $.ajax({
        data:parametros,
        url:'adminAjax.php',
        success:function(data){
          data = JSON.parse(data);
          console.log(data);
          $("#nombreAdd").val(data.nombre);
          $("#apellidosAdd").val(data.apellidos);
          $("#telAdd").val(data.telefono);
          $("#ciudadAdd").val(data.ciudad);
          $("#rolAdd").val(data.idTipoUsuario);
          $("#correoAdd").val(data.correo);
          $("#passwordAdd").val(data.contrasena);
          $("#btnNuevoUsuario").attr("data-id",data.idPersona);
          $("#btnNuevoUsuario").attr("data-id",data.idUsuario);
        }
      })
    });

//AGREGAR función para jalar datos de campos add y realizar validaciones
$("#btnNuevoUsuario").click(function(){
      var idPersona=$("#btnNuevoUsuario").data('id');
      var nombreAdd = $("#nombreAdd").val();
      var apellidosAdd = $("#apellidosAdd").val();
      var telAdd = $("#telAdd").val();
      var ciudadAdd = $("#ciudadAdd").val();
      var rolAdd = $("#rolAdd").val();
      var correoAdd = $("#correoAdd").val();
      var passwordAdd = $("#passwordAdd").val();
      var caracter = ";";
      var validate = false;

      if(correoAdd.includes(caracter)){
        validate=false;
      }else{
        validate=true;
      }

      if(nombreAdd != "" && apellidosAdd != "" && telAdd != "" && ciudadAdd != "" && correoAdd != "" && passwordAdd != "" && validate) {
      var parametros={
        "action":"agregarUsuario",
        "idPersona":idPersona,
        "nombreAdd":nombreAdd,
        "apellidosAdd":apellidosAdd,
        "telAdd":telAdd,
        "ciudadAdd":ciudadAdd,
        "rolAdd":rolAdd,
        "correoAdd":correoAdd,
        "passwordAdd":passwordAdd
      }
      $.ajax({
            url: "adminAjax.php",
            data: parametros,
            success: function(data) {
              console.log(data);
              load();
              if(data==1){
                $('#btnNuevoUsuario').hide();
                $('#avisoAgregar').html("<i class='bi bi-check2-square'></i> Agregado con Éxito").css("color", "#0f5132");
              }
            }
        }).done(function(){
        document.getElementById("nombreAdd").value = "";
        document.getElementById("apellidosAdd").value = "";
        document.getElementById("telAdd").value = "";
        document.getElementById("ciudadAdd").value = "";
        document.getElementById("rolAdd").value = "";
        document.getElementById("correoAdd").value = "";
        document.getElementById("passwordAdd").value = "";
      });
      }else{
        $('#avisoAgregar').html("<i class='bi bi-x-square'></i> Datos Incorrectos o Vacíos").css("color", "red");
            console.log("Existen campos vacios");
      }
      });

//EDITAR función para JALAR Datos
    $(document).on("click", "#btnEditModal", function() {
      var idPersona=$(this).data('id');
      var parametros = {
        "action": "getDatosUsuario",
        "idPersona": idPersona
      };

      $.ajax({
        data:parametros,
        url:'adminAjax.php',
        success:function(data){
          data = JSON.parse(data);
          // var data = jQuery.parseJSON(data);
          console.log(data);
          $("#nombreEdit").val(data.nombre);
          $("#apellidosEdit").val(data.apellidos);
          $("#telEdit").val(data.telefono);
          $("#ciudadEdit").val(data.ciudad);
          $("#rolEdit").val(data.idTipoUsuario);
          $("#correoEdit").val(data.correo);
          $("#btnEditarUsuario").attr("data-id",data.idPersona);
        }
      })
    });

//EDITAR función para jalar datos de campos editar y realizar validaciones
    $("#btnEditarUsuario").click(function btnEditarUsuario(idPersona, nombreEdit, apellidosEdit, telEdit, ciudadEdit, rolEdit, correoEdit){
      var idPersona=$("#btnEditarUsuario").attr('data-id');
      var nombreEdit = $("#nombreEdit").val();
      var apellidosEdit = $("#apellidosEdit").val();
      var telEdit = $("#telEdit").val();
      var ciudadEdit = $("#ciudadEdit").val();
      var rolEdit = $("#rolEdit").val();
      var correoEdit = $("#correoEdit").val();
      var caracter = ";";
      var validate = false;

      if(correoEdit.includes(caracter)){
        validate=false;
      }else{
        validate=true;
      }

      if(nombreEdit != "" && apellidosEdit != "" && telEdit != "" && ciudadEdit != "" && correoEdit != "" && validate==true) {
      var parametros={
        "action":"editarUsuario",
        "idPersona":idPersona,
        "nombreEdit":nombreEdit,
        "apellidosEdit":apellidosEdit,
        "telEdit":telEdit,
        "ciudadEdit":ciudadEdit,
        "rolEdit":rolEdit,
        "correoEdit":correoEdit
      }
      $.ajax({
            url: "adminAjax.php",
            data: parametros,
            success: function(data) {
              load();
              if(data==1){
                $('#btnEditarUsuario').hide();
                $('#avisoEditar').html("<i class='bi bi-check2-square'></i> Agregado con Éxito").css("color", "#0f5132");
              }
            }
        });
      }else{
        $('#avisoEditar').html("<i class='bi bi-x-square'></i> Datos Incorrectos o Vacíos").css("color", "red");
            console.log("Existen campos vacios");
      }
      });

  //CONTRASEÑA función para JALA
    $(document).on("click", "#btnPassModal", function() {
      var idPersona=$(this).attr('data-id');
      var parametros = {
        "action": "getDatosUsuario",
        "idPersona": idPersona
      };

      $.ajax({
        data:parametros,
        url:'adminAjax.php',
        success:function(data){
          data = JSON.parse(data);
          $("#btnEditPassword").attr("data-id",data.idPersona);
        }
      })
    });

//CONTRASEÑA función para jalar datos de campos editar y realizar validaciones
$("#btnEditPassword").click(function btnEditPassword(idPersona, passwordEdit){
      var idPersona=$("#btnEditPassword").attr('data-id');
      var passwordEdit = $("#passwordEdit").val();

      if(passwordEdit != "") {
      var parametros={
        "action":"editPassword",
        "idPersona":idPersona,
        "passwordEdit":passwordEdit
      }
      $.ajax({
            url: "adminAjax.php",
            data: parametros,
            success: function(data) {
              load();
              if(data==1){
                $('#btnEditPassword').hide();
                $('#avisoPass').html("<i class='bi bi-check2-square'></i> Datos Guardados").css("color", "#0f5132");
              }
            }
        }).done(function(){
        document.getElementById("passwordEdit").value = "";
      });
      }else{
        $('#avisoPass').html("<i class='bi bi-x-square'></i> Datos Incorrectos o Vacíos").css("color", "red");
            console.log("Existen campos vacios");
      }
      });

//Función para SELECT automático
function getTipoUsuario(){
    var parametros={
        "action": "getTipoUsuario"
    }
    $.ajax({
        url:'adminAjax.php',
        data:parametros,
        success:function(data){
            console.log(data);
            $("#rolAdd").html(data);
        }
    });
}

function getEditUsuario(){
    var parametros={
        "action": "getTipoUsuario"
    }
    $.ajax({
        url:'adminAjax.php',
        data:parametros,
        success:function(data){
            console.log(data);
            $("#rolEdit").html(data);
        }
    });
}

// limpiar avisos
$(document).on("click", "#btnCerrarAdd", function(){
  $('#avisoAgregar').html("");
  $('#btnNuevoUsuario').show();
});
$(document).on("click", "#btnCerrarEdit", function(){
  $('#avisoEditar').html("");
  $('#btnEditarUsuario').show();
});
$(document).on("click", "#btnCerrarPass", function(){
  $('#avisoPass').html("");
  $('#btnEditPassword').show();
});

</script>
