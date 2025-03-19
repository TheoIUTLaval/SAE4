<?php

function dbConnect(){
    $utilisateur = "etu";
    $serveur = "localhost";
    $motdepasse = "Achanger!";
    $basededonnees = "sae";
    // Connect to database
    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
}

$bdd=dbConnect();
function envoyerMessage($id_user, $id_other_people, $content){
    $bdd = dbConnect();
    $query = $bdd->query(('CALL envoyerMessage('.$id_user.', '.$id_other_people.", '".htmlspecialchars($content)."');"));
    
    
}
?>