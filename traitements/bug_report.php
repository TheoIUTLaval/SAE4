<?php


$utilisateur = "etu";
$serveur = "localhost";
$motdepasse = "Achanger!";
$basededonnees = "sae";
$bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  
  $bdd->query('CALL broadcast_admin(' . $_SESSION["Id_Uti"] . ', \'' . $message . '\');');
} else {
  
  $bdd->query('CALL broadcast_admin(0 , \''. $_POST["mail"]. $message . '\');');
}

