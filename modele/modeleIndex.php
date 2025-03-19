<?php
function dbConnect(){
    $utilisateur = "etu";
    $serveur = "localhost";
    $motdepasse = "Achanger!";
    $basededonnees = "sae";
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