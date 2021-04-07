<?php
// añadimos conexion y cachamos datos
include('includes/conection.php');
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//Inicia parte de ZONAS ******************************************************************************************
//mostramos datos de la db en la tabla
if($action=="getZona"){
    //paginación
    include 'includes/pagination.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 8; //la cantidad de registros que desea mostrar
    $adjacents  = 3; //brecha entre páginas después de varios adyacentes
    $offset = ($page - 1) * $per_page;

    //filtrado
    $nombre=(isset($_REQUEST['busquedaNombre'])&& $_REQUEST['busquedaNombre'] !=NULL)?$_REQUEST['busquedaNombre']:'';

    if($nombre != ''){
        $sqlTipoN = " WHERE nombre LIKE '%$nombre%'";

        $queryZonas= "Select * FROM zona";

        $queryZonas.=$sqlTipoN." ORDER BY idZona LIMIT $offset,$per_page";
        
    }else{
        $queryZonas = "Select * FROM zona ORDER BY idZona LIMIT $offset,$per_page";
    }

    //tabla
    $query= mysqli_query($con, $queryZonas);
    while($res=mysqli_fetch_array($query)){
        $idZona=$res['idZona'];
        $nombre=$res['nombre'];
        $zonas .= "<tr> 
                <th> $nombre </th>
                <td><button type='button' data-id='$idZona' class='btn' id='btnEditModal' data-toggle='modal' data-target='#modalEditar'>Datos <i class='far fa-edit'></i>
              </svg></button>
            </td>
            </tr>";
    } 

    //paginación
    $queryZonas = "SELECT count(*) AS numrows FROM zona";
            $count_query = mysqli_query($con, $queryZonas);

            if($row= mysqli_fetch_array($count_query)):$numrows = $row['numrows'];endif;
                $total_pages = ceil($numrows/$per_page);
                $reload = 'tableroZonas.php';
        
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "zonas" => $zonas,
                    "pagination" => $pagination,
                );
              echo json_encode($array);

}elseif($action=="agregarZona"){
    $nombreAdd=(isset($_REQUEST['nombreAdd'])&& $_REQUEST['nombreAdd'] !=NULL)?$_REQUEST['nombreAdd']:'';
    mysqli_query($con,'BEGIN');

    $duplicado=mysqli_query($con, "Select nombre From zona where nombre='$nombreAdd'");
        if(mysqli_num_rows($duplicado)>0){
            mysqli_query($con,'ROLLBACK');
            echo 0;
        }else{      
        $insertZona = "INSERT INTO zona (idZona, nombre) VALUES ('', '$nombreAdd')";
            mysqli_query($con, $insertZona);
            mysqli_query($con,'COMMIT');
            echo 1;
        }

//editar zona
}elseif($action=="getDatosZona"){
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $query=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM zona WHERE idZona= $idZona"));
    echo json_encode($query);

}elseif($action=="editarZona"){
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $nombreEdit=(isset($_REQUEST['nombreEdit'])&& $_REQUEST['nombreEdit'] !=NULL)?$_REQUEST['nombreEdit']:'';

    mysqli_query($con,'BEGIN');

    $duplicado=mysqli_query($con, "Select nombre From zona where nombre='$nombreEdit'");
        if(mysqli_num_rows($duplicado)>0){
            mysqli_query($con,'ROLLBACK');
            echo 0;
        }else{      
            $updateZona = "UPDATE zona SET nombre='$nombreEdit' WHERE idZona= $idZona"; 
            mysqli_query($con, $updateZona);
            mysqli_query($con,'COMMIT');
            echo 1;
        }
}

//Inicia parte de PUNTOS VENTA **********************************************************************************

if($action=="getPuntosV"){
    //paginación
    include 'includes/pagination.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 8; //la cantidad de registros que desea mostrar
    $adjacents  = 3; //brecha entre páginas después de varios adyacentes
    $offset = ($page - 1) * $per_page;

    //filtrado x tipo de usuario/nombre/zona/vendedor
    $tipo=(isset($_REQUEST['tipo'])&& $_REQUEST['tipo'] !=NULL)?$_REQUEST['tipo']:'';
    $nombre=(isset($_REQUEST['busquedaNombre'])&& $_REQUEST['busquedaNombre'] !=NULL)?$_REQUEST['busquedaNombre']:'';
    $idZonaV=(isset($_REQUEST['idZonaV'])&& $_REQUEST['idZonaV'] !=NULL)?$_REQUEST['idZonaV']:'';
    $idVendedorV=(isset($_REQUEST['idVendedorV'])&& $_REQUEST['idVendedorV'] !=NULL)?$_REQUEST['idVendedorV']:'';

    if($nombre != '' || $tipo != '' || $idZonaV != '' || $idVendedorV != ''){
        if($tipo != '') $sqlTipoP = " AND pv.tipo = '$tipo'";
        if($nombre != '') $sqlTipoN = " AND pv.nombre LIKE '%$nombre%'";
        if($idZonaV != '') $sqlTipoZ = " AND pv.idZona = '$idZonaV'";
        if($idVendedorV != '') $sqlTipoV = " AND pv.idVendedor = '$idVendedorV'";

        $queryPuntosV = "Select pv.nombre AS nombrePV, pv.idPuntoVenta, pv.idVendedor, pv.idZona, pv.tipo, z.idZona, z.nombre AS nombreZ, p.idPersona, p.nombre AS nombreV FROM 
        persona p, zona z, puntoventa pv WHERE pv.idZona = z.idZona AND pv.idVendedor = p.idPersona";

        $queryPuntosV.=$sqlTipoN.$sqlTipoP.$sqlTipoZ.$sqlTipoV." ORDER BY idPuntoVenta LIMIT $offset,$per_page";
        
    }else{
        $queryPuntosV = "Select pv.nombre AS nombrePV, pv.idPuntoVenta, pv.idVendedor, pv.idZona, pv.tipo, z.idZona, z.nombre AS nombreZ, p.idPersona, p.nombre AS nombreV FROM 
        persona p, zona z, puntoventa pv WHERE pv.idZona = z.idZona AND pv.idVendedor = p.idPersona ORDER BY idPuntoVenta LIMIT $offset,$per_page";
    }

    //tabla
    $query= mysqli_query($con, $queryPuntosV);
    while($res=mysqli_fetch_array($query)){
        $idPuntoVenta=$res['idPuntoVenta'];
        $nombre=$res['nombrePV'];
        $tipo=$res['tipo'];
        $idVendedor = $res['nombreV'];
        $idZona = $res['nombreZ'];
        $pventa .= "<tr> 
                <th> $nombre </th>
                <td> $tipo </td>
                <td> $idZona </td>
                <td> $idVendedor </td>
                <td><button type='button' data-id='$idPuntoVenta' class='btn' id='btnEditModalP' data-toggle='modal' data-target='#modalEditar'>Datos <i class='far fa-edit'></i>
              </svg></button>
              </td>
            </tr>";
    } 

    //paginación
    $queryPuntosV = "SELECT count(*) AS numrows FROM persona p, zona z, puntoventa pv WHERE pv.idZona = z.idZona AND pv.idVendedor = p.idPersona";
            $count_query = mysqli_query($con, $queryPuntosV);

            if($row= mysqli_fetch_array($count_query)):$numrows = $row['numrows'];endif;
                $total_pages = ceil($numrows/$per_page);
                $reload = 'tableroPuntosVenta.php';
        
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "pventa" => $pventa,
                    "pagination" => $pagination,
                );
              echo json_encode($array);

//Agregar
}elseif($action=="agregarPuntoV"){
    $nombreAdd=(isset($_REQUEST['nombreAdd'])&& $_REQUEST['nombreAdd'] !=NULL)?$_REQUEST['nombreAdd']:'';
    $tipoAdd=(isset($_REQUEST['tipoAdd'])&& $_REQUEST['tipoAdd'] !=NULL)?$_REQUEST['tipoAdd']:'';
    $zonaAdd=(isset($_REQUEST['zonaAdd'])&& $_REQUEST['zonaAdd'] !=NULL)?$_REQUEST['zonaAdd']:'';
    $vendedorAdd=(isset($_REQUEST['vendedorAdd'])&& $_REQUEST['vendedorAdd'] !=NULL)?$_REQUEST['vendedorAdd']:'';

    mysqli_query($con,'BEGIN');
    $insertPuntoV = "INSERT INTO puntoventa (idPuntoVenta, idVendedor, idZona, nombre, tipo)
    VALUES ('', '$vendedorAdd', '$zonaAdd', '$nombreAdd', '$tipoAdd')"; 
    mysqli_query($con, $insertPuntoV);
    
    if($insertPuntoV){
        mysqli_query($con,'COMMIT');
        echo 1;
    }else{
        mysqli_query($con,'ROLLBACK');
        echo 0; 
    }

//editar
}elseif($action=="getDatosPuntoV"){
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:'';
    $query=mysqli_fetch_array(mysqli_query($con,"SELECT idPuntoVenta, idVendedor, idZona, nombre, tipo FROM puntoventa WHERE idPuntoVenta = $idPuntoVenta"));
    echo json_encode($query);

}elseif($action=="editarPuntoV"){
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:''; 
    $nombreEdit=(isset($_REQUEST['nombreEdit'])&& $_REQUEST['nombreEdit'] !=NULL)?$_REQUEST['nombreEdit']:'';
    $tipoEdit=(isset($_REQUEST['tipoEdit'])&& $_REQUEST['tipoEdit'] !=NULL)?$_REQUEST['tipoEdit']:'';
    $zonaEdit=(isset($_REQUEST['zonaEdit'])&& $_REQUEST['zonaEdit'] !=NULL)?$_REQUEST['zonaEdit']:'';
    $vendedorEdit=(isset($_REQUEST['vendedorEdit'])&& $_REQUEST['vendedorEdit'] !=NULL)?$_REQUEST['vendedorEdit']:'';

    mysqli_query($con,'BEGIN');
    $updatePuntoV = "UPDATE puntoventa SET idVendedor='$vendedorEdit', idZona='$zonaEdit', nombre='$nombreEdit', tipo='$tipoEdit' WHERE idPuntoVenta= $idPuntoVenta"; 
    mysqli_query($con, $updatePuntoV);

    if($updatePuntoV){
        mysqli_query($con,'COMMIT');
        echo 1;
    }else{
        mysqli_query($con,'ROLLBACK');
        echo 0; 
    }

//SELECT AUTOMATICO
}elseif($action=="getZonas"){
    $queryZona=mysqli_query($con,"SELECT idZona, nombre From zona");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryZona)){
       $idZona = $res['idZona'];
       $nombre = $res['nombre'];
      echo "<option value='".$idZona."'>$nombre</option>";
   } 

}elseif($action=="getVendedor"){
    $queryVendedor=mysqli_query($con,"SELECT idPersona, nombre From persona");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryVendedor)){
       $idPersona = $res['idPersona'];
       $nombre= $res['nombre'];
      echo "<option value='".$idPersona."'>$nombre</option>";
   } 

//Filtros de búsqueda
}elseif($action == "getZonaFiltro"){
    $zona = mysqli_query($con,"SELECT * from zona"); 
    while($res = mysqli_fetch_array($zona)){
        echo "<a class='dropdown-item opcFilZonaV' href='#' data-id='".$res['idZona']."' data-zonaV='".$res['nombre']."'>".$res['nombre']."</a>";         
    }   
}elseif($action == "getVendedorFiltro"){
    $vende = mysqli_query($con,"SELECT * from persona"); 
    while($res = mysqli_fetch_array($vende)){
        echo "<a class='dropdown-item opcFilVendedorV' href='#' data-id='".$res['idPersona']."' data-vendedorV='".$res['nombre']."'>".$res['nombre']."</a>";         
    }   
}
