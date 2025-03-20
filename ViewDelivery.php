<?php
session_start();
require "language/language.php";
require "modele/modeleDelivery.php";

$bdd = dbConnect();

// Vérifier si l'utilisateur est connecté et récupérer son ID
if (!isset($_SESSION["Id_Uti"])) {
    die("Utilisateur non connecté !");
}
$utilisateur = $_SESSION["Id_Uti"];

// Vérifier si le filtre existe, sinon prendre 0 (toutes les commandes)
$filtreCategorie = $_POST['typeCategorie'] ?? 0;

// Récupérer les commandes de l'utilisateur
$returnQueryGetCommande = getCommandes($bdd, $utilisateur, $filtreCategorie);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $htmlMarque; ?></title>

    <!-- ✅ Assurez-vous que les fichiers CSS sont bien chargés -->
    <link rel="stylesheet" href="css/style_général.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/template.css">
    
    <!-- ✅ Bootstrap CDN pour le bon affichage -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ✅ Conteneur principal -->
<div class="custom-container">
    <div class="leftColumn">
        <a href="index.php"><img class="logo" src="asset/img/logo.png" alt="Logo"></a>
        <div class="contenuBarre">
            <center><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></center>
            <br>
            <strong><?php echo $htmlStatut; ?></strong>
            <br>
            <form action="ViewDelivery.php" method="post">
                <?php
                $categories = [
                    0 => $htmlTOUT,
                    1 => $htmlENCOURS,
                    2 => $htmlPRETE,
                    3 => $htmlANNULEE,
                    4 => $htmlLIVREE
                ];
                foreach ($categories as $key => $label) {
                    echo "<label>
                            <input type='radio' name='typeCategorie' value='$key' " . 
                            ($filtreCategorie == $key ? 'checked' : '') . "> $label
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navMenu">
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
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="contenuPage">
            <?php if (empty($returnQueryGetCommande)): ?>
                <p><?php echo $htmlAucuneCommande; ?></p>
            <?php else: ?>
                <?php foreach ($returnQueryGetCommande as $commande): ?>
                    <div class="commande">
                        <strong><?php echo $htmlClient; ?>:</strong> <?php echo "{$commande['Prenom_Uti']} {$commande['Nom_Uti']}"; ?><br>
                        <strong><?php echo $htmlCOMMANDE; ?>:</strong> <?php echo strtoupper($commande['Desc_Statut']); ?><br>

                        <?php if (!in_array($commande['Id_Statut'], [3, 4])): ?>
                            <form action="change_status_commande.php" method="post">
                                <select name="categorie">
                                    <option value=""><?php echo $htmlModifierStatut; ?></option>
                                    <?php 
                                    foreach ($categories as $key => $label) {
                                        if ($key > 0) echo "<option value='$key'>$label</option>";
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="idCommande" value="<?php echo $commande['Id_Commande']; ?>">
                                <button type="submit"><?php echo $htmlConfirmer; ?></button>
                            </form>
                        <?php endif; ?>

                        <?php
                        $total = 0;
                        $produitsCommande = getProduitsCommande($bdd, $commande['Id_Commande']);
                        ?>
                        <?php if (!empty($produitsCommande)): ?>
                            <?php foreach ($produitsCommande as $produit): ?>
                                <p>- <?php echo "{$produit['Nom_Produit']} ({$produit['Qte_Produit_Commande']} {$produit['Nom_Unite_Prix']} x {$produit['Prix_Produit_Unitaire']}€) = " .
                                    ($produit['Prix_Produit_Unitaire'] * $produit['Qte_Produit_Commande']) . "€"; ?>
                                </p>
                                <?php $total += $produit['Prix_Produit_Unitaire'] * $produit['Qte_Produit_Commande']; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <strong>Total:</strong> <?php echo $total; ?>€<br>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ✅ Inclusion des popups -->
<?php require "popups/gestion_popups.php"; ?>

<!-- ✅ Rechargement de Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>