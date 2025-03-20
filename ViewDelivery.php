<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language/language.php" ;
    include 'modele/modeleDelivery.php';
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" type="text/css" href="css/style_général.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>
<body>

    <?php
        if(!isset($_SESSION)){
            session_start();
        }
    ?>


    <div class="custom-container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="asset/img/logo.png">
            <div class="contenuBarre">
                
            

            <center>
                <p><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></p>
                <br>
            </center>
            <?php echo $htmlStatut; ?> 
            <br>
            
            <form action="ViewDelivery.php" method="post">
                <label>
                    <input type="radio" name="typeCategorie" value="0" <?php if($filtreCategorie==0) echo 'checked="true"';?>> <?php echo $htmlTOUT; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="1" <?php if($filtreCategorie==1) echo 'checked="true"';?>> <?php echo $htmlENCOURS; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="2"<?php if($filtreCategorie==2) echo 'checked="true"';?>> <?php echo $htmlPRETE; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="4" <?php if($filtreCategorie==4) echo 'checked="true"';?>> <?php echo $htmlLIVREE; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="3" <?php if($filtreCategorie==3) echo 'checked="true"';?>> <?php echo $htmlANNULEE; ?>
                </label>

                <br>
                <br>
                <center>
                    <input type="submit" value="<?php echo $htmlFiltrer; ?>">
                </center>
            </form>


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

        
            <div class="contenuPage">



            
            <?php
               
               
                if(count($returnQueryGetCommande)==0){
                    echo $htmlAucuneCommande;
                }
                else{
                    while ($iterateurCommande<count($returnQueryGetCommande)){
						$Id_Commande = $returnQueryGetCommande[$iterateurCommande]["Id_Commande"];
						$Desc_Statut = $returnQueryGetCommande[$iterateurCommande]["Desc_Statut"];
						$Desc_Statut = mb_strtoupper($Desc_Statut);
                        $Nom_Client = $returnQueryGetCommande[$iterateurCommande]["Nom_Uti"];
						$Nom_Client = mb_strtoupper($Nom_Client);
                        $Prenom_Client = $returnQueryGetCommande[$iterateurCommande]["Prenom_Uti"];
                        $Id_Statut = $returnQueryGetCommande[$iterateurCommande]["Id_Statut"];
                        $Id_Uti = $returnQueryGetCommande[$iterateurCommande]["Id_Uti"];
                        //echo $Id_Statut;
                        
						$total=0;
                        $returnQueryGetProduitCommande = getProduitsCommande($bdd,$Id_Commande);
						$iterateurProduit=0;
						$nbProduit=count($returnQueryGetProduitCommande);

						if (($nbProduit>0)){
							echo '<div class="commande" >';
							echo $htmlClient, $Prenom_Client." ".$Nom_Client;
							echo '</br>';
							echo $htmlCOMMANDE, $Desc_Statut." <br>";
                            if (($Id_Statut!=4) and ($Id_Statut!=3)){
                        ?>
                            <form action="change_status_commande.php" method="post">
                                <select name="categorie">
                                    <option value=""><?php echo $htmlModifierStatut; ?></option>
                                    <option value="1"><?php echo $htmlENCOURS; ?></option>
                                    <option value="2"><?php echo $htmlPRETE; ?></option>
                                    <option value="3"><?php echo $htmlANNULEE; ?></option>
                                    <option value="4"><?php echo $htmlLIVREE; ?></option>
                                </select>
                                <input type="hidden" name="idCommande" value="<?php echo $Id_Commande?>">
                                <button type="submit"><?php echo $htmlConfirmer; ?></button>
                            </form>
                        <?php
						    }
                        }
						while ($iterateurProduit<$nbProduit){
							$Nom_Produit=$returnQueryGetProduitCommande[$iterateurProduit]["Nom_Produit"];
							$Qte_Produit_Commande=$returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
							$Nom_Unite_Prix=$returnQueryGetProduitCommande[$iterateurProduit]["Nom_Unite_Prix"];
							$Prix_Produit_Unitaire=$returnQueryGetProduitCommande[$iterateurProduit]["Prix_Produit_Unitaire"];
							echo "- " . $Nom_Produit ." - ".$Qte_Produit_Commande.' '.$Nom_Unite_Prix.' * '.$Prix_Produit_Unitaire.'€ = '.intval($Prix_Produit_Unitaire)*intval($Qte_Produit_Commande).'€';
							echo "</br>";
							$total=$total+intval($Prix_Produit_Unitaire)*intval($Qte_Produit_Commande);
							$iterateurProduit++;
						}

						if ($nbProduit>0){
                            echo '<input type="button" onclick="window.location.href=\'ViewMessagerie.php?Id_Interlocuteur='.$Id_Uti.'\'" value="'.$htmlEnvoyerMessage.'"><br>';
                            ?>
                            <form action="download_pdf.php" method="post">
                                <input type="hidden" name="idCommande" value="<?php echo $Id_Commande?>">
                                <button type="submit"><?php echo $htmlGenererPDF; ?></button>
                            </form>
                            <?php
                            echo '<div class="aDroite"'.$htmlTotalDeuxPoints, $total.'€</div>';
							echo '</div><br> '; 
						}
                        $iterateurCommande++;
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
