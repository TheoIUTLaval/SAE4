<?php
    require "../../language/language.php" ; 
?>
<?php

$email = $_POST["email"];
$_SESSION["mailTemp"]=$email;

require __DIR__ . '/../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
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
// Vérifiez d'abord si l'adresse e-mail existe déjà dans la table UTILISATEUR
$checkEmailQuery = "SELECT COUNT(*) AS count FROM UTILISATEUR WHERE Mail_Uti = :mail";
$checkEmailStmt = $bdd->prepare($checkEmailQuery);
$checkEmailStmt->bindParam(':mail', $_SESSION["mailTemp"]);
$checkEmailStmt->execute();
$emailCount = $checkEmailStmt->fetch(PDO::FETCH_ASSOC)['count'];

if ($emailCount > 0) {  
    // Génération d'un code aléatoire à 6 chiffres
    $code = rand(100000, 999999);
    $_SESSION["code"] = $code;
    
    // Envoi du code par e-mail
    $subject = $htmlReinVotreMdp;
    $message = $htmlTonMdpEst . $code;
    $headers = "From: no-reply@letalenligne.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Envoi de l'e-mail
    $mailSent = mail($email, $subject, $message, $headers);

    // Redirection
    if ($mailSent) {
        $_POST['popup'] = 'mdp_oublie/code';
    } else {
        $_SESSION['erreur'] = $htmlErreurMailIncorrect;
    }
} else {
    $_SESSION['erreur'] = $htmlPasMailDansBDD;
}
?>
