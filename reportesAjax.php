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

}elseif($action == "imprimirReporte"){
    $idVendedor=(isset($_REQUEST['idVendedor'])&& $_REQUEST['idVendedor'] !=NULL)?$_REQUEST['idVendedor']:'';
    $idZona=(isset($_REQUEST['idZona'])&& $_REQUEST['idZona'] !=NULL)?$_REQUEST['idZona']:'';
    $idPuntoVenta=(isset($_REQUEST['idPuntoVenta'])&& $_REQUEST['idPuntoVenta'] !=NULL)?$_REQUEST['idPuntoVenta']:'';
    $idInicio=(isset($_REQUEST['idInicio'])&& $_REQUEST['idInicio'] !=NULL)?$_REQUEST['idInicio']:'';
    $idFin=(isset($_REQUEST['idFin'])&& $_REQUEST['idFin'] !=NULL)?$_REQUEST['idFin']:'';

    //consulta general y conexión
        $queryGeneral="SELECT v.idVisita, v.idVendedor, v.idPuntoVenta, v.fecha, pv.idPuntoVenta, pv.nombre AS nomPVenta,
        u.idUsuario, u.idPersona, p.idPersona, p.nombre AS nomPersona, pv.idZona, z.idZona, z.nombre AS nomZona 
        FROM visita v, puntoventa pv, usuario u, persona p, zona z WHERE v.idVendedor=u.idUsuario AND u.idPersona=p.idPersona 
        AND v.idPuntoVenta=pv.idPuntoVenta AND pv.idZona=z.idZona ORDER BY fecha";

    //Validación con cierto vendedor
        if($idVendedor !=''){
            $queryVendedor = " AND u.idUsuario = $idVendedor ";
        }
        $queryGeneral.=$queryVendedor;
        echo $queryGeneral;
        $query=mysqli_query($con,$queryGeneral);

    //Validación con cierta zona
        if($idZona !=''){
            $queryZona = " AND z.idZona = $idZona ";
        }
        $queryGeneral.=$queryZona;
        $query=mysqli_query($con,$queryGeneral);

    //Validación con cierto punto de venta
    if($idPuntoVenta !=''){
        $queryPV = " AND pv.idPuntoVenta = $idPuntoVenta ";
    }
    $queryGeneral.=$queryPV;
    $query=mysqli_query($con,$queryGeneral);

    //Validación con cierta fecha
    if($idInicio !='' && $idFin !=''){
        $queryFecha = " AND v.fecha BETWEEN '$idInicio' and '$idFin'";

    }elseif($idInicio !='' && $idFin =='') {
        $queryFecha = " AND v.fecha >= '$idInicio'";

    }elseif($idInicio =='' && $idFin !='') {
        $queryFecha = " AND v.fecha <= '$idFin'";
    }
    $queryGeneral.=$queryFecha;

    $query=mysqli_query($con, $queryGeneral);

    //creo hoja en excel con el header en posición
    //inicializo contador
        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $writer = new Xlsx($spreadsheet);
        $headerRowData = ["Vendedor", "Zona", "Punto Venta", "Fecha"];
        $spreadsheet->getActiveSheet()->fromArray($headerRowData,NULL,'A3'); 
        $c = 5;
     
    //while con datos establecidos y consulta de detalles
    //rowdata para escribir datos en  excel
        while($res=mysqli_fetch_array($query)){
            $fecha=$res['fecha'];
            $nomZona=$res['nomZona'];
            $nomPVenta=$res['nomPVenta'];
            $nomPersona=$res['nomPersona'];
            
            $rowData=[$res['nomPersona'], $res['nomZona'],  $res['nomPVenta'], $res['fecha']];
                  $spreadsheet->getActiveSheet()->fromArray($rowData,NULL,'A'.$c);
                  $c++;
          }

    //Damos ajuste automático a las celdas de Excel
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    //guardamos en excel
        $writer->save('reporte.xlsx');
        echo '<a href="reporte.xlsx" download="REPORTES-'.date('Y-m-d').'.xlsx"><button id="buttonDescargar"><i class="fas fa-download"></i></button></a>';
   }

?>