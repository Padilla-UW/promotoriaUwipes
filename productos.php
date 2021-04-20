<?php
    include 'includes/header.php';
    include 'includes/conection.php';
?>
    <div class="container"></div>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Productos</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#agreProducModal">Agregar <i class="fas fa-plus"></i> </button>
            </div>
        </div> <br>
        <div class="row justify-content-between">
            <div class="col-6 col-lg-7" id="filtros">
                <div class="btn-group" role="group">
                    <button id="filtroProcedProd" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-globe-americas"></i> Procedencia
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item opcFilProceProd" data-proceProd="" href="#">Todos</a>
                        <a class="dropdown-item opcFilProceProd" data-proceProd="Propio" href="#">Propios</a>
                        <a class="dropdown-item opcFilProceProd" data-proceProd="Competencia" href="#">Competencia</a>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button id="filtroCateProd" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i> Categoria
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroCategorias">

                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button id="filtroSegmProd" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-funnel-dollar"></i> Segmento
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item opcFilSegmProd" data-segmProd="Alto" href="#">Alto</a>
                        <a class="dropdown-item opcFilSegmProd" data-segmProd="Medio" href="#">Medio</a>
                        <a class="dropdown-item opcFilSegmProd" data-segmProd="Bajo" href="#">Bajo</a>
                    </div>
                </div>

            </div>
            <div class="col-6 col-lg-4">
                <input type="text" class="form-control" id="buscNombreProd" aria-describedby="emailHelp">
            </div>
        </div>
    </div> <br>

    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Procedencia</th>
                            <th scope="col">Segmento</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Conteo</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="tablaProductos">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col ">
                <div class="justify-content-center d-flex" id="paginationProd"></div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Producto-->
    <div class="modal fade" id="agreProducModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnAgregarProd" class="btn btn-outline-success">Agregar <i class="far fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Producto-->
    <div class="modal fade" id="editProducModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-10">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="nombreProdEdit">Nombre</label>
                                        <input type="text" class="form-control" id="nombreProdEdit">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="categoriaProdEdit">Categoria</label>
                                        <select id="categoriaProdEdit" class="form-control">
                                                
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="procedenciaProdEdit">Procedencia</label>
                                        <select id="procedenciaProdEdit" class="form-control">
                                                <option value="Propio">Propio</option>
                                                <option value="Competencia">Competencia</option>
                                              </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="segmentoProdEdit">Segmento</label>
                                        <select id="segmentoProdEdit" class="form-control">
                                                <option value="Alto">Alto</option>
                                                <option value="Medio">Medio</option>
                                                <option value="Bajo">Bajo</option>
                                              </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="conteoProdEdit">Conteo</label>
                                        <input type="number" min="1" class="form-control" id="conteoProdEdit">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="precioProdEdit">Precio</label>
                                        <input type="number" min="0" class="form-control" id="precioProdEdit">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-10 text-center p-2" id="msjEditar">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnEditarProd" class="btn btn-outline-success">Guardar <i class="far fa-save"></i></button>
                </div>
            </div>
        </div>


    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>

    <script>
        getCategoriasSelect("#categoriaProd");
        getCategoriasSelect("#categoriaProdEdit");
        getCategoriasFiltro("#filtroCategorias");
        getProductos();

        $("#btnAgregarProd").click(function() {
            var nombre = $("#nombreProd").val();
            var categoria = $("#categoriaProd").val();
            var segmento = $("#segmentoProd").val();
            var precio = $("#precioProd").val();
            var conteo = $("#conteoProd").val();
            var procedencia = $("#procedenciaProd").val();
            var img = $("#imgProd")[0].files[0];

            if (!nombre || !categoria || !segmento || !precio || !conteo || !img) {
                console.log("LLena todos los datos");
            } else {
                var extension = img.name.substring(img.name.lastIndexOf("."));
                console.log(extension);
                if (extension != ".png" && extension != ".jpg") {
                    console.log("Error de formato");
                } else {
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
            }
        });

        $(".opcFilProceProd").click(function() {
            var procedenciaBusc = $(this).attr('data-proceProd');
            $("#filtroProcedProd").attr('data-proceBusc', procedenciaBusc);
            load();
            if ($("#buscProcedencia").length) {
                $("#buscProcedencia").remove();
            }
            if (procedenciaBusc)
                $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscProcedencia">' + procedenciaBusc + ' <i class="far fa-times-circle"></i></a>');
        });

        $(".opcFilSegmProd").click(function() {
            var segmentoBusc = $(this).attr('data-segmProd');
            $("#filtroSegmProd").attr('data-segmBusc', segmentoBusc);
            load();
            if ($("#buscSegmento").length) {
                $("#buscSegmento").remove();
            }
            if (segmentoBusc)
                $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscSegmento">' + segmentoBusc + ' <i class="far fa-times-circle"></i></a>');
        });

        $("#buscNombreProd").keyup(function() {
            load();
        });
        $(document).on("click", ".opcFilCateProd", function() {
            var categoriaBusc = $(this).attr('data-id');
            var categoria = $(this).attr('data-categoria');
            $("#filtroCateProd").attr("data-cateBusc", categoriaBusc);
            load();
            if ($("#buscCategoria").length) {
                $("#buscCategoria").remove();
            }
            if (categoriaBusc)
                $("#filtros").append('<a class="badge badge-pill badge-secondary" href="#" id="buscCategoria">' + categoria + ' <i class="far fa-times-circle"></i></a>');
        });


        //Eliminar los filtros cuando lo borran en la x
        $(document).on("click", "#buscProcedencia", function() {
            $("#filtroProcedProd").attr('data-proceBusc', '');
            $("#buscProcedencia").remove();
            load();
        });

        $(document).on("click", "#buscCategoria", function() {
            $("#filtroCateProd").attr('data-cateBusc', '');
            $("#buscCategoria").remove();
            load();
        });

        $(document).on("click", "#buscSegmento", function() {
            $("#filtroSegmProd").attr('data-segmBusc', '');
            $("#buscSegmento").remove();
            load();
        });


        //Fin de eliminar los filtros cuando lo borran en la x

        $(document).on("click", ".editProd", function() {
            $("#msjEditar").removeClass("border-success border-warning border-danger");
            $("#msjEditar").html("");
            var idProducto = $(this).attr('data-id');
            $("#btnEditarProd").attr('data-id', idProducto);
            var producto = getProducto(idProducto);
            $("#nombreProdEdit").val(producto.nombre);
            $("#categoriaProdEdit").val(producto.idCategoria);
            $("#procedenciaProdEdit").val(producto.procedencia);
            $("#segmentoProdEdit").val(producto.segmento);
            $("#conteoProdEdit").val(producto.conteo);
            $("#precioProdEdit").val(producto.precio);
        });

        $("#btnEditarProd").click(function() {
            var idProducto = $(this).attr('data-id');
            var nombre = $("#nombreProdEdit").val();
            var categoria = $("#categoriaProdEdit").val();
            var procedencia = $("#procedenciaProdEdit").val();
            var segmento = $("#segmentoProdEdit").val();
            var conteo = $("#conteoProdEdit").val();
            var precio = $("#precioProdEdit").val();

            if (!nombre || !categoria || !procedencia || !segmento || !conteo || !precio) {
                console.log("LLena todos los campos");
            } else {
                var parametros = {
                    "action": "actualizarProd",
                    "idProducto": idProducto,
                    "nombre": nombre,
                    "categoria": categoria,
                    "procedencia": procedencia,
                    "segmento": segmento,
                    "conteo": conteo,
                    "precio": precio
                }
                editarProd(parametros);
            }
        });

        function load(pagina) {
            var procedencia = $("#filtroProcedProd").attr('data-proceBusc');
            var categoria = $("#filtroCateProd").attr("data-cateBusc");
            var segmento = $("#filtroSegmProd").attr('data-segmBusc');
            var nombre = $("#buscNombreProd").val();
            getProductos(pagina, procedencia, categoria, segmento, nombre);
        }

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
                    console.log(data);
                }
            });
        }

        function getCategoriasSelect(select) {
            var parametros = {
                "action": "getCategoriasSelect"
            }
            $.ajax({
                url: "productosAjax.php",
                data: parametros,
                success: function(data) {
                    $(select).html(data);
                }
            });
        }

        function getCategoriasFiltro(filtro) {
            var parametros = {
                "action": "getCategoriasFiltro"
            }
            $.ajax({
                url: "productosAjax.php",
                data: parametros,
                success: function(data) {
                    $(filtro).html(data);
                }
            });
        }

        function getProductos(pagina, procedencia, categoria, segmento, nombre) {
            var parametros = {
                "action": "getProductos",
                "pagina": pagina,
                "procedencia": procedencia,
                "categoria": categoria,
                "segmento": segmento,
                "nombre": nombre
            }
            console.log("Entra a buscar prod");
            console.log(parametros);
            $.ajax({
                url: "productosAjax.php",
                data: parametros,
                success: function(data) {
                    /* console.log(data); */
                    console.log(data);
                    datos = JSON.parse(data);
                    $("#tablaProductos").html(datos.productos);
                    $("#paginationProd").html(datos.pagination);
                }
            });
        }

        function getProducto(idProducto) {
            var producto;
            var parametros = {
                "action": "getProducto",
                "idProducto": idProducto
            }
            $.ajax({
                url: "productosAjax.php",
                data: parametros,
                async: false,
                success: function(data) {
                    producto = JSON.parse(data);
                }
            });
            return producto;
        }

        function editarProd(parametros) {
            $.ajax({
                data: parametros,
                url: "productosAjax.php",
                success: function(data) {
                    console.log(data);
                    if (data == 1) {
                        $("#msjEditar").html("Cambios Guardados");
                        $("#msjEditar").removeClass("border-warning border-danger");
                        $("#msjEditar").addClass("border border-success rounded");
                        load();
                    } else if (data == 3) {
                        $("#msjEditar").html("Producto repetido");
                        $("#msjEditar").removeClass("border-success border-danger");
                        $("#msjEditar").addClass("border border-warning rounded");
                    } else {
                        $("#msjEditar").html("Error");
                        $("#msjEditar").removeClass("border-success border-warning");
                        $("#msjEditar").addClass("border border-danger rounded");
                    }
                }
            });
        }
    </script>