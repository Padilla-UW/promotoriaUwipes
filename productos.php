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
            <div class="col-6 col-lg-4">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i> filtrar
            </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <a class="dropdown-item" href="#">Todos</a>
                        <a class="dropdown-item" href="#">Propios</a>
                        <a class="dropdown-item" href="#">Competencia</a>
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
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>

    <script>
    </script>