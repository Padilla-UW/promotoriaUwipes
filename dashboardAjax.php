<?php
session_start();
// añadimos conexion y cachamos datos
include('includes/conection.php');
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//SELECT GRAFICA
if($action=="getPVenta"){
    $zona=(isset($_REQUEST['zona'])&& $_REQUEST['zona'] !=NULL)?$_REQUEST['zona']:'';
        if($zona != ''){
            $queryPVenta=mysqli_query($con,"SELECT * From zona z, puntoventa pv WHERE z.idZona = pv.idZona AND pv.idZona = $zona");
        }else{
            $queryPVenta=mysqli_query($con,"SELECT * From puntoventa");
        }
        echo "<option value=''>Seleccione</option>";
        while($res = mysqli_fetch_array($queryPVenta)){
            $idPuntoVenta = $res['idPuntoVenta'];
            $nombre = $res['nombre'];
           echo "<option value='".$idPuntoVenta."'>$nombre</option>";
        }  

}elseif($action=="getZona"){
    $queryZona=mysqli_query($con,"SELECT * From zona");
    echo "<option value=''>Seleccione</option>";
   while($res = mysqli_fetch_array($queryZona)){
       $idZona = $res['idZona'];
       $nombre = $res['nombre'];
      echo "<option value='".$idZona."'>$nombre</option>";
   } 

//TABLA PRECIOS PRODUCTOS PROPIOS BARATO
}elseif($action=="getPrecioProdPropio"){
$queryProductos = "SELECT * FROM producto p, imgProducto i WHERE procedencia='Propio' AND p.idProducto=i.idProducto ORDER BY precio ASC LIMIT 5";
$query= mysqli_query($con, $queryProductos);
while($res=mysqli_fetch_array($query)){
    $nombre=$res['nombre'];
    $img=$res['idProducto'];
    $producto .= "<tr> 
            <td> $nombre </td>
            <td><img width='150px' height='auto' src='imgProductos/".$img.".png'> </td>
        </tr>";
} 
            $array = array(
                "producto" => $producto
            );
          echo json_encode($array);

//TABLA PRECIOS PRODUCTOS COMPETENCIA BARATO
}elseif($action=="getPrecioProdCompetencia"){
    $queryProductos = "SELECT * FROM producto p, imgProducto i WHERE procedencia='Competencia' AND p.idProducto=i.idProducto ORDER BY precio ASC LIMIT 5";
    $query= mysqli_query($con, $queryProductos);
    while($res=mysqli_fetch_array($query)){
        $nombre=$res['nombre'];
        $img=$res['idProducto'];
        $producto .= "<tr> 
                <td> $nombre </td>
                <td><img width='150px' height='auto' src='imgProductos/".$img.".png'> </td>
            </tr>";
    } 
                $array = array(
                    "producto" => $producto
                );
              echo json_encode($array);
            
//TABLA PRECIOS PRODUCTOS PROPIOS EN MÁS PUNTOS DE VENTA (ACCESIBLES) BARATO
}elseif($action=="getPrecioProdAccesible"){
    $queryProductos = "SELECT d.idProducto, p.idProducto, p.nombre, i.idProducto, count(DISTINCT(v.idPuntoVenta)) AS puntosVenta FROM 
    visita v, detallesvisita d, producto p, imgproducto i WHERE d.idVisita = v.idVisita AND d.idProducto=p.idProducto AND p.idProducto=i.idProducto GROUP BY d.idProducto ORDER BY puntosVenta ASC LIMIT 5";
    $query= mysqli_query($con, $queryProductos);
    while($res=mysqli_fetch_array($query)){
        $nombre=$res['nombre'];
        $numPV=$res['puntosVenta'];
        $img=$res['idProducto'];
        $producto .= "<tr> 
                <td> $nombre </td>
                <td> $numPV </td>
                <td><img width='150px' height='auto' src='imgProductos/".$img.".png'> </td>
            </tr>";
    } 
                $array = array(
                    "producto" => $producto
                );
              echo json_encode($array);

//TABLA PRECIOS PRODUCTOS PROPIOS CARO
}elseif($action=="getPrecioProdPropioCaro"){
    $queryProductos = "SELECT * FROM producto p, imgProducto i WHERE procedencia='Propio' AND p.idProducto=i.idProducto ORDER BY precio DESC LIMIT 5";
    $query= mysqli_query($con, $queryProductos);
    while($res=mysqli_fetch_array($query)){
        $nombre=$res['nombre'];
        $img=$res['idProducto'];
        $producto .= "<tr> 
                <td> $nombre </td>
                <td><img width='150px' height='auto' src='imgProductos/".$img.".png'> </td>
            </tr>";
    } 
                $array = array(
                    "producto" => $producto
                );
              echo json_encode($array);
    
//TABLA PRECIOS PRODUCTOS COMPETENCIA CARO
}elseif($action=="getPrecioProdCompetenciaCaro"){
        $queryProductos = "SELECT * FROM producto p, imgProducto i WHERE procedencia='Competencia' AND p.idProducto=i.idProducto ORDER BY precio DESC LIMIT 5";
        $query= mysqli_query($con, $queryProductos);
        while($res=mysqli_fetch_array($query)){
            $nombre=$res['nombre'];
            $img=$res['idProducto'];
            $producto .= "<tr> 
                    <td> $nombre </td>
                    <td><img width='150px' height='auto' src='imgProductos/".$img.".png'> </td>
                </tr>";
        } 
                    $array = array(
                        "producto" => $producto
                    );
                  echo json_encode($array);
                
//TABLA PRECIOS PRODUCTOS PROPIOS EN MÁS PUNTOS DE VENTA CARO (ACCESIBLES)
}elseif($action=="getPrecioProdAccesibleCaro"){
        $queryProductos = "SELECT d.idProducto, p.idProducto, p.nombre, i.idProducto, count(DISTINCT(v.idPuntoVenta)) AS puntosVenta FROM 
        visita v, detallesvisita d, producto p, imgproducto i WHERE d.idVisita = v.idVisita AND d.idProducto=p.idProducto AND p.idProducto=i.idProducto GROUP BY d.idProducto ORDER BY puntosVenta DESC LIMIT 5";
        $query= mysqli_query($con, $queryProductos);
        while($res=mysqli_fetch_array($query)){
            $nombre=$res['nombre'];
            $numPV=$res['puntosVenta'];
            $img=$res['idProducto'];
            $producto .= "<tr> 
                    <td> $nombre </td>
                    <td> $numPV </td>
                    <td><img width='150px' height='auto' src='imgProductos/".$img.".png'> </td>
                </tr>";
        } 
                    $array = array(
                        "producto" => $producto
                    );
                  echo json_encode($array);
    
//GRÁFICA PUNTOS DE VENTA/PRECIO/PRODUCTO/FECHA X SEMANA
}elseif($action=="getGrafica"){
    $selGrafica=(isset($_REQUEST['selGrafica'])&& $_REQUEST['selGrafica'] !=NULL)?$_REQUEST['selGrafica']:'';
    $fechaSemana=(isset($_REQUEST['fechaSemana'])&& $_REQUEST['fechaSemana'] !=NULL)?$_REQUEST['fechaSemana']:'';

    $sqlProductos="SELECT v.idVisita, v.idPuntoVenta, v.fecha, p.idProducto, p.nombre, d.idDetallesVisita, d.idProducto, d.idVisita, d.precio 
    FROM visita v, detallesVisita d, producto p WHERE idPuntoVenta = '$selGrafica' AND v.idVisita = d.idVisita AND p.idProducto=d.idProducto
    AND (v.fecha>='$fechaSemana' AND v.fecha<date_add('$fechaSemana', INTERVAL 8 DAY))";

    $result = mysqli_query($con,$sqlProductos);

    $data = array();
    foreach ($result as $row) {
        $data[] = $row;
    }

    mysqli_close($con);
    echo json_encode($data);

}

?>
