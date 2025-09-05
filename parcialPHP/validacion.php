<?php
include_once('conf/conf.php');
$correo= isset($_POST['email']) ? $_POST['email']:"";
$pwd=  isset($_POST['pwd']) ? $_POST['pwd']:"";

$consulta="SELECT usuario, email, pwd from usuario where email= '$correo' AND pwd='$pwd'";
$ejecucion= (mysqli_query($con,$consulta));
// var_dump($ejecucion);
$usuario= mysqli_fetch_assoc($ejecucion);
session_start();
$_SESSION['usuario']= $usuario['usuario'];

$validar= mysqli_num_rows($ejecucion);
if($validar>0){
    header('Location: home.php');
}else {
    header('Location: index.php?error=error');
}
?>