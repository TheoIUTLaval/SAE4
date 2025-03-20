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

// Vérifier si le filtre est défini, sinon prendre 0 (toutes les commandes)
$filtreCategorie = $_POST['typeCategorie'] ?? 0;

// Récupérer les commandes de l'utilisateur
$returnQueryGetCommande = getCommandes($bdd, $utilisateur, $filtreCategorie);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style_général.css">
    <link rel="stylesheet" href="css/template.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="contenuPage">
    <?php if (empty($returnQueryGetCommande)): ?>
        <p><?php echo $htmlAucuneCommande; ?></p>
    <?php else: ?>
        <?php foreach ($returnQueryGetCommande as $commande): ?>
            <div class='commande'>
                <strong><?php echo $htmlClient; ?>:</strong> <?php echo "{$commande['Prenom_Uti']} {$commande['Nom_Uti']}"; ?><br>
                <strong><?php echo $htmlCOMMANDE; ?>:</strong> <?php echo strtoupper($commande['Desc_Statut']); ?><br>

                <!-- Modification du statut de commande -->
                <?php if (!in_array($commande['Id_Statut'], [3, 4])): ?>
                    <form action="change_status_commande.php" method="post">
                        <select name="categorie">
                            <option value=""><?php echo $htmlModifierStatut; ?></option>
                            <option value="1"><?php echo $htmlENCOURS; ?></option>
                            <option value="2"><?php echo $htmlPRETE; ?></option>
                            <option value="3"><?php echo $htmlANNULEE; ?></option>
                            <option value="4"><?php echo $htmlLIVREE; ?></option>
                        </select>
                        <input type="hidden" name="idCommande" value="<?php echo $commande['Id_Commande']; ?>">
                        <button type="submit"><?php echo $htmlConfirmer; ?></button>
                    </form>
                <?php endif; ?>

                <!-- Affichage des produits associés à la commande -->
                <?php
                $total = 0;
                $produitsCommande = getProduitsCommande($bdd, $commande['Id_Commande']);
                ?>

                <?php if (!empty($produitsCommande)): ?>
                    <?php foreach ($produitsCommande as $produit): ?>
                        <p>
                            <?php echo "- {$produit['Nom_Produit']} : {$produit['Qte_Produit_Commande']} {$produit['Nom_Unite_Prix']} x {$produit['Prix_Produit_Unitaire']}€ = " .
                                ($produit['Prix_Produit_Unitaire'] * $produit['Qte_Produit_Commande']) . "€"; ?>
                        </p>
                        <?php $total += $produit['Prix_Produit_Unitaire'] * $produit['Qte_Produit_Commande']; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <strong>Total:</strong> <?php echo $total; ?>€<br>
            </div>
        <?php endforeach; ?>
    <?php endif;