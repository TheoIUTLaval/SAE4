<?php
// Activer l'affichage des erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session si elle n'est pas déjà démarrée
if (!isset($_SESSION)) {
    session_start();
}

// Chargement des variables d'environnement
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Connexion à la base de données
try {
    $bdd = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Récupération de l'utilisateur (admin ou utilisateur courant)
$utilisateur = isset($_POST["Id_Uti"]) ? htmlspecialchars($_POST["Id_Uti"]) : htmlspecialchars($_SESSION["Id_Uti"]);
$delParAdmin = isset($_POST["Id_Uti"]);
$msg = $delParAdmin ? '' : "?msg=compte supprimé";

// Vérification si l'utilisateur est un producteur
$queryIsProducteur = $bdd->prepare('SELECT 1 FROM PRODUCTEUR WHERE Id_Uti = :utilisateur LIMIT 1;');
$queryIsProducteur->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
$queryIsProducteur->execute();
$isProducteur = $queryIsProducteur->fetchColumn();
$queryIsProducteur->closeCursor();

// Traitement selon le statut utilisateur (Producteur ou simple utilisateur)
if ($isProducteur) {
    handleProducteur($bdd, $utilisateur);
} else {
    handleNonProducteur($bdd, $utilisateur);
}

// Déconnexion et redirection
header('Location: ' . ($delParAdmin ? '../ViewPanelAdmin.php' : 'log_out.php' . $msg));
exit;

/**
 * Suppression d'un utilisateur non-producteur
 */
function handleNonProducteur($bdd, $utilisateur)
{
    // Récupérer les produits commandés par l'utilisateur
    $queryProduitCommande = $bdd->prepare('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes WHERE Id_Uti = :utilisateur;');
    $queryProduitCommande->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
    $queryProduitCommande->execute();
    $produitsCommandes = $queryProduitCommande->fetchAll(PDO::FETCH_ASSOC);
    $queryProduitCommande->closeCursor();

    // Mettre à jour les stocks de produits et nettoyer les contenus
    foreach ($produitsCommandes as $produitCommande) {
        $updateProduit = $bdd->prepare('UPDATE PRODUIT SET Qte_Produit = Qte_Produit + :qte WHERE Id_Produit = :id;');
        $updateProduit->bindParam(':qte', $produitCommande["Qte_Produit_Commande"], PDO::PARAM_INT);
        $updateProduit->bindParam(':id', $produitCommande["Id_Produit"], PDO::PARAM_INT);
        $updateProduit->execute();

        $delContenu = $bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit = :id;');
        $delContenu->bindParam(":id", $produitCommande["Id_Produit"], PDO::PARAM_INT);
        $delContenu->execute();
    }

    // Supprimer les commandes, messages et utilisateur
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

/**
 * Suppression d'un utilisateur producteur
 */
function handleProducteur($bdd, $utilisateur)
{
    // Récupérer l'ID du producteur
    $queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti = :utilisateur;');
    $queryIdProd->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
    $queryIdProd->execute();
    $IdProd = $queryIdProd->fetchColumn();
    $queryIdProd->closeCursor();

    // Récupérer tous les produits du producteur
    $queryGetProduits = $bdd->prepare('SELECT Id_Produit FROM PRODUIT WHERE Id_Prod = :idProd;');
    $queryGetProduits->bindParam(":idProd", $IdProd, PDO::PARAM_INT);
    $queryGetProduits->execute();
    $produits = $queryGetProduits->fetchAll(PDO::FETCH_ASSOC);
    $queryGetProduits->closeCursor();

    // Supprimer les produits et leur contenu
    foreach ($produits as $produit) {
        $delContenu = $bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit = :id;');
        $delContenu->bindParam(":id", $produit["Id_Produit"], PDO::PARAM_INT);
        $delContenu->execute();

        $delProduit = $bdd->prepare('DELETE FROM PRODUIT WHERE Id_Produit = :id;');
        $delProduit->bindParam(":id", $produit["Id_Produit"], PDO::PARAM_INT);
        $delProduit->execute();
    }

    // Suppression des commandes, messages, producteur et utilisateur
    $queries = [
        'DELETE FROM COMMANDE WHERE Id_Uti=:utilisateur;',
        'DELETE FROM COMMANDE WHERE Id_Prod=:idProd;',
        'DELETE FROM MESSAGE WHERE Emetteur=:utilisateur OR Destinataire=:utilisateur;',
        'DELETE FROM PRODUCTEUR WHERE Id_Uti=:utilisateur;',
        'DELETE FROM UTILISATEUR WHERE Id_Uti=:utilisateur;'
    ];

    foreach ($queries as $query) {
        $stmt = $bdd->prepare($query);
        if (strpos($query, 'Id_Prod') !== false) {
            $stmt->bindParam(':idProd', $IdProd, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        }
        $stmt->execute();
    }
}