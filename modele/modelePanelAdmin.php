<?php
if(!isset($_SESSION)){
    session_start();
    }

    function dbConnect(){
        $utilisateur = "etu";
        $serveur = "localhost";
        $motdepasse = "Achanger!";
        $basededonnees = "sae";
        // Connect to database
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    }

    $bdd=dbConnect();

?>