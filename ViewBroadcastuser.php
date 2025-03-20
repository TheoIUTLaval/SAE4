<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language/language.php" ; 
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/template.css">
    <link rel="stylesheet" type="text/css" href="css/style_général.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
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
        </div>
        <div class="rightColumn">
            <div class="contenuPage d-flex justify-content-center style= margin-top : 10%>

            <form action="traitements/traitement_broadcast_user.php" method="post">
                <div class="mb-3">
                    <label for="message" class="form-label"><?php echo $htmlVotreMessage; ?></label>
                    <textarea id="message" name="message" rows="5" maxlength="5000" class="form-control" required></textarea>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary"><?php echo $htmlEnvoyerMessageATousUti; ?></button>
                </div>
            </form>
            

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