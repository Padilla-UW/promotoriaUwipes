<?php
session_start();
// añadimos conexion y cachamos datos
include('includes/conection.php');
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//Inicia parte de VISITAS *****************************************************************************************
if($action=="getPVenta"){
    $queryZona=mysqli_query($con,"SELECT * From puntoventa");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryZona)){
       $idPuntoVenta = $res['idPuntoVenta'];
       $nombre = $res['nombre'];
      echo "<option value='".$idPuntoVenta."'>$nombre</option>";
   } 

//SESSIONES de visita
}elseif($action=="entrarVisita"){
    $punVenta=(isset($_REQUEST['selPuntoV'])&& $_REQUEST['selPuntoV'] !=NULL)?$_REQUEST['selPuntoV']:'';
    
    $_SESSION['checkUsuario'] = $row['correo'];

    $_SESSION['checkVisita']=array(
        'punVenta' => $punVenta
    );    

//Selects automáticos
}elseif($action=="getFProducto"){
    $queryRes=mysqli_query($con,"SELECT * From producto");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $idProducto = $res['idProducto'];
       $nombre = $res['nombre'];
      echo "<option value='".$idProducto."'>$nombre</option>";
   } 

}elseif($action=="getNProducto"){
    $queryRes=mysqli_query($con,"SELECT * From producto");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $nombre = $res['nombre'];
      echo "<option value='".$nombre."'>$nombre</option>";
   } 

}elseif($action=="getFTipoExi"){
    $queryRes=mysqli_query($con,"SELECT * From tipoexibicion");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $idTipoExibicion = $res['idTipoExibicion'];
       $nombre = $res['tipoExibicion'];
      echo "<option value='".$idTipoExibicion."'>$nombre</option>";
   } 

//guarda datos en fichero boton guardar
}elseif($action=="guardarFichero"){
    $producto=(isset($_REQUEST['selProducto'])&& $_REQUEST['selProducto'] !=NULL)?$_REQUEST['selProducto']:'';
    $tipoExi=(isset($_REQUEST['selTipoExi'])&& $_REQUEST['selTipoExi'] !=NULL)?$_REQUEST['selTipoExi']:'';
    $existencia=(isset($_REQUEST['selExistencia'])&& $_REQUEST['selExistencia'] !=NULL)?$_REQUEST['selExistencia']:'';
    $precio=(isset($_REQUEST['selPrecio'])&& $_REQUEST['selPrecio'] !=NULL)?$_REQUEST['selPrecio']:'';
    $frentes=(isset($_REQUEST['selFrentes'])&& $_REQUEST['selFrentes'] !=NULL)?$_REQUEST['selFrentes']:'';
   
    if(isset($_SESSION['fichero'])){
            $count = count($_SESSION['fichero']);
			$datos=array_column($_SESSION['fichero'], 'producto', 'tipoExi', 'existencia', 'precio', 'frentes');
       if(!in_array($producto, $datos)){
           $_SESSION['fichero'][$count] = array(
					'producto' => $producto,
					'tipoExi' =>$tipoExi,
					'existencia'=> $existencia,
                    'precio'=> $precio,
                    'frentes'=> $frentes
				);
       }else{
           for($i=0; $i < count($datos); $i++){
               
               if($datos[$i]==$producto){
                  $_SESSION['fichero'][$i]['producto']=$producto;
                  $_SESSION['fichero'][$i]['tipoExi']= $tipoExi;
                  $_SESSION['fichero'][$i]['existencia']= $existencia;
                  $_SESSION['fichero'][$i]['precio']= $precio;
                  $_SESSION['fichero'][$i]['frentes']= $frentes;
                 
                  echo ($producto);
                  echo ($tipoExi);
                  echo ($existencia);
                  echo ($precio);
                  echo ($frentes);
               }
           }
       }
   }else{
       $_SESSION['fichero'][0] = array(
					                           'producto' => $producto,
					                           'tipoExi' =>$tipoExi,
					                           'existencia'=> $existencia,
                                               'precio'=> $precio,
                                               'frentes'=> $frentes
				                                );
        }

//mostrar datos en guardar tabla 1
}elseif($action=="finalFichero1"){
    $fechaActual = date('d-m-Y');
                $idPuntoVenta=$_SESSION['checkVisita']['punVenta'];
                $queryRes= mysqli_query($con,"SELECT * From puntoventa WHERE idPuntoVenta = $idPuntoVenta");
                $nombre = $nombre;
                $res=mysqli_fetch_array($queryRes);

                 echo "<tr> 
                 <td> $fechaActual </td>
                 <td> ".$res['nombre']." </td>
                 </tr>";
            
//mostrar datos en guardar tabla 2
}elseif($action=="finalFichero2"){

    $count = count($_SESSION['fichero']);
    for($i=0;$i<$count; $i++){
        
            $idProducto=$_SESSION['fichero'][$i]['producto'];
            $tipoExi=$_SESSION['fichero'][$i]['tipoExi'];
            $existencia=$_SESSION['fichero'][$i]['existencia'];
            $precio=$_SESSION['fichero'][$i]['precio'];
            $frentes=$_SESSION['fichero'][$i]['frentes'];

            $res=mysqli_fetch_array(mysqli_query($con,"SELECT nombre From producto WHERE idProducto = $idProducto"));
            $nombre= $res['nombre'];
            $resu=mysqli_fetch_array(mysqli_query($con,"SELECT tipoExibicion From tipoExibicion WHERE idTipoExibicion = $tipoExi"));
            $tipoExibicion= $resu['tipoExibicion'];

            echo "<tr> 
            <td> $nombre </td>
            <td> $tipoExibicion </td>
            <td> $existencia </td>
            <td> $precio </td>
            <td> $frentes </td>
        </tr>";
        } 
 
//insertar confirmacion en bd
}elseif($action=="confirmarFichero"){
    //parte funcional de visita
    $fecha = date('Y-m-d');
    $idPuntoVenta=$_SESSION['checkVisita']['punVenta'];
    $idVendedor= $_SESSION["idUsuario"];
    
        $queryVisita="INSERT INTO visita(idVisita, idVendedor, idPuntoVenta, fecha) 
                VALUES ('', $idVendedor, $idPuntoVenta, '$fecha')";
                mysqli_query($con,$queryVisita);
                $idVisita = mysqli_insert_id($con);
    
    //parte funcional de detallesVisita y matrizUbicacion
        $count = count($_SESSION['fichero']);
        for($i=0;$i<$count; $i++){
            $producto=$_SESSION['fichero'][$i]['producto'];
            $tipoExi=$_SESSION['fichero'][$i]['tipoExi'];
            $existencia=$_SESSION['fichero'][$i]['existencia'];
            $precio=$_SESSION['fichero'][$i]['precio'];
            $frentes=$_SESSION['fichero'][$i]['frentes'];
            $supIzq=$_SESSION['matrix'][$i]['supIzq'];
            $supCen=$_SESSION['matrix'][$i]['supCen'];
            $supDer=$_SESSION['matrix'][$i]['supDer'];
            $cenIzq=$_SESSION['matrix'][$i]['cenIzq'];
            $centro=$_SESSION['matrix'][$i]['centro'];
            $cenDer=$_SESSION['matrix'][$i]['cenDer'];
            $infIzq=$_SESSION['matrix'][$i]['infIzq'];
            $infCen=$_SESSION['matrix'][$i]['infCen'];
            $infDer=$_SESSION['matrix'][$i]['infDer'];
    
                $queryDetalles="INSERT INTO detallesvisita(idDetallesVisita, idVisita, idProducto, idTipoExibicion, existencia, precio, frentes) 
                VALUES ('', $idVisita, $producto, $tipoExi, '$existencia', $precio, $frentes)";
                mysqli_query($con,$queryDetalles);
                $idDetallesVisita = mysqli_insert_id($con);
                $insertMatriz = "INSERT INTO matrizubicacion(idDetallesVisita, supIzq, supCentro, supDer, centroIzq, centroCentro, centroDer, infIzq, infCentro, infDer)
                VALUES ('$idDetallesVisita', '$supIzq', '$supCen', '$supDer', '$cenIzq', '$centro', '$cenDer', '$infIzq', '$infCen', '$infDer')"; 
                mysqli_query($con, $insertMatriz);
                echo $insertMatriz;
                var_dump($_SESSION);
        } 

        unset($_SESSION["fichero"]);
        unset($_SESSION["matrix"]);

//matriz guardar datos
}elseif($action=="guardarMatriz"){
    $supIzq=(isset($_REQUEST['supIzq'])&& $_REQUEST['supIzq'] !=NULL)?$_REQUEST['supIzq']:''; 
    $supCen=(isset($_REQUEST['supCen'])&& $_REQUEST['supCen'] !=NULL)?$_REQUEST['supCen']:'';
    $supDer=(isset($_REQUEST['supDer'])&& $_REQUEST['supDer'] !=NULL)?$_REQUEST['supDer']:'';
    $cenIzq=(isset($_REQUEST['cenIzq'])&& $_REQUEST['cenIzq'] !=NULL)?$_REQUEST['cenIzq']:'';
    $centro=(isset($_REQUEST['centro'])&& $_REQUEST['centro'] !=NULL)?$_REQUEST['centro']:'';
    $cenDer=(isset($_REQUEST['cenDer'])&& $_REQUEST['cenDer'] !=NULL)?$_REQUEST['cenDer']:'';
    $infIzq=(isset($_REQUEST['infIzq'])&& $_REQUEST['infIzq'] !=NULL)?$_REQUEST['infIzq']:'';
    $infCen=(isset($_REQUEST['infCen'])&& $_REQUEST['infCen'] !=NULL)?$_REQUEST['infCen']:'';
    $infDer=(isset($_REQUEST['infDer'])&& $_REQUEST['infDer'] !=NULL)?$_REQUEST['infDer']:'';

    if(isset($_SESSION['matrix'])){
        $count = count($_SESSION['matrix']);
        $datos=array_column($_SESSION['matrix'], 'supIzq', 'supCen', 'supDer', 'cenIzq', 'centro', 'cenDer', 'infIzq', 'infCen', 'infDer');
   if(!in_array($supIzq, $datos)){
       $_SESSION['matrix'][$count] = array(
                'supIzq' => $supIzq,
                'supCen' => $supCen,
                'supDer' => $supDer,
                'cenIzq' => $cenIzq,
                'centro' => $centro,
                'cenDer' => $cenDer,
                'infIzq' => $infIzq,
                'infCen' => $infCen,
                'infDer' => $infDer
            );
   }else{
       for($i=0; $i < count($datos); $i++){
           
           if($datos[$i]==$supIzq){
              $_SESSION['matrix'][$i]['supIzq']= $supIzq;
              $_SESSION['matrix'][$i]['supCen']= $supCen;
              $_SESSION['matrix'][$i]['supDer']= $supDer;
              $_SESSION['matrix'][$i]['cenIzq']= $cenIzq;
              $_SESSION['matrix'][$i]['centro']= $centro;
              $_SESSION['matrix'][$i]['cenDer']= $cenDer;
              $_SESSION['matrix'][$i]['infIzq']= $infIzq;
              $_SESSION['matrix'][$i]['infCen']= $infCen;
              $_SESSION['matrix'][$i]['infDer']= $infDer;

              echo ($supIzq);
              echo ($supDer);
              echo ($supCen);
              echo ($cenIzq);
              echo ($centro);
              echo ($cenDer);
              echo ($infIzq);
              echo ($infCen);
              echo ($infDer);
           }
       }
   }
}else{
   $_SESSION['matrix'][0] = array(
    'supIzq' => $supIzq,
    'supCen' => $supCen,
    'supDer' => $supDer,
    'cenIzq' => $cenIzq,
    'centro' => $centro,
    'cenDer' => $cenDer,
    'infIzq' => $infIzq,
    'infCen' => $infCen,
    'infDer' => $infDer
        );
    }
}

//Inicia parte de VISITAS ADMIN ***************************************************************************************
if($action=="getPVisitas"){
    //paginación
    include 'includes/pagination.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 8; //la cantidad de registros que desea mostrar
    $adjacents  = 3; //brecha entre páginas después de varios adyacentes
    $offset = ($page - 1) * $per_page;

    //filtrado x punto de venta/nombre/fecha
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:'';
    $idVendedor=(isset($_REQUEST['idVendedor'])&& $_REQUEST['idVendedor'] !=NULL)?$_REQUEST['idVendedor']:'';
    $fechaInicio=(isset($_REQUEST['fechaInicio'])&& $_REQUEST['fechaInicio'] !=NULL)?$_REQUEST['fechaInicio']:'';
    $fechaFin=(isset($_REQUEST['fechaFin'])&& $_REQUEST['fechaFin'] !=NULL)?$_REQUEST['fechaFin']:'';

    if($idPuntoVenta != '' || $fecha != '' || $idVendedor != '' || $fechaInicio != '' || $fechaFin != ''){
        if($idPuntoVenta != '') $sqlVenta = " AND v.idPuntoVenta = '$idPuntoVenta'";
        if($idVendedor != '') $sqlVendedor = " AND v.idVendedor = '$idVendedor'";
        if($fechaInicio !='' && $fechaFin !=''){ $sqlFecha = " AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
        }elseif($fechaInicio !='' && $fechaFin ==''){ $sqlFecha = " AND v.fecha >= '$fechaInicio'";
        }elseif($fechaInicio =='' && $fechaFin !=''){ $sqlFecha = " AND v.fecha <= '$fechaFin'";}

        $queryVisitas = "SELECT v.idVisita, v.idVendedor, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre AS nombrePunto,
        pv.idVendedor, p.nombre AS nombrePersona, p.idPersona FROM visita v, persona p, puntoventa pv WHERE v.idPuntoVenta=pv.idPuntoVenta AND v.idVendedor=p.idPersona";
        $queryVisitas.=$sqlVenta.$sqlFecha.$sqlVendedor." ORDER BY idVisita LIMIT $offset,$per_page";
        
    }else{
        $queryVisitas = "SELECT v.idVisita, v.idVendedor, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre AS nombrePunto,
        pv.idVendedor, p.nombre AS nombrePersona, p.idPersona FROM visita v, persona p, puntoventa pv WHERE v.idPuntoVenta=pv.idPuntoVenta AND v.idVendedor=p.idPersona
        ORDER BY idVisita LIMIT $offset,$per_page";
    }

    //tabla
    $query= mysqli_query($con, $queryVisitas);
    while($res=mysqli_fetch_array($query)){
        $idVisita=$res['idVisita'];
        $vendedor=$res['nombrePersona'];
        $idPuntoVenta=$res['nombrePunto'];
        $fecha = $res['fecha'];
        $visitas .= "<tr> 
                <th> $vendedor </th>
                <td> $idPuntoVenta </td>
                <td> $fecha </td>
                <td><button type='button' data-id='$idVisita' class='btn' id='btnDetalleModal' data-toggle='modal' data-target='#modalDetalles'>Detalles <i class='far fa-edit'></i>
              </svg></button>
              <button type='button' data-id='$idVisita' class='btn' id='btnMatrizModal' data-toggle='modal' data-target='#modalMatriz'>Matriz <i class='fab fa-slack-hash'></i></button></td>
            </tr>";
    } 

    //paginación
    $queryVisitas = "SELECT count(*) AS numrows FROM visita v, persona p, puntoventa pv WHERE v.idPuntoVenta=pv.idPuntoVenta AND v.idVendedor=p.idPersona";
            $count_query = mysqli_query($con, $queryVisitas);

            if($row= mysqli_fetch_array($count_query)):$numrows = $row['numrows'];endif;
                $total_pages = ceil($numrows/$per_page);
                $reload = 'tableroVisitas.php';
        
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "visitas" => $visitas,
                    "pagination" => $pagination,
                );
              echo json_encode($array);

//FILTRADO BÚSQUEDA
}elseif($action == "getPVentaFiltro"){
    $visitas = mysqli_query($con,"SELECT * FROM puntoventa"); 
    while($res = mysqli_fetch_array($visitas)){
        echo "<a class='dropdown-item opcFilPunVenta' href='#' data-id='".$res['idPuntoVenta']."' data-tipVisita='".$res['nombre']."'>".$res['nombre']."</a>";         
    } 

}elseif($action == "getVendedorFiltro"){
    $vende = mysqli_query($con,"SELECT p.idPersona, p.nombre, u.idPersona, u.idTipoUsuario From 
    persona p, usuario u WHERE p.idPersona=u.idPersona AND u.idTipoUsuario=2"); 
    while($res = mysqli_fetch_array($vende)){
        echo "<a class='dropdown-item opcFilVendedor' href='#' data-id='".$res['idPersona']."' data-tipVende='".$res['nombre']."'>".$res['nombre']."</a>";         
    }

//detalles
}elseif($action=="getDetalles"){
        $idVisita=(isset($_REQUEST['idVisita'])&& $_REQUEST['idVisita'] !=NULL)?$_REQUEST['idVisita']:'';
        $query= mysqli_query($con,"SELECT v.idVisita, d.idDetallesVisita, d.idVisita, d.idProducto, p.idProducto, p.nombre, d.idTipoExibicion, d.existencia, d.precio, d.frentes, t.idTipoExibicion, t.tipoExibicion
        FROM detallesvisita d, visita v, producto p, tipoexibicion t WHERE v.idVisita = d.idVisita AND v.idVisita = $idVisita AND p.idProducto=d.idProducto");
        while($res=mysqli_fetch_array($query)){
            $producto=$res['nombre'];
            $idTipoExibicion = $res['tipoExibicion'];
            $existencia = $res['existencia'];
            $precio = $res['precio'];
            $frentes = $res['frentes'];
             
            echo "<tr> 
                    <td> $producto </td>
                    <td> $idTipoExibicion </td>
                    <td> $existencia </td>
                    <td> $precio </td>
                    <td> $frentes </td>
                </tr>";
        } 

//detalles Matriz
}elseif($action=="getDetalleMatriz"){
        $idVisita=(isset($_REQUEST['idVisita'])&& $_REQUEST['idVisita'] !=NULL)?$_REQUEST['idVisita']:'';
        $query= mysqli_query($con,"SELECT * FROM detallesvisita d, matrizubicacion u, visita v 
        WHERE d.idDetallesVisita = u.idDetallesVisita AND d.idVisita = $idVisita AND d.idVisita = v.idVisita");
        while($res=mysqli_fetch_array($query)){
            $supIzq = $res['supIzq'];
            $supCen = $res['supCentro'];
            $supDer = $res['supDer'];
            $centroIzq = $res['centroIzq'];
            $centroCentro = $res['centroCentro'];
            $centroDer = $res['centroDer'];
            $infIzq = $res['infIzq'];
            $infCen = $res['infCentro'];
            $infDer = $res['infDer'];
           
            echo "<tr> 
                    <td> $supIzq </td>
                    <td> $supCen </td>
                    <td> $supDer </td>
                    <td> $centroIzq </td>
                    <td> $centroCentro </td>
                    <td> $centroDer </td>
                    <td> $infIzq </td>
                    <td> $infCen </td>
                    <td> $infDer </td>    
                </tr>";
        } 
    }
?>
