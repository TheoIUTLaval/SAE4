<!DOCTYPE html>
<html lang="fr">
<?php
    require "language/language.php" ; 

?> 
<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/template.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link rel="stylesheet" type="text/css" href="css/messagerie.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- name of the page -->
</head>

    <?php
        var_dump(__DIR__);
        if(!isset($_SESSION)){
            session_start();
        }
    ?>
    <div class="custom-container">
    <div class="leftColumn">
			<img class="logo" src="asset/img/logo.png">
            <p><?php echo $htmlContactsRecentsDeuxPoints?></p>
			<?php
			require 'controller/controllerMessage.php';
			?>
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
				<div class="interlocuteur" >
				<?php if (!isset($_GET['Id_Interlocuteur'])) ?>
				
				<?php 
				if (isset($_GET['Id_Interlocuteur'])){
					$bdd = dbConnect();
					$query = $bdd->query('SELECT Nom_Uti, Prenom_Uti FROM UTILISATEUR WHERE Id_Uti='.$_GET['Id_Interlocuteur']);
					$interlocuteur=$query->fetchAll(PDO::FETCH_ASSOC);
				
					$query = $bdd->query('CALL isProducteur('.$_GET['Id_Interlocuteur'].');');
				
					echo ($interlocuteur[0]['Nom_Uti'].' '.$interlocuteur[0]['Prenom_Uti'].($query->fetchAll(PDO::FETCH_ASSOC))[0]['result']);
				
				}
				?>
				
				</div>
				<div class="contenuMessagerie">
            	
                <?php
					if (isset($_SESSION['Id_Uti'])){
						if (isset($_GET['Id_Interlocuteur'])){
							afficheMessages($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur']);
							$formDisabled=false;
						}else {
							echo($htmlSelectConversation);
							$formDisabled=true;
						}
					}else{
						echo($htmlPasAccesPageContactAdmin);
						$formDisabled=true;
					}
					?>
                    <div class="inuput-group nb-3 " >
                        <form method="post" id="zoneDEnvoi" class="d-flex">
                            <input type="text"  name="content" id="zoneDeTexte" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2" <?php if ($formDisabled) { echo 'disabled';} ?>>
                            <button  class ="btn btn-outline-secondary" type="submit" id ="boutonEnvoyerMessage" <?php if ($formDisabled) { echo 'disabled';} ?>
                            style ="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514"
                            onmouseover="this.style.backgroundColor='#305514'; this.style.color='#FFFFFF';"
                            onmouseout="this.style.backgroundColor=''; this.style.color='#305514';">
                            Envoyer</button> 
                        </form>
                    </div>
					<?php
					if (isset($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], $_POST['content'])){
						if ($_POST['content']!=""){
							envoyerMessage($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], htmlspecialchars($_POST['content']));
						}
						unset($_POST['content']);
						header("Refresh:0");
					}
					?>
				</div>
            </div>






            <div class="basDePage">
        
            
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


    <?php require "popups/gestion_popups.php" ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</body>