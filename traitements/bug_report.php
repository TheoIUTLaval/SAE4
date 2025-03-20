<?php

require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
function dbConnect(){
  $utilisateur = $_ENV['DB_USER'];
  $serveur = $_ENV['DB_HOST'];
  $motdepasse = $_ENV['DB_PASSWORD'];
  $basededonnees = $_ENV['DB_NAME'];
  // Connect to database
  return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
}
$bdd=dbConnect();
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  
  $bdd->query('CALL broadcast_admin(' . $_SESSION["Id_Uti"] . ', \'' . $message . '\');');
} else {
  
  $bdd->query('CALL broadcast_admin(0 , \''. $_POST["mail"]. $message . '\');');
}

