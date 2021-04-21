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
        $repetidos=existeProducto($nombre,'',$con);
        if($repetidos > 0){
            echo 2;
        }else{
            mysqli_query($con,'BEGIN');  
            $queryInsertProd = mysqli_query($con,"INSERT INTO producto(idCategoria,nombre,segmento,conteo,precio,procedencia) VALUES ('$categoria','$nombre','$segmento', '$conteo', '$precio', '$procedencia')");
            $idProducto=mysqli_insert_id($con); 
            $directorio = "imgProductos";
                if (!file_exists($directorio)) {
                     mkdir($directorio, 0777, true);
                }
             
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
                    mysqli_query($con,'ROLLBACK');
                    echo 0;
                  }
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
        $page = (isset($_REQUEST['pagina']) && !empty($_REQUEST['pagina']))?$_REQUEST['pagina']:1;
        
        include 'includes/pagination.php'; 
        $per_page = 8; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;



        if($nombre != '' || $segmento != '' || $categoria != '' || $procedencia != ''){
            if($categoria != '') $sqlCategoria = " AND c.idCategoria = '$categoria'";
            if($procedencia != '')$sqlProcedencia = " AND p.procedencia = '$procedencia'";
            if($segmento != '')$sqlSegmento = " AND p.segmento = '$segmento'";
            if($nombre != '')$sqlNombre = " AND p.nombre LIKE '%$nombre%' ";
            $queryProductos = "SELECT * FROM producto p, categoria c WHERE p.idCategoria = c.idCategoria";
            $queryProductosCount=$queryProductos.$sqlCategoria.$sqlProcedencia.$sqlSegmento.$sqlNombre;
            $queryProductos.=$sqlCategoria.$sqlProcedencia.$sqlSegmento.$sqlNombre." LIMIT $offset,$per_page";
            
            $productos = mysqli_query($con,$queryProductos);
            $productosCount = mysqli_query($con,$queryProductosCount);
            
        }else{
            $productos = mysqli_query($con,"SELECT * FROM producto p, categoria c WHERE p.idCategoria = c.idCategoria LIMIT $offset,$per_page");
            $productosCount = mysqli_query($con,"SELECT * FROM producto p, categoria c WHERE p.idCategoria = c.idCategoria");
        }
        $totalPaginas = mysqli_num_rows($productosCount);
        $totalPaginas = ceil($totalPaginas/$per_page);
        $reload = 'productos.php';
        
        while($producto = mysqli_fetch_array($productos)){
            $idProducto= $producto['idProducto'];
            $nombre= $producto['nombre'];
            $categoria = $producto ['categoria'];
            $segmento = $producto['segmento'];
            $procedencia = $producto ['procedencia'];
            $precio = $producto['precio'];
            $conteo = $producto['conteo'];
            $tablaProductos.= "<tr>
                    <th>$nombre</th>
                    <td>$categoria</td>
                    <td>$procedencia</td>
                    <td>$segmento</td>
                    <td>$precio</td>
                    <td>$conteo</td>
                    <td><button type='button' data-id='$idProducto' class='btn btn-light editProd' data-toggle='modal' data-target='#editProducModal'><i class='far fa-edit'></i>Editar</button></td>
                    <td><button type='button' data-id='$idProducto' data-toggle='modal' data-target='#editImgProdModal' class='btn btn-light editImgProd'><i class='far fa-image'></i>Imagen</button></td>
                </tr>";
        }

       
        $pagination = paginate($reload, $page, $totalPaginas, $adjacents);
        
        $array=array('productos' => $tablaProductos, 'pagination' => $pagination, 'consulta' => $queryProductosCount);
        echo json_encode($array);
    


    }elseif($action == "getCategoriasFiltro"){
        $categorias = mysqli_query($con,"SELECT * from categoria"); 
        while($categoria = mysqli_fetch_array($categorias)){
           echo "<a class='dropdown-item opcFilCateProd' href='#' data-id='".$categoria['idCategoria']."' data-categoria='".$categoria['categoria']."'>".$categoria['categoria']."</a>";         
       } 
    }elseif($action == "getProducto"){
        $idProducto=(isset($_REQUEST['idProducto'])&& $_REQUEST['idProducto'] !=NULL)?$_REQUEST['idProducto']:'';
        $producto = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM producto WHERE idProducto = $idProducto"));
       echo json_encode($producto);

    }elseif($action == "actualizarProd"){
        $idProducto=(isset($_REQUEST['idProducto'])&& $_REQUEST['idProducto'] !=NULL)?$_REQUEST['idProducto']:'';
        $nombre=(isset($_REQUEST['nombre'])&& $_REQUEST['nombre'] !=NULL)?$_REQUEST['nombre']:'';
        $categoria=(isset($_REQUEST['categoria'])&& $_REQUEST['categoria'] !=NULL)?$_REQUEST['categoria']:'';
        $conteo=(isset($_REQUEST['conteo'])&& $_REQUEST['conteo'] !=NULL)?$_REQUEST['conteo']:'';
        $precio=(isset($_REQUEST['precio'])&& $_REQUEST['precio'] !=NULL)?$_REQUEST['precio']:'';
        $procedencia=(isset($_REQUEST['procedencia'])&& $_REQUEST['procedencia'] !=NULL)?$_REQUEST['procedencia']:'';
        $repetido=existeProducto($nombre,$idProducto,$con);
        if($repetido > 0){
            echo 2;
        }else{
            $queryUpdateProd = mysqli_query($con,"Update producto SET nombre ='$nombre', idCategoria = '$categoria', conteo = '$conteo', precio = '$precio', procedencia = '$procedencia' WHERE idProducto = $idProducto");
            if($queryUpdateProd){
                echo 1;
            }else{
                echo 0;
            }
        }
        
    }elseif($action == "editarImgProd"){
        $idProducto=(isset($_REQUEST['idProducto'])&& $_REQUEST['idProducto'] !=NULL)?$_REQUEST['idProducto']:'';
        $imgAnt = mysqli_fetch_array(mysqli_query($con,"Select * FROM imgproducto WHERE idProducto = '$idProducto'"));
        $idImgAnt = $imgAnt['idImgProducto'];
        $rutaAnt = $imgAnt['ruta'];

        if(isset($_FILES['img']) || $_FILES['img']['size']!=0){
            mysqli_query($con,'BEGIN'); 
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
                if($queryInsertImg && $queryUpdateImg ){
                    if(unlink(realpath($rutaAnt))){
                        $queryDelImg=mysqli_query($con,"DELETE from imgproducto WHERE idImgProducto ='$idImgAnt'");
                        if($queryDelImg){
                            mysqli_query($con,'COMMIT');
                            echo 1;
                        }else{
                            mysqli_query($con,'ROLLBACK');
                            echo 0;
                        }
                    }else{
                        mysqli_query($con,'ROLLBACK');
                        echo 0;
                    }
                }else{
                    mysqli_query($con,'ROLLBACK');
                    echo 0;  
                }
              }else{
                mysqli_query($con,'ROLLBACK');
                echo 0;
              }
        }
    }elseif($action == "getImgProd"){
        $idProducto=(isset($_REQUEST['idProducto'])&& $_REQUEST['idProducto'] !=NULL)?$_REQUEST['idProducto']:'';
        $img = mysqli_fetch_array(mysqli_query($con,"SELECT * From imgProducto WHERE idProducto = $idProducto"));
        echo $img['ruta'];
    }

    function existeProducto($producto,$idProducto,$con){
        $productos = mysqli_num_rows(mysqli_query($con,"SELECT * FROM producto WHERE nombre = '$producto' AND idProducto != '$idProducto'"));
        return $productos;
    }





?>