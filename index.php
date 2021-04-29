<?php
session_start();
include('includes/header.php');
?>

<style>
button:hover {
  opacity: 0.8;
}

.container {
  width: 100%;
  background-color:#f4f4f4;
  padding-top:10px;
  margin-top:70px;
  border-radius: 6px;
  border: 3px solid #dedede;
}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: block;
  border: 1px solid #dedede;
  box-sizing: border-box;
  border-radius: 6px;
}

button {
  background-color: #dedede;
  color: black;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 6px;
}

.fa-user-circle{
    color:#d4d4d4;
}
</style> 

<body style="background-color:#eeeeee;">
<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-6">
<div style="display: flex; align-items: center; justify-content: center;">
<i class="far fa-user-circle fa-5x"></i>
    </div>
    <input id="correoLogin" type="text" placeholder="CORREO" name="correoUsuario" required>
    <input id="passwordLogin" type="password" placeholder="CONTRASEÑA" name="password" required>
    <p style="color:red;"> </p>
    <button data-id="" id="btnLogin">Iniciar Sesión</button>
   </div>
</div>
</div>
</body>
<?php include ('includes/footer.php')?>

<script>
//verificamos que existan los datos de correo y pass
//ajax manda a sus respectivas sesiones php
$("#btnLogin").click(function(){
  var correoLogin = $('#correoLogin').val();
  var passwordLogin = $('#passwordLogin').val();

  if(correoLogin == "" || passwordLogin == "") {

}else{
      var parametros = {
        "action": "entrarLogin",
        "correoLogin": correoLogin,
        "passwordLogin": passwordLogin
      }};
      $.ajax({
        data:parametros,
        url:'usuarioAjax.php',
        success:function(data){
          console.log(data);
          if(data=="Administrador"){
            window.location="dashboard.php";
          }
          if(data=="Vendedor"){
            window.location="visitas.php";
          }
          if(data==0){
            $("p").text("Datos Incorrectos");
          }
        }
      });
    });

</script>
