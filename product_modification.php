<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language/language.php" ; 
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>
<body>
    <?php
        if(!isset($_SESSION)){
            session_start();
        }
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
            $bdd=dbConnect();
          $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
          $Id_Produit_Update=htmlspecialchars($_POST["modifyIdProduct"]);
          $_SESSION["Id_Produit"]=$Id_Produit_Update;
          
          $bdd=dbConnect();
          $queryGetProducts = $bdd->prepare('SELECT * FROM PRODUIT WHERE Id_Produit = :Id_Produit_Update');
          $queryGetProducts->bindParam(':Id_Produit_Update', $Id_Produit_Update, PDO::PARAM_INT);
          $queryGetProducts->execute();
          $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);
          //var_dump($returnQueryGetProducts);
          $IdProd = $returnQueryGetProducts[0]["Id_Prod"];
          $Nom_Produit = $returnQueryGetProducts[0]["Nom_Produit"];
          $Id_Type_Produit = $returnQueryGetProducts[0]["Id_Type_Produit"];
          $Qte_Produit = $returnQueryGetProducts[0]["Qte_Produit"];
          $Id_Unite_Stock = $returnQueryGetProducts[0]["Id_Unite_Stock"];
          $Prix_Produit_Unitaire = $returnQueryGetProducts[0]["Prix_Produit_Unitaire"];
          $Id_Unite_Prix = $returnQueryGetProducts[0]["Id_Unite_Prix"];
          //var_dump($Id_Type_Produit);
        ?>
    <div class="custom-container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="asset/img/logo.png">
            <div class="contenuBarre">
                
            
            <center><p><strong><?php echo $htmlAjouterProduit?></strong></p>
            <form action="modele/modify_product.php" method="post" enctype="multipart/form-data">

                <label for="pwd"><?php echo $htmlProduitDeuxPoints?> </label>
                <input type="hidden" name="IdProductAModifier" value="<?php echo $Id_Produit_Update ?>">
                <input type="text" name="nomProduit" value="<?php echo $Nom_Produit?>" required><br><br>
                <select name="categorie">
                    <?php 
                        switch ($Id_Type_Produit) {
                            case 1:
                                echo "";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 2:
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 3:
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 4:
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 5:
                                echo "<option value=\"5\">Vin</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                break;
                            case 6:
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 7:
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                            break;
                        }
                    ?>

			    </select>
                <br>
                <br><?php echo $htmlPrix?>
                <input style="width: 50px;" value="<?php echo $Prix_Produit_Unitaire?>" type="number" min="0" name="prix" required>€
                <?php
                    switch ($Id_Unite_Prix) {
                        case 1:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"1\" checked=\"checked\"> ".$htmlLeKilo;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"4\"> ".$htmlLaPiece;
                            echo "</label>";
                        break;
                        case 4:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"1\"> ".$htmlLeKilo;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"4\" checked=\"checked\"> ".$htmlLaPiece;
                            echo "</label>";
                        break;
                    }
                ?>
                <br>
                <br>Stock : 
                <input type="number" value="<?php echo $Qte_Produit?>" style="width: 50px;" min="0" name="quantite" required>
                <?php
                    switch ($Id_Unite_Stock) {
                        case 1:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\" checked=\"checked\"> ".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\">".$htmlPiece;
                            echo "</label>";
                            break;
                        case 2:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\"> ".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\" checked=\"checked\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\">".$htmlPiece;
                            echo "</label>";
                            break;
                        case 3:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\"> ".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\" checked=\"checked\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\">".$htmlPiece;
                            echo "</label>";
                            break;
                        case 4:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\">".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\" checked=\"checked\">".$htmlPiece;
                            echo "</label>";
                            break;
                    }
                ?>
                <br>
                <br>
                <input type="file" name="image" accept=".png">
                <br>
                <br>
                <input type="submit" value="<?php echo $htmlConfirmerModifProd?>">
            </form>
            <br>
            <form action="produits.php" method="post">
                <input type="submit" value="<?php echo $htmlAnnulerModifProd?>">
            </form>
            <br>
            <?php
            //echo '<img class="img-produit" src="asset/img/img_produit/' . $Id_Produit_Update  . '.png" alt="Image non fournie" style="width: 100%; height: 85%;" ><br>';
            ?>
            <br>
            <br>
            </center>




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
                    <p><center><U><?php echo $htmlMesProduitsEnStock?></U></center></p>
                    <div class="gallery-container">
                        <?php
                            $bdd=dbConnect();
                            $queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti = :utilisateur');
                            $queryIdProd->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
                            $queryIdProd->execute();
                            $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
                            $Id_Prod=$returnQueryIdProd[0]["Id_Prod"];

                            $bdd=dbConnect();
                            $queryGetProducts = $bdd->prepare('SELECT Id_Produit, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit, Nom_Unite_Stock FROM Produits_d_un_producteur WHERE Id_Prod = :idProd');
                            $queryGetProducts->bindParam(':idProd', $Id_Prod, PDO::PARAM_INT);
                            $queryGetProducts->execute();                            
                            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                            $i=0;
                            if(count($returnQueryGetProducts)==0){
                                echo $htmlAucunProduitEnStock;
                            }
                            else{
                                while ($i<count($returnQueryGetProducts)){
                                    $Id_Produit = $returnQueryGetProducts[$i]["Id_Produit"];
                                    $nomProduit = $returnQueryGetProducts[$i]["Nom_Produit"];
                                    $typeProduit = $returnQueryGetProducts[$i]["Desc_Type_Produit"];
                                    $prixProduit = $returnQueryGetProducts[$i]["Prix_Produit_Unitaire"];
                                    $QteProduit = $returnQueryGetProducts[$i]["Qte_Produit"];
                                    $unitePrixProduit = $returnQueryGetProducts[$i]["Nom_Unite_Prix"];
                                    $Nom_Unite_Stock = $returnQueryGetProducts[$i]["Nom_Unite_Stock"];
                                    
                                    if ($QteProduit>0){
                                        echo '<style>';
                                        echo 'form { display: inline-block; margin-right: 1px; }'; // Ajustez la marge selon vos besoins
                                        echo 'button { display: inline-block; }';
                                        echo '</style>';

                                        echo '<div class="square1" >';
                                        echo $htmlProduitDeuxPoints, $nomProduit . "<br>";
                                        echo $htmlTypeDeuxPoints, $typeProduit . "<br><br>";
                                        echo '<img class="img-produit" src="asset/img/img_produit/' . $Id_Produit  . '.png" alt="'.$htmlImageNonFournie.'" style="width: 85%; height: 70%;" ><br>';
                                        echo $htmlPrix, $prixProduit .' €/'.$unitePrixProduit. "<br>";
                                        echo $htmlStockDeuxPoints, $QteProduit .' '.$Nom_Unite_Stock. "<br>";
                                        if ($Id_Produit==$Id_Produit_Update){
                                            echo '<input type="submit" disabled="disabled" value="'.$htmlModification.'"/></button>';
                                        }
                                        else{
                                            echo '<form action="product_modification.php" method="post">';
                                            echo '<input type="hidden" name="modifyIdProduct" value="'.$Id_Produit.'">';
                                            echo '<button type="submit" name="action">'.$htmlModifier.'</button>';
                                            echo '</form>';
                                        }
                                        echo '<form action="SAE4/modele/delete_product.php" method="post">';
                                        echo '<input type="hidden" name="deleteIdProduct" value="'.$Id_Produit.'">';
                                        echo '<button type="submit" name="action">'.$htmlSupprimer.'</button>';
                                        echo '</form>';
                                        echo '</div> '; 
                                    }
                                    $i++;
                                }
                            }
                        ?>
                    </div>





            <div class="basDePage">
                <form method="post">
                    <input type="submit" value="<?php echo $htmlSignalerDys?>" class="lienPopup">
                    <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
                    <input type="submit" value="<?php echo $htmlCGU?>" class="lienPopup">
                    <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
