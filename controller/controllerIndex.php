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
    $utilisateurDb = "etu";
    $serveur = "localhost";
    $motdepasse = "Achanger!";
    $basededonnees = "sae";
    try {
        $bdd = new PDO("mysql:host=$serveur;dbname=$basededonnees;charset=utf8", $utilisateurDb, $motdepasse);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Préparation de la requête SQL
    $sql = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti, COUNT(PRODUIT.Id_Produit) AS nombreDeProduits
            FROM PRODUCTEUR 
            JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti
            LEFT JOIN PRODUIT ON PRODUCTEUR.Id_Prod = PRODUIT.Id_Prod
            GROUP BY UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti';
    
    if ($categorie != "Tout") {
        $sql .= ' HAVING PRODUCTEUR.Prof_Prod = :categorie';
    } else {
        $sql .= ' HAVING PRODUCTEUR.Prof_Prod LIKE \'%\'';
    }

    if (!empty($rechercheVille)) {
        $sql .= ' AND Adr_Uti LIKE \'%, _____ %' . $rechercheVille . '%\'';
    }

    // Ajout du tri
    $sql .= ' ORDER BY ';
    if ($tri === "nombreDeProduits") {
        $sql .= ' nombreDeProduits DESC';
    } else if ($tri === "ordreNomAlphabétique") {
        $sql .= ' Nom_Uti ASC';
    } else if ($tri === "ordreNomAntiAlphabétique") {
        $sql .= ' Nom_Uti DESC';
    } else if ($tri === "ordrePrenomAlphabétique") {
        $sql .= ' Prenom_Uti ASC';
    } else if ($tri === "ordrePrenomAntiAlphabétique") {
        $sql .= ' Prenom_Uti DESC';
    } else {
        $sql .= ' nombreDeProduits ASC';
    }

    // Préparation de la requête
    $stmt = $bdd->prepare($sql);

    // Liaison des paramètres
    if ($categorie != "Tout") {
        $stmt->bindValue(':categorie', $categorie, PDO::PARAM_STR);
    }

    // Exécution de la requête
    $stmt->execute();

    // Récupération des résultats
    $producteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des coordonnées GPS de la ville de recherche
    $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($row["Adr_Uti"]);
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