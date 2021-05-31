<?php
session_start();

include('includes/header.php');
include('includes/menu.php');

if($_SESSION["tipoUsuario"]!="Vendedor"){
  echo '<script type="text/javascript">alert("Inicie sesión nuevamente.");</script>';
  echo '<script type="text/javascript">onload=window.location="index.php";</script>';
}else{
?>

<style>
#btnGuardar, 
#btnFinalizar,
#btnConfirmar,
#btnNuevaMatriz,
#btnEditarMatriz{
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
#btnNuevaMatriz:hover,
#btnEditarMatriz:hover{
  opacity: 0.8;
}

.matrix {
  border: .1px solid #dedede;
  border-spacing: 5px;
}
</style>

<!-- selects principal -->
<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-8">
<div style="display: flex; align-items: center; justify-content: center;">
    </div>
    <div class="row">
    <div class="col-6">
    <br>
    <label for=""><b>Producto</b></label>
    <select name="producto" id="selProducto" class="form-control" required></select> 
    <br></div>
    <div class="col-6">
    <br>
    <label for=""><b>Nuevo Producto</b></label><br>
    <button type="button" class="btn btn-light" style="margin-top:1px; width:100%;" id="btnAddProducto" data-toggle="modal"
        data-target="#modalNvoProducto">Agregar <i class="fas fa-plus"></i></button>
    </div>
    </div>
    <div class="row">
    <div class="col-6">
    <label for=""><b>Tipo de Exhibición</b></label><br>
    <select name="" id="selTipoExi" class="form-control" required></select> 
    <br></div>
    <div class="col-6">
    <label for=""><b>Existencia</b></label><br>
    <select name="selector" id="selExistencia" class="form-control" required>
    <option value="">¿Está en existencia el producto?</option>
    <option value="Si">Sí</option>
    <option value="No">No</option>
    </select> 
    </div>
    </div>

    <div class="row">
    <div class="col-6">
    <label for=""><b>Precio</b></label>
    <input type="text" class="form-control" name="precio" id="selPrecio" placeholder='100.00' required>
    <br></div>
    <div class="col-6">
    <label for=""><b>Frentes</b></label>
    <input type="number" class="form-control" name="frentes" id="selFrentes" min="0" required>
    </div>
    </div>

    <div class="row">
    <div class="col-6">
    <label for=""><b>Nivel</b></label><br>
    <select name="selector" id="selNivel" class="form-control" required>
    <option value="">Seleccione el nivel de su producto</option>
    <option value="5">5</option>
    <option value="4">4</option>
    <option value="3">3</option>
    <option value="2">2</option>
    <option value="1">1</option>
    </select> 
    <br></div>
    <div class="col-6">
    <label for=""><b>Matriz</b></label><br>
    <button type="button" class="btn btn-light" style="margin-top:1px; width:100%;" data-toggle="modal"
    data-target="#modalMatriz" id="btnMatriz">Agregar <i class="fab fa-buromobelexperte"></i></button>
    </div>
    </div>
    <label for=""><b>Img Evidencia</b></label><br>
    <input type="file" name="imagen" id="imgEvidencia">
    <br><br>  
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
        <h5 class="modal-title" id="modalFinalizarLabel" style="color:#607d8b">Visita</h5>
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
                   <th>Nivel</th>
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
          <label for="">Seleccione o ingrese el producto según sea su posición</label>
          <br><br>
          <!-- Sección Superior -->
          <div class="row">
            <div class="col matrix">
              <label for="">Sup Izq</label><br>
              <select class="form-control" name="supIzq" id="supIzq" required></select>
              <br>
              <input class="form-control" type="text" id="txtSupIzq" placeholder="Ingrese" name="txtSupIzq" required>
              <br>
            </div>
            <div class="col matrix">
              <label for="">Sup Centro</label><br>
              <select class="form-control" name="supCen" id="supCen" required></select>
              <br>
              <input class="form-control" type="text" id="txtSupCen" placeholder="Ingrese" name="txtSupCen" required>
              <br>
            </div>
            <div class="col matrix">
              <label for="">Sup Der</label><br>
              <select class="form-control" name="supDer" id="supDer" required></select>
              <br>
              <input class="form-control" type="text" id="txtSupDer" placeholder="Ingrese" name="txtSupDer" required>
              <br>
            </div>
          </div>
          <!-- Sección Central -->
          <div class="row">
            <div class="col matrix">
              <label for="">Izquierda</label><br>
              <select class="form-control" name="cenIzq" id="cenIzq" required></select>
              <br>
              <input class="form-control" type="text" id="txtCenIzq" placeholder="Ingrese" name="txtCenIzq" required>
              <br>
            </div>
            <div class="col matrix">
              <label for="">Centro</label><br>
              <select class="form-control" name="cen" id="centro" required></select>
              <br>
              <input class="form-control" type="text" id="txtCentro" placeholder="Ingrese" name="txtCentro" required>
              <br>
            </div>
            <div class="col matrix">
              <label for="">Derecha</label><br>
              <select class="form-control" name="cenDer" id="cenDer" required></select>
              <br>
              <input class="form-control" type="text" id="txtCenDer" placeholder="Ingrese" name="txtCenDer" required>
              <br>
            </div>
          </div>
          <!-- Sección Inferior -->
          <div class="row matrix">
            <div class="col matrix">
              <label for="">Inf Izq</label><br>
              <select class="form-control" name="infIzq" id="infIzq" required></select>
              <br>
              <input class="form-control" type="text" id="txtInfIzq" placeholder="Ingrese" name="txtInfIzq" required>
              <br>
            </div>
            <div class="col matrix">
              <label for="">Inf Centro</label><br>
              <select class="form-control" name="infCen" id="infCen" required></select>
              <br>
              <input class="form-control" type="text" id="txtInfCen" placeholder="Ingrese" name="txtInfCen" required>
              <br>
            </div>
            <div class="col matrix">
              <label for="">Inf Der</label><br>
              <select class="form-control" name="infDer" id="infDer" required></select>
              <br>
              <input class="form-control" type="text" id="txtInfDer" placeholder="Ingrese" name="txtInfDer" required>
              <br>
            </div>
          </div>
          <div id="avisoMatriz"> </div>
          <br>
          <button class="btn btn-outline-success" style="margin:1%;" type="button" data-id=""
            id="btnNuevaMatriz">Agregar <i class="far fa-save"></i></button>
            <button class="btn btn-outline-success" style="margin:1%;" type="button" data-id=""
            id="btnEditarMatriz">Editar <i class="far fa-save"></i></button>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Matriz-->

<!-- Modal Agregar Producto-->
<div class="modal fade" id="modalNvoProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"  id="btnCerrarAddPro">
            <span aria-hidden="true">&times;</span>
          </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="nombreProd">Nombre</label>
                                        <input type="text" class="form-control" id="nombreProd">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="categoriaProd">Categoria</label>
                                        <select id="categoriaProd" class="form-control">   
                                          </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="procedenciaProd">Procedencia</label>
                                        <select id="procedenciaProd" class="form-control">
                                            <option value="Propio">Propio</option>
                                            <option value="Competencia">Competencia</option>
                                          </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="segmentoProd">Segmento</label>
                                        <select id="segmentoProd" class="form-control">
                                            <option value="Alto">Alto</option>
                                            <option value="Medio">Medio</option>
                                            <option value="Bajo">Bajo</option>
                                          </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="conteoProd">Conteo</label>
                                        <input type="number" min="1" class="form-control" id="conteoProd">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="precioProd">Precio</label>
                                        <input type="number" min="0" class="form-control" id="precioProd">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="precioProd">Imagen</label>
                                        <input type="file" class="form-control-file" id="imgProd" lang="es">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-10 text-center p-2 rounded" id="msjAgregarProd">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCerrarAddPro">Cerrar</button>
                    <button type="button" id="btnAgregarProd" class="btn btn-outline-success">Agregar <i class="far fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
<!--Fin Modal Agregar Producto-->

<?php include ('includes/footer.php')?>

<script>
$(document).ready(function(){
    getFProducto(); 
    getNProducto(); 
    getFTipoExi();
    getCategoria();
    $('#btnEditarMatriz').hide();
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

function getNProducto() {
  var parametros = {
    "action": "getNProducto"
  }
  $.ajax({
    url: 'visitasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#supIzq").html(data);
      $("#supCen").html(data);
      $("#supDer").html(data);
      $("#cenIzq").html(data);
      $("#centro").html(data);
      $("#cenDer").html(data);
      $("#infIzq").html(data);
      $("#infCen").html(data);
      $("#infDer").html(data);
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
  var selNivel = $('#selNivel').val();
  var img = $("#imgEvidencia")[0].files[0];
  var imagen = $('#imgEvidencia').val();

  //parte imagen
  if(imagen == ""){
        console.log("Imagen no seleccionada");
          var evidencia = new FormData();
          evidencia.append("action", "guardarFichero");
          evidencia.append("selProducto", selProducto);
          evidencia.append("selTipoExi", selTipoExi);
          evidencia.append("selExistencia", selExistencia);
          evidencia.append("selPrecio", selPrecio);
          evidencia.append("selFrentes", selFrentes);
          evidencia.append("selNivel", selNivel);
      }else{
        var extension = img.name.substring(img.name.lastIndexOf("."));
        console.log(extension);
        if(extension != ".png" && extension != ".jpg"){
          console.log("Error de formato");
        }else{
          var evidencia = new FormData();
          evidencia.append("action", "guardarFichero");
          evidencia.append("img", img);
          evidencia.append("selProducto", selProducto);
          evidencia.append("selTipoExi", selTipoExi);
          evidencia.append("selExistencia", selExistencia);
          evidencia.append("selPrecio", selPrecio);
          evidencia.append("selFrentes", selFrentes);
          evidencia.append("selNivel", selNivel);
        }
      }

  if(selProducto != "" && selTipoExi != "" && selExistencia != "" && selPrecio != "" && selFrentes >= 0 && selNivel != ""){
      $.ajax({
        data: evidencia,
        url:'visitasAjax.php',
        type: 'POST',
        contentType: false,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        success:function(data){
        $("#mns").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito").delay(100).fadeIn(200);
        $("#mns").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito").delay(1000).fadeOut(200);
        $('#selProducto').val("");
        $('#selTipoExi').val("");
        $('#selExistencia').val("");
        $('#selPrecio').val("");
        $('#selFrentes').val("");
        $('#selNivel').val("");
        $('#imgEvidencia').val("");
        $("#btnFinalizar").removeAttr('disabled');
        $('#supIzq').val("");
        $('#supCen').val("");
        $('#supDer').val("");
        $('#cenIzq').val("");
        $('#centro').val("");
        $('#cenDer').val("");
        $('#infIzq').val("");
        $('#infCen').val("");
        $('#infDer').val("");
        $('#txtSupIzq').val("");
        $('#txtSupCen').val("");
        $('#txtSupDer').val("");
        $('#txtCenIzq').val("");
        $('#txtCentro').val("");
        $('#txtCenDer').val("");
        $('#txtInfIzq').val("");
        $('#txtInfCen').val("");
        $('#txtInfDer').val("");
        $('#btnNuevaMatriz').show();
        $('#btnEditarMatriz').hide();
        }
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
  var selNivel = $('#selNivel').val();

  var parametros = {
        "action": "finalFichero2",
        "selProducto": selProducto,
        "selTipoExi": selTipoExi,
        "selExistencia":selExistencia,  
        "selPrecio":selPrecio,
        "selFrentes":selFrentes,
        "selNivel":selNivel
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
  var selNivel = $('#selNivel').val();
  var supIzq = $('#supIzq').val();
  var supCen = $('#supCen').val();
  var supDer = $('#supDer').val();
  var cenIzq = $('#cenIzq').val();
  var centro = $('#centro').val();
  var cenDer = $('#cenDer').val();
  var infIzq = $('#infIzq').val();
  var infCen = $('#infCen').val();
  var infDer = $('#infDer').val();
  var txtSupIzq = $('#txtSupIzq').val();
  var txtSupCen = $('#txtSupCen').val();
  var txtSupDer = $('#txtSupDer').val();
  var txtCenIzq = $('#txtCenIzq').val();
  var txtCentro = $('#txtCentro').val();
  var txtCenDer = $('#txtCenDer').val();
  var txtInfIzq = $('#txtInfIzq').val();
  var txtInfCen = $('#txtInfCen').val();
  var txtInfDer = $('#txtInfDer').val();

  $("#mns2").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito");
  var parametros = {
        "action": "confirmarFichero",
        "selProducto": selProducto,
        "selTipoExi": selTipoExi,
        "selExistencia":selExistencia,  
        "selPrecio":selPrecio,
        "selFrentes":selFrentes,
        "selNivel":selNivel,
        "supIzq": (supIzq || txtSupIzq),
        "supCen": (supCen || txtSupCen),
        "supDer": (supDer || txtSupDer),
        "cenIzq": (cenIzq || txtCenIzq),
        "centro": (centro || txtCentro),
        "cenDer": (cenDer || txtCenDer),
        "infIzq": (infIzq || txtInfIzq),
        "infCen": (infCen || txtInfCen),
        "infDer": (infDer || txtInfDer)
      }
      $.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
          console.log(data);
        $('#mns').html("");
        $('#btnConfirmar').hide();
        // setTimeout("redireccionarPagina()", 1500);
        }
      });
    });

//MATRIZ guardar datos
$("#btnNuevaMatriz").click(function(x){
  var supIzq = $('#supIzq').val();
  var supCen = $('#supCen').val();
  var supDer = $('#supDer').val();
  var cenIzq = $('#cenIzq').val();
  var centro = $('#centro').val();
  var cenDer = $('#cenDer').val();
  var infIzq = $('#infIzq').val();
  var infCen = $('#infCen').val();
  var infDer = $('#infDer').val();
  var txtSupIzq = $('#txtSupIzq').val();
  var txtSupCen = $('#txtSupCen').val();
  var txtSupDer = $('#txtSupDer').val();
  var txtCenIzq = $('#txtCenIzq').val();
  var txtCentro = $('#txtCentro').val();
  var txtCenDer = $('#txtCenDer').val();
  var txtInfIzq = $('#txtInfIzq').val();
  var txtInfCen = $('#txtInfCen').val();
  var txtInfDer = $('#txtInfDer').val();
  var prodPrincipal = $('#selProducto').val();
  var idSupIzq = $('#supIzq>option:selected').data("id");
  var idSupCen = $('#supCen>option:selected').data("id");
  var idSupDer = $('#supDer>option:selected').data("id");
  var idCenIzq = $('#cenIzq>option:selected').data("id");
  var idCentro = $('#centro>option:selected').data("id");
  var idCenDer = $('#cenDer>option:selected').data("id");
  var idInfIzq = $('#infIzq>option:selected').data("id");
  var idInfCen = $('#infCen>option:selected').data("id");
  var idInfDer = $('#infDer>option:selected').data("id");

  if((supIzq != "" && txtSupIzq != "") || (supCen != "" && txtSupCen != "") || (supDer != "" && txtSupDer != "") || (cenIzq != "" && txtCenIzq != "") || (centro != "" && txtCentro != "") || (cenDer != "" && txtCenDer != "") ||
     (infIzq != "" && txtInfIzq != "") || (infCen != "" && txtInfCen != "") || (infDer != "" && txtInfDer != "")){
        $("#avisoMatriz").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> Sólo 1 producto por sección");
      }else{

  if(((supIzq == "" && txtSupIzq != "") || (supIzq != "" && txtSupIzq == "" )) && ((supCen == "" && txtSupCen != "") || (supCen != "" && txtSupCen == "" )) && ((supDer == "" && txtSupDer != "") || (supDer != "" && txtSupDer == "" )) &&
     ((cenIzq == "" && txtCenIzq != "") || (cenIzq != "" && txtCenIzq == "" )) && ((centro == "" && txtCentro != "") || (centro != "" && txtCentro == "" )) && ((cenDer == "" && txtCenDer != "") || (cenDer != "" && txtCenDer == "" )) &&
     ((infIzq == "" && txtInfIzq != "") || (infIzq != "" && txtInfIzq == "" )) && ((infCen == "" && txtInfCen != "") || (infCen != "" && txtInfCen == "" )) && ((infDer == "" && txtInfDer != "") || (infDer != "" && txtInfDer == "" ))){
 
  if(idSupIzq != prodPrincipal && idSupCen != prodPrincipal && idSupDer != prodPrincipal && idCenIzq != prodPrincipal && idCentro != prodPrincipal && 
     idCenDer != prodPrincipal && idInfIzq != prodPrincipal && idInfCen != prodPrincipal && idInfDer != prodPrincipal){
        $("#avisoMatriz").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> No se hace referencia al producto seleccionado");
    }else{

    $("#avisoMatriz").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito");
    var parametros = {
        "action": "guardarMatriz",
        "supIzq": (supIzq || txtSupIzq),
        "supCen": (supCen || txtSupCen),
        "supDer": (supDer || txtSupDer),
        "cenIzq": (cenIzq || txtCenIzq),
        "centro": (centro || txtCentro),
        "cenDer": (cenDer || txtCenDer),
        "infIzq": (infIzq || txtInfIzq),
        "infCen": (infCen || txtInfCen),
        "infDer": (infDer || txtInfDer)
      }
      $.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
          $('#btnEditarMatriz').show();
          $('#btnNuevaMatriz').hide();
          console.log(data);
        }
      });
    }
  }else{
    $("#avisoMatriz").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos");
      }
    }
  });

//MATRIZ editar datos
$("#btnEditarMatriz").click(function(x){
  var supIzq = $('#supIzq').val();
  var supCen = $('#supCen').val();
  var supDer = $('#supDer').val();
  var cenIzq = $('#cenIzq').val();
  var centro = $('#centro').val();
  var cenDer = $('#cenDer').val();
  var infIzq = $('#infIzq').val();
  var infCen = $('#infCen').val();
  var infDer = $('#infDer').val();
  var txtSupIzq = $('#txtSupIzq').val();
  var txtSupCen = $('#txtSupCen').val();
  var txtSupDer = $('#txtSupDer').val();
  var txtCenIzq = $('#txtCenIzq').val();
  var txtCentro = $('#txtCentro').val();
  var txtCenDer = $('#txtCenDer').val();
  var txtInfIzq = $('#txtInfIzq').val();
  var txtInfCen = $('#txtInfCen').val();
  var txtInfDer = $('#txtInfDer').val();
  var prodPrincipal = $('#selProducto').val();
  var idSupIzq = $('#supIzq>option:selected').data("id");
  var idSupCen = $('#supCen>option:selected').data("id");
  var idSupDer = $('#supDer>option:selected').data("id");
  var idCenIzq = $('#cenIzq>option:selected').data("id");
  var idCentro = $('#centro>option:selected').data("id");
  var idCenDer = $('#cenDer>option:selected').data("id");
  var idInfIzq = $('#infIzq>option:selected').data("id");
  var idInfCen = $('#infCen>option:selected').data("id");
  var idInfDer = $('#infDer>option:selected').data("id");

  if((supIzq != "" && txtSupIzq != "") || (supCen != "" && txtSupCen != "") || (supDer != "" && txtSupDer != "") || (cenIzq != "" && txtCenIzq != "") || (centro != "" && txtCentro != "") || (cenDer != "" && txtCenDer != "") ||
     (infIzq != "" && txtInfIzq != "") || (infCen != "" && txtInfCen != "") || (infDer != "" && txtInfDer != "")){
        $("#avisoMatriz").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> Sólo 1 producto por sección");
      }else{

  if(((supIzq == "" && txtSupIzq != "") || (supIzq != "" && txtSupIzq == "" )) && ((supCen == "" && txtSupCen != "") || (supCen != "" && txtSupCen == "" )) && ((supDer == "" && txtSupDer != "") || (supDer != "" && txtSupDer == "" )) &&
     ((cenIzq == "" && txtCenIzq != "") || (cenIzq != "" && txtCenIzq == "" )) && ((centro == "" && txtCentro != "") || (centro != "" && txtCentro == "" )) && ((cenDer == "" && txtCenDer != "") || (cenDer != "" && txtCenDer == "" )) &&
     ((infIzq == "" && txtInfIzq != "") || (infIzq != "" && txtInfIzq == "" )) && ((infCen == "" && txtInfCen != "") || (infCen != "" && txtInfCen == "" )) && ((infDer == "" && txtInfDer != "") || (infDer != "" && txtInfDer == "" ))){
 
  if(idSupIzq != prodPrincipal && idSupCen != prodPrincipal && idSupDer != prodPrincipal && idCenIzq != prodPrincipal && idCentro != prodPrincipal && 
     idCenDer != prodPrincipal && idInfIzq != prodPrincipal && idInfCen != prodPrincipal && idInfDer != prodPrincipal){
        $("#avisoMatriz").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> No se hace referencia al producto seleccionado");
    }else{

    $("#avisoMatriz").css("color", "#0f5132").html("<i class='far fa-save'></i> Agregado con Éxito");
    var parametros = {
        "action": "editarMatriz",
        "supIzq": (supIzq || txtSupIzq),
        "supCen": (supCen || txtSupCen),
        "supDer": (supDer || txtSupDer),
        "cenIzq": (cenIzq || txtCenIzq),
        "centro": (centro || txtCentro),
        "cenDer": (cenDer || txtCenDer),
        "infIzq": (infIzq || txtInfIzq),
        "infCen": (infCen || txtInfCen),
        "infDer": (infDer || txtInfDer)
      }
      $.ajax({
        data:parametros,
        url:'visitasAjax.php',
        success:function(data){
          console.log(data);
        }
      });
    }
  }else{
    $("#avisoMatriz").css("color", "red").html("<i class='fas fa-exclamation-triangle'></i> Datos Incorrectos o Vacíos");
      }
    }
  });

//limpiar avisos
$(document).on("click", "#btnCerrarF", function(){
  $('#mns2').html("");
  $('#btnConfirmar').show();
});

$(document).on("click", "#btnCerrarMat", function () {
  $('#avisoMatriz').html("");
});




//Agregar producto
$("#btnAgregarProd").click(function() {
            var nombre = $("#nombreProd").val();
            var categoria = $("#categoriaProd").val();
            var segmento = $("#segmentoProd").val();
            var precio = $("#precioProd").val();
            var conteo = $("#conteoProd").val();
            var procedencia = $("#procedenciaProd").val();
            var img = $("#imgProd")[0].files[0];
            var imagen = $('#imgProd').val();

            if(nombre != "" && categoria != "" && segmento != "" && precio != "" && conteo != "" && imagen != ""){
              var extension = img.name.substring(img.name.lastIndexOf("."));
                console.log(extension);
                if(extension != ".png" && extension != ".jpg"){
                    $("#msjAgregarProd").removeClass("border-success border-danger");
                    $("#msjAgregarProd").html("Formato no permitido <i class='fas fa-exclamation' style = 'color:#ffc107;'></i>");
                    $("#msjAgregarProd").addClass("border border-warning");
                }else{
                    var producto = new FormData();
                    producto.append("action", "agregarProducto");
                    producto.append("nombre", nombre);
                    producto.append("categoria", categoria);
                    producto.append("segmento", segmento);
                    producto.append("conteo", conteo);
                    producto.append("precio", precio);
                    producto.append("procedencia", procedencia);
                    producto.append("img", img);
                    agregarProducto(producto);
                }
            }else{
                    var producto = new FormData();
                    producto.append("action", "agregarProducto");
                    producto.append("nombre", nombre);
                    producto.append("categoria", categoria);
                    producto.append("segmento", segmento);
                    producto.append("conteo", conteo);
                    producto.append("precio", precio);
                    producto.append("procedencia", procedencia);
                    agregarProducto(producto);
            }
        });

        function agregarProducto(producto) {
            $.ajax({
                url: "productosAjax.php",
                data: producto,
                type: 'POST',
                contentType: false,
                enctype: 'multipart/form-data',
                cache: false,
                processData: false,
                success: function(data) {
                    if(data == 1){
                        $("#msjAgregarProd").html("Producto Guardado <i class='fas fa-check-double' style='color:#28a745;'></i>");
                        $("#msjAgregarProd").removeClass("border-warning border-danger");
                        $("#msjAgregarProd").addClass("border border-success");
                        getFProducto(); 
                        getNProducto();
                    }else if(data == 2) {
                        $("#msjAgregarProd").html("Producto repetido <i class='fas fa-exclamation' style = 'color:#ffc107;'></i>");
                        $("#msjAgregarProd").removeClass("border-success border-danger");
                        $("#msjAgregarProd").addClass("border border-warning ");
                    }else{
                        $("#msjAgregarProd").html("Error <i class='fas fa-times' style='color:#dc3545;'></i>");
                        $("#msjAgregarProd").removeClass("border-success border-warning");
                        $("#msjAgregarProd").addClass("border border-danger");
                    }
                }
            });
        }                

function getCategoria() {
  var parametros = {
    "action": "getCategoria"
  }
  $.ajax({
    url: 'visitasAjax.php',
    data: parametros,
    success: function (data) {
      console.log(data);
      $("#categoriaProd").html(data);
    }
  });
}

//limpiar
$(document).on("click", "#btnCerrarAddPro", function () {
            $("#nombreProd").val("");
            $("#categoriaProd").val("");
            $("#segmentoProd").val("");
            $("#precioProd").val("");
            $("#conteoProd").val("");
            $("#procedenciaProd").val("");
            $('#imgProd').val("");
            $('#msjAgregarProd').html("");
            $("#msjAgregarProd").removeClass("border-success border-danger");
});
</script>

<?php
} //cierre llave else SESSION
?>
