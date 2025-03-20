<?php

if(!isset($_SESSION)){
        session_start();
        }

// Database connection
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
  $message = $bdd->quote($message);

  $bdd->query('CALL broadcast_Producteur(' . $_SESSION["Id_Uti"] . ', ' . $message . ');');
  header("Location: ../ViewMessagerie.php");
} else {
    echo "error";
    echo $message;
    var_dump(isset($_SESSION["Id_Uti"]));
    var_dump(isset($message));

  }
  
  ?>
