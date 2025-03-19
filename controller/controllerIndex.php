<?php
include("modele/modeleIndex.php");

if(!isset($_SESSION)){
    session_start();
}
if (isset($_GET["rechercheVille"])==true){
    $rechercheVille=htmlspecialchars($_GET["rechercheVille"]);
}
else{
    $rechercheVille="";
}
if (isset($_GET["categorie"])==false){
    $_GET["categorie"]="Tout";
}
if (isset($_SESSION["Id_Uti"])==false){
    $utilisateur=-1;
}
else{
    $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
}
if (isset($_GET["rayon"])==false){
    $rayon=100;
}
else{
    $rayon=htmlspecialchars($_GET["rayon"]);
}
if (isset($_GET["tri"])==true){
    $tri=htmlspecialchars($_GET["tri"]);
}
else{
    $tri="nombreDeProduits";
}
if (isset($_SESSION["language"])==false){
    $_SESSION["language"]="fr";
}

function latLongGps($url){
    // Configuration de la requête cURL
    $ch = curl_init($url);
    // Si vous avez besoin d'un proxy, décommentez les lignes suivantes et ajustez les paramètres
    curl_setopt($ch, CURLOPT_PROXY, 'proxy.univ-lemans.fr');
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Exécution de la requête
    $response = curl_exec($ch);
    // Vérifier s'il y a eu une erreur cURL
    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
        curl_close($ch);
        return [0, 0];
    } else {
        // Analyser la réponse JSON
        $data = json_decode($response);
        // Vérifier si la réponse a été correctement analysée
        if (!empty($data) && is_array($data) && isset($data[0])) {
            // Récupérer la latitude et la longitude
            $latitude = $data[0]->lat ?? 0;
            $longitude = $data[0]->lon ?? 0;
            curl_close($ch);
            return [$latitude, $longitude];
        }
        curl_close($ch);
        return [0,0];
    }
}

/*---------------------------------------------------------------*/
/*
    Titre : Calcul la distance entre 2 points en km                                                                       
                                                                                                                        
    URL   : https://phpsources.net/code_s.php?id=1091
    Auteur           : sheppy1                                                                                            
    Website auteur   : https://lejournalabrasif.fr/qwanturank-concours-seo-qwant/                                         
    Date édition     : 05 Aout 2019                                                                                       
    Date mise à jour : 16 Aout 2019                                                                                       
    Rapport de la maj:                                                                                                    
    - fonctionnement du code vérifié                                                                                    
*/
/*---------------------------------------------------------------*/
?>