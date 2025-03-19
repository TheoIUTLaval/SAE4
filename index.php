<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    include "controller/controllerIndex.php";
    require "language/language.php";
    echo(__DIR__);

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
</head>
<body>
    <?php
    //var_dump($_SESSION);
    ?>
<!-- Vue-->
    <div class="container">
        <div class="leftColumn">
            <img class="logo" href="index.php" src="asset/img/logo.png">
            <div class="contenuBarre">
                <center><strong><p><?php echo $htmlRechercherPar; ?></p></strong></center>
                <form method="get" action="index.php">
                    <label><?php echo $htmlParProfession?></label>
                    <br>
                    <select name="categorie" id="categories">
                        <option value="Tout" <?php if($_GET["categorie"]=="Tout") echo 'selected="selected"';?>><?php echo $htmlTout?></option>
                        <option value="Agriculteur" <?php if($_GET["categorie"]=="Agriculteur") echo 'selected="selected"';?>><?php echo $htmlAgriculteur?></option>
                        <option value="Vigneron" <?php if($_GET["categorie"]=="Vigneron") echo 'selected="selected"';?>><?php echo $htmlVigneron?></option>
                        <option value="Maraîcher" <?php if($_GET["categorie"]=="Maraîcher") echo 'selected="selected"';?>><?php echo $htmlMaraîcher?></option>
                        <option value="Apiculteur" <?php if($_GET["categorie"]=="Apiculteur") echo 'selected="selected"';?>><?php echo $htmlApiculteur?></option>
                        <option value="Éleveur de volaille" <?php if($_GET["categorie"]=="Éleveur de volaille") echo 'selected="selected"';?>><?php echo $htmlÉleveurdevolailles?></option>
                        <option value="Viticulteur" <?php if($_GET["categorie"]=="Viticulteur") echo 'selected="selected"';?>><?php echo $htmlViticulteur?></option>
                        <option value="Pépiniériste" <?php if($_GET["categorie"]=="Pépiniériste") echo 'selected="selected"';?>><?php echo $htmlPépiniériste?></option>
                    </select>
                    <br>
                    <br><?php echo $htmlParVille?>
                    <br>
                    <input type="text" name="rechercheVille" pattern="[A-Za-z0-9 ]{0,100}" value="<?php echo $rechercheVille?>" placeholder="<?php echo $htmlVille; ?>">
                    <br>
                    <?php
                    $returnQueryAdrUti = AdrUti($bdd,$utilisateur);
                    if (count($returnQueryAdrUti) > 0) {
                        $Adr_Uti_En_Cours = $returnQueryAdrUti[0]["Adr_Uti"];
                    ?>
                        <br>
                        <br><?php echo $htmlAutourDeChezMoi.' ('.$Adr_Uti_En_Cours.')';?>
                        <br>
                        <br>
                        <input name="rayon" type="range" value="<?php echo $rayon;?>" min="1" max="100" step="1" onchange="AfficheRange2(this.value)" onkeyup="AfficheRange2(this.value)">
                        <span id="monCurseurKm"><?php echo $htmlRayonDe?> <?php echo $rayon; if($rayon>=100) echo '+';?></span>
                        <script>
                            function AfficheRange2(newVal) {
                                var monCurseurKm = document.getElementById("monCurseurKm");
                                if (newVal >= 100) {
                                    monCurseurKm.innerHTML = "Rayon de " + newVal + "+ ";
                                } else {
                                    monCurseurKm.innerHTML = "Rayon de " + newVal + " ";
                                }
                            }

                        </script>
                        <?php echo $htmlKm?>
                        <br>
                        <br>
                    <?php
                    } else {
                        $Adr_Uti_En_Cours = 'France';
                    }
                    ?>
                    <br>
                    <label><?php echo $htmlTri?></label>
                    <br>
                    <select name="tri" required>
                        <option value="nombreDeProduits" <?php if($tri=="nombreDeProduits") echo 'selected="selected"';?>><?php echo $htmlNombreDeProduits?></option>
                        <option value="ordreNomAlphabétique" <?php if($tri=="ordreNomAlphabétique") echo 'selected="selected"';?>><?php echo $htmlParNomAl?></option>
                        <option value="ordreNomAntiAlphabétique" <?php if($tri=="ordreNomAntiAlphabétique") echo 'selected="selected"';?>><?php echo $htmlParNomAntiAl?></option>
                        <option value="ordrePrenomAlphabétique" <?php if($tri=="ordrePrenomAlphabétique") echo 'selected="selected"';?>><?php echo $htmlParPrenomAl?></option>
                        <option value="ordrePrenomAntiAlphabétique" <?php if($tri=="ordrePrenomAntiAlphabétique") echo 'selected="selected"';?>><?php echo $htmlParPrenomAntiAl?></option>
                    </select>
                    <br>
                    <br>
                    <br>
                    <center><input type="submit" value="<?php echo $htmlRechercher?>"></center>
                </form>
            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                    if (isset($_SESSION["Id_Uti"])) {
                        echo '<a class="bontonDeNavigation" href="ViewMessagerie.php">'.$htmlMessagerie.'</a>';
                        echo '<a class="bontonDeNavigation" href="ViewAchats.php">'.$htmlAchats.'</a>';
                    }
                    if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"] == true)) {
                        echo '<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                        echo '<a class="bontonDeNavigation" href="ViewDelivery.php">'.$htmlCommandes.'</a>';
                    }
                    if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) {
                        echo '<a class="bontonDeNavigation" href="ViewPanelAdmin.php">'.$htmlPanelAdmin.'</a>';
                    }
                    ?>
                </div>
                <form action="language/language.php" method="post" id="languageForm">
                    <select name="language" id="languagePicker" onchange="submitForm()">
                        <option value="fr" <?php if($_SESSION["language"]=="fr") echo 'selected';?>>Français</option>
                        <option value="en" <?php if($_SESSION["language"]=="en") echo 'selected';?>>English</option>
                        <option value="es" <?php if($_SESSION["language"]=="es") echo 'selected';?>>Español</option>
                        <option value="al" <?php if($_SESSION["language"]=="al") echo 'selected';?>>Deutsch</option>
                        <option value="ru" <?php if($_SESSION["language"]=="ru") echo 'selected';?>>русский</option>
                        <option value="ch" <?php if($_SESSION["language"]=="ch") echo 'selected';?>>中國人</option>
                    </select>
                </form>
                <form method="post">
                    <script>
                        function submitForm() {
                            document.getElementById("languageForm").submit();
                        }
                    </script>
                    <?php
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    if (isset($_SESSION, $_SESSION['tempPopup'])) {
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>
                    <input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])) { echo($htmlSeConnecter); } else { echo ''.$_SESSION['Mail_Uti'].''; } ?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])) { echo '"info_perso"'; } else { echo '"sign_in"'; } ?>>
                </form>
            </div>
            <h1> <?php echo $htmlProducteursEnMaj?> </h1>
            <div class="gallery-container">
            <?php
            

            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (isset($_GET["categorie"])) {
                    $categorie = htmlspecialchars($_GET["categorie"]);
                    $result = getProducteurs($categorie, $rechercheVille, $tri, $rayon, $Adr_Uti_En_Cours);
                    $coordonneesUti = latLongGps($Adr_Uti_En_Cours);
                    $latitudeUti = $coordonneesUti[0];
                    $longitudeUti = $coordonneesUti[1];

                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            if ($rayon >= 100) {
                                displayProducteur($row);
                            } else {
                                $coordonneesProd = getCoordinates($row["Adr_Uti"]);
                                $latitudeProd = $coordonneesProd[0];
                                $longitudeProd = $coordonneesProd[1];
                                $distance = calculateDistance($latitudeUti, $longitudeUti, $latitudeProd, $longitudeProd);
                                if ($distance < $rayon) {
                                    displayProducteur($row);
                                }
                            }
                        }
                    } else {
                        echo $htmlAucunResultat;
                    }
                }
            }
            ?>
            </div>
            <br>
            <div class="basDePage">
                <form method="post">
                    <input type="submit" value="<?php echo $htmlSignalerDys?>" class="lienPopup">
                    <input type="hidden" name="popup" value="contact_admin">
                </form>
                <form method="post">
                    <input type="submit" value="<?php echo $htmlCGU?>" class="lienPopup">
                    <input type="hidden" name="popup" value="cgu">
                </form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>
</html>