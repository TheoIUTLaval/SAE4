<!DOCTYPE html>
<html lang="fr">
<?php
    require "language/language.php";
    
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

<body>
    <?php

        session_start();
    ?>
    <div class="custom-container">
        <div class="leftColumn">
            <img class="logo" src="asset/img/logo.png">
            <h5><?php echo $htmlContactsRecentsDeuxPoints?></h5>
            <?php
            require 'controller/controllerMessage.php';
            ?>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <nav class="navbar navbar-expand-lg bg-body-tertiary">
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
                                    
                                    <li class="nav-item">
                                        <a class="nav-link" href="ViewBroadcastuser.php">Broadcast User</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="ViewBroadcastprod.php">Broadcast Prod</a>
                                    </li>
                                    <?php endif; ?>
                                    <li class="nav-item">
                                        <form action="language/language.php" method="post" id="languageForm" class="d-flex align-items-center dropdown">
                                            <select name="language" id="languagePicker" onchange="submitForm()" class="form-select" aria-label="Default select example">
                                                <option selected disabled>Language</option>
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
                <div class="interlocuteur">
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
                <hr>
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
                    <div class="input-group nb-3">
                        <form method="post" id="zoneDEnvoi" class="d-flex">
                            <input type="text" name="content" id="zoneDeTexte" class="form-control" placeholder="Ecrire votre message pour votre à l'utilisateur" aria-label="Recipient's username" aria-describedby="button-addon2" <?php if ($formDisabled) { echo 'disabled';} ?>>
                            <button class="btn btn-outline-secondary" type="submit" id="boutonEnvoyerMessage" <?php if ($formDisabled) { echo 'disabled';} ?>
                            style="border: 1px solid #305514; border-radius: 5px; padding: 5px; color: #305514"
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
        </div>
    </div>
    
    <div class="collapse w-100 mt-2" id="collapseExample">
        <div class="card card-body">
            ...
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>