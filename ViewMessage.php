<!DOCTYPE html>
<!-- page non temporaire ne doit pas etre accessible -->
<html>
<head>

<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/messagerie.css">

</head>
<body>
    <div class="container">
        <div class="left-column">
			<img class="logo" src="asset/img/logo.png">
			
            <p>Contacts récents :</p>
			<?php
			include 'controller/controllerMessage.php';
			?>
        </div>
        <div class="right-column">
            <div class="fixed-banner">
                <!-- Partie gauche du bandeau -->
                <div class="banner-left">
                    <div class="button-container">
                        <button class="button"><a href="index.php">accueil</a></button>
                        <button class="button"><a href="ViewMessage.php">messagerie</a></button>
                        <button class="button"><a href="commandes.php">commandes</a></button>
                    </div>
                </div>
                <!-- Partie droite du bandeau -->
                <div class="banner-right">
					<a class="fixed-size-button" href="form_sign_in.php" >
					<?php 
					$_SESSION['Id_Uti']=2;
					if (!isset($_SESSION)) {
						if(!isset($_SESSION)){
							session_start();
							}
					echo "connection";
					}
					else {
					echo $_SESSION['Mail_Uti']; 
					}
					?>
					</a>
                </div>
            </div>
			<div class="surContenu">
				<div class="interlocuteur" <?php if (!isset($_GET['Id_Interlocuteur'])) { echo 'disabled';} ?>>
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
		</div>
    </div>
</body>
</html>
