<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$bdd=dbConnect();


// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
$requete = 'SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=?';
$stmt = $connexion->prepare($requete);
$stmt->bind_param("s", $_SESSION['Mail_Uti']); // "s" indique que la valeur est une chaîne de caractères
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$connexion->close();
?>
