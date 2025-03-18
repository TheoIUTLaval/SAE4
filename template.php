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
    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="asset/img/logo.png" alt="Logo">
            <div class="contenuBarre">
                <p>Chase imaginary bugs. Gimme attention gimme attention gimme attention gimme attention gimme attention gimme attention just kidding i don't want it anymore meow bye bird bird bird bird bird bird human why take bird out i could have eaten that yet woops poop hanging from butt must get rid run run around house drag poop on floor maybe it comes off woops left brown marks on floor human slave clean lick butt now, car rides are evil so break lamps and curl up into a ball. Tweeting a baseball shove bum in owner's face like camera lens sleeping in the box decide to want nothing to do with my owner today try to hold own back foot to clean it but foot reflexively kicks you in face, go into a rage and bite own foot, hard so hide head under blanket so no one can see. Thinking about you i'm joking it's food always food. Pee on walls it smells like breakfast meow meow you are my owner so here is a dead bird for pushes butt to face. Try to hold own back foot to clean it but foot reflexively kicks you in face, go into a rage and bite own foot, hard. Drink water out of the faucet shake treat bag, yet eat plants, meow, and throw up because i ate plants meow meow. Who's the baby this human feeds me, i should be a god yet touch my tail, i shred your hand purrrr or hide head under blanket so no one can see or attempt to leap between furniture but woefully miscalibrate and bellyflop onto the floor; what's your problem? i meant to do that now i shall wash myself intently. Toilet paper attack claws fluff everywhere meow miao french ciao litterbox. Need to check on human, have not seen in an hour might be dead oh look, human is alive, hiss at human, feed me curl up and sleep on the freshly laundered towels. Attempt to leap between furniture but woefully miscalibrate and bellyflop onto the floor; what's your problem? i meant to do that now i shall wash myself intently jump up to edge of bath, fall in then scramble in a mad panic to get out and stares at human while pushing stuff off a table, or chew on cable. Sun bathe go crazy with excitement when plates are clanked together signalling the arrival of cat food claws in your leg cat sit like bread. Purr purr purr until owner pets why owner not pet me hiss scratch meow. Please let me outside pouty face yay! wait, it's cold out please let me inside pouty face oh, thank you rub against mommy's leg oh it looks so nice out, please let me outside again the neighbor cat was mean to me please let me back inside bite the neighbor's bratty kid, for you have cat to be kitten me right meow drink water out of the faucet or attack feet chill on the couch table. Attack the child cats secretly make all the worlds muffins. Touch my tail, i shred your hand purrrr my cat stared at me he was sipping his tea, too yet kitty kitty pussy cat doll. Run in circles cats go for world domination. Bleghbleghvomit my furball really tie the room together cough hairball, eat toilet paper or loved it, hated it, loved it, hated it the fat cat sat on the mat bat away with paws. Floof tum, tickle bum, jellybean footies curly toes. Twitch tail in permanent irritation you are a captive audience while sitting on the toilet, pet me open the door, let me out, let me out, let me-out, let me-aow, let meaow, meaow! for snuggles up to shoulders or knees and purrs you to sleep for bawl under human beds. Why can't i catch that stupid red dot get scared by sudden appearance of cucumber ha ha, you're funny i'll kill you last, but when in doubt, wash yet meow leave hair on owner's clothes claws in your leg.</p>
            </div>
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
                        <form action="language/language.php" method="post" id="languageForm">
                    <select name="language" id="languagePicker" onchange="submitForm()">
                        <option value="fr" <?php if($_SESSION["language"]=="fr") echo 'selected';?>>Français</option>
                        <option value="en" <?php if($_SESSION["language"]=="en") echo 'selected';?>>English</option>
                        <option value="es" <?php if($_SESSION["language"]=="es") echo 'selected';?>>Español</option>
                        <option value="al" <?php if($_SESSION["language"]=="al") echo 'selected';?>>Deutsch</option>
                        <option value="ru" <?php if($_SESSION["language"]=="ru") echo 'selected';?>>русский</option>
                        <option value="ch" <?php if($_SESSION["language"]=="ch") echo 'selected';?>>中國人</option>
                    </select>
                    </form>
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

					<input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo($htmlSeConnecter);} else {echo ''.$_SESSION['Mail_Uti'].'';}?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                
                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>