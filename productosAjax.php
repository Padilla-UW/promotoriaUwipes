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
    }elseif($action == "getCategoriasFiltro"){
        $categorias = mysqli_query($con,"SELECT * from categoria"); 
        while($categoria = mysqli_fetch_array($categorias)){
           echo "<a class='dropdown-item' href='#' data-id='".$categoria['idCategoria']."'>".$categoria['categoria']."</a>";         
       } 
    }





?>