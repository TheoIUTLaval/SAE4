<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Fonction de connexion à la base de données
 * @return PDO
 */
function dbConnect() {
    try {
        $bdd = new PDO(
            'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $bdd;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

/**
 * Récupérer les commandes en fonction du filtre de catégorie
 * @param PDO $bdd
 * @param int|null $utilisateur : ID du propriétaire (producteur)
 * @param int|null $filtreCategorie : Filtrer par statut (0 pour tout récupérer)
 * @return array
 */
function getCommandes(PDO $bdd, int $utilisateur, ?int $filtreCategorie = 0): array {
    $query = 'SELECT Desc_Statut, Id_Commande, COMMANDE.Id_Uti, UTILISATEUR.Nom_Uti, 
                     UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut 
              FROM COMMANDE 
              INNER JOIN info_producteur ON COMMANDE.Id_Prod = info_producteur.Id_Prod 
              INNER JOIN STATUT ON COMMANDE.Id_Statut = STATUT.Id_Statut 
              INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti = UTILISATEUR.Id_Uti 
              WHERE info_producteur.Id_Uti = :utilisateur';

    // Ajouter le filtre si ce n'est pas 0 (c.-à-d. si un statut est spécifié)
    if ($filtreCategorie !== 0) {
        $query .= ' AND COMMANDE.Id_Statut = :filtreCategorie';
    }

    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);

    if ($filtreCategorie !== 0) {
        $stmt->bindParam(':filtreCategorie', $filtreCategorie, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupérer les produits d'une commande donnée
 * @param PDO $bdd
 * @param int $Id_Commande
 * @return array
 */
function getProduitsCommande(PDO $bdd, int $Id_Commande): array {
    $query = 'SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix 
              FROM produits_commandes 
              WHERE Id_Commande = :idCommande';

    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':idCommande', $Id_Commande, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>