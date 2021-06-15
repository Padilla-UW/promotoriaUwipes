<?php
session_start();
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
    $idVendedorV=(isset($_REQUEST['idVendedorV'])&& $_REQUEST['idVendedorV'] !=NULL)?$_REQUEST['idVendedorV']:'';

    if($nombre != '' || $idVendedorV != ''){
        if($nombre != '') $sqlTipoN = " WHERE z.nombre LIKE '%$nombre%'";
        if($idVendedorV != '') $sqlTipoZ = " WHERE z.idVendedor = '$idVendedorV'";
        $qZonas= "Select z.idZona, z.nombre, z.idVendedor, p.idPersona, p.nombre as nombrePer FROM zona z LEFT JOIN persona p ON z.idVendedor = p.idPersona";
        $qZonasCount.=$qZonas.$sqlTipoN.$sqlTipoZ;
        $qZonas.=$sqlTipoN.$sqlTipoZ." ORDER BY nombre ASC LIMIT $offset,$per_page";
            
            $queryZonas = mysqli_query($con,$qZonas);
            $queryZonasCount = mysqli_query($con,$qZonasCount);  
    }else{
        $queryZonas = mysqli_query($con,"Select z.idZona, z.nombre, z.idVendedor, p.idPersona, p.nombre as nombrePer FROM zona z LEFT JOIN persona p ON z.idVendedor = p.idPersona GROUP by z.nombre ORDER BY nombre ASC LIMIT $offset,$per_page");
        $queryZonasCount = mysqli_query($con,"Select z.idZona, z.nombre, z.idVendedor, p.idPersona, p.nombre as nombrePer FROM zona z LEFT JOIN persona p ON z.idVendedor = p.idPersona GROUP by z.nombre");
    }
    $total_pages = mysqli_num_rows($queryZonasCount);
    $total_pages = ceil($total_pages/$per_page);
    $reload = 'tableroZonas.php';

    //tabla
    while($res=mysqli_fetch_array($queryZonas)){
        $idZona=$res['idZona'];
        $nombre=$res['nombre'];
        $idVendedor=$res['nombrePer'];
        $vendedor=$res['idVendedor'];
        $zonas .= "<tr> 
                <th> $nombre </th>
                <td> $idVendedor </td>
                <td><button type='button' data-ven='$vendedor' data-id='$idZona' class='btn' id='btnEditModal' data-toggle='modal' data-target='#modalEditar'>Datos <i class='far fa-edit'></i>
              </svg></button>
            </td>
            </tr>";
    } 

    //paginación
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "zonas" => $zonas,
                    "pagination" => $pagination
                );
              echo json_encode($array);

}elseif($action=="agregarZona"){
    $nombreAdd=(isset($_REQUEST['nombreAdd'])&& $_REQUEST['nombreAdd'] !=NULL)?$_REQUEST['nombreAdd']:'';
    $vendedorAdd=(isset($_REQUEST['vendedorAdd'])&& $_REQUEST['vendedorAdd'] !=NULL)?$_REQUEST['vendedorAdd']:'';
    mysqli_query($con,'BEGIN');

    $duplicado=mysqli_query($con, "Select nombre From zona where nombre='$nombreAdd'");  
        if(mysqli_num_rows($duplicado)>0){
            mysqli_query($con,'ROLLBACK');
            echo 0;
        }else{      
            if($vendedorAdd != ''){
            $insertZona = "INSERT INTO zona (idVendedor, nombre) VALUES ('$vendedorAdd', '$nombreAdd')";
            mysqli_query($con, $insertZona);
            mysqli_query($con,'COMMIT');
            echo 1;
            }else{
            $insertZona = "INSERT INTO zona (idVendedor, nombre) VALUES (NULL, '$nombreAdd')";
            mysqli_query($con, $insertZona);
            mysqli_query($con,'COMMIT');
            echo 1;
            }
        }

//editar zona
}elseif($action=="getDatosZona"){
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $idVendedor=(isset($_REQUEST['idVendedor'])&& $_REQUEST['idVendedor'] !=NULL)?$_REQUEST['idVendedor']:'';

    $query=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM zona WHERE idZona= $idZona"));
    echo json_encode($query);

}elseif($action=="editarZona"){
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $nombreEdit=(isset($_REQUEST['nombreEdit'])&& $_REQUEST['nombreEdit'] !=NULL)?$_REQUEST['nombreEdit']:'';
    $vendedorEdit=(isset($_REQUEST['vendedorEdit'])&& $_REQUEST['vendedorEdit'] !=NULL)?$_REQUEST['vendedorEdit']:'';

    mysqli_query($con,'BEGIN');
   
    if($vendedorEdit != ''){
        $updateZona = "UPDATE zona SET idVendedor='$vendedorEdit', nombre='$nombreEdit' WHERE idZona= $idZona"; 
        mysqli_query($con, $updateZona);
        mysqli_query($con,'COMMIT');
        echo 1;
        }else{
        $updateZona = "UPDATE zona SET nombre='$nombreEdit' WHERE idZona= $idZona";
        mysqli_query($con, $updateZona);
        mysqli_query($con,'COMMIT');
        echo 1;
        }

}elseif($action=="getVendedorZona"){
    $queryVendedor=mysqli_query($con, "SELECT p.idPersona, p.nombre, u.idTipoUsuario, u.idPersona FROM usuario u, persona p WHERE NOT EXISTS (SELECT idVendedor FROM zona WHERE p.idPersona=idVendedor) AND u.idTipoUsuario=2 AND p.idPersona=u.idPersona");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryVendedor)){
       $idPersona = $res['idPersona'];
        $nombre= $res['nombre'];
      echo "<option value='".$idPersona."'>$nombre</option>";
   } 

}elseif($action=="getVendedorZonaEdit"){
    $idVendedor=(isset($_REQUEST['idVendedor'])&& $_REQUEST['idVendedor'] !=NULL)?$_REQUEST['idVendedor']:'';
    if($idVendedor == 0 || $idVendedor == "" || $idVendedor == NULL){
    $queryVendedor=mysqli_query($con, "SELECT p.idPersona, p.nombre, u.idTipoUsuario, u.idPersona FROM usuario u, persona p WHERE NOT EXISTS (SELECT idVendedor FROM zona WHERE p.idPersona=idVendedor) AND u.idTipoUsuario=2 AND p.idPersona=u.idPersona");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryVendedor)){
       $idPersona = $res['idPersona'];
        $nombre= $res['nombre'];
      echo "<option value='".$idPersona."'>$nombre</option>";
   }
    }else{
        $queryVendedor=mysqli_query($con, "SELECT p.idPersona, p.nombre, u.idTipoUsuario, u.idPersona FROM usuario u, persona p WHERE (NOT EXISTS (SELECT idVendedor FROM zona WHERE p.idPersona=idVendedor) AND u.idTipoUsuario=2 AND p.idPersona=u.idPersona) or (p.idPersona = $idVendedor AND u.idTipoUsuario=2 AND p.idPersona = u.idPersona)");
        while($res = mysqli_fetch_array($queryVendedor)){
            $idPersona = $res['idPersona'];
             $nombre= $res['nombre'];
           echo "<option value='".$idPersona."'>$nombre</option>";
        }
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

    //filtrado x tipo de usuario/nombre/zona
    $tipo=(isset($_REQUEST['tipo'])&& $_REQUEST['tipo'] !=NULL)?$_REQUEST['tipo']:'';
    $nombre=(isset($_REQUEST['busquedaNombre'])&& $_REQUEST['busquedaNombre'] !=NULL)?$_REQUEST['busquedaNombre']:'';
    $idZonaV=(isset($_REQUEST['idZonaV'])&& $_REQUEST['idZonaV'] !=NULL)?$_REQUEST['idZonaV']:'';

    if($nombre != '' || $tipo != '' || $idZonaV != ''){
        if($tipo != '') $sqlTipoP = " AND pv.tipo = '$tipo'";
        if($nombre != '') $sqlTipoN = " AND pv.nombre LIKE '%$nombre%'";
        if($idZonaV != '') $sqlTipoZ = " AND pv.idZona = '$idZonaV'";

        $qPuntosV = "Select pv.nombre AS nombrePV, pv.idPuntoVenta, pv.idZona, pv.tipo, z.idZona, z.nombre AS nombreZ FROM 
        zona z, puntoventa pv WHERE pv.idZona = z.idZona";
        $qPuntosVCount.=$qPuntosV.$sqlTipoN.$sqlTipoP.$sqlTipoZ;
        $qPuntosV.=$sqlTipoN.$sqlTipoP.$sqlTipoZ." ORDER BY nombrePV ASC LIMIT $offset,$per_page";
            
            $queryPuntosV = mysqli_query($con,$qPuntosV);
            $queryPuntosVCount = mysqli_query($con,$qPuntosVCount);
        
    }else{
        $queryPuntosV = mysqli_query($con,"Select pv.nombre AS nombrePV, pv.idPuntoVenta, pv.idZona, pv.tipo, z.idZona, z.nombre AS nombreZ FROM 
        zona z, puntoventa pv WHERE pv.idZona = z.idZona ORDER BY nombrePV ASC LIMIT $offset,$per_page");
        $queryPuntosVCount = mysqli_query($con,"Select pv.nombre AS nombrePV, pv.idPuntoVenta, pv.idZona, pv.tipo, z.idZona, z.nombre AS nombreZ FROM 
        zona z, puntoventa pv WHERE pv.idZona = z.idZona");
    }
        $total_pages = mysqli_num_rows($queryPuntosVCount);
        $total_pages = ceil($total_pages/$per_page);
        $reload = 'tableroPuntosVenta.php';

    //tabla
    while($res=mysqli_fetch_array($queryPuntosV)){
        $idPuntoVenta=$res['idPuntoVenta'];
        $nombre=$res['nombrePV'];
        $tipo=$res['tipo'];
        $idZona = $res['nombreZ'];
        $pventa .= "<tr> 
                <th> $nombre </th>
                <td> $tipo </td>
                <td> $idZona </td>
                <td><button type='button' data-id='$idPuntoVenta' class='btn' id='btnEditModalP' data-toggle='modal' data-target='#modalEditar'>Datos <i class='far fa-edit'></i>
              </svg></button>
              </td>
            </tr>";
    } 

    //paginación
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "pventa" => $pventa,
                    "pagination" => $pagination
                );
              echo json_encode($array);

//Agregar
}elseif($action=="agregarPuntoV"){
    $nombreAdd=(isset($_REQUEST['nombreAdd'])&& $_REQUEST['nombreAdd'] !=NULL)?$_REQUEST['nombreAdd']:'';
    $tipoAdd=(isset($_REQUEST['tipoAdd'])&& $_REQUEST['tipoAdd'] !=NULL)?$_REQUEST['tipoAdd']:'';
    $zonaAdd=(isset($_REQUEST['zonaAdd'])&& $_REQUEST['zonaAdd'] !=NULL)?$_REQUEST['zonaAdd']:'';

    mysqli_query($con,'BEGIN');
    $duplicado=mysqli_query($con, "SELECT nombre FROM puntoventa WHERE nombre='$nombreAdd' AND idZona='$zonaAdd'");
    if(mysqli_num_rows($duplicado)>0){
        mysqli_query($con,'ROLLBACK');
        echo 0;
        }else{      
        $insertPuntoV = "INSERT INTO puntoventa (idZona, nombre, tipo)
        VALUES ('$zonaAdd', '$nombreAdd', '$tipoAdd')";
        mysqli_query($con, $insertPuntoV);
        mysqli_query($con,'COMMIT');
        echo 1;
        }
        
//editar
}elseif($action=="getDatosPuntoV"){
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:'';
    $query=mysqli_fetch_array(mysqli_query($con,"SELECT idPuntoVenta, idZona, nombre, tipo FROM puntoventa WHERE idPuntoVenta = $idPuntoVenta"));
    echo json_encode($query);

}elseif($action=="editarPuntoV"){
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:''; 
    $nombreEdit=(isset($_REQUEST['nombreEdit'])&& $_REQUEST['nombreEdit'] !=NULL)?$_REQUEST['nombreEdit']:'';
    $tipoEdit=(isset($_REQUEST['tipoEdit'])&& $_REQUEST['tipoEdit'] !=NULL)?$_REQUEST['tipoEdit']:'';
    $zonaEdit=(isset($_REQUEST['zonaEdit'])&& $_REQUEST['zonaEdit'] !=NULL)?$_REQUEST['zonaEdit']:'';

    mysqli_query($con,'BEGIN');
    $updatePuntoV = "UPDATE puntoventa SET idZona='$zonaEdit', nombre='$nombreEdit', tipo='$tipoEdit' WHERE idPuntoVenta= $idPuntoVenta"; 
    $conUpdate=mysqli_query($con, $updatePuntoV);

    if($conUpdate){
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
    $queryVendedor=mysqli_query($con,"SELECT p.idPersona, p.nombre, u.idPersona, u.idTipoUsuario From 
    persona p, usuario u WHERE p.idPersona=u.idPersona AND u.idTipoUsuario=2");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryVendedor)){
       $idPersona = $res['idPersona'];
       $nombre= $res['nombre'];
      echo "<option value='".$idPersona."'>$nombre</option>";
   } 

//Filtros de búsqueda
}elseif($action == "getZonaFiltro"){
    $zona = mysqli_query($con,"SELECT * from zona ORDER BY nombre"); 
    while($res = mysqli_fetch_array($zona)){
        echo "<a class='dropdown-item opcFilZonaV' href='#' data-id='".$res['idZona']."' data-zonaV='".$res['nombre']."'>".$res['nombre']."</a>";         
    }   
}elseif($action == "getVendedorFiltro"){
    $vende = mysqli_query($con,"SELECT p.idPersona, p.nombre, u.idPersona, u.idTipoUsuario From 
    persona p, usuario u WHERE p.idPersona=u.idPersona AND u.idTipoUsuario=2 ORDER BY nombre"); 
    while($res = mysqli_fetch_array($vende)){
        echo "<a class='dropdown-item opcFilVendedorV' href='#' data-id='".$res['idPersona']."' data-vendedorV='".$res['nombre']."'>".$res['nombre']."</a>";         
    }   
}

//Inicia parte de SUCURSALES **********************************************************************************

if($action=="getSucursal"){
    //paginación
    include 'includes/pagination.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 8; //la cantidad de registros que desea mostrar
    $adjacents  = 3; //brecha entre páginas después de varios adyacentes
    $offset = ($page - 1) * $per_page;

    //filtrado x nombre/pventa
    $nombre=(isset($_REQUEST['busquedaNombre'])&& $_REQUEST['busquedaNombre'] !=NULL)?$_REQUEST['busquedaNombre']:'';
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:'';

    if($nombre != '' || $idPuntoVenta != ''){
        if($nombre != '') $sqlTipoN = " AND s.nombre LIKE '%$nombre%'";
        if($idPuntoVenta != '') $sqlTipoZ = " AND s.idPuntoVenta = '$idPuntoVenta'";

        $qPuntosV = "Select s.nombre as nombreSuc, s.numero, s.idPuntoVenta, s.idSucursal, pv.idPuntoVenta, pv.nombre as nombrePuntoV From sucursal s, puntoventa pv WHERE pv.idPuntoVenta=s.idPuntoVenta";
        $qPuntosVCount.=$qPuntosV.$sqlTipoN.$sqlTipoZ;
        $qPuntosV.=$sqlTipoN.$sqlTipoZ." ORDER BY nombreSuc ASC LIMIT $offset,$per_page";
            
            $queryPuntosV = mysqli_query($con,$qPuntosV);
            $queryPuntosVCount = mysqli_query($con,$qPuntosVCount);
        
    }else{
        $queryPuntosV = mysqli_query($con,"Select s.nombre as nombreSuc, s.numero, s.idPuntoVenta, s.idSucursal, pv.idPuntoVenta, pv.nombre as nombrePuntoV From sucursal s, puntoventa pv WHERE pv.idPuntoVenta=s.idPuntoVenta ORDER BY nombreSuc ASC LIMIT $offset,$per_page");
        $queryPuntosVCount = mysqli_query($con,"Select s.nombre as nombreSuc, s.numero, s.idPuntoVenta, s.idSucursal, pv.idPuntoVenta, pv.nombre as nombrePuntoV From sucursal s, puntoventa pv WHERE pv.idPuntoVenta=s.idPuntoVenta");
    }
        $total_pages = mysqli_num_rows($queryPuntosVCount);
        $total_pages = ceil($total_pages/$per_page);
        $reload = 'tableroSucursales.php';

    //tabla
    while($res=mysqli_fetch_array($queryPuntosV)){
        $idSucursal=$res['idSucursal'];
        $nombre=$res['nombreSuc'];
        $numero=$res['numero'];
        $idPuntoVenta = $res['nombrePuntoV'];
        $pventa .= "<tr> 
                <th> $nombre </th>
                <td> $numero </td>
                <td> $idPuntoVenta </td>
                <td><button type='button' data-id='$idSucursal' class='btn' id='btnEditModalS' data-toggle='modal' data-target='#modalEditar'>Datos <i class='far fa-edit'></i>
              </svg></button>
              </td>
            </tr>";
    } 

    //paginación
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "pventa" => $pventa,
                    "pagination" => $pagination
                );
              echo json_encode($array);

//Agregar
}elseif($action=="agregarSuc"){
    $nombreAdd=(isset($_REQUEST['nombreAdd'])&& $_REQUEST['nombreAdd'] !=NULL)?$_REQUEST['nombreAdd']:'';
    $numeroAdd=(isset($_REQUEST['numeroAdd'])&& $_REQUEST['numeroAdd'] !=NULL)?$_REQUEST['numeroAdd']:'';
    $pventaAdd=(isset($_REQUEST['pventaAdd'])&& $_REQUEST['pventaAdd'] !=NULL)?$_REQUEST['pventaAdd']:'';

        mysqli_query($con,'BEGIN');
        $insertSuc = "INSERT INTO sucursal (idPuntoVenta, nombre, numero)
        VALUES ('$pventaAdd', '$nombreAdd', '$numeroAdd')"; 
        $agregarSuc=mysqli_query($con, $insertSuc);
    
        if($agregarSuc){
            mysqli_query($con,'COMMIT');
            echo 1;
        }else{
            mysqli_query($con,'ROLLBACK');
            echo 0; 
        }
        
//Editar
}elseif($action=="getDatosSuc"){
    $idSucursal=(isset($_REQUEST['idSucursal'])&& $_REQUEST['idSucursal'] !=NULL)?$_REQUEST['idSucursal']:'';
    $query=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sucursal WHERE idSucursal = $idSucursal"));
    echo json_encode($query);

}elseif($action=="editarSuc"){
    $idSucursal=(isset($_REQUEST['idSucursal'])&& $_REQUEST['idSucursal'] !=NULL)?$_REQUEST['idSucursal']:''; 
    $nombreEdit=(isset($_REQUEST['nombreEdit'])&& $_REQUEST['nombreEdit'] !=NULL)?$_REQUEST['nombreEdit']:'';
    $numeroEdit=(isset($_REQUEST['numeroEdit'])&& $_REQUEST['numeroEdit'] !=NULL)?$_REQUEST['numeroEdit']:'';
    $pventaEdit=(isset($_REQUEST['pventaEdit'])&& $_REQUEST['pventaEdit'] !=NULL)?$_REQUEST['pventaEdit']:'';

    mysqli_query($con,'BEGIN');
    $updateS = "UPDATE sucursal SET idPuntoVenta='$pventaEdit', nombre='$nombreEdit', numero='$numeroEdit' WHERE idSucursal= $idSucursal"; 
    $conUpdate=mysqli_query($con, $updateS);

    if($conUpdate){
        mysqli_query($con,'COMMIT');
        echo 1;
    }else{
        mysqli_query($con,'ROLLBACK');
        echo 0; 
    }

//filtros y selects automaticos
}elseif($action == "getPVentaFiltro"){
    $visitas = mysqli_query($con,"SELECT * FROM puntoventa ORDER BY nombre"); 
    while($res = mysqli_fetch_array($visitas)){
        echo "<a class='dropdown-item opcFilPunVenta' href='#' data-id='".$res['idPuntoVenta']."' data-tipVisita='".$res['nombre']."'>".$res['nombre']."</a>";         
    } 

}elseif($action == "getPVenta"){
    $queryRes=mysqli_query($con,"SELECT * From puntoventa");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $idPuntoVenta = $res['idPuntoVenta'];
       $nombre = $res['nombre'];
      echo "<option value='".$idPuntoVenta."'>$nombre</option>";
   } 
}

?>
