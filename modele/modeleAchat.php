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
    else{
        while ($iterateurCommande<count($returnQueryGetCommande)){
            $Id_Commande = $returnQueryGetCommande[$iterateurCommande]["Id_Commande"];
            $Nom_Prod = $returnQueryGetCommande[$iterateurCommande]["Nom_Uti"];
            $Nom_Prod = mb_strtoupper($Nom_Prod);
            $Prenom_Prod = $returnQueryGetCommande[$iterateurCommande]["Prenom_Uti"];
            $Adr_Uti = $returnQueryGetCommande[$iterateurCommande]["Adr_Uti"];
            $Desc_Statut = $returnQueryGetCommande[$iterateurCommande]["Desc_Statut"];
            $Desc_Statut = mb_strtoupper($Desc_Statut);
            $Id_Statut = $returnQueryGetCommande[$iterateurCommande]["Id_Statut"];
            $idUti = $returnQueryGetCommande[$iterateurCommande]["Id_Uti"];


            $total=0;
            $queryGetProduitCommande = $bdd->prepare('SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix FROM produits_commandes  WHERE Id_Commande = :Id_Commande;');
            $queryGetProduitCommande->bindParam(":Id_Commande", $Id_Commande, PDO::PARAM_STR);
            $queryGetProduitCommande->execute();
            $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
            $iterateurProduit=0;
            $nbProduit=count($returnQueryGetProduitCommande);

            if ($nbProduit > 0) { ?>
                <div class="container">
                    <div class="card mb-3">
                        <h5 class="card-header">Commande <?php echo $iterateurCommande + 1; ?> : <?php echo $htmlChez . " " . $Prenom_Prod . " " . $Nom_Prod . " - " . $Adr_Uti; ?></h5>
                        <div class="card-body">
                            <p class="card-text"> <?php echo $Desc_Statut; ?> </p>
                            
                            <?php if ($Id_Statut != 3 && $Id_Statut != 4) { ?>
                                <form action="modele/delete_commande.php" method="post">
                                    <input type="hidden" name="deleteValeur" value="<?php echo $Id_Commande; ?>">
                                    <button type="submit" class="btn btn-danger"> <?php echo $htmlAnnulerCommande; ?> </button>
                                </form>
                            <?php } ?>
                            
                            <button class="btn btn-primary mt-2" onclick="window.location.href='ViewMessagerie.php?Id_Interlocuteur=<?php echo $idUti; ?>'">
                                <?php echo $htmlEnvoyerMessage; ?>
                            </button>
                            
                            <ul class="list-group mt-3">
                                <?php while ($iterateurProduit < $nbProduit) { 
                                    $Nom_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Produit"];
                                    $Qte_Produit_Commande = $returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
                                    $Nom_Unite_Prix = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Unite_Prix"];
                                    $Prix_Produit_Unitaire = $returnQueryGetProduitCommande[$iterateurProduit]["Prix_Produit_Unitaire"];
                                    $totalProduit = intval($Prix_Produit_Unitaire) * intval($Qte_Produit_Commande);
                                    $total += $totalProduit;
                                ?>
                                    <li class="list-group-item">
                                        <?php echo "- " . $Nom_Produit . " - " . $Qte_Produit_Commande . " " . $Nom_Unite_Prix . " * " . $Prix_Produit_Unitaire . "€ = " . $totalProduit . "€"; ?>
                                    </li>
                                <?php $iterateurProduit++; } ?>
                            </ul>
                            
                            <div class="text-end mt-3">
                                <h5><?php echo $htmlTotalDeuxPoints . " " . $total . "€"; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } 
        }
    }