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
function envoyerMessage($id_user, $id_other_people, $content){
    $bdd = dbConnect();
    $query = $bdd->query(('CALL envoyerMessage('.$id_user.', '.$id_other_people.", '".htmlspecialchars($content)."');"));
    
    
}
function afficheMessages($id_user, $id_other_people){
    $bdd = dbConnect();
    $query = $bdd->query(('CALL conversation('.$id_user.', '.$id_other_people.');'));
    $messages = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach($messages as $message){
        afficheMessage($message);
    }
}
function afficheContacts($id_user){
    require "language/language.php" ; 
    $bdd = dbConnect();
    $query = $bdd->query(('CALL listeContact('.$id_user.');'));
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (count($contacts)==0){
        $test = $htmlPasDeConversation;
        echo($test);
    }else{
        foreach($contacts as $contact){
            afficherContact($contact);
        }
    }    
}

?>