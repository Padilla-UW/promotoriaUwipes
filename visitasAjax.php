<?php
session_start();
// añadimos conexion y cachamos datos
include('includes/conection.php');
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//Inicia parte de VISITAS VENDEDOR ************************************************************************************
if($action=="getPVenta"){
    $idUsuarioV=$_SESSION['idUsuario']['idUsuario'];
    $queryRes=mysqli_query($con,"SELECT pv.idPuntoVenta, pv.idZona, pv.nombre, z.idZona, z.idVendedor From puntoventa pv, zona z
    WHERE z.idVendedor=$idUsuarioV AND z.idZona=pv.idZona");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $idPuntoVenta = $res['idPuntoVenta'];
       $nombre = $res['nombre'];
      echo "<option value='".$idPuntoVenta."'>$nombre</option>";
   }

}elseif($action=="getSucursal"){
    $pv=(isset($_REQUEST['pv'])&& $_REQUEST['pv'] !=NULL)?$_REQUEST['pv']:'';
        if($pv != ''){
            $queryPVenta=mysqli_query($con,"SELECT s.idSucursal, s.nombre AS nombreSuc, s.idPuntoVenta, pv.idPuntoVenta From sucursal s, puntoventa pv WHERE s.idPuntoVenta = pv.idPuntoVenta AND pv.idPuntoVenta = $pv");
        }else{
            $queryPVenta=mysqli_query($con,"SELECT * From sucursal");
        }
        echo "<option value=''>Seleccione</option>";
        while($res = mysqli_fetch_array($queryPVenta)){
            $idSucursal = $res['idSucursal'];
            $nombre = $res['nombreSuc'];
           echo "<option value='".$idSucursal."'>$nombre</option>";
        }

//SESSIONES de visita
}elseif($action=="entrarVisita"){
    $punVenta=(isset($_REQUEST['selPuntoV'])&& $_REQUEST['selPuntoV'] !=NULL)?$_REQUEST['selPuntoV']:'';

    $_SESSION['checkVisita']=array(
        'punVenta' => $punVenta
    );   

//Selects automáticos
}elseif($action=="getFProducto"){
    $queryRes=mysqli_query($con, "SELECT * From producto ORDER BY nombre ASC");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $idProducto = $res['idProducto'];
       $nombre = $res['nombre'];
      echo "<option value='".$idProducto."'>$nombre</option>";
   } 

}elseif($action=="getNProducto"){
    $queryRes=mysqli_query($con, "SELECT * From producto ORDER BY nombre ASC");
    echo "<option data-id='' value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $nombre = $res['nombre'];
       $idProducto = $res['idProducto'];
      echo "<option data-id='$idProducto' value='".$nombre."'>$nombre</option>";
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
    $nivel=(isset($_REQUEST['selNivel'])&& $_REQUEST['selNivel'] !=NULL)?$_REQUEST['selNivel']:'';
    $imgFinal = "imgEvidencias/img";
    $rutaFinal = "";
    //parte imagen
    mysqli_query($con,'BEGIN');
    $directorio = "imgEvidencias";
                if (!file_exists($directorio)) {
                     mkdir($directorio, 0777, true);
                }

    if(isset($_FILES['img']) || $_FILES['img']['size']!=0){
        $nombre = $_FILES['img']['name'];
        $nombre_tmp = $_FILES['img']['tmp_name'];
        $partes_nombre = explode('.', $nombre);
        $extension = end($partes_nombre);
        $ruta ="imgEvidencias/";

        $insertRuta = "SELECT MAX(idImgDetallesVisita)+1 as Siguiente FROM imgdetallesvisita";
        $queryUpdateImg = mysqli_fetch_array(mysqli_query($con, $insertRuta));
        $rutaFinal = $imgFinal.$queryUpdateImg['Siguiente'];

       if(move_uploaded_file($nombre_tmp, $ruta."Img".$queryUpdateImg['Siguiente'].".".$extension)){
            $insertRuta2 = "INSERT INTO imgdetallesvisita(idDetallesVisita, ruta) VALUES (null,'$rutaFinal')";
            $queryUpdateImg2 = mysqli_query($con, $insertRuta2);
            if($queryUpdateImg && $queryUpdateImg2){
                mysqli_query($con,'COMMIT');
                echo 1;
            }else{
                mysqli_query($con,'ROLLBACK');
                echo 0;  
            }
          }
      }

    //parte session
    if(isset($_SESSION['fichero'])){
        $count = count($_SESSION['fichero']);
        $datos=array_column($_SESSION['fichero'], 'producto', 'tipoExi', 'existencia', 'precio', 'frentes', 'rutaFinal','extension', 'nivel');
   if(!in_array($producto, $datos)){
       $_SESSION['fichero'][$count] = array(
                'producto' => $producto,
                'tipoExi' =>$tipoExi,
                'existencia'=> $existencia,
                'precio'=> $precio,
                'frentes'=> $frentes,
                'rutaFinal'=> $rutaFinal,
                'extension'=> $extension,
                'nivel'=> $nivel
            );
   }else{
       for($i=0; $i < count($datos); $i++){
           
           if($datos[$i]==$producto){
              $_SESSION['fichero'][$i]['producto']=$producto;
              $_SESSION['fichero'][$i]['tipoExi']= $tipoExi;
              $_SESSION['fichero'][$i]['existencia']= $existencia;
              $_SESSION['fichero'][$i]['precio']= $precio;
              $_SESSION['fichero'][$i]['frentes']= $frentes;
              $_SESSION['fichero'][$i]['rutaFinal']= $rutaFinal;
              $_SESSION['fichero'][$i]['extension']= $extension;
              $_SESSION['fichero'][$i]['nivel']= $nivel;
             
              echo ($producto);
              echo ($tipoExi);
              echo ($existencia);
              echo ($precio);
              echo ($frentes);
              echo ($nivel);
              echo ($rutaFinal);
              
           }
       }
   }
}else{
   $_SESSION['fichero'][0] = array(
                                           'producto' => $producto,
                                           'tipoExi' =>$tipoExi,
                                           'existencia'=> $existencia,
                                           'precio'=> $precio,
                                           'frentes'=> $frentes,
                                           'rutaFinal'=> $rutaFinal,
                                           'extension'=> $extension,
                                           'nivel'=> $nivel
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
            $nivel=$_SESSION['fichero'][$i]['nivel'];

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
            <td> $nivel </td>
        </tr>";
        } 
 
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
  
//insertar confirmacion en bd
}elseif($action=="confirmarFichero"){
    //parte funcional de visita
    $fecha = date('Y-m-d');
    $idPuntoVenta=$_SESSION['checkVisita']['punVenta'];
    $idVendedor= $_SESSION["idUsuario"];
    
        $queryVisita="INSERT INTO visita(idVendedor, idPuntoVenta, fecha) 
                VALUES ($idVendedor, $idPuntoVenta, '$fecha')";
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
            $nivel=$_SESSION['fichero'][$i]['nivel'];
            $rutaFinal=$_SESSION['fichero'][$i]['rutaFinal'];
            $extension=$_SESSION['fichero'][$i]['extension'];
            $supIzq=$_SESSION['matrix'][$i]['supIzq'];
            $supCen=$_SESSION['matrix'][$i]['supCen'];
            $supDer=$_SESSION['matrix'][$i]['supDer'];
            $cenIzq=$_SESSION['matrix'][$i]['cenIzq'];
            $centro=$_SESSION['matrix'][$i]['centro'];
            $cenDer=$_SESSION['matrix'][$i]['cenDer'];
            $infIzq=$_SESSION['matrix'][$i]['infIzq'];
            $infCen=$_SESSION['matrix'][$i]['infCen'];
            $infDer=$_SESSION['matrix'][$i]['infDer'];

            $queryDetalles="INSERT INTO detallesvisita(idVisita, idProducto, idTipoExibicion, existencia, precio, frentes, nivel) 
            VALUES ($idVisita, $producto, $tipoExi, '$existencia', $precio, $frentes, $nivel)";
            mysqli_query($con,$queryDetalles);
            $idDetallesVisita = mysqli_insert_id($con);
            $insertMatriz = "INSERT INTO matrizubicacion(idDetallesVisita, supIzq, supCentro, supDer, centroIzq, centroCentro, centroDer, infIzq, infCentro, infDer)
            VALUES ('$idDetallesVisita', '$supIzq', '$supCen', '$supDer', '$cenIzq', '$centro', '$cenDer', '$infIzq', '$infCen', '$infDer')"; 
            mysqli_query($con, $insertMatriz);
            $updateImgDetalles = "UPDATE imgdetallesvisita SET idDetallesVisita = '$idDetallesVisita', ruta = '$rutaFinal.$extension' WHERE `ruta` = '$rutaFinal'";
            mysqli_query($con, $updateImgDetalles);

            echo $insertMatriz;
            var_dump($_SESSION);
      } 

        unset($_SESSION["fichero"]);
        unset($_SESSION["matrix"]);

//agregar producto si no existe
}elseif($action=="getCategoria"){
    $queryRes=mysqli_query($con,"SELECT * From categoria");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryRes)){
       $idCategoria = $res['idCategoria'];
       $categoria = $res['categoria'];
      echo "<option value='".$idCategoria."'>$categoria</option>";
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
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $fechaInicio=(isset($_REQUEST['fechaInicio'])&& $_REQUEST['fechaInicio'] !=NULL)?$_REQUEST['fechaInicio']:'';
    $fechaFin=(isset($_REQUEST['fechaFin'])&& $_REQUEST['fechaFin'] !=NULL)?$_REQUEST['fechaFin']:'';

    if($idPuntoVenta != '' || $fecha != '' || $idVendedor != '' || $fechaInicio != '' || $fechaFin != '' || $idZona != ''){
        if($idPuntoVenta != '') $sqlVenta = " AND v.idPuntoVenta = '$idPuntoVenta'";
        if($idVendedor != '') $sqlVendedor = " AND v.idVendedor = '$idVendedor'";
        if($idZona != '') $sqlZona = " AND pv.idZona = '$idZona'";
        if($fechaInicio !='' && $fechaFin !='') $sqlFecha1 = " AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
        if($fechaInicio !='' && $fechaFin =='') $sqlFecha2 = " AND v.fecha >= '$fechaInicio'";
        if($fechaInicio =='' && $fechaFin !='') $sqlFecha3 = " AND v.fecha <= '$fechaFin'";

        $qVisitas = "SELECT v.idVisita,  v.idVendedor, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre AS nombrePunto,
        p.nombre AS nombrePersona, p.idPersona, u.idUsuario, u.idPersona, pv.idZona, z.idZona, z.nombre AS nombreZona FROM visita v, persona p, puntoventa pv, usuario u, zona z WHERE v.idPuntoVenta=pv.idPuntoVenta 
        AND v.idVendedor=u.idUsuario AND u.idPersona=p.idPersona AND pv.idZona=z.idZona";
        $qVisitasCount.=$qVisitas.$sqlVenta.$sqlFecha1.$sqlFecha2.$sqlFecha3.$sqlVendedor.$sqlZona;
        $qVisitas .=$sqlVenta.$sqlFecha1.$sqlFecha2.$sqlFecha3.$sqlVendedor.$sqlZona." ORDER BY idVisita DESC LIMIT $offset,$per_page";
            $queryVisitas = mysqli_query($con,$qVisitas);
            $queryVisitasCount = mysqli_query($con,$qVisitasCount);

    }else{
        $queryVisitas = mysqli_query($con,"SELECT v.idVisita, v.idVendedor, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre AS nombrePunto,
        p.nombre AS nombrePersona, p.idPersona, u.idUsuario, u.idPersona, pv.idZona, z.idZona, z.nombre AS nombreZona FROM visita v, persona p, puntoventa pv, usuario u, zona z WHERE v.idPuntoVenta=pv.idPuntoVenta 
        AND v.idVendedor=u.idUsuario AND u.idPersona=p.idPersona AND pv.idZona=z.idZona ORDER BY idVisita DESC LIMIT $offset,$per_page");
        $queryVisitasCount = mysqli_query($con,"SELECT v.idVisita, v.idVendedor, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre AS nombrePunto,
        p.nombre AS nombrePersona, p.idPersona, u.idUsuario, u.idPersona, pv.idZona, z.idZona, z.nombre AS nombreZona FROM visita v, persona p, puntoventa pv, usuario u, zona z WHERE v.idPuntoVenta=pv.idPuntoVenta 
        AND v.idVendedor=u.idUsuario AND u.idPersona=p.idPersona AND pv.idZona=z.idZona");
    }
        $total_pages = mysqli_num_rows($queryVisitasCount);
        $total_pages = ceil($total_pages/$per_page);
        $reload = 'tableroVisitas.php';

    //tabla
    while($res=mysqli_fetch_array($queryVisitas)){
        $idVisita=$res['idVisita'];
        $vendedor=$res['nombrePersona'];
        $idZona=$res['nombreZona'];
        $idPuntoVenta=$res['nombrePunto'];
        $fecha = $res['fecha'];
        $visitas .= "<tr> 
                <th> $vendedor </th>
                <td> $idZona </td>
                <td> $idPuntoVenta </td>
                <td> $fecha </td>
                <td><button type='button' data-id='$idVisita' class='btn' id='btnDetalleModal' data-toggle='modal' data-target='#modalDetalles'>Detalles <i class='far fa-eye'></i>
              </button><button type='button' data-id='$idVisita' class='btn' style='padding:0%;margin:0%' id='btnEvidenciaModal' data-toggle='modal' data-target='#modalEvidencia'> Evidencia <i class='far fa-image'></i></button></td>
            </tr>";
    } 

    //paginación
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "visitas" => $visitas,
                    "pagination" => $pagination
                );
              echo json_encode($array);

//FILTRADO BÚSQUEDA
}elseif($action == "getPVentaFiltro"){
    $visitas = mysqli_query($con,"SELECT * FROM puntoventa"); 
    while($res = mysqli_fetch_array($visitas)){
        echo "<a class='dropdown-item opcFilPunVenta' href='#' data-id='".$res['idPuntoVenta']."' data-tipVisita='".$res['nombre']."'>".$res['nombre']."</a>";         
    } 

}elseif($action == "getVendedorFiltro"){
    $vende = mysqli_query($con,"SELECT p.idPersona, p.nombre, u.idPersona, u.idTipoUsuario, u.idUsuario From 
    persona p, usuario u WHERE p.idPersona=u.idPersona AND u.idTipoUsuario=2"); 
    while($res = mysqli_fetch_array($vende)){
        echo "<a class='dropdown-item opcFilVendedor' href='#' data-id='".$res['idUsuario']."' data-tipVende='".$res['nombre']."'>".$res['nombre']."</a>";         
    }

}elseif($action == "getZonaFiltro"){
    $zona = mysqli_query($con,"SELECT * from zona"); 
    while($res = mysqli_fetch_array($zona)){
        echo "<a class='dropdown-item opcFilZona' href='#' data-id='".$res['idZona']."' data-tipZona='".$res['nombre']."'>".$res['nombre']."</a>";         
    } 

//detalles
}elseif($action=="getDetalles"){
        $idVisita=(isset($_REQUEST['idVisita'])&& $_REQUEST['idVisita'] !=NULL)?$_REQUEST['idVisita']:'';
        $query= mysqli_query($con,"SELECT v.idVisita, d.idDetallesVisita, d.idVisita, d.idProducto, p.idProducto, p.nombre, d.idTipoExibicion, d.existencia, d.precio, d.frentes, d.nivel, t.idTipoExibicion, t.tipoExibicion
        FROM detallesvisita d, visita v, producto p, tipoexibicion t WHERE v.idVisita = d.idVisita AND v.idVisita = $idVisita AND p.idProducto=d.idProducto AND d.idTipoExibicion=t.idTipoExibicion ORDER BY idDetallesVisita");
        while($res=mysqli_fetch_array($query)){
            $idDetallesVisita=$res['idDetallesVisita'];
            $producto=$res['nombre'];
            $idTipoExibicion = $res['tipoExibicion'];
            $existencia = $res['existencia'];
            $precio = $res['precio'];
            $frentes = $res['frentes'];
            $nivel = $res['nivel'];
             
            echo "<tr> 
                    <td> $producto </td>
                    <td> $idTipoExibicion </td>
                    <td> $existencia </td>
                    <td> $precio </td>
                    <td> $frentes </td>
                    <td> $nivel </td>
                    <td><button type='button' data-id='$idDetallesVisita' class='btn' style='padding:0%;margin:0%' id='btnMatrizModal' data-toggle='modal' data-target='#modalMatriz'><i class='fab fa-buromobelexperte'></i> Matriz</button>
              </td>
                </tr>";
        } 

}elseif($action=="getDetallesVisita"){
    $idVisita=(isset($_REQUEST['idVisita'])&& $_REQUEST['idVisita'] !=NULL)?$_REQUEST['idVisita']:'';
    $query= mysqli_query($con,"SELECT v.idVisita, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre, pv.idZona, z.idZona, z.nombre AS nombreZona
    FROM detallesvisita d, visita v, puntoventa pv, zona z WHERE v.idVisita = d.idVisita AND pv.idZona=z.idZona AND v.idVisita = $idVisita AND pv.idPuntoVenta= v.idPuntoVenta LIMIT 1");
    while($res=mysqli_fetch_array($query)){
        $idDetallesVisita=$res['idDetallesVisita'];
        $idPuntoVenta=$res['nombre'];
        $fecha = $res['fecha'];
        $idZona = $res['nombreZona'];
         
        echo "<tr> 
                <td> &nbsp &nbsp $idZona &nbsp &nbsp &nbsp</td>
                <td> &nbsp &nbsp &nbsp $idPuntoVenta &nbsp &nbsp</td>
                <td> &nbsp &nbsp &nbsp $fecha </td>
            </tr>";
    } 

//detalles Matriz
}elseif($action=="getDetalleMatrizSup"){
        $idDetallesVisita=(isset($_REQUEST['idDetallesVisita'])&& $_REQUEST['idDetallesVisita'] !=NULL)?$_REQUEST['idDetallesVisita']:'';
        $query= mysqli_query($con,"SELECT * FROM detallesvisita d, matrizubicacion u, visita v 
        WHERE d.idDetallesVisita = u.idDetallesVisita AND d.idDetallesVisita = $idDetallesVisita AND d.idVisita = v.idVisita");
        while($res=mysqli_fetch_array($query)){
            $supIzq = $res['supIzq'];
            $supCen = $res['supCentro'];
            $supDer = $res['supDer'];
           
            echo "<tr> 
                    <td> $supIzq </td>
                    <td> $supCen </td>
                    <td> $supDer </td>    
                </tr>";
        } 
}elseif($action=="getDetalleMatrizCen"){
        $idDetallesVisita=(isset($_REQUEST['idDetallesVisita'])&& $_REQUEST['idDetallesVisita'] !=NULL)?$_REQUEST['idDetallesVisita']:'';
        $query= mysqli_query($con,"SELECT * FROM detallesvisita d, matrizubicacion u, visita v 
        WHERE d.idDetallesVisita = u.idDetallesVisita AND d.idDetallesVisita = $idDetallesVisita AND d.idVisita = v.idVisita");
        while($res=mysqli_fetch_array($query)){
            $centroIzq = $res['centroIzq'];
            $centroCentro = $res['centroCentro'];
            $centroDer = $res['centroDer'];
            
            echo "<tr> 
                    <td> $centroIzq </td>
                    <td> $centroCentro </td>
                    <td> $centroDer </td> 
                </tr>";
        } 
}elseif($action=="getDetalleMatrizInf"){
        $idDetallesVisita=(isset($_REQUEST['idDetallesVisita'])&& $_REQUEST['idDetallesVisita'] !=NULL)?$_REQUEST['idDetallesVisita']:'';
        $query= mysqli_query($con,"SELECT * FROM detallesvisita d, matrizubicacion u, visita v 
        WHERE d.idDetallesVisita = u.idDetallesVisita AND d.idDetallesVisita = $idDetallesVisita AND d.idVisita = v.idVisita");
        while($res=mysqli_fetch_array($query)){
            $infIzq = $res['infIzq'];
            $infCen = $res['infCentro'];
            $infDer = $res['infDer'];
           
            echo "<tr> 
                    <td> $infIzq </td>
                    <td> $infCen </td>
                    <td> $infDer </td>    
                </tr>";
        } 

}elseif($action=="getEvidencia"){
    $idVisita=(isset($_REQUEST['idVisita'])&& $_REQUEST['idVisita'] !=NULL)?$_REQUEST['idVisita']:'';
    $query= mysqli_query($con,"SELECT d.idDetallesVisita, d.idVisita, i.idDetallesVisita, i.ruta, i.idImgDetallesVisita, v.idVisita, d.idProducto, p.idProducto, p.nombre,
    v.fecha, d.precio, d.frentes FROM detallesvisita d, imgdetallesvisita i, visita v, producto p WHERE v.idVisita = d.idVisita 
    AND v.idVisita = $idVisita AND d.idDetallesVisita = i.idDetallesVisita AND d.idProducto = p.idProducto");
    
    while($res=mysqli_fetch_array($query)){
    $imagen=$res['ruta'];
    $name = 'Img';
    $idProducto = $res['nombre'];
    $fecha = $res['fecha'];
    $frentes = $res['frentes'];
    $precio = $res['precio'];

    echo "<tr> 
            <td>$idProducto</td>
            <td>$frentes</td>
            <td>$$precio</td>
            <td>$fecha</td>
            <td><img width='130px' height='auto' src='$imagen'> </td>
        </tr>";
    }
}
?>
