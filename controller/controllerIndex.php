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
function getProducteurs($rechercheVille, $categorie, $rayon, $tri, $utilisateur) {
    // Connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=nom_de_la_base', 'nom_utilisateur', 'mot_de_passe');
    
    // Préparation de la requête SQL
    $sql = "SELECT * FROM producteurs WHERE 1=1";
    
    if (!empty($rechercheVille)) {
        $sql .= " AND ville LIKE :ville";
    }
    if ($categorie != "Tout") {
        $sql .= " AND categorie = :categorie";
    }
    if ($utilisateur != -1) {
        $sql .= " AND utilisateur_id = :utilisateur";
    }
    
    // Ajout du tri
    $sql .= " ORDER BY " . $tri;
    
    // Préparation de la requête
    $stmt = $db->prepare($sql);
    
    // Liaison des paramètres
    if (!empty($rechercheVille)) {
        $stmt->bindValue(':ville', '%' . $rechercheVille . '%', PDO::PARAM_STR);
    }
    if ($categorie != "Tout") {
        $stmt->bindValue(':categorie', $categorie, PDO::PARAM_STR);
    }
    if ($utilisateur != -1) {
        $stmt->bindValue(':utilisateur', $utilisateur, PDO::PARAM_INT);
    }
    
    // Exécution de la requête
    $stmt->execute();
    
    // Récupération des résultats
    $producteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupération des coordonnées GPS de la ville de recherche
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($rechercheVille) . "&format=json&limit=1";
    list($latitude, $longitude) = latLongGps($url);
    
    // Filtrage des producteurs par distance
    $filteredProducteurs = [];
    foreach ($producteurs as $producteur) {
        $distance = distance($producteur['latitude'], $producteur['longitude'], $latitude, $longitude);
        if ($distance <= $rayon) {
            $filteredProducteurs[] = $producteur;
        }
    }
    
    return $filteredProducteurs;
}
?>