<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>
    <?php
        if(!isset($_SESSION)){
            session_start();
        }
    ?>
    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="asset/img/logo.png" alt="Logo">
            <div class="contenuBarre">
                <!-- some code -->
            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#index.php">Accueil</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <?php if (isset($_SESSION["Id_Uti"])): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active"  href="messagerie.php">Messagerie</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="achats.php">Achats</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isProd"]) && ($_SESSION["isProd"]==true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link"  href="produits.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  href="delivery.php">Commandes</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isAdmin"]) && ($_SESSION["isAdmin"]==true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active"  href="panel_admin.php">Panel Admin</a>
                                    </li>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                    </nav>
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
					<input type="submit" value=<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo '"Se Connecter"';}else {echo '"'.$_SESSION['Mail_Uti'].'"';}?> class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
				</form>
            </div>
            <div class="contenuPage">

               <!-- some code -->

            </div>
            <div class="basDePage">
                <form method="post">
						<input type="submit" value="Signaler un dysfonctionnement" class="lienPopup">
                        <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
						<input type="submit" value="CGU" class="lienPopup">
                        <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>