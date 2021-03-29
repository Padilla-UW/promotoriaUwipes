<?php
    include 'includes/conection.php';
    $action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if($action == "agregarProducto"){
        $nombre=(isset($_REQUEST['nombre'])&& $_REQUEST['nombre'] !=NULL)?$_REQUEST['nombre']:'';
        $segmento=(isset($_REQUEST['segmento'])&& $_REQUEST['segmento'] !=NULL)?$_REQUEST['segmento']:'';
        $categoria=(isset($_REQUEST['categoria'])&& $_REQUEST['categoria'] !=NULL)?$_REQUEST['categoria']:'';
        $conteo=(isset($_REQUEST['conteo'])&& $_REQUEST['conteo'] !=NULL)?$_REQUEST['conteo']:'';
        $precio=(isset($_REQUEST['precio'])&& $_REQUEST['precio'] !=NULL)?$_REQUEST['precio']:'';
        $procedencia=(isset($_REQUEST['procedencia'])&& $_REQUEST['procedencia'] !=NULL)?$_REQUEST['procedencia']:'';
        mysqli_query($con,'BEGIN');  
        $queryInsertProd = mysqli_query($con,"INSERT INTO producto(idCategoria,nombre,segmento,conteo,precio,procedencia) VALUES ('$categoria','$nombre','$segmento', '$conteo', '$precio', '$procedencia')");
        $idProducto=mysqli_insert_id($con);
        
        if(isset($_FILES['img']) || $_FILES['img']['size']!=0){
            $queryInsertImg = mysqli_query($con,"INSERT INTO imgproducto(idProducto) VALUES ($idProducto)");
            $ultimoId=mysqli_insert_id($con);
            $nombre = $_FILES['img']['name'];
            $nombre_tmp = $_FILES['img']['tmp_name'];
            $partes_nombre = explode('.', $nombre);
            $extension = end($partes_nombre);
            $ruta ="imgProductos/";

            if(move_uploaded_file($nombre_tmp, $ruta.$ultimoId.".".$extension)){
                $nombrenuevo = $ultimoId.".".$extension;
                $rutabd = "imgProductos/".$nombrenuevo;
                $insertRuta = "UPDATE imgproducto SET ruta='$rutabd' WHERE idImgProducto='$ultimoId'";
                $queryUpdateImg = mysqli_query($con, $insertRuta);
                if($queryInsertProd && $queryInsertImg && $queryUpdateImg ){
                    mysqli_query($con,'COMMIT');
                    echo 1;
                }else{
                    mysqli_query($con,'ROLLBACK');
                    echo 0;  
                }
              }else{
                
              }
          }
      

    }elseif($action == "getCategoriasSelect"){
        $categorias = mysqli_query($con,"SELECT * from categoria"); 
         while($categoria = mysqli_fetch_array($categorias)){
            echo "<option value='".$categoria['idCategoria']."'>".$categoria['categoria']."</option>";        
        }  
    }elseif($action == "getProductos"){
        $nombre=(isset($_REQUEST['nombre'])&& $_REQUEST['nombre'] !=NULL)?$_REQUEST['nombre']:'';
        $segmento=(isset($_REQUEST['segmento'])&& $_REQUEST['segmento'] !=NULL)?$_REQUEST['segmento']:'';
        $categoria=(isset($_REQUEST['categoria'])&& $_REQUEST['categoria'] !=NULL)?$_REQUEST['categoria']:'';
        $procedencia=(isset($_REQUEST['procedencia'])&& $_REQUEST['procedencia'] !=NULL)?$_REQUEST['procedencia']:'';

        if($nombre != '' || $segmento != '' || $categoria != '' || $nombre != ''){
            $productos = mysqli_query($con,"SELECT * FROM producto p, categoria c WHERE p.idCategoria = c.idCategoria AND (nombre = '$nombre' || idCategoria = '$categoria' || segmento = '$segmento' || procedencia = '$procedencia')");
        }else{
            $productos = mysqli_query($con,"SELECT * FROM producto p, categoria c WHERE p.idCategoria = c.idCategoria");
        }
        
        while($producto = mysqli_fetch_array($productos)){
            $idProducto= $producto['idProducto'];
            $nombre= $producto['nombre'];
            $categoria = $producto ['categoria'];
            $segmento = $producto['segmento'];
            $procedencia = $producto ['procedencia'];
            $precio = $producto['precio'];
            $conteo = $producto['conteo'];
            echo "<tr>
                    <th>$nombre</th>
                    <td>$categoria</td>
                    <td>$procedencia</td>
                    <td>$segmento</td>
                    <td>$precio</td>
                    <td>$conteo</td>
                    <td><button type='button' data-id='$idProducto' class='btn btn-light editProd' data-toggle='modal' data-target='#editProducModal'><i class='far fa-edit'></i>Editar</button></td>
                    <td><button type='button' data-id='$idProducto' class='btn btn-light'><i class='far fa-image'></i>Imagen</button></td>
                </tr>";
        }
    }elseif($action == "getCategoriasFiltro"){
        $categorias = mysqli_query($con,"SELECT * from categoria"); 
        while($categoria = mysqli_fetch_array($categorias)){
           echo "<a class='dropdown-item' href='#' data-id='".$categoria['idCategoria']."'>".$categoria['categoria']."</a>";         
       } 
    }elseif($action == "getProducto"){
        $idProducto=(isset($_REQUEST['idProducto'])&& $_REQUEST['idProducto'] !=NULL)?$_REQUEST['idProducto']:'';
       $producto = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM producto WHERE idProducto = $idProducto"));
       echo json_encode($producto);

    }





?>