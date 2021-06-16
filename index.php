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
  background-color:white;
  padding-top:10px;
  margin-top:70px;
  border-radius: 6px;
}

input[type=text], input[type=password]{
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: block;
  border: 1px solid #004c98;
  box-sizing: border-box;
  border-radius: 6px;
  color:#004c98;
}

button {
  background-color: #004c98e6;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 6px;
}

#correoLogin:focus, #passwordLogin:focus{
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
}

</style> 

<body style="background-color:white;">
<div class="container">
<div class="row justify-content-center">
<div class="col-12 col-lg-6">
<div style="display: flex; align-items: center; justify-content: center;">
<img src="logo/logoUwipes.png" alt="">
    </div>
    <br>
    <input id="correoLogin" type="text" placeholder="CORREO" name="correoUsuario" required>
    <input id="passwordLogin" type="password" placeholder="CONTRASEÑA" name="password" required>
    <p style="color:red;"> </p>
    <button data-id="" id="btnLogin">ENTRAR</button>
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

//Entrar con Enter que es tecla 13
var input = $('#passwordLogin')[0];
input.addEventListener("keyup", function(event) {
  if(event.keyCode === 13) {
    $("#btnLogin").click();
  }
});
var input2 = $('#correoLogin')[0];
input2.addEventListener("keyup", function(event) {
  if(event.keyCode === 13) {
    $("#btnLogin").click();
  }
});
</script>
