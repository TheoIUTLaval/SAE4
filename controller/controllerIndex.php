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
        curl_setopt($ch, CURLOPT_PROXY, 'proxy.univ-lemans.fr');
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Permet de suivre les redirections
        // Ajout du User Agent
        $customUserAgent = "LEtalEnLigne/1.0"; // Remplacez par le nom et la version de votre application
        curl_setopt($ch, CURLOPT_USERAGENT, $customUserAgent);
        // Ajout du Referrer
        $customReferrer = "https://proxy.univ-lemans.fr:3128"; // Remplacez par l'URL de votre application
        curl_setopt($ch, CURLOPT_REFERER, $customReferrer);
        // Exécution de la requête
        $response = curl_exec($ch);
        // Vérifier s'il y a eu une erreur cURL
        if (curl_errno($ch)) {
            echo 'Erreur cURL : ' . curl_error($ch);
        } else {
            // Analyser la réponse JSON
            $data = json_decode($response);
        
            // Vérifier si la réponse a été correctement analysée
            if (!empty($data) && is_array($data) && isset($data[0])) {
                // Récupérer la latitude et la longitude
                $latitude = $data[0]->lat;
                $longitude = $data[0]->lon;
                return [$latitude, $longitude];
            }
            return [0,0];
        }
        // Fermeture de la session cURL
        curl_close($ch);
    }
    $urlUti = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($Adr_Uti_En_Cours);
    $coordonneesUti = latLongGps($urlUti);
    $latitudeUti = $coordonneesUti[0];
    $longitudeUti = $coordonneesUti[1];

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

    function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // rayon moyen de la Terre en km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin(
$dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
    
        return ($miles ? ($km * 0.621371192) : $km);
}

?>