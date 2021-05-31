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
      <h2>Visitas</h2>
    </div>
  </div>
</div>

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
      <div class="btn-group" role="group">
        <button id="filtroVendedores" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Vendedor
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroVendedor">
        </div>
      </div> 
      <div class="btn-group" role="group">
        <button id="filtroZonas" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-filter"></i> Zona
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroZona">
        </div>
      </div> 
    </div>
    <div class="col-2">
    <label for="">Desde:</label>
      <input type="date" class="form-control" id="fechaInicio" onchange="load()" onclick="aparecerCalendario()">
    </div>
    <div class="col-2">
    <label for="">Hasta:</label>
      <input type="date" class="form-control" id="fechaFin" onchange="load()" onclick="aparecerCalendario()">
    </div>
    <div class="col">
    <i class="far fa-calendar-times" id="calendario" onclick="desaparecerCalendario()"></i>
    </div></div>
</div>
<br>
<!--Fin Filtrados Búsqueda -->

<!-- Tabla visita con campos principales-->
<div class="container">
<div class="row">
<div class="col">
<table class="table">
  <thead>
    <tr>
      <th>Vendedor</th>
      <th>Zona</th>
      <th>Punto Venta</th>
      <th>Fecha</th>
      <th>Ver</th>
    </tr>
  </thead>
  <tbody id="tablaPVisitas">
  </tbody>
</table>
</div>
</div>
<div class="row">
<div class="col">
<div style="display: flex; align-items: center; justify-content: center;">
<br><br>
<nav aria-label="Page navigation example" id="pagVisita"></nav></div>
</div>
</div>
</div>
<!-- FIN Tabla Visita -->

<!-- Modal Detalles-->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width:130%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetallesLabel" style="color:#607d8b">Detalles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarDetalles">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <label for="" id="detallesVisita"></label>
      <table class="table">
           <thead>
               <tr>
                   <th>Producto</th>
                   <th>Exhibición</th>
                   <th>Existencia</th>
                   <th>Precio</th>
                   <th>Frentes</th>
                   <th>Nivel</th>
                   <th>Ver</th>
               </tr>
           </thead>
           <tbody id="tablaDetalles">
           </tbody>
       </table> 
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Detalles-->

<!-- Modal Evidencia-->
<div class="modal fade" id="modalEvidencia" tabindex="-1" aria-labelledby="modalEvidenciaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width:125%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEvidenciaLabel" style="color:#607d8b">Evidencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarEvidencia">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <table class="table table-borderless">
           <thead>
               <tr>
                   <th>Producto seleccionado</th>
                   <th>Frentes</th>
                   <th>Precio</th>
                   <th>Fecha</th>
                   <th>Evidencia</th>
               </tr>
           </thead>
           <tbody id="evidenciaVisita">
           </tbody>
       </table>
       </div>
    </div>
  </div>
</div>
<!-- FIN Modal Evidencia-->

<!-- Modal Matriz-->
<div class="modal fade" id="modalMatriz" tabindex="-1" aria-labelledby="modalMatrizLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width:120%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalMatrizLabel" style="color:#607d8b">Matriz</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCerrarMatriz">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      <table class="table">
           <thead>
               <tr>
                   <th style="width:35%; max-width:35%;">Sup Izq</th>
                   <th style="width:35%; max-width:35%;">Sup Centro</th>
                   <th style="width:35%; max-width:35%;">Sup Der</th>
               </tr>
           </thead>
           <tbody id="tablaMatrizSup">
           </tbody>
       </table> 
       <table class="table">
           <thead>
               <tr>
                   <th style="width:35%; max-width:35%;">Cen Izq</th>
                   <th style="width:35%; max-width:35%;">Cen Centro</th>
                   <th style="width:35%; max-width:35%;">Cen Der</th>
               </tr>
           </thead>
           <tbody id="tablaMatrizCen">
           </tbody>
       </table>
       <table class="table">
           <thead>
               <tr>
                   <th style="width:35%; max-width:35%;">Inf Izq</th>
                   <th style="width:35%; max-width:35%;">Inf Centro</th>
                   <th style="width:35%; max-width:35%;">Inf Der</th>
               </tr>
           </thead>
           <tbody id="tablaMatrizInf">
           </tbody>
       </table>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal Matriz-->

<?php include ('includes/footer.php')?>

<script>
getPVentaFiltro("#filtroPuntoVenta");
getVendedorFiltro("#filtroVendedor");
getZonaFiltro("#filtroZona");

$(document).ready(function () {
  load('');
  getPVentaFiltro();
  getVendedorFiltro();
  getZonaFiltro();
  $('#calendario').hide();
});

//función para mostrar datos en el tbody de la tabla
//llamamos paginación y filtros
function load(page, idPuntoVenta, fechaInicio, fechaFin, idVendedor, idZona) {
  var idPuntoVenta = $("#filtroPVenta").attr("data-visitBusc");
  getPVentaFiltro(idPuntoVenta);
  var idVendedor = $("#filtroVendedores").attr("data-vendeBusc");
  getVendedorFiltro(idVendedor);
  var idZona = $("#filtroZonas").attr("data-zonaBusc");
  getZonaFiltro(idZona);
  var fechaInicio = $("#fechaInicio").val();
  var fechaFin = $("#fechaFin").val();
  
  var parametros = {
    "action": "getPVisitas",
    "page": page,
    "idPuntoVenta": idPuntoVenta,
    "fechaInicio": fechaInicio,
    "fechaFin": fechaFin,
    "idVendedor": idVendedor,
    "idZona": idZona
  }
  $.ajax({
    data: parametros,
    url: 'visitasAjax.php',
    success: function (data) {
      data = jQuery.parseJSON(data);
      console.log(data);
      $("#tablaPVisitas").html(data.visitas);
      $("#pagVisita").html(data.pagination);
    }
  });
}

//filtros de búsqueda
function getPVentaFiltro(filtro) {
  var parametros = {
    "action": "getPVentaFiltro"
  }
  $.ajax({
    url: "visitasAjax.php",
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
    url: "visitasAjax.php",
    data: parametros,
    success: function (data) {
      $(filtro).html(data);
    }
  });
}

function getZonaFiltro(filtro) {
  var parametros = {
    "action": "getZonaFiltro"
  }
  $.ajax({
    url: "visitasAjax.php",
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

$(document).on("click", ".opcFilVendedor", function () {
  var vendedorBusc = $(this).attr('data-id');
  var idVendedor = $(this).attr('data-tipVende');
  $("#filtroVendedores").attr("data-vendeBusc", vendedorBusc);
  load();
  if ($("#buscVende").length) {
    $("#buscVende").remove();
  }
  if (vendedorBusc)
    $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscVende">' + idVendedor + ' <i class="far fa-times-circle"></i></a>');
});

$(document).on("click", ".opcFilZona", function () {
  var zonaBusc = $(this).attr('data-id');
  var idZona = $(this).attr('data-tipZona');
  $("#filtroZonas").attr("data-zonaBusc", zonaBusc);
  load();
  if ($("#buscZona").length) {
    $("#buscZona").remove();
  }
  if (zonaBusc)
    $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscZona">' + idZona + ' <i class="far fa-times-circle"></i></a>');
});

//limpiar avisos
$(document).on("click", "#buscPVenta", function() {
  $("#filtroPVenta").attr('data-visitBusc', '');
  $("#buscPVenta").remove();
  load();
});

$(document).on("click", "#buscVende", function() {
  $("#filtroVendedores").attr('data-vendeBusc', '');
  $("#buscVende").remove();
  load();
});

$(document).on("click", "#buscZona", function() {
  $("#filtroZonas").attr('data-zonaBusc', '');
  $("#buscZona").remove();
  load();
});

$(document).on("click", "#calendario", function() {
  $("#fechaInicio").val("");
  $("#fechaFin").val("");
  load();
});

//modal detalles, idVisita tomara solo 1 fila
$(document).on("click", "#btnDetalleModal", function() {
    var idVisita =  $(this).attr("data-id");
    getDetalles(idVisita);
    getDetallesVisita(idVisita);
});

function getDetalles(idVisita){
  var parametros={
    "action":"getDetalles",
    "idVisita": idVisita
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#tablaDetalles").html(data);
    }
  });
}

//imágenes evidencia
$(document).on("click", "#btnEvidenciaModal", function() {
    var idVisita =  $(this).attr("data-id");
    
    getDetallesEvidencia(idVisita);
});

function getDetallesEvidencia(idVisita){
  var parametros={
    "action":"getEvidencia",
    "idVisita": idVisita
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#evidenciaVisita").html(data);
    }
  });
}

//detalles tabla principal
function getDetallesVisita(idVisita){
  var parametros={
    "action":"getDetallesVisita",
    "idVisita": idVisita
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#detallesVisita").html(data);
    }
  });
}

//modal matriz
$(document).on("click", "#btnMatrizModal", function() {
    var idDetallesVisita =  $(this).attr("data-id");
    getDetalleMatrizSup(idDetallesVisita);
    getDetalleMatrizCen(idDetallesVisita);
    getDetalleMatrizInf(idDetallesVisita);
});

function getDetalleMatrizSup(idDetallesVisita){
  var parametros={
    "action":"getDetalleMatrizSup",
    "idDetallesVisita": idDetallesVisita
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#tablaMatrizSup").html(data);
    }
  });
}

function getDetalleMatrizCen(idDetallesVisita){
  var parametros={
    "action":"getDetalleMatrizCen",
    "idDetallesVisita": idDetallesVisita
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#tablaMatrizCen").html(data);
    }
  });
}

function getDetalleMatrizInf(idDetallesVisita){
  var parametros={
    "action":"getDetalleMatrizInf",
    "idDetallesVisita": idDetallesVisita
  }
  $.ajax({
    data:parametros,
    url:'visitasAjax.php',
    success: function(data){
      $("#tablaMatrizInf").html(data);
    }
  });
}

function aparecerCalendario(){
  $('#calendario').show();
}

function desaparecerCalendario(){
  $('#calendario').hide();
}

</script>

<?php
} //cierre llave else SESSION
?>
