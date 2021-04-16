<?php
session_start();
// añadimos conexion y cachamos datos
include('includes/conection.php');
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//SELECT GRAFICA
if($action=="getPVenta"){
    $queryZona=mysqli_query($con,"SELECT * From puntoventa");
    echo "<option value=''>Seleccione Punto de Venta</option>";
   while($res = mysqli_fetch_array($queryZona)){
       $idPuntoVenta = $res['idPuntoVenta'];
       $nombre = $res['nombre'];
      echo "<option value='".$idPuntoVenta."'>$nombre</option>";
   } 

//TABLA PRECIOS PRODUCTOS PROPIOS
}elseif($action=="getPrecioProdPropio"){
$queryProductos = "SELECT * FROM producto WHERE procedencia='Propio' ORDER BY precio ASC LIMIT 5";
$query= mysqli_query($con, $queryProductos);
while($res=mysqli_fetch_array($query)){
    $nombre=$res['nombre'];
    $producto .= "<tr> 
            <td> $nombre </td>
        </tr>";
} 
            $array = array(
                "producto" => $producto
            );
          echo json_encode($array);

//TABLA PRECIOS PRODUCTOS COMPETENCIA
}elseif($action=="getPrecioProdCompetencia"){
    $queryProductos = "SELECT * FROM producto WHERE procedencia='Competencia' ORDER BY precio ASC LIMIT 5";
    $query= mysqli_query($con, $queryProductos);
    while($res=mysqli_fetch_array($query)){
        $nombre=$res['nombre'];
        $producto .= "<tr> 
                <td> $nombre </td>
            </tr>";
    } 
                $array = array(
                    "producto" => $producto
                );
              echo json_encode($array);
            
//TABLA PRECIOS PRODUCTOS PROPIOS EN MÁS PUNTOS DE VENTA (ACCESIBLES)
}elseif($action=="getPrecioProdAccesible"){
    $queryProductos = "SELECT d.idProducto, p.idProducto, p.nombre, count(DISTINCT(v.idPuntoVenta)) AS puntosVenta FROM 
    visita v, detallesvisita d, producto p WHERE d.idVisita = v.idVisita AND d.idProducto=p.idProducto GROUP BY d.idProducto ORDER BY puntosVenta DESC LIMIT 5";
    $query= mysqli_query($con, $queryProductos);
    while($res=mysqli_fetch_array($query)){
        $nombre=$res['nombre'];
        $producto .= "<tr> 
                <td> $nombre </td>
            </tr>";
    } 
                $array = array(
                    "producto" => $producto
                );
              echo json_encode($array);

//GRÁFICA PUNTOS DE VENTA/PRECIO/PRODUCTO
}elseif($action=="getGrafica"){
    $selGrafica=(isset($_REQUEST['selGrafica'])&& $_REQUEST['selGrafica'] !=NULL)?$_REQUEST['selGrafica']:'';

    $sqlProductos="SELECT v.idVisita, v.idPuntoVenta, v.fecha, p.idProducto, p.nombre, d.idDetallesVisita, d.idProducto, d.idVisita, d.precio 
    FROM visita v, detallesVisita d, producto p WHERE idPuntoVenta = 17 AND v.idVisita = d.idVisita AND p.idProducto=d.idProducto;";

    $result = mysqli_query($con,$sqlProductos);

    $data = array();
    foreach ($result as $row) {
        $data[] = $row;
    }

    mysqli_close($con);
    echo json_encode($data);

}

?>