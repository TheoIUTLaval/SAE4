<?php
    require "language/language.php" ; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>
<body>

<?php
        require __DIR__ . '/vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
        $dotenv->load();
        function dbConnect(){
            $utilisateur = $_ENV['DB_USER'];
            $serveur = $_ENV['DB_HOST'];
            $motdepasse = $_ENV['DB_PASSWORD'];
            $basededonnees = $_ENV['DB_NAME'];
            // Connect to database
            return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
        }
        
      if(!isset($_SESSION)){
        session_start();
    }
      $utilisateur=$_SESSION["Id_Uti"];
      htmlspecialchars($utilisateur);

      $bdd=dbConnect();
      $queryIdProd = $bdd->prepare(('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti= :Id_Uti ;'));
      $queryIdProd->bindParam(":Id_Uti", $utilisateur, PDO::PARAM_STR);
      $queryIdProd->execute();
      $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
      $Id_Prod=$returnQueryIdProd[0]["Id_Prod"];
    ?>

    <div class="custom-container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="asset/img/logo.png" alt="Logo">
            <div class="barretache">
    <div class="card shadow-lg">
        <div class="card-header text-center">
            <h4><strong><?php echo $htmlAjouterProduit; ?></strong></h4>
        </div>
        <div class="card-body">
            <form action="modele/insert_products.php" method="post" enctype="multipart/form-data">
                <!-- Nom du produit -->
                <div class="mb-3">
                    <label class="form-label"><?php echo $htmlProduitDeuxPoints; ?></label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9 ]{0,100}" name="nomProduit" placeholder="<?php echo $htmlNomDuProduit; ?>" required>
                </div>

                <!-- Catégorie -->
                <div class="mb-3">
                    <label class="form-label"><?php echo $htmlCategorie; ?></label>
                    <select name="categorie" class="form-select">
                        <option value="6"><?php echo $htmlAnimaux; ?></option>
                        <option value="1"><?php echo $htmlFruit; ?></option>
                        <option value="3"><?php echo $htmlGraine; ?></option>
                        <option value="2"><?php echo $htmlLégume; ?></option>
                        <option value="7"><?php echo $htmlPlanche; ?></option>
                        <option value="4"><?php echo $htmlViande; ?></option>
                        <option value="5"><?php echo $htmlVin; ?></option>
                    </select>
                </div>

                <!-- Prix -->
                <div class="mb-3">
                    <label class="form-label"><?php echo $htmlPrix; ?></label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="prix" min="0" required>
                        <span class="input-group-text">€</span>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="unitPrix" value="1" checked>
                        <label class="form-check-label"><?php echo $htmlLeKilo; ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="unitPrix" value="4">
                        <label class="form-check-label"><?php echo $htmlLaPiece; ?></label>
                    </div>
                </div>

                <!-- Stock -->
                <div class="mb-3">
                    <label class="form-label"><?php echo $htmlStockDeuxPoints; ?></label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="quantite" min="0" required>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="unitQuantite" value="1" checked>
                        <label class="form-check-label"><?php echo $htmlKg; ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="unitQuantite" value="2">
                        <label class="form-check-label"><?php echo $htmlL; ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="unitQuantite" value="3">
                        <label class="form-check-label"><?php echo $htmlM2; ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="unitQuantite" value="4">
                        <label class="form-check-label"><?php echo $htmlPiece; ?></label>
                    </div>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label class="form-label"><?php echo $htmlImageDeuxPoints; ?></label>
                    <input type="file" class="form-control" name="image" accept=".png" required>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success"><?php echo $htmlAjouterProduit; ?></button>
                    <a href="produits.php" class="btn btn-secondary"><?php echo $htmlAnnulerModifProd; ?></a>
                </div>
            </form>
        </div>
    </div>





            </div>
        </div>
        <div class="rightColumn">
        <div class="topBanner">
            <div class="divNavigation">
                <nav class="navbar navbar-expand-lg bg-body-tertiary" >
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">Accueil</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <?php if (isset($_SESSION["Id_Uti"])): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="ViewMessagerie.php">Messagerie</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="ViewAchats.php">Achats</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isProd"]) && ($_SESSION["isProd"] == true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produits.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="ViewDelivery.php">Commandes</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isAdmin"]) && ($_SESSION["isAdmin"] == true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="ViewPanelAdmin.php">Panel Admin</a>
                                    </li>
                                <?php endif; ?>

                                <li class="nav-item">
                                        <form action="language/language.php" method="post" id="languageForm" class="d-flex align-items-center dropdown">
                                            <select name="language" id="languagePicker" onchange="submitForm()" class="form-select" aria-label="Default select example">
                                                <option slected disabled>Language</option>
                                                <option value="fr" <?php if ($_SESSION["language"] == "fr") echo 'selected'; ?>>Français</option>
                                                <option value="en" <?php if ($_SESSION["language"] == "en") echo 'selected'; ?>>English</option>
                                                <option value="es" <?php if ($_SESSION["language"] == "es") echo 'selected'; ?>>Español</option>
                                                <option value="al" <?php if ($_SESSION["language"] == "al") echo 'selected'; ?>>Deutsch</option>
                                                <option value="ru" <?php if ($_SESSION["language"] == "ru") echo 'selected'; ?>>русский</option>
                                                <option value="ch" <?php if ($_SESSION["language"] == "ch") echo 'selected'; ?>>中國人</option>
                                            </select>
                                        </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="divConnection">
                <form method="post">

                <script>
                    function submitForm() {
                        document.getElementById("languageForm").submit();
                    }
                </script>
                    <?php
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    
                    ?>

					<input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo("Se connecter");} else {echo ''.$_SESSION['Mail_Uti'].'';}?> " class="boutonDeConnection" > <!-- Changer se connecter -->
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                
                </form>
                </div>
            </div>
        </div>
            


                    <!-- partie de gauche avec les produits -->
                    <div class="galerie-produit mt-5">
    <h3 class="text-center text-decoration-underline"><?php echo $htmlMesProduitsEnStock; ?></h3>
    <div>
    <?php if (isset($_SESSION['erreur'])): ?>
    <div class="d-flex justify-content-center align-items-center" style="margin-top: 10px;  margin-bottom: 10px;">
        <div class="alert alert-danger text-center" role="alert" style="font-weight: bold;">
            <?php echo htmlspecialchars($_SESSION['erreur']); ?>
        </div>
    </div>
    </div>
    <?php unset($_SESSION['erreur']); // Supprimer le message après l'affichage ?>
<?php endif; ?>
    <div class="row g-4 mt-3">
        <?php
            $bdd = dbConnect();
            $queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti = :utilisateur');
            $queryIdProd->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
            $queryIdProd->execute();
            $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
            $Id_Prod = $returnQueryIdProd[0]["Id_Prod"];

            $queryGetProducts = $bdd->prepare('SELECT Id_Produit, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit, Nom_Unite_Stock FROM Produits_d_un_producteur WHERE Id_Prod = :idProd');
            $queryGetProducts->bindParam(':idProd', $Id_Prod, PDO::PARAM_INT);
            $queryGetProducts->execute();
            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

            if (count($returnQueryGetProducts) == 0) {
                echo "<p class='text-center text-muted fs-5'>$htmlAucunProduitEnStock</p>";
            } else {
                foreach ($returnQueryGetProducts as $product) {
                    $Id_Produit = $product["Id_Produit"];
                    $nomProduit = $product["Nom_Produit"];
                    $typeProduit = $product["Desc_Type_Produit"];
                    $prixProduit = $product["Prix_Produit_Unitaire"];
                    $QteProduit = $product["Qte_Produit"];
                    $unitePrixProduit = $product["Nom_Unite_Prix"];
                    $Nom_Unite_Stock = $product["Nom_Unite_Stock"];

                    if ($QteProduit > 0) {
                        $imagePath = 'asset/img/img_produit/' . $Id_Produit . '.png';
                        $defaultImage = 'asset/img/default_produit.png';
                        $imageSrc = file_exists($imagePath) ? $imagePath : $defaultImage;
                        echo '
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100">
                                <img src="' . $imageSrc . '" class="card-img-top img-fluid" alt="' . $htmlImageNonFournie . '" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">' . $nomProduit . '</h5>
                                    <p class="card-text"><strong>' . $htmlTypeDeuxPoints . '</strong> ' . $typeProduit . '</p>
                                    <p class="card-text"><strong>' . $htmlPrix . '</strong> ' . $prixProduit . ' €/ ' . $unitePrixProduit . '</p>
                                    <p class="card-text"><strong>' . $htmlStockDeuxPoints . '</strong> ' . $QteProduit . ' ' . $Nom_Unite_Stock . '</p>
                                    <div class="d-flex justify-content-between">
                        ';

                        if ($Id_Produit == $Id_Produit_Update) {
                            echo '<button class="btn btn-secondary" disabled>' . $htmlModification . '</button>';
                        } else {
                            echo '
                            <form action="product_modification.php" method="post">
                                <input type="hidden" name="modifyIdProduct" value="' . $Id_Produit . '">
                                <button type="submit" class="btn btn-primary" style ="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #EBF4EC; background-color: #305514;">
                                    <i class="bi bi-pencil-square"></i> ' . $htmlModifier . '
                                </button>
                            </form>';
                        }

                        echo '
                            <form action="modele/delete_product.php" method="post">
                                <input type="hidden" name="deleteIdProduct" value="' . $Id_Produit . '">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> ' . $htmlSupprimer . '
                                </button>
                            </form>
                        ';

                        echo '
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                }
            }
        ?>
    </div>
</div>

                    
                    <div class="basDePage" style="margin-top: 10px;">
                        
        
            
        <div class="btn-group" role="group" aria-label="Basic outlined example" >
                    <form method="post">
                    <button type=submit class="btn btn-outline-primary" style ="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514"
                    onmouseover="this.style.backgroundColor='#305514'; this.style.color='#FFFFFF';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='#305514';"
                    > 
                    Signaler un dysfonctionnement
                            <input type="hidden" name="popup" value="contact_admin">
                    </button>
                    </form>
                    <button class="btn btn-outline-primary bouton" type="button" style ="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514" 
                    onmouseover="this.style.backgroundColor='#305514'; this.style.color='#FFFFFF';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='#305514';"
                    data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Conditions générales d'utilisation
                    </button>
                
                
            </div>
            <div class="collapse w-100 mt-2" id="collapseExample">
                    <div class="card card-body">
                        Introduction: 1.1. Les présentes Conditions Générales d'Utilisation (ci-après "les CGU") régissent l'utilisation de la Plateforme de Vente de Produits Locaux (ci-après "la Plateforme"), accessible à l'adresse etalenligne.fr . La Plateforme est exploitée par L'étal en ligne (ci-après "l'Exploitant"). En utilisant la Plateforme, vous acceptez de vous conformer à ces CGU. Définitions : 2.1. Dans les présentes CGU, les termes suivants ont les significations suivantes : -"Utilisateur" : Toute personne accédant ou utilisant la Plateforme, qu'il s'agisse d'un producteur, d'un client ou de l'administrateur de la Plateforme. -"Producteur" : Un utilisateur qui propose des produits à la vente sur la Plateforme. -"Client" : Un utilisateur qui achète des produits sur la Plateforme. -"Administrateur" : L'équipe en charge de la gestion et de l'administration de la Plateforme. Utilisation de la Plateforme : 3.1. Acceptation des CGU : Vous devez accepter ces CGU pour utiliser la Plateforme. Si vous n'acceptez pas ces CGU, veuillez ne pas utiliser la Plateforme. 3.2. Compte Utilisateur : 3.2.1. Pour devenir membre de la Plateforme, vous devez créer un compte en fournissant des informations personnelles, y compris votre nom, votre adresse email et d'autres informations requises. 3.2.2. Vous acceptez de fournir des informations exactes, complètes et à jour lors de la création de votre compte et de les maintenir à jour. 3.2.3. Vous êtes responsable de toutes les activités qui se déroulent sous votre compte, y compris la confidentialité de vos identifiants de connexion. 3.3. Conditions d'Âge : 3.3.1. Vous devez avoir au moins 18 ans pour utiliser la Plateforme. Si vous avez moins de 18 ans, vous devez obtenir le consentement de vos parents ou tuteurs légaux pour utiliser la Plateforme. Transactions et Paiements : 4.1. Transactions Directes : 4.1.1. La Plateforme ne traite pas les paiements en ligne. Les transactions de paiement doivent être effectuées directement entre les utilisateurs (producteurs et clients) en dehors de la Plateforme. 4.1.2. Les prix des produits et les modalités de paiement sont convenus entre les producteurs et les clients. La Plateforme ne joue aucun rôle dans la fixation des prix ou des modalités de paiement. 4.2. Taxes et Légalité : 4.2.1. Les producteurs sont responsables de se conformer aux lois et réglementations fiscales locales applicables concernant la collecte et le paiement des taxes sur les ventes. 4.2.2. Les transactions sur la Plateforme doivent respecter toutes les lois locales applicables. Responsabilités des Utilisateurs : 5.1. Responsabilités des Producteurs : 5.1.1. Les producteurs sont responsables de l'exactitude des informations concernant leurs produits, de la qualité des produits vendus, de la gestion des stocks et de la communication avec les clients. 5.1.2. Les producteurs garantissent que les produits proposés sont conformes aux normes de sécurité alimentaire et à toutes les réglementations applicables. 5.2. Responsabilités des Clients : 5.2.1. Les clients sont responsables de la vérification des informations fournies par les producteurs, de la passation de commandes et du paiement des produits conformément aux modalités convenues. Signalement de Problèmes : 6.1. Signalement des Problèmes : 6.1.1. Les utilisateurs peuvent signaler tout comportement abusif, faux produits, ou tout autre problème à l'administrateur de la Plateforme via la fonction de signalement. 6.1.2. L'administrateur s'engage à examiner les signalements et à prendre des mesures appropriées pour résoudre les problèmes signalés. Modification et Résiliation du Compte: 7.1. Modification et Résiliation : 7.1.1. Vous pouvez modifier ou supprimer votre compte à tout moment en accédant aux paramètres de votre compte. 7.1.2. L'administrateur se réserve le droit de suspendre ou de résilier votre compte en cas de violation des présentes CGU ou de comportement inapproprié. Propriété Intellectuelle : 8.1. Droits de Propriété Intellectuelle : 8.1.1. Tous les contenus publiés sur la Plateforme, y compris les textes, les images et les logos, sont protégés par les lois sur les droits d'auteur et la propriété intellectuelle. Vous acceptez de ne pas utiliser ou reproduire ces contenus sans autorisation. Limitation de Responsabilité : 9.1. Exclusion de Garanties : 9.1.1. La Plateforme est fournie "telle quelle" et l'administrateur ne garantit pas son bon fonctionnement. L'administrateur n'est pas responsable des dommages directs ou indirects résultant de l'utilisation de la Plateforme. Modification des CGU : 10.1. Modification des CGU : 10.1.1. Les présentes CGU peuvent être modifiées à tout moment par l'administrateur. Les utilisateurs seront informés des modifications par le biais de la Plateforme. Loi Applicable et Juridiction : 11.1. Loi Applicable et Juridiction : 11.1.1. Les présentes CGU sont régies par la LIL et tout litige sera soumis à la juridiction exclusive des tribunaux de la Mayenne. Ces CGU visent à établir des règles claires et juridiquement contraignantes pour l'utilisation de votre Plateforme de Vente de Produits Locaux. Pour garantir la conformité aux lois locales et aux réglementations, il est recommandé de consulter un avocat ou un juriste pour adapter ces CGU à votre situation particulière.
                    </div>
                </div>
        </div>
    </div>
</div>
    <?php require "popups/gestion_popups.php";?>
</body>
