<?php
function dbConnect(){
    $utilisateur = "etu";
    $serveur = "localhost";
    $motdepasse = "Achanger!";
    $basededonnees = "sae";
    // Connect to database
    return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
}

$bdd=dbConnect();
if ($filtreCategorie!=0){
    $query = 'SELECT Desc_Statut, Id_Commande, COMMANDE.Id_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut 
    FROM COMMANDE 
    INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod 
    INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut 
    INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti=UTILISATEUR.Id_Uti 
    WHERE info_producteur.Id_Uti = :utilisateur AND COMMANDE.Id_Statut = :filtreCategorie';
    $queryGetCommande = $bdd->prepare($query);
    $queryGetCommande->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
    $queryGetCommande->bindParam(':filtreCategorie', $filtreCategorie, PDO::PARAM_INT);            
}
else{
    $query = 'SELECT Desc_Statut, Id_Commande, COMMANDE.Id_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut 
    FROM COMMANDE 
    INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod 
    INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut 
    INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti=UTILISATEUR.Id_Uti 
    WHERE info_producteur.Id_Uti = :utilisateur';

    $queryGetCommande = $bdd->prepare($query);
    $queryGetCommande->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
    
}
$queryGetCommande->execute();
$returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
$iterateurCommande=0;


function getProduitsCommande($bdd, $Id_Commande) {
    $query = 'SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix 
              FROM produits_commandes 
              WHERE Id_Commande = :idCommande';
    $queryGetProduitCommande = $bdd->prepare($query);
    $queryGetProduitCommande->bindParam(':idCommande', $Id_Commande, PDO::PARAM_INT);
    $queryGetProduitCommande->execute();
    return $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
}
?>