<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
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
    
    $query='SELECT PRODUCTEUR.Id_Uti, Desc_Statut, Id_Commande, Nom_Uti, Prenom_Uti, Adr_Uti, COMMANDE.Id_Statut FROM COMMANDE INNER JOIN PRODUCTEUR ON COMMANDE.Id_Prod=PRODUCTEUR.Id_Prod INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut WHERE COMMANDE.Id_Uti= :utilisateur';
    if ($filtreCategorie!=0){
        $query=$query.' AND COMMANDE.Id_Statut= :filtreCategorie ;';
    }
    $queryGetCommande = $bdd->prepare($query);
    $queryGetCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
    if ($filtreCategorie!=0){
        $queryGetCommande->bindParam(":filtreCategorie", $filtreCategorie, PDO::PARAM_STR);
    }
    $queryGetCommande->execute();
    $returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
    $iterateurCommande=0;
    if(count($returnQueryGetCommande)==0 and ($filtreCategorie==0)){
        echo $htmlAucuneCommande;
        ?>
        <br>
        <input type="button" onclick="window.location.href='index.php'" value="<?php echo $htmlDecouverteProducteurs; ?>">
        <?php
    }
    elseif(count($returnQueryGetCommande)==0){
        echo $htmlAucuneCommandeCorrespondCriteres;
    }

    else { 
        while ($iterateurCommande < count($returnQueryGetCommande)) {
            $Id_Commande = $returnQueryGetCommande[$iterateurCommande]["Id_Commande"];
            $Nom_Prod = $returnQueryGetCommande[$iterateurCommande]["Nom_Uti"];
            $Nom_Prod = mb_strtoupper($Nom_Prod);
            $Prenom_Prod = $returnQueryGetCommande[$iterateurCommande]["Prenom_Uti"];
            $Adr_Uti = $returnQueryGetCommande[$iterateurCommande]["Adr_Uti"];
            $Desc_Statut = $returnQueryGetCommande[$iterateurCommande]["Desc_Statut"];
            $Desc_Statut = mb_strtoupper($Desc_Statut);
            $Id_Statut = $returnQueryGetCommande[$iterateurCommande]["Id_Statut"];
            $idUti = $returnQueryGetCommande[$iterateurCommande]["Id_Uti"];
    
            $total = 0;
            $queryGetProduitCommande = $bdd->prepare('SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix FROM produits_commandes WHERE Id_Commande = :Id_Commande;');
            $queryGetProduitCommande->bindParam(":Id_Commande", $Id_Commande, PDO::PARAM_STR);
            $queryGetProduitCommande->execute();
            $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
            $iterateurProduit = 0;
            $nbProduit = count($returnQueryGetProduitCommande);
    
            if ($nbProduit > 0) {
                // Début de la carte Bootstrap
                echo '<div class="card mb-3">'; // carte principale avec une marge en bas
                echo '<div class="card-header"><strong>Commande ' . ($iterateurCommande + 1) . ' :</strong> ' . $htmlChez . ' ' . $Prenom_Prod . ' ' . $Nom_Prod . ' - ' . $Adr_Uti . '</div>';
                echo '<div class="card-body">';
                echo '<p class="card-text"> <strong>' . $Desc_Statut . '</strong></p>';
    
                if ($Id_Statut != 3 && $Id_Statut != 4) {
                    echo '<form action="modele/delete_commande.php" method="post">';
                    echo '<input type="hidden" name="deleteValeur" value="' . $Id_Commande . '">';
                    echo '<button type="submit" class="btn btn-danger">' . $htmlAnnulerCommande . '</button>';
                    echo '</form>';
                }
    
                // Bouton pour envoyer un message
                echo '<input type="button" class="btn btn-outline-primary" 
                        style="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514;" 
                        onmouseover="this.style.backgroundColor=\'#305514\'; this.style.color=\'#FFFFFF\';" 
                        onmouseout="this.style.backgroundColor=\'\'; this.style.color=\'#305514\';" 
                        onclick="window.location.href=\'ViewMessagerie.php?Id_Interlocuteur=' . $idUti . '\'" 
                        value="' . $htmlEnvoyerMessage . '" class="btn btn-info mt-3">';

                echo '<br>';
    
                while ($iterateurProduit < $nbProduit) {
                    $Nom_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Produit"];
                    $Qte_Produit_Commande = $returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
                    $Nom_Unite_Prix = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Unite_Prix"];
                    $Prix_Produit_Unitaire = $returnQueryGetProduitCommande[$iterateurProduit]["Prix_Produit_Unitaire"];
                    echo '<br><p class="card-text"> <strong> ' . $Nom_Produit . ' </strong> ' . $Qte_Produit_Commande . ' ' . $Nom_Unite_Prix . ' * ' . $Prix_Produit_Unitaire . '€ = ' . floatval($Prix_Produit_Unitaire) * floatval($Qte_Produit_Commande) . '€</p>';
                    $total += floatval($Prix_Produit_Unitaire) * floatval($Qte_Produit_Commande);
                    $iterateurProduit++;
                }
    
                // Affichage du total dans un div aligné à droite
                echo '<div class="text-end"><strong>' . $htmlTotalDeuxPoints . ' ' . $total . '€</strong></div>';
                echo '</div>'; // Ferme card-body
                echo '</div>'; // Ferme card
            }
            $iterateurCommande++;
        }
    }
    ?>
    