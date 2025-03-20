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
    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

$bdd = dbConnect();

try {
    // Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
    $requete = 'SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti = :mail';
    $stmt = $bdd->prepare($requete);
    $stmt->bindParam(':mail', $_SESSION['Mail_Uti'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();

    // Traitement des résultats
    foreach ($result as $row) {
        // Faites quelque chose avec chaque ligne de résultat
    }

    $stmt->closeCursor();
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$bdd = null;
?>
