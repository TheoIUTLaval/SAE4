<?php

if (!isset($_SESSION)) {
  session_start();
}
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$utilisateur = $_ENV['DB_USER'];
$serveur = $_ENV['DB_HOST'];
$motdepasse = $_ENV['DB_PASSWORD'];
$basededonnees = $_ENV['DB_NAME'];

try {
  $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die('Erreur : ' . $e->getMessage());
}

$utilisateur = isset($_POST["Id_Uti"]) ? htmlspecialchars($_POST["Id_Uti"]) : htmlspecialchars($_SESSION["Id_Uti"]);
$delParAdmin = isset($_POST["Id_Uti"]);
$msg = $delParAdmin ? '' : "?msg=compte supprimer";

$isProducteur = $bdd->prepare('CALL isProducteur(:utilisateur);');
$isProducteur->bindParam(':utilisateur', $utilisateur, PDO::PARAM_STR);
$isProducteur->execute();
$reponse = $isProducteur->fetchColumn();

if ($reponse === NULL) {
  handleNonProducteur($bdd, $utilisateur);
} else {
  handleProducteur($bdd, $utilisateur);
}

header('Location: ' . ($delParAdmin ? '../ViewPanelAdmin.php' : 'log_out.php' . $msg));

function handleNonProducteur($bdd, $utilisateur) {
  $queryGetProduitCommande = $bdd->prepare('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes WHERE Id_Uti = :utilisateur;');
  $queryGetProduitCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
  $queryGetProduitCommande->execute();
  $produitsCommandes = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);

  foreach ($produitsCommandes as $produitCommande) {
    $updateProduit = $bdd->prepare('UPDATE PRODUIT SET Qte_Produit = Qte_Produit + :Qte_Produit_Commande WHERE Id_Produit = :Id_Produit');
    $updateProduit->bindParam(':Qte_Produit_Commande', $produitCommande["Qte_Produit_Commande"], PDO::PARAM_INT);
    $updateProduit->bindParam(':Id_Produit', $produitCommande["Id_Produit"], PDO::PARAM_INT);
    $updateProduit->execute();

    $delContenu = $bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit= :Id_Produit;');
    $delContenu->bindParam(":Id_Produit", $produitCommande["Id_Produit"], PDO::PARAM_STR);
    $delContenu->execute();
  }

  $queries = [
    'DELETE FROM COMMANDE WHERE Id_Uti= :utilisateur;',
    'DELETE FROM MESSAGE WHERE Emetteur= :utilisateur OR Destinataire= :utilisateur;',
    'DELETE FROM UTILISATEUR WHERE Id_Uti=:utilisateur;'
  ];

  foreach ($queries as $query) {
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
    $stmt->execute();
  }
}

function handleProducteur($bdd, $utilisateur) {
  $queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti=:Id_Uti;');
  $queryIdProd->bindParam(":Id_Uti", $utilisateur, PDO::PARAM_STR);
  $queryIdProd->execute();
  $IdProd = $queryIdProd->fetchColumn();

  $queryGetProduitCommande = $bdd->prepare('SELECT Id_Produit FROM PRODUIT WHERE Id_Prod = :IdProd;');
  $queryGetProduitCommande->bindParam(":IdProd", $IdProd, PDO::PARAM_STR);
  $queryGetProduitCommande->execute();
  $produits = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);

  foreach ($produits as $produit) {
    $delContenu = $bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit=:Id_Produit;');
    $delContenu->bindParam(":Id_Produit", $produit["Id_Produit"], PDO::PARAM_STR);
    $delContenu->execute();

    $delProduit = $bdd->prepare('DELETE FROM PRODUIT WHERE Id_Produit=:Id_Produit;');
    $delProduit->bindParam(":Id_Produit", $produit["Id_Produit"], PDO::PARAM_STR);
    $delProduit->execute();
  }

  $queries = [
    'DELETE FROM COMMANDE WHERE Id_Uti= :utilisateur;',
    'DELETE FROM COMMANDE WHERE Id_Prod = :IdProd;',
    'DELETE FROM MESSAGE WHERE Emetteur= :utilisateur OR Destinataire= :utilisateur;',
    'DELETE FROM PRODUCTEUR WHERE Id_Uti=:utilisateur;',
    'DELETE FROM UTILISATEUR WHERE Id_Uti=:utilisateur;'
  ];

  foreach ($queries as $query) {
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(strpos($query, 'Id_Prod') !== false ? ':IdProd' : ':utilisateur', strpos($query, 'Id_Prod') !== false ? $IdProd : $utilisateur, PDO::PARAM_STR);
    $stmt->execute();
  }
}

?>
