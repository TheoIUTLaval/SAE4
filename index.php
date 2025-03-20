<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require "language/language.php";
    require __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
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
    $htmlMarque = "L'Étal en Ligne";
    $htmlFrançais = "Français";
    $htmlAnglais = "English";
    $htmlEspagnol = "Español";
    $htmlAllemand = "Deutch";
    $htmlRusse = "русский";
    $htmlChinois = "中國人";
    ?>
    <title> <?php echo $htmlMarque; ?> </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>
<body>
<?php
//var_dump($_SESSION);
if (!isset($_SESSION)) {
    session_start();
}
$rechercheVille = isset($_GET["rechercheVille"]) ? htmlspecialchars($_GET["rechercheVille"]) : "";
$_GET["categorie"] = isset($_GET["categorie"]) ? $_GET["categorie"] : "Tout";
$utilisateur = isset($_SESSION["Id_Uti"]) ? htmlspecialchars($_SESSION["Id_Uti"]) : -1;
$rayon = isset($_GET["rayon"]) ? $rayon = htmlspecialchars($_GET["rayon"]) : 100;
$tri = isset($_GET["tri"]) ? htmlspecialchars($_GET["tri"]) : $tri = "nombreDeProduits";
if (isset($_SESSION["language"]) == false) {
    $_SESSION["language"] = "fr";
}
//            die("test");

function latLongGps($url) {
    try {
        // Initialize cURL with error checking
        $ch = curl_init();
        if (!$ch) {
            error_log("Failed to initialize cURL");
            return [0, 0];
        }

        // Set the URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // Configure proxy if needed - consider making these conditional
        curl_setopt($ch, CURLOPT_PROXY, 'proxy.univ-lemans.fr');
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);

        // Other cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "LEtalEnLigne/1.0");
        curl_setopt($ch, CURLOPT_REFERER, "http://proxy.univ-lemans.fr:3128");

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            error_log('Erreur cURL : ' . curl_error($ch));
            curl_close($ch);
            return [0, 0];
        }

        // Process the response
        $data = json_decode($response);

        if (!empty($data) && is_array($data) && isset($data[0])) {
            $latitude = $data[0]->lat;
            $longitude = $data[0]->lon;
            curl_close($ch);
            return [$latitude, $longitude];
        }

        curl_close($ch);
        return [0, 0];
    } catch (Exception $e) {
        error_log("Exception in latLongGps: " . $e->getMessage());
        return [0, 0];
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

?>
<div class="custom-container">
    <div class="leftColumn">
        <img class="logo" href="index.php" src="asset/img/logo.png">
        <div class="contenuBarre">

            <center><strong><p><?php echo $htmlRechercherPar; ?></p></strong></center>
            <form method="get" action="index.php">
                <label><?php echo $htmlParProfession ?></label>
                <br>
                <select name="categorie" id="categories">
                    <option value="Tout" <?php if ($_GET["categorie"] == "Tout") echo 'selected="selected"'; ?>><?php echo $htmlTout ?></option>
                    <option value="Agriculteur" <?php if ($_GET["categorie"] == "Agriculteur") echo 'selected="selected"'; ?>><?php echo $htmlAgriculteur ?></option>
                    <option value="Vigneron" <?php if ($_GET["categorie"] == "Vigneron") echo 'selected="selected"'; ?>><?php echo $htmlVigneron ?></option>
                    <option value="Maraîcher" <?php if ($_GET["categorie"] == "Maraîcher") echo 'selected="selected"'; ?>><?php echo $htmlMaraîcher ?></option>
                    <option value="Apiculteur" <?php if ($_GET["categorie"] == "Apiculteur") echo 'selected="selected"'; ?>><?php echo $htmlApiculteur ?></option>
                    <option value="Éleveur de volaille" <?php if ($_GET["categorie"] == "Éleveur de volaille") echo 'selected="selected"'; ?>><?php echo $htmlÉleveurdevolailles ?></option>
                    <option value="Viticulteur" <?php if ($_GET["categorie"] == "Viticulteur") echo 'selected="selected"'; ?>><?php echo $htmlViticulteur ?></option>
                    <option value="Pépiniériste" <?php if ($_GET["categorie"] == "Pépiniériste") echo 'selected="selected"'; ?>><?php echo $htmlPépiniériste ?></option>
                </select>
                <br>
                <br><?php echo $htmlParVille ?>
                <br>
                <input type="text" name="rechercheVille" pattern="[A-Za-z0-9 ]{0,100}"
                       value="<?php echo $rechercheVille ?>" placeholder="<?php echo $htmlVille; ?>">
                <br>
                <?php
                $queryAdrUti = $bdd->prepare(('SELECT Adr_Uti FROM UTILISATEUR WHERE Id_Uti= :utilisateur;'));
                $queryAdrUti->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
                $queryAdrUti->execute();
                $returnQueryAdrUti = $queryAdrUti->fetchAll(PDO::FETCH_ASSOC);

                if (count($returnQueryAdrUti) > 0) {
                    $Adr_Uti_En_Cours = $returnQueryAdrUti[0]["Adr_Uti"];
                    ?>
                    <br>
                    <br><?php echo $htmlAutourDeChezMoi . ' (' . $Adr_Uti_En_Cours . ')'; ?>
                    <br>
                    <br>
                    <input name="rayon" type="range" value="<?php echo $rayon; ?>" min="1" max="100" step="1"
                           onchange="AfficheRange2(this.value)" onkeyup="AfficheRange2(this.value)">
                    <span id="monCurseurKm"><?php echo $htmlRayonDe ?><?php echo $rayon;
                        if ($rayon >= 100) echo '+'; ?></span>
                    <script>
                        function AfficheRange2(newVal) {
                            var monCurseurKm = document.getElementById("monCurseurKm");
                            if ((newVal >= 100)) {
                                monCurseurKm.innerHTML = "Rayon de " + newVal + "+ ";
                            } else {
                                monCurseurKm.innerHTML = "Rayon de " + newVal + " ";
                            }
                        }
                    </script>
                <?php echo $htmlKm ?>
                    <br>
                    <br>
                    <?php

                } else {
                    $Adr_Uti_En_Cours = 'France';
                }
                ?>
                <br>


                <label><?php echo $htmlTri ?></label>
                <br>
                <select name="tri" required>
                    <option value="nombreDeProduits" <?php if ($tri == "nombreDeProduits") echo 'selected="selected"'; ?>><?php echo $htmlNombreDeProduits ?></option>
                    <option value="ordreNomAlphabétique" <?php if ($tri == "ordreNomAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParNomAl ?></option>
                    <option value="ordreNomAntiAlphabétique" <?php if ($tri == "ordreNomAntiAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParNomAntiAl ?></option>
                    <option value="ordrePrenomAlphabétique" <?php if ($tri == "ordrePrenomAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParPrenomAl ?></option>
                    <option value="ordrePrenomAntiAlphabétique" <?php if ($tri == "ordrePrenomAntiAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParPrenomAntiAl ?></option>
                </select>
                <br>
                <br>
                <br>


                <center><input type="submit" value="<?php echo $htmlRechercher ?>"></center>
            </form>


        </div>
    </div>
    <div class="rightColumn">
    <div class="topBanner">
            <div class="divNavigation">
                <nav class="navbar navbar-expand-lg bg-body-tertiary" >
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">Accueil</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <?php if (isset($_SESSION["Id_Uti"])): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="ViewMessagerie.php">Messagerie</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="ViewAchats.php">Achats</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isProd"]) && ($_SESSION["isProd"] == true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produits.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="ViewDelivery.php">Commandes</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isAdmin"]) && ($_SESSION["isAdmin"] == true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="ViewPanelAdmin.php">Panel Admin</a>
                                    </li>
                                <?php endif; ?>

                                <li class="nav-item">
                                        <form action="language/language.php" method="post" id="languageForm" class="d-flex align-items-center dropdown">
                                            <select name="language" id="languagePicker" onchange="submitForm()" class="form-select" aria-label="Default select example">
                                                <option slected disabled>Language</option>
                                                <option value="fr" <?php if ($_SESSION["language"] == "fr") echo 'selected'; ?>>Français</option>
                                                <option value="en" <?php if ($_SESSION["language"] == "en") echo 'selected'; ?>>English</option>
                                                <option value="es" <?php if ($_SESSION["language"] == "es") echo 'selected'; ?>>Español</option>
                                                <option value="al" <?php if ($_SESSION["language"] == "al") echo 'selected'; ?>>Deutsch</option>
                                                <option value="ru" <?php if ($_SESSION["language"] == "ru") echo 'selected'; ?>>русский</option>
                                                <option value="ch" <?php if ($_SESSION["language"] == "ch") echo 'selected'; ?>>中國人</option>
                                            </select>
                                        </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="divConnection">
                <form method="post">

                <script>
                    function submitForm() {
                        document.getElementById("languageForm").submit();
                    }
                </script>
                    <?php
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    
                    ?>

					<input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo("Se connecter");} else {echo ''.$_SESSION['Mail_Uti'].'';}?> " class="boutonDeConnection" > <!-- Changer se connecter -->
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                
                </form>
                </div>
            </div>
        </div>

        <h2> <?php echo $htmlProducteursEnMaj ?> </h2>
        <div class="gallery-container">
            <?php
            // Replace this section - starting around line 368
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (isset($_GET["categorie"])) {
                    $categorie = htmlspecialchars($_GET["categorie"]);
                    try {
                        // Use the existing database connection from the top of the file
                        // $bdd was already established earlier

                        // Prepare the appropriate SQL query based on category
                        if ($_GET["categorie"] == "Tout") {
                            $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti, COUNT(PRODUIT.Id_Produit) as ProduitCount
                        FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti
                        LEFT JOIN PRODUIT ON PRODUCTEUR.Id_Prod=PRODUIT.Id_Prod
                        GROUP BY UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti
                        HAVING PRODUCTEUR.Prof_Prod LIKE :profession';

                            $stmt = $bdd->prepare($requete);
                            $profession = '%';
                            $stmt->bindParam(':profession', $profession);
                        } else {
                            $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti, COUNT(PRODUIT.Id_Produit) as ProduitCount
                        FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti
                        LEFT JOIN PRODUIT ON PRODUCTEUR.Id_Prod=PRODUIT.Id_Prod
                        GROUP BY UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti
                        HAVING PRODUCTEUR.Prof_Prod = :categorie';

                            $stmt = $bdd->prepare($requete);
                            $stmt->bindParam(':categorie', $categorie);
                        }

                        // Add city search condition if provided
                        if ($rechercheVille != "") {
                            $requete .= ' AND Adr_Uti LIKE :adresse';
                            $stmt = $bdd->prepare($requete);

                            // Rebind parameters as needed
                            if ($_GET["categorie"] == "Tout") {
                                $profession = '%';
                                $stmt->bindParam(':profession', $profession);
                            } else {
                                $stmt->bindParam(':categorie', $categorie);
                            }

                            $adressePattern = '%, _____ %' . $rechercheVille . '%';
                            $stmt->bindParam(':adresse', $adressePattern);
                        }
                        // Add sorting
                        $requete .= ' ORDER BY ';

                        if ($tri === "nombreDeProduits") {
                            $requete .= 'ProduitCount DESC';
                        } else if ($tri === "ordreNomAlphabétique") {
                            $requete .= 'Nom_Uti ASC';
                        } else if ($tri === "ordreNomAntiAlphabétique") {
                            $requete .= 'Nom_Uti DESC';
                        } else if ($tri === "ordrePrenomAlphabétique") {
                            $requete .= 'Prenom_Uti ASC';
                        } else if ($tri === "ordrePrenomAntiAlphabétique") {
                            $requete .= 'Prenom_Uti DESC';
                        } else {
                            $requete .= 'ProduitCount ASC';
                        }
                        // Prepare the statement with the complete query
                        $stmt = $bdd->prepare($requete);

                        // Rebind parameters again for the final query
                        if ($_GET["categorie"] == "Tout") {
                            $profession = '%';
                            $stmt->bindParam(':profession', $profession);
                        } else {
                            $stmt->bindParam(':categorie', $categorie);
                        }

                        if ($rechercheVille != "") {
                            $adressePattern = '%, _____ %' . $rechercheVille . '%';
                            $stmt->bindParam(':adresse', $adressePattern);
                        }
                        // Execute the query
                        $stmt->execute();
                        // Get coordinates of current user
                        $urlUti = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($Adr_Uti_En_Cours);
                        $coordonneesUti = latLongGps($urlUti);
                        $latitudeUti = $coordonneesUti[0];
                        $longitudeUti = $coordonneesUti[1];
//                         Fetch and display results
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($results) > 0) {
                            foreach ($results as $row) {
                                if ($rayon >= 100) {
                                    echo '<div class="card" style="width: 18rem; ">';
                                    echo '<a href="producteur.php?Id_Prod=' . $row["Id_Prod"] . '" class="text-decoration-none">';  
                                    $imagePath = 'asset/img/img_producteur/' . $row["Id_Prod"] . '.png';
                                    $defaultImage = 'asset/img/img_producteur/default_image.png';  

                                    $imageSrc = file_exists($imagePath) ? $imagePath : $defaultImage;

                                    echo '<img src="' . $imageSrc . '" class="card-img-top" alt="' . $htmlImageUtilisateur . '" style="height: 180px; object-fit: cover;">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . $row["Prof_Prod"] . '</h5>';
                                    echo '<p class="card-text">';
                                    echo $row["Prenom_Uti"] . " " . mb_strtoupper($row["Nom_Uti"]) . "<br>";
                                    echo $row["Adr_Uti"] . "<br>";
                                    echo '</p>';
                                    echo '<a href="producteur.php?Id_Prod=' . $row["Id_Prod"] . '" class="btn btn-outline-secondary" style="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514;" 
                                    onmouseover="this.style.backgroundColor=\'#305514\'; this.style.color=\'#FFFFFF\';"
                                    onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#305514\';">
                                    Voir le producteur</a>';

                                    echo '</div>';
                                    echo '</a>';
                                    echo '</div>';

                                } else {
                                    $urlProd = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($row["Adr_Uti"]);
                                    $coordonneesProd = latLongGps($urlProd);
                                    $latitudeProd = $coordonneesProd[0];
                                    $longitudeProd = $coordonneesProd[1];
                                    $distance = distance($latitudeUti, $longitudeUti, $latitudeProd, $longitudeProd);

                                    if ($distance < $rayon) {
                                        echo '<div class="card" style="width: 18rem; ">';
                                        echo '<a href="producteur.php?Id_Prod=' . $row["Id_Prod"] . '" class="text-decoration-none">';  
                                        $imagePath = 'asset/img/img_producteur/' . $row["Id_Prod"] . '.png';
                                    $defaultImage = 'asset/img/img_producteur/default_image.png';  

                                    $imageSrc = file_exists($imagePath) ? $imagePath : $defaultImage;

                                    echo '<img src="' . $imageSrc . '" class="card-img-top" alt="' . $htmlImageUtilisateur . '" style="height: 180px; object-fit: cover;">';
                                        echo '<div class="card-body">';
                                        echo '<h5 class="card-title">Producteur</h5>';  
                                        echo '<p class="card-text">';
                                        echo "Nom : " . $row["Nom_Uti"] . "<br>";
                                        echo "Prénom : " . $row["Prenom_Uti"] . "<br>";
                                        echo "Adresse : " . $row["Adr_Uti"] . "<br>";
                                        echo '</p>';
                                        echo '<a href="producteur.php?Id_Prod=' . $row["Id_Prod"] . '" class="btn btn-outline-secondary" style="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514;" 
                                    onmouseover="this.style.backgroundColor=\'#305514\'; this.style.color=\'#FFFFFF\';"
                                    onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#305514\';">
                                    Voir le producteur</a>';
                                        echo '</div>';
                                        echo '</a>';
                                        echo '</div>';

                                    }
                                }
                            }
                        } else {
                            echo $htmlAucunResultat;
                        }

                    } catch (PDOException $e) {
                        echo "Erreur de base de données : " . $e->getMessage();
                    }
                }
            }
            ?>
        </div>
        <br>


        <div class="basDePage">
        
            
        <div class="btn-group" role="group" aria-label="Basic outlined example" >
                    <form method="post">
                    <button type=submit class="btn btn-outline-primary" style ="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514"
                    onmouseover="this.style.backgroundColor='#305514'; this.style.color='#FFFFFF';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='#305514';"
                    > 
                    Signaler un dysfonctionnement
                            <input type="hidden" name="popup" value="contact_admin">
                    </button>
                    </form>
                    <button class="btn btn-outline-primary bouton" type="button" style ="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514" 
                    onmouseover="this.style.backgroundColor='#305514'; this.style.color='#FFFFFF';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='#305514';"
                    data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Conditions générales d'utilisation
                    </button>
                
                
            </div>
            <div class="collapse w-100 mt-2" id="collapseExample">
                    <div class="card card-body">
                        Introduction: 1.1. Les présentes Conditions Générales d'Utilisation (ci-après "les CGU") régissent l'utilisation de la Plateforme de Vente de Produits Locaux (ci-après "la Plateforme"), accessible à l'adresse etalenligne.fr . La Plateforme est exploitée par L'étal en ligne (ci-après "l'Exploitant"). En utilisant la Plateforme, vous acceptez de vous conformer à ces CGU. Définitions : 2.1. Dans les présentes CGU, les termes suivants ont les significations suivantes : -"Utilisateur" : Toute personne accédant ou utilisant la Plateforme, qu'il s'agisse d'un producteur, d'un client ou de l'administrateur de la Plateforme. -"Producteur" : Un utilisateur qui propose des produits à la vente sur la Plateforme. -"Client" : Un utilisateur qui achète des produits sur la Plateforme. -"Administrateur" : L'équipe en charge de la gestion et de l'administration de la Plateforme. Utilisation de la Plateforme : 3.1. Acceptation des CGU : Vous devez accepter ces CGU pour utiliser la Plateforme. Si vous n'acceptez pas ces CGU, veuillez ne pas utiliser la Plateforme. 3.2. Compte Utilisateur : 3.2.1. Pour devenir membre de la Plateforme, vous devez créer un compte en fournissant des informations personnelles, y compris votre nom, votre adresse email et d'autres informations requises. 3.2.2. Vous acceptez de fournir des informations exactes, complètes et à jour lors de la création de votre compte et de les maintenir à jour. 3.2.3. Vous êtes responsable de toutes les activités qui se déroulent sous votre compte, y compris la confidentialité de vos identifiants de connexion. 3.3. Conditions d'Âge : 3.3.1. Vous devez avoir au moins 18 ans pour utiliser la Plateforme. Si vous avez moins de 18 ans, vous devez obtenir le consentement de vos parents ou tuteurs légaux pour utiliser la Plateforme. Transactions et Paiements : 4.1. Transactions Directes : 4.1.1. La Plateforme ne traite pas les paiements en ligne. Les transactions de paiement doivent être effectuées directement entre les utilisateurs (producteurs et clients) en dehors de la Plateforme. 4.1.2. Les prix des produits et les modalités de paiement sont convenus entre les producteurs et les clients. La Plateforme ne joue aucun rôle dans la fixation des prix ou des modalités de paiement. 4.2. Taxes et Légalité : 4.2.1. Les producteurs sont responsables de se conformer aux lois et réglementations fiscales locales applicables concernant la collecte et le paiement des taxes sur les ventes. 4.2.2. Les transactions sur la Plateforme doivent respecter toutes les lois locales applicables. Responsabilités des Utilisateurs : 5.1. Responsabilités des Producteurs : 5.1.1. Les producteurs sont responsables de l'exactitude des informations concernant leurs produits, de la qualité des produits vendus, de la gestion des stocks et de la communication avec les clients. 5.1.2. Les producteurs garantissent que les produits proposés sont conformes aux normes de sécurité alimentaire et à toutes les réglementations applicables. 5.2. Responsabilités des Clients : 5.2.1. Les clients sont responsables de la vérification des informations fournies par les producteurs, de la passation de commandes et du paiement des produits conformément aux modalités convenues. Signalement de Problèmes : 6.1. Signalement des Problèmes : 6.1.1. Les utilisateurs peuvent signaler tout comportement abusif, faux produits, ou tout autre problème à l'administrateur de la Plateforme via la fonction de signalement. 6.1.2. L'administrateur s'engage à examiner les signalements et à prendre des mesures appropriées pour résoudre les problèmes signalés. Modification et Résiliation du Compte: 7.1. Modification et Résiliation : 7.1.1. Vous pouvez modifier ou supprimer votre compte à tout moment en accédant aux paramètres de votre compte. 7.1.2. L'administrateur se réserve le droit de suspendre ou de résilier votre compte en cas de violation des présentes CGU ou de comportement inapproprié. Propriété Intellectuelle : 8.1. Droits de Propriété Intellectuelle : 8.1.1. Tous les contenus publiés sur la Plateforme, y compris les textes, les images et les logos, sont protégés par les lois sur les droits d'auteur et la propriété intellectuelle. Vous acceptez de ne pas utiliser ou reproduire ces contenus sans autorisation. Limitation de Responsabilité : 9.1. Exclusion de Garanties : 9.1.1. La Plateforme est fournie "telle quelle" et l'administrateur ne garantit pas son bon fonctionnement. L'administrateur n'est pas responsable des dommages directs ou indirects résultant de l'utilisation de la Plateforme. Modification des CGU : 10.1. Modification des CGU : 10.1.1. Les présentes CGU peuvent être modifiées à tout moment par l'administrateur. Les utilisateurs seront informés des modifications par le biais de la Plateforme. Loi Applicable et Juridiction : 11.1. Loi Applicable et Juridiction : 11.1.1. Les présentes CGU sont régies par la LIL et tout litige sera soumis à la juridiction exclusive des tribunaux de la Mayenne. Ces CGU visent à établir des règles claires et juridiquement contraignantes pour l'utilisation de votre Plateforme de Vente de Produits Locaux. Pour garantir la conformité aux lois locales et aux réglementations, il est recommandé de consulter un avocat ou un juriste pour adapter ces CGU à votre situation particulière.
                    </div>
                </div>
        </div>
    </div>
</div>
<?php require "popups/gestion_popups.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>