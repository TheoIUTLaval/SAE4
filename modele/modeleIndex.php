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
$bdd = dbConnect();

function AdrUti($bdd, $utilisateur){
    $queryAdrUti = $bdd->prepare('SELECT Adr_Uti FROM UTILISATEUR WHERE Id_Uti= :utilisateur;');
    $queryAdrUti->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
    $queryAdrUti->execute();
    $returnQueryAdrUti = $queryAdrUti->fetchAll(PDO::FETCH_ASSOC);
    
    return $returnQueryAdrUti;
}
?>