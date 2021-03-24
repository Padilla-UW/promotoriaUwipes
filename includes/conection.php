<?php 

//Datos de servidor
$server = "localhost";
$user = "root";
$password = "12345678";
$db = "db_promotoria_uwipes  ";
 
$con = (mysqli_connect($server,$user,$password,$db));

if(!$con){
    echo "Error en la conexion a la base de datos.";
}
?>