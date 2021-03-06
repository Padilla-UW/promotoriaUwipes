<?php
session_start();
// añadimos conexion y cachamos datos
include('includes/conection.php');
    
$action=(isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

//Inicia parte de USUARIO ******************************************************************************************
//mostramos datos de la db en la tabla
if($action=="getUsuarios"){
    //paginación
    include 'includes/pagination.php';
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    $per_page = 8; //la cantidad de registros que desea mostrar
    $adjacents  = 3; //brecha entre páginas después de varios adyacentes
    $offset = ($page - 1) * $per_page;

    //filtrado x tipo de usuario/nombre
    $idTipoUsuario=(isset($_REQUEST['idTipoUsuario'])&& $_REQUEST['idTipoUsuario'] !=NULL)?$_REQUEST['idTipoUsuario']:'';
    $nombre=(isset($_REQUEST['busquedaNombre'])&& $_REQUEST['busquedaNombre'] !=NULL)?$_REQUEST['busquedaNombre']:'';

    if($idTipoUsuario != '' || $nombre != ''){
        if($idTipoUsuario != '') $sqlTipoU = " AND t.idTipoUsuario = '$idTipoUsuario'";
        if($nombre != '') $sqlTipoN = " AND p.nombre LIKE '%$nombre%'";

        $qUsuarios = "SELECT p.nombre, p.idPersona, u.correo, p.ciudad, u.idTipoUsuario, t.idTipoUsuario, t.tipoUsuario FROM 
        usuario u, persona p, tipousuario t WHERE u.idPersona = p.idPersona AND t.idTipoUsuario=u.idTipoUsuario AND p.idPersona=u.idPersona";
        $qUsuariosCount.=$qUsuarios.$sqlTipoU.$sqlTipoN;
        $qUsuarios.=$sqlTipoU.$sqlTipoN." ORDER BY nombre ASC LIMIT $offset,$per_page";
            
            $queryUsuarios = mysqli_query($con,$qUsuarios);
            $queryUsuariosCount = mysqli_query($con,$qUsuariosCount);
        
    }else{
        $queryUsuarios=mysqli_query($con,"SELECT p.nombre, p.idPersona, u.correo, p.ciudad, u.idTipoUsuario, t.idTipoUsuario, t.tipoUsuario FROM 
        usuario u, persona p, tipousuario t WHERE u.idPersona = p.idPersona AND t.idTipoUsuario=u.idTipoUsuario ORDER BY nombre ASC LIMIT $offset,$per_page");
        $queryUsuariosCount =mysqli_query($con, "SELECT p.nombre, p.idPersona, u.correo, p.ciudad, u.idTipoUsuario, t.idTipoUsuario, t.tipoUsuario FROM 
        usuario u, persona p, tipousuario t WHERE u.idPersona = p.idPersona AND t.idTipoUsuario=u.idTipoUsuario AND p.idPersona=u.idPersona");
    }
        $total_pages = mysqli_num_rows($queryUsuariosCount);
        $total_pages = ceil($total_pages/$per_page);
        $reload = 'tableroUsuarios.php';

    //tabla
    while($res=mysqli_fetch_array($queryUsuarios)){
        $idPersona=$res['idPersona'];
        $nombre=$res['nombre'];
        $idTipoUsuario=$res['tipoUsuario'];
        $correo = $res['correo'];
        $ciudad = $res['ciudad'];
        $usuarios .= "<tr> 
                <th> $nombre </th>
                <td> $idTipoUsuario </td>
                <td> $correo </td>
                <td> $ciudad </td>
                <td><button type='button' data-id='$idPersona' class='btn' id='btnEditModal' data-toggle='modal' data-target='#modalEditar'>Datos <i class='far fa-edit'></i></button>
              <button type='button' data-id='$idPersona' class='btn' id='btnPassModal' data-toggle='modal' data-target='#modalPassword'>Contraseña <i class='fas fa-user-lock'></i></button></td>
            </tr>";
    } 

    //paginación
                $pagination=paginate($reload, $page, $total_pages, $adjacents);
                $array = array(
                    "usuarios" => $usuarios,
                    "pagination" => $pagination
                );
              echo json_encode($array);

//parte de editar/actualizar usuario
}elseif($action=="getDatosUsuario"){
    $idPersona=(isset($_REQUEST['idPersona'])&& $_REQUEST['idPersona'] !=NULL)?$_REQUEST['idPersona']:'';
    $query=mysqli_fetch_array(mysqli_query($con,"SELECT p.idPersona, p.nombre, p.apellidos, p.telefono, p.ciudad, u.idUsuario, u.idPersona, u.idTipoUsuario, u.correo, u.contrasena FROM persona p, usuario u WHERE p.idPersona=u.idPersona AND p.idPersona= $idPersona"));
    echo json_encode($query);

}elseif($action=="editarUsuario"){
    $idPersona=(isset($_REQUEST['idPersona'])&& $_REQUEST['idPersona'] !=NULL)?$_REQUEST['idPersona']:''; 
    $nombreEdit=(isset($_REQUEST['nombreEdit'])&& $_REQUEST['nombreEdit'] !=NULL)?$_REQUEST['nombreEdit']:'';
    $apellidosEdit=(isset($_REQUEST['apellidosEdit'])&& $_REQUEST['apellidosEdit'] !=NULL)?$_REQUEST['apellidosEdit']:'';
    $telEdit=(isset($_REQUEST['telEdit'])&& $_REQUEST['telEdit'] !=NULL)?$_REQUEST['telEdit']:'';
    $ciudadEdit=(isset($_REQUEST['ciudadEdit'])&& $_REQUEST['ciudadEdit'] !=NULL)?$_REQUEST['ciudadEdit']:'';
    $rolEdit=(isset($_REQUEST['rolEdit'])&& $_REQUEST['rolEdit'] !=NULL)?$_REQUEST['rolEdit']:'';
    $correoEdit=(isset($_REQUEST['correoEdit'])&& $_REQUEST['correoEdit'] !=NULL)?$_REQUEST['correoEdit']:'';

    mysqli_query($con,'BEGIN');
    $updatePersona = "UPDATE usuario u, persona p SET nombre='$nombreEdit', apellidos='$apellidosEdit', 
    telefono='$telEdit', ciudad='$ciudadEdit' WHERE p.idPersona=u.idPersona AND p.idPersona= $idPersona"; 
    $conUpdatePersona = mysqli_query($con, $updatePersona);
    $updateUsuario = "UPDATE usuario u, persona p SET u.idTipoUsuario='$rolEdit', u.correo='$correoEdit' 
    WHERE p.idPersona=u.idPersona AND p.idPersona= $idPersona";
    $conUpdateUsuario = mysqli_query($con, $updateUsuario);

    if($conUpdatePersona && $conUpdateUsuario ){
        mysqli_query($con,'COMMIT');
        echo 1;
    }else{
        mysqli_query($con,'ROLLBACK');
        echo 0; 
    }

//parte de editar contraseña
}elseif($action=="editPassword"){
    $idPersona=(isset($_REQUEST['idPersona'])&& $_REQUEST['idPersona'] !=NULL)?$_REQUEST['idPersona']:''; 
    $passwordEdit=(isset($_REQUEST['passwordEdit'])&& $_REQUEST['passwordEdit'] !=NULL)?$_REQUEST['passwordEdit']:'';
        
    mysqli_query($con,'BEGIN');
    $updateContrasena = "UPDATE persona p, usuario u SET u.contrasena = SHA1('$passwordEdit') WHERE p.idPersona=u.idPersona AND p.idPersona= $idPersona";
    $conUpdateContrasena=mysqli_query($con, $updateContrasena);

    if($conUpdateContrasena){
        mysqli_query($con,'COMMIT');
        echo 1;
    }else{
        mysqli_query($con,'ROLLBACK');
        echo 0; 
    }
    
//agregar nuevo usuario
}elseif($action=="agregarUsuario"){
    $idPersona=(isset($_REQUEST['idPersona'])&& $_REQUEST['idPersona'] !=NULL)?$_REQUEST['idPersona']:''; 
    $nombreAdd=(isset($_REQUEST['nombreAdd'])&& $_REQUEST['nombreAdd'] !=NULL)?$_REQUEST['nombreAdd']:'';
    $apellidosAdd=(isset($_REQUEST['apellidosAdd'])&& $_REQUEST['apellidosAdd'] !=NULL)?$_REQUEST['apellidosAdd']:'';
    $telAdd=(isset($_REQUEST['telAdd'])&& $_REQUEST['telAdd'] !=NULL)?$_REQUEST['telAdd']:'';
    $ciudadAdd=(isset($_REQUEST['ciudadAdd'])&& $_REQUEST['ciudadAdd'] !=NULL)?$_REQUEST['ciudadAdd']:'';
    $idUsuario=(isset($_REQUEST['idUsuario'])&& $_REQUEST['idUsuario'] !=NULL)?$_REQUEST['idUsuario']:'';
    $rolAdd=(isset($_REQUEST['rolAdd'])&& $_REQUEST['rolAdd'] !=NULL)?$_REQUEST['rolAdd']:'';
    $correoAdd=(isset($_REQUEST['correoAdd'])&& $_REQUEST['correoAdd'] !=NULL)?$_REQUEST['correoAdd']:'';
    $passwordAdd=(isset($_REQUEST['passwordAdd'])&& $_REQUEST['passwordAdd'] !=NULL)?$_REQUEST['passwordAdd']:'';

    mysqli_query($con,'BEGIN');
    $duplicado=mysqli_query($con, "SELECT correo FROM usuario WHERE correo='$correoAdd'");

    if(mysqli_num_rows($duplicado)>0){
        mysqli_query($con,'ROLLBACK');
        echo 0;
        }else{      
            $insertPersona = "INSERT INTO persona (nombre, apellidos, telefono, ciudad)
            VALUES ('$nombreAdd', '$apellidosAdd', '$telAdd', '$ciudadAdd')"; 
            mysqli_query($con, $insertPersona);
            $idPersona = mysqli_insert_id($con);
            $insertUsuario = "INSERT INTO usuario (idPersona, idTipoUsuario, correo, contrasena)
            VALUES ('$idPersona', '$rolAdd', '$correoAdd', SHA1('$passwordAdd'))";
            mysqli_query($con, $insertUsuario);
        mysqli_query($con,'COMMIT');
        echo 1;
        }

//SELECT AUTOMÁTICO
}elseif($action=="getTipoUsuario"){
    $queryCadena=mysqli_query($con,"SELECT idTipoUsuario, tipoUsuario From tipousuario");
    echo "<option value=''>Seleccione</option>";
   while($cadena = mysqli_fetch_array($queryCadena)){
       $idTipoUsuario = $cadena['idTipoUsuario'];
       $tipoUsuario= $cadena['tipoUsuario'];
      echo "<option value='".$idTipoUsuario."'>$tipoUsuario</option>";
   } 

//FILTRADO BÚSQUEDA
}elseif($action == "getUsuarioFiltro"){
    $usuarios = mysqli_query($con,"SELECT * from tipousuario"); 
    while($res = mysqli_fetch_array($usuarios)){
        echo "<a class='dropdown-item opcFilTipoUsu' href='#' data-id='".$res['idTipoUsuario']."' data-tipUsuario='".$res['tipoUsuario']."'>".$res['tipoUsuario']."</a>";         
    } 
   
//Entrar Login con Sesiones
}elseif($action=="entrarLogin"){
    $correoLogin=(isset($_REQUEST['correoLogin'])&& $_REQUEST['correoLogin'] !=NULL)?$_REQUEST['correoLogin']:'';
    $passwordLogin=sha1((isset($_REQUEST['passwordLogin'])&& $_REQUEST['passwordLogin'] !=NULL)?$_REQUEST['passwordLogin']:'');

    $result = mysqli_query($con,"SELECT * FROM usuario u, tipousuario t WHERE u.correo = '$correoLogin' and u.contrasena = '$passwordLogin' AND u.idTipoUsuario=t.idTipoUsuario");
    $row  = mysqli_fetch_array($result);
    
    if(count($row)>0){
        $_SESSION["idUsuario"] = $row['idUsuario'];
        $_SESSION["tipoUsuario"] = $row['tipoUsuario'];
    }else{
        echo 0;
    }

 if(isset($_SESSION["idUsuario"])){   
     if($_SESSION["tipoUsuario"]=="Administrador"){
        echo $_SESSION["tipoUsuario"];
     }elseif($_SESSION["tipoUsuario"]=="Vendedor"){
        echo $_SESSION["tipoUsuario"];
     }  
 }else{
     echo 0;
 }
}

?>
