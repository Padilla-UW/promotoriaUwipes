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
                <td> $nombre </td>
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
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:''; 
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