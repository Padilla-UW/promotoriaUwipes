<?php
session_start();

include('includes/menu.php');
include('includes/header.php');
?>

<style>
#btnGuardar, 
#btnFinalizar,
#btnConfirmar,
#btnNuevaMatriz{
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 6px;
}

#btnGuardar:hover,
#btnFinalizar:hover:enabled,
#btnConfirmar:hover,
#btnNuevaMatriz:hover{
  opacity: 0.8;
}
</style>

<!-- selects principal -->
<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-6">
<div style="display: flex; align-items: center; justify-content: center;">
    </div>
    <label for=""><b>Producto</b></label>
    <select name="" id="selProducto" class="form-control"></select> 
    <br>
    <label for=""><b>Tipo de Exhibición</b></label><br>
    <select name="" id="selTipoExi" class="form-control"></select> 
    <br>
    <label for=""><b>Existencia</b></label><br>
    <select name="" id="selExistencia" class="form-control">
    <option value="0">¿Está en existencia el producto?</option>
    <option value="Si">Sí</option>
    <option value="No">No</option>
    </select> 
    <br>
    <label for=""><b>Precio</b></label>
    <input type="text" class="form-control" name="" id="selPrecio" placeholder='$100.00'>
    <br>
    <label for=""><b>Frentes</b></label>
    <input type="number" class="form-control" name="" id="selFrentes" min="0">
    <br>
    <button type="button" class="btn btn-light" style="margin-top:5px;" data-toggle="modal"
    data-target="#modalMatriz" id="btnMatriz">Matriz <i class="fab fa-slack-hash"></i></button>
    <br>
    <div id="mns"></div>
    <br>
    <button class="btn btn-secondary" data-id="" id="btnGuardar"><i class="far fa-save"></i> Guardar</button>
    <br>
    <button class="btn btn-secondary" disabled tabindex="-1" aria-disabled="true" data-id="" id="btnFinalizar" data-toggle="modal" data-target="#modalFinalizar"><i class="fas fa-check-double"></i> Finalizar</button>
   </div>
</div>
</div>
<!--Fin selects principal -->

<!-- MODAL pedido final -->
<div class="modal fade" id="modalFinalizar" tabindex="-1" aria-labelledby="modalFinalizarLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content" style="width:130%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFinalizarLabel" style="color:#0d6efd">Visita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarF">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

       <!-- Tabla datos generales con campos principales-->
       <table class="table">
           <thead>
               <tr>
                   <th>Fecha</th>
                   <th>Punto Venta</th>
               </tr>
           </thead>
           <tbody id="tablaDatosG">
           </tbody>
       </table>
       <!-- FIN Tabla datos g -->

       <!-- Tabla final con campos principales-->
       <table class="table">
           <thead>
               <tr>
                   <th>Producto</th>
                   <th>Tipo Exhibición</th>
                   <th>Existencia</th>
                   <th>Precio</th>
                   <th>Frentes</th>
               </tr>
           </thead>
           <tbody id="tablaFinal">
           </tbody>
       </table>
       <br>
       <div style="font-weight: bold;" id="mns2"></div>
       <button class="btn btn-secondary" data-id="" id="btnConfirmar"><i class="fas fa-check-double"></i> Confirmar</button>
       <!-- FIN Tabla final -->
           </div>
         </div>
        </div>
       </div>
<!-- fin MODAL pedido final -->

<!-- Modal Matriz-->
<div class="modal fade" id="modalMatriz" tabindex="-1" aria-labelledby="modalMatrizLabel" aria-hidden="true"
  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalMatrizLabel" style="color:#607d8b">Matriz de Productos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarMat">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="formMatriz">
          <label for="">Ingrese o seleccione el producto según su posición</label>
          <br><br>
          <!-- Sección Superior -->
          <div class="row">
    <div class="col">
    <label for=""><b>Sup. Izquierda</b></label><br>
          <select class="form-control" name="supIzq" id="supIzq" required></select>
          <br>
          <input class="form-control" type="text" id="txtSupIzq" placeholder="Ingrese" name="txtSupIzq" required>
          <br>
    </div>
    <div class="col">
    <label for=""><b>Superior Centro</b></label><br>
          <select class="form-control" name="supCen" id="supCen" required></select>
          <br>
          <input class="form-control" type="text" id="txtSupCen" placeholder="Ingrese" name="txtSupCen" required>
          <br>
    </div>
    <div class="col">
    <label for=""><b>Superior Derecha</b></label><br>
          <select class="form-control" name="supDer" id="supDer" required></select>
          <br>
          <input class="form-control" type="text" id="txtSupDer" placeholder="Ingrese" name="txtSupDer" required>
          <br>
    </div>
  </div>
<!-- Sección Central -->
  <div class="row">
    <div class="col">
    <label for=""><b>Centro Izquierda</b></label><br>
          <select class="form-control" name="cenIzq" id="cenIzq" required></select>
          <br>
          <input class="form-control" type="text" id="txtCenIzq" placeholder="Ingrese" name="txtCenIzq" required>
          <br>
    </div>
    <div class="col">
    <label for=""><b>Centro</b></label><br>
          <select class="form-control" name="cen" id="centro" required></select>
          <br>
          <input class="form-control" type="text" id="txtCentro" placeholder="Ingrese" name="txtCentro" required>
          <br>
    </div>
    <div class="col">
    <label for=""><b>Centro Derecha</b></label><br>
          <select class="form-control" name="cenDer" id="cenDer" required></select>
          <br>
          <input class="form-control" type="text" id="txtCenDer" placeholder="Ingrese" name="txtCenDer" required>
          <br>
    </div>
  </div>
<!-- Sección Inferior -->
  <div class="row">
    <div class="col">
    <label for=""><b>Inferior Izquierda</b></label><br>
          <select class="form-control" name="infIzq" id="infIzq" required></select>
          <br>
          <input class="form-control" type="text" id="txtInfIzq" placeholder="Ingrese" name="txtInfIzq" required>
          <br>
    </div>
    <div class="col">
    <label for=""><b>Inferior Centro</b></label><br>
          <select class="form-control" name="infCen" id="infCen" required></select>
          <br>
          <input class="form-control" type="text" id="txtInfCen" placeholder="Ingrese" name="txtInfCen" required>
          <br>
    </div>
    <div class="col">
    <label for=""><b>Inferior Derecha</b></label><br>
    <select class="form-control" name="infDer" id="infDer" required></select>
          <br>
          <input class="form-control" type="text" id="txtInfDer" placeholder="Ingrese" name="txtInfDer" required>
          <br>
    </div>
  </div>  
          <div id="avisoAgregar"> </div>
          <br>
          <button class="btn btn-light"
            style="margin:1%; border-color:#607d8b; color: black; background-color:#607d8b57;" type="button" data-id=""
            id="btnNuevaMatriz">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Matriz-->

<?php include ('includes/footer.php')?>

<script>
$(document).ready(function(){
    getFProducto();  
    getFTipoExi();
});

//Select automáticos
function getFProducto() {
  var parametros = {
    "action": "getFProducto"
  }
  $.ajax({
    url: 'visitasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#selProducto").html(data);
    }
  });
}

function getFTipoExi() {
  var parametros = {
    "action": "getFTipoExi"
  }
  $.ajax({
    url: 'visitasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#selTipoExi").html(data);
    }
  });
}

//GUARDAR datos para sesiones
$("#btnGuardar").click(function(){
  var selProducto = $('#selProducto').val();
  var selTipoExi = $('#selTipoExi').val();
  var selExistencia = $('#selExistencia').val();
  var selPrecio = $('#selPrecio').val();
  var selFrentes = parseInt( $('#selFrentes').val());

  if(selProducto != "" && selTipoExi != "" && selExistencia != "" && selPrecio != "" && selFrentes >= 0){

    $("#mns").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito");
    var parametros = {
        "action": "guardarFichero",
        "selProducto": selProducto,
        "selTipoExi": selTipoExi,
        "selExistencia":selExistencia,  
        "selPrecio":selPrecio,
        "selFrentes":selFrentes
        
      }
      $.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
        }
      }).done(function(){
        document.getElementById("selProducto").value = "";
        document.getElementById("selTipoExi").value = "";
        document.getElementById("selExistencia").value = "0";
        document.getElementById("selPrecio").value = "";
        document.getElementById("selFrentes").value = "";
        $("#btnFinalizar").removeAttr('disabled');
      });
  }else{
    $("#mns").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos");
      }
    });

//función para mostrar datos en el tbody de la tabla
function finalFichero1(){
  var parametros={
    "action":"finalFichero1"
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#tablaDatosG").html(data);
    }
  });
}

function finalFichero2(){
  var parametros={
    "action":"finalFichero2"
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#tablaFinal").html(data);
    }
  });
}

$("#btnFinalizar").click(function(){
  var selProducto = $('#selProducto').val();
  var selTipoExi = $('#selTipoExi').val();
  var selExistencia = $('#selExistencia').val();
  var selPrecio = $('#selPrecio').val();
  var selFrentes = parseInt( $('#selFrentes').val());

  var parametros = {
        "action": "finalFichero2",
        "selProducto": selProducto,
        "selTipoExi": selTipoExi,
        "selExistencia":selExistencia,  
        "selPrecio":selPrecio,
        "selFrentes":selFrentes
      };
      $.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
          console.log(data);
          finalFichero2();
          finalFichero1();
        }
      });
    });

//redirecciona a visita después de cerrar pedido
function redireccionarPagina() {
window.location = "visitas.php";
}

//CONFIRMACIÓN datos para sesiones
$("#btnConfirmar").click(function(){
  var selProducto = $('#selProducto').val();
  var selTipoExi = $('#selTipoExi').val();
  var selExistencia = $('#selExistencia').val();
  var selPrecio = $('#selPrecio').val();
  var selFrentes = $('#selFrentes').val();

  $("#mns2").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito");
  var parametros = {
        "action": "confirmarFichero",
        "selProducto": selProducto,
        "selTipoExi": selTipoExi,
        "selExistencia":selExistencia,  
        "selPrecio":selPrecio,
        "selFrentes":selFrentes
      }
      $.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
          console.log(data);
        }
      }).done(function(){
        document.getElementById("mns").innerHTML = "";
        $('#btnConfirmar').hide();
        setTimeout("redireccionarPagina()", 1000);
      });
    });

//limpiar avisos
$(document).on("click", "#btnCerrarF", function(){
  $('#mns2').html("");
  $('#btnConfirmar').show();
});


</script>
