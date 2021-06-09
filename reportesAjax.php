<?php
session_start();
// añadimos conexion y cachamos datos
include('includes/conection.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//selects automáticos
if($action=="getVendedor"){
    $querySelect=mysqli_query($con,"SELECT p.idPersona, p.nombre, u.idPersona, u.idTipoUsuario, u.idUsuario From 
    persona p, usuario u WHERE p.idPersona=u.idPersona AND u.idTipoUsuario=2");
        echo "<option value=''>Seleccione</option>";
       while($res = mysqli_fetch_array($querySelect)){
           $idUsuario = $res['idUsuario'];
           $nombre= $res['nombre'];
          echo "<option value='".$idUsuario."'>$nombre</option>";
       } 

}elseif($action=="getZona"){
    $querySelect=mysqli_query($con,"SELECT * From zona");
        echo "<option value=''>Seleccione</option>";
       while($res = mysqli_fetch_array($querySelect)){
           $idZona = $res['idZona'];
           $nombre= $res['nombre'];
          echo "<option value='".$idZona."'>$nombre</option>";
       } 

}elseif($action=="getPunVenta"){
    $querySelect=mysqli_query($con,"SELECT * FROM puntoventa");
        echo "<option value=''>Seleccione</option>";
       while($res = mysqli_fetch_array($querySelect)){
           $idPuntoVenta = $res['idPuntoVenta'];
           $nombre= $res['nombre'];
          echo "<option value='".$idPuntoVenta."'>$nombre</option>";
       } 

}elseif($action=="getCategoria"){
    $querySelect=mysqli_query($con,"SELECT * FROM categoria");
        echo "<option value=''>Seleccione</option>";
       while($res = mysqli_fetch_array($querySelect)){
           $idCategoria = $res['idCategoria'];
           $categoria= $res['categoria'];
          echo "<option value='".$idCategoria."'>$categoria</option>";
       } 

}elseif($action == "imprimirReporte"){
    $idVendedor=(isset($_REQUEST['idVendedor'])&& $_REQUEST['idVendedor'] !=NULL)?$_REQUEST['idVendedor']:'';
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:'';
    $idInicio=(isset($_REQUEST['idInicio'])&& $_REQUEST['idInicio'] !=NULL)?$_REQUEST['idInicio']:'';
    $idFin=(isset($_REQUEST['idFin'])&& $_REQUEST['idFin'] !=NULL)?$_REQUEST['idFin']:'';
    $idCategoria=(isset($_REQUEST['idCategoria'])&& $_REQUEST['idCategoria'] !=NULL)?$_REQUEST['idCategoria']:'';
    $segmento=(isset($_REQUEST['segmento'])&& $_REQUEST['segmento'] !=NULL)?$_REQUEST['segmento']:'';
    $idProcedencia=(isset($_REQUEST['idProcedencia'])&& $_REQUEST['idProcedencia'] !=NULL)?$_REQUEST['idProcedencia']:'';

    //consulta general y conexión
        $queryGeneral="SELECT v.idVendedor, v.fecha, pv.idPuntoVenta, pv.nombre AS nomPVenta,
        p.nombre AS nomPersona, z.nombre AS nomZona, s.nombre AS nomSucursal, s.numero,
        d.existencia, d.precio, d.frentes, d.nivel, pro.nombre AS nomProducto, te.tipoExibicion,
        c.categoria, pro.segmento, pro.procedencia, mu.supIzq, mu.supCentro, 
        mu.supDer, mu.centroIzq, mu.centroCentro, mu.centroDer, mu.infIzq, mu.infCentro, mu.infDer, CASE WHEN pro.nombre = mu.supIzq THEN 'SupIzq' 
        WHEN pro.nombre = mu.supCentro THEN 'supCentro' WHEN pro.nombre = mu.supDer THEN 'supDer' WHEN pro.nombre = mu.centroIzq THEN 'centroIzq' WHEN pro.nombre = mu.centroCentro THEN 'centroCentro' 
        WHEN pro.nombre = mu.centroDer THEN 'centroDer' WHEN pro.nombre = mu.infIzq THEN 'infIzq' WHEN pro.nombre = mu.infCentro THEN 'infCentro' WHEN pro.nombre = mu.infDer THEN 'infDer' END 'ubicacion' FROM visita v, puntoventa pv,
        usuario u, persona p, zona z, sucursal s, detallesvisita d, producto pro, tipoexibicion te, categoria c, matrizubicacion mu
        WHERE v.idVendedor=u.idUsuario AND u.idPersona=p.idPersona AND te.idTipoExibicion=d.idTipoExibicion AND pro.idCategoria=c.idCategoria
        AND v.idPuntoVenta=pv.idPuntoVenta AND pv.idZona=z.idZona AND pv.idPuntoVenta=s.idPuntoVenta AND d.idVisita=v.idVisita AND pro.idProducto=d.idProducto
        AND mu.idDetallesVisita=d.idDetallesVisita AND v.idSucursal = s.idSucursal ";

    //Validación con cierto vendedor
        if($idVendedor !=''){
            $queryVendedor = " AND u.idUsuario = $idVendedor";
        }
        
    //Validación con cierta zona
        if($idZona !=''){
            $queryZona = " AND z.idZona = $idZona";
        }      

    //Validación con cierto punto de venta
    if($idPuntoVenta !=''){
        $queryPV = " AND pv.idPuntoVenta = $idPuntoVenta";
    }
    
    //Validación con cierta categoria
    if($idCategoria !=''){
        $queryCategoria = " AND c.idCategoria = $idCategoria";
    }

    //Validación con cierto segmento
    if($segmento !=''){
        $querySegmento = " AND pro.segmento = '$segmento'";
    }
    
    //Validación con cierta procedencia
    if($idProcedencia !=''){
        $queryProcedencia = " AND pro.procedencia = '$idProcedencia'";
    } 

    //Validación con cierta fecha
    if($idInicio !='' && $idFin !=''){
        $queryFecha = " AND v.fecha BETWEEN '$idInicio' and '$idFin'";
    }elseif($idInicio !='' && $idFin =='') {
        $queryFecha = " AND v.fecha >= '$idInicio'";
    }elseif($idInicio =='' && $idFin !='') {
        $queryFecha = " AND v.fecha <= '$idFin'";
    }

    $queryGeneral.=$queryVendedor.$queryZona.$queryPV.$queryCategoria.$querySegmento.$queryProcedencia.$queryFecha." ORDER BY fecha";
    $query=mysqli_query($con,$queryGeneral);
    echo ($queryGeneral);

    //creo hoja en excel con el header en posición
    //inicializo contador
        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $writer = new Xlsx($spreadsheet);
        $headerRowData = ["Producto", "Zona", "Punto de Venta", "Sucursal", "#Sucursal", "Vendedor", "Fecha", "Tipo Exhibición", "Categoría", "Segmento",
        "Procedencia", "Existencia", "Precio", "Frentes", "Nivel", "Ubicacion", "SupIzq", "SupCen", "SupDer", "cenIzq", "cenCen", "cenDer", "infIzq", "infCen", "infDer"];
        $spreadsheet->getActiveSheet()->fromArray($headerRowData,NULL,'A3'); 
        $c = 5;
     
    //while con datos establecidos y consulta de detalles
    //rowdata para escribir datos en  excel
        while($res=mysqli_fetch_array($query)){
            $fecha=$res['fecha'];
            $nomZona=$res['nomZona'];
            $nomPVenta=$res['nomPVenta'];
            $nomSucursal=$res['nomSucursal'];
            $numero=$res['numero'];
            $nomPersona=$res['nomPersona'];
            $nomProducto=$res['nomProducto'];
            $tipoExibicion=$res['tipoExibicion'];
            $categoria=$res['categoria'];
            $segmento=$res['segmento'];
            $procedencia=$res['procedencia'];
            $existencia=$res['existencia'];
            $precio=$res['precio'];
            $frentes=$res['frentes'];
            $supDer=$res['supDer'];
            $supCentro=$res['supCentro'];
            $supIzq=$res['supIzq'];
            $centroIzq=$res['centroIzq'];
            $centroCentro=$res['centroCentro'];
            $centroDer=$res['centroDer'];
            $infIzq=$res['infIzq'];
            $infCentro=$res['infCentro'];
            $infDer=$res['infDer'];
            $ubicacion=$res['ubicacion'];
            $nivel=$res['nivel'];
            
            $rowData=[$res['nomProducto'], $res['nomZona'],  $res['nomPVenta'], $res['nomSucursal'], $res['numero'], $res['nomPersona'], $res['fecha'],  $res['tipoExibicion'], $res['categoria'], $res['segmento'],
            $res['procedencia'], $res['existencia'],  $res['precio'], $res['frentes'], $res['nivel'], $res['ubicacion'], $res['supIzq'], $res['supCentro'], $res['supDer'],  $res['centroIzq'], $res['centroCentro'], $res['centroDer'], $res['infIzq'], $res['infCentro'], $res['infDer'] ];
                  $spreadsheet->getActiveSheet()->fromArray($rowData,NULL,'A'.$c);
                  $c++;
          }

    //Damos ajuste automático a las celdas de Excel
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        

    //guardamos en excel
        $writer->save('reporte.xlsx');
        echo '<a href="reporte.xlsx" download="REPORTES-'.date('Y-m-d').'.xlsx"><button id="buttonDescargar"><i class="fas fa-download"></i></button></a>';
   }

?>
