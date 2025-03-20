<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
        session_start();
        require "language/language.php";
        include 'modele/modeleDelivery.php';
    ?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" type="text/css" href="css/style_général.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link rel="stylesheet" type="text/css" href="css/template.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="custom-container">
        <div class="leftColumn">
            <a href="index.php"><img class="logo" src="asset/img/logo.png"></a>
            <div class="contenuBarre">
                <center><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></center>
                <br>
                <strong><?php echo $htmlStatut; ?></strong>
                <br>
                <form action="ViewDelivery.php" method="post">
                    <?php
                    $categories = [
                        "0" => $htmlTOUT,
                        "1" => $htmlENCOURS,
                        "2" => $htmlPRETE,
                        "3" => $htmlANNULEE,
                        "4" => $htmlLIVREE
                    ];
                    foreach ($categories as $key => $label) {
                        echo "<label>
                                <input type='radio' name='typeCategorie' value='$key' " . ($filtreCategorie == $key ? 'checked' : '') . "> $label
                              </label><br>";
                    }
                    ?>
                    <br>
                    <center><input type="submit" value="<?php echo $htmlFiltrer; ?>"></center>
                </form>
            </div>
        </div>

        <div class="rightColumn">
            <div class="topBanner">
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">Accueil</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto">
                                <?php if (isset($_SESSION["Id_Uti"])): ?>
                                    <li class="nav-item"><a class="nav-link" href="ViewMessagerie.php">Messagerie</a></li>
                                    <li class="nav-item"><a class="nav-link" href="ViewAchats.php">Achats</a></li>
                                <?php endif; ?>
                                <?php if (!empty($_SESSION["isProd"])): ?>
                                    <li class="nav-item"><a class="nav-link" href="produits.php">Produits</a></li>
                                    <li class="nav-item"><a class="nav-link" href="ViewDelivery.php">Commandes</a></li>
                                <?php endif; ?>
                                <?php if (!empty($_SESSION["isAdmin"])): ?>
                                    <li class="nav-item"><a class="nav-link" href="ViewPanelAdmin.php">Panel Admin</a></li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <form action="language/language.php" method="post" id="languageForm">
                                        <select name="language" id="languagePicker" onchange="document.getElementById('languageForm').submit()" class="form-select">
                                            <option disabled selected>Language</option>
                                            <?php
                                            $languages = ["fr" => "Français", "en" => "English", "es" => "Español", "al" => "Deutsch", "ru" => "русский", "ch" => "中國人"];
                                            foreach ($languages as $code => $lang) {
                                                echo "<option value='$code' " . ($_SESSION["language"] == $code ? "selected" : "") . ">$lang</option>";
                                            }
                                            ?>
                                        </select>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="divConnection">
                    <form method="post">
                        <?php
                        $_SESSION['tempPopup'] = $_SESSION['tempPopup'] ?? "";
                        if ($_SESSION['tempPopup']) {
                            $_POST['popup'] = $_SESSION['tempPopup'];
                            unset($_SESSION['tempPopup']);
                        }
                        ?>
                        <input type="submit" value="<?php echo isset($_SESSION['Mail_Uti']) ? $_SESSION['Mail_Uti'] : "Se connecter"; ?>" class="boutonDeConnection">
                        <input type="hidden" name="popup" value="<?php echo isset($_SESSION['Mail_Uti']) ? 'info_perso' : 'sign_in'; ?>">
                    </form>
                </div>
            </div>
            
            <div class="contenuPage">
                <?php
                if (empty($returnQueryGetCommande)) {
                    echo "<p>$htmlAucuneCommande</p>";
                } else {
                    foreach ($returnQueryGetCommande as $commande) {
                        $Desc_Statut = mb_strtoupper($commande["Desc_Statut"]);
                        $Nom_Client = mb_strtoupper($commande["Nom_Uti"]);
                        $Prenom_Client = $commande["Prenom_Uti"];
                        $Id_Statut = $commande["Id_Statut"];
                        $Id_Commande = $commande["Id_Commande"];

                        echo "<div class='commande'>";
                        echo "<strong>$htmlClient :</strong> $Prenom_Client $Nom_Client<br>";
                        echo "<strong>$htmlCOMMANDE :</strong> $Desc_Statut<br>";

                        if (!in_array($Id_Statut, [3, 4])) {
                            echo '<form action="change_status_commande.php" method="post">
                                    <select name="categorie">
                                        <option value="">' . $htmlModifierStatut . '</option>';
                            foreach ($categories as $key => $label) {
                                if ($key > 0) echo "<option value='$key'>$label</option>";
                            }
                            echo '  </select>
                                    <input type="hidden" name="idCommande" value="' . $Id_Commande . '">
                                    <button type="submit">' . $htmlConfirmer . '</button>
                                  </form>';
                        }

                        $total = 0;
                        $produitsCommande = getProduitsCommande($bdd, $Id_Commande);
                        if (!empty($produitsCommande)) {
                            foreach ($produitsCommande as $produit) {
                                $prixTotal = intval($produit["Prix_Produit_Unitaire"]) * intval($produit["Qte_Produit_Commande"]);
                                $total += $prixTotal;
                                echo "- {$produit['Nom_Produit']} ({$produit['Qte_Produit_Commande']} {$produit['Nom_Unite_Prix']} * {$produit['Prix_Produit_Unitaire']}€) = $prixTotal €<br>";
                            }
                            echo '<input type="button" onclick="window.location.href=\'ViewMessagerie.php?Id_Interlocuteur=' . $commande["Id_Uti"] . '\'" value="' . $htmlEnvoyerMessage . '"><br>';
                        }
                        echo "<strong style='float:right;'>$htmlTotalDeuxPoints $total€</strong>";
                        echo "</div><br>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php require "popups/gestion_popups.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>