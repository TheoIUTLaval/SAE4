<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$bdd=dbConnect();


// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
$requete = 'SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=?';
$stmt = $bdd->prepare($requete);
$stmt->bindValue(1, $_SESSION['Mail_Uti'], PDO::PARAM_STR); // "1" indique le premier paramètre, PDO::PARAM_STR indique une chaîne de caractères
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$bdd = null;
?>
