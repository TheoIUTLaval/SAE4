<?php
include 'modele/modeleDelivery.php';

$utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
if (isset($_POST["typeCategorie"])==true){
  $filtreCategorie=htmlspecialchars($_POST["typeCategorie"]);
}
else{
  $filtreCategorie=0;
}




?>