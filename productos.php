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
            <div class="col-6 col-lg-5">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-globe-americas"></i> Procedencia
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="#">Todos</a>
                        <a class="dropdown-item" href="#">Propios</a>
                        <a class="dropdown-item" href="#">Competencia</a>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i> Categoria
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" id="filtroCategorias">

                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-funnel-dollar"></i> Segmento
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="#">Alto</a>
                        <a class="dropdown-item" href="#">Medio</a>
                        <a class="dropdown-item" href="#">Bajo</a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
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
                            <th scope="col">Precio</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
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
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>

    <script>
        getCategoriasSelect("#categoriaProd");
        getCategoriasFiltro("#filtroCategorias");


        $("#btnAgregarProd").click(function() {
            var nombre = $("#nombreProd").val();
            var categoria = $("#categoriaProd").val();
            var segmento = $("#segmentoProd").val();
            var precio = $("#precioProd").val();
            var conteo = $("#conteoProd").val();
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
                    producto.append("img", img);
                    console.log(producto);
                    agregarProducto(producto);
                }
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
    </script>