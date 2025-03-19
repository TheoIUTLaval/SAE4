<!DOCTYPE html>
<html lang="fr">
<?php
    require "language/language.php" ; 
?> 
<head>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link rel="stylesheet" type="text/css" href="css/messagerie.css"> 
    <!-- name of the page -->
</head>
<body>
    <?php
    var_dump(__DIR__);
    if(!isset($_SESSION)){
        session_start();
    }
    ?>
    <div class="container">
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
                <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])){
                            echo'<a class="bontonDeNavigation" href="ViewMessagerie.php">'.$htmlMessagerie.'</a>';
                            echo'<a class="bontonDeNavigation" href="ViewAchats.php">'.$htmlAchats.'</a>';
                        }
                        if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                            echo'<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                            echo'<a class="bontonDeNavigation" href="ViewDelivery.php">'.$htmlCommandes.'</a>';
                        }
                        if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"]==true)){
                            echo'<a class="bontonDeNavigation" href="ViewPanelAdmin.php">'.$htmlPanelAdmin.'</a>';
                        }
                    ?>
                </div>
                <form method="post">
                <?php
                    if(!isset($_SESSION)){
                    session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>
					<input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo($htmlSeConnecter);} else {echo ''.$_SESSION['Mail_Uti'].'';}?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                    <?php
                    if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"]==true)){
                    echo'<a class="bontonDeNavigation" href="ViewBroadcastuser.php">'.$htmlbroadcastuser.'</a>';
                    echo'<a class="bontonDeNavigation" href="ViewBroadcastprod.php">'.$htmlbroadcastprod.'</a>';
                    }
                    ?>
                </form>
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
					<form method="post" id="zoneDEnvoi">
						<input type="text" name="content" id="zoneDeTexte" <?php if ($formDisabled) { echo 'disabled';} ?>>
						<input type="submit" value="" id="boutonEnvoyerMessage" <?php if ($formDisabled) { echo 'disabled';} ?>>
					</form>
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
    <?php require "popups/gestion_popups.php" ?>
</body>
