<?php
    require "language/language.php" ;
    ob_start();
?> 
<head>
    <link rel="stylesheet" type="text/css" href="css/template.css">
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
        <p><?php echo $htmlContactsRecentsDeuxPoints?></p>
			<?php
			require 'controller/controllerMessage.php';
			?>
        </div>
    <?php $contentLeft=ob_get_clean(); 
    include 'template.php'; 
    ?>
    <?php
    ob_start();
    ?>
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
            
    <?php require "popups/gestion_popups.php";
    $content=ob_get_clean();
    include 'template.php'; ?>
</body>
