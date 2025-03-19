<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
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
        <img class="logo" href="index.php" src="asset/img/logo.png" alt="Logo">
        <div class="contenuBarre">
            <p>Chase imaginary bugs...</p>
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
                                        <a class="nav-link active" href="messagerie.php">Messagerie</a>
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
                                        <a class="nav-link" href="delivery.php">Commandes</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION["isAdmin"]) && ($_SESSION["isAdmin"] == true)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="panel_admin.php">Panel Admin</a>
                                    </li>
                                <?php endif; ?>
                                <li class= "nav-item dropdown">
                                    <a class ="nav-link dropdown-toggle" herf="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Langage</a>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="language/language.php?language=fr">Français</a></li>
                                        <form action="language/language.php" method="post" id="languageForm">
                                            <select name="language" id="languagePicker" onchange="submitForm()">
                                                <option value="fr" <?php if ($_SESSION["language"] == "fr") echo 'selected'; ?>>Français</option>
                                                <option value="en" <?php if ($_SESSION["language"] == "en") echo 'selected'; ?>>English</option>
                                                <option value="es" <?php if ($_SESSION["language"] == "es") echo 'selected'; ?>>Español</option>
                                                <option value="al" <?php if ($_SESSION["language"] == "al") echo 'selected'; ?>>Deutsch</option>
                                                <option value="ru" <?php if ($_SESSION["language"] == "ru") echo 'selected'; ?>>русский</option>
                                                <option value="ch" <?php if ($_SESSION["language"] == "ch") echo 'selected'; ?>>中國人</option>
                                            </select>
                                        </form>
                                    </ul>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>