<?php
session_start();

if($_SESSION["tipoUsuario"]=="Administrador"){
?>

<!-- MENÚ ADMINISTRADOR -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
  <img src="logo/logoUwipes.png" alt="" style="height: 42px;"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link" href="dashboard.php">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tableroVisitas.php">Visitas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="reportes.php">Reportes</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Administrar
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="tableroUsuarios.php">Usuarios</a>
          <a class="dropdown-item" href="tableroZonas.php">Zonas</a>
          <a class="dropdown-item" href="tableroPuntosVenta.php">Puntos de Venta</a>
          <a class="dropdown-item" href="productos.php">Productos</a>
        </div>
      </li>
    </ul>
        <a href="cerrarSesion.php" class="btn btn-light">Cerrar Sesión</a>
  </div>
</nav>

<?php
}elseif($_SESSION["tipoUsuario"]=="Vendedor"){
?>

<!-- Menú Vendedores -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><img src="logo/logoUwipes.png" alt="" style="height: 42px;"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link" href="visitas.php">Visitas</a>
      </li>
    </ul>
        <a href="cerrarSesion.php" class="btn btn-light">Cerrar Sesión</a>
  </div>
</nav>

<?php
} //cierre else SESSION para menú
?>
