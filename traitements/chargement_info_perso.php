<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$bdd=dbConnect();


// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
$requete = 'SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti = :mail';
$stmt = $bdd->prepare($requete);
$stmt->bindParam(':mail', $_SESSION['Mail_Uti'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = null;
$bdd = null;
?>
