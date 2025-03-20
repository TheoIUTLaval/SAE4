<?php
    require "language/language.php" ; 
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php
        if (isset($_POST['formClicked'])){
            unset($_POST['formClicked']);
            require 'traitements/update_user_info.php';
            $_SESSION['actualiser'] = true;
        }
        if(isset($_POST['deconnexion'])){
            unset($_POST['deconnexion']);
            require 'traitements/log_out.php';
            $_SESSION['actualiser'] = true;
        }
        ?>
    <div class="popup">
    <div class="contenuPopup">
        <div style="display:flex;justify-content:space-between;">
            <form method="post" action="">
                <input class="lienPopup" type="submit" value="<?php echo $htmlSeDeconnecter?>" name="deconnexion">
                <input type="hidden" value='info_perso' name="popup">
            </form>
            <form method="post">
                <input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
            </form>
        </div>
        <p class="titrePopup"><?php echo $htmlInformationsPersonelles?></p>
        <div>
        <?php
        require 'traitements/chargement_info_perso.php';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <form class="formPopup" action='traitements/update_user_info.php' method="post">
                <form class="formPopup" method="post" action="">
                    <!--  Set default values to current user information -->
                    <div>
                        <label for="new_nom"><?php echo $htmlNomDeuxPoints?></label>
                        <input class="zoneDeTextePopup zoneDeTextePopupFixSize" type="text" name="new_nom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value="<?php echo isset($row["Nom_Uti"]) ? htmlspecialchars($row["Nom_Uti"]) : '' ?>">
                    </div>
                    <div>
                        <label for="new_prenom"><?php echo $htmlPrénomDeuxPoints?></label>
                        <input class="zoneDeTextePopup zoneDeTextePopupFixSize" type="text" name="new_prenom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value="<?php echo isset($row["Prenom_Uti"]) ? htmlspecialchars($row["Prenom_Uti"]) : '' ?>">
                    </div>
                    <div>
                        <label><?php echo $htmlAdrPostDeuxPoints?></label>
                        <label><?php echo htmlspecialchars($row["Adr_Uti"])?></label>
                    </div>
                    <div>
                        <label for="rue"><?php echo $htmlRueDeuxPoints?></label>
                        <input class="zoneDeTextePopup" type="text" name="rue" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" title="<?php echo $htmlConditionsRue; ?>" value="<?php echo isset($row["Rue_Uti"]) ? htmlspecialchars($row["Rue_Uti"]) : '' ?>" required>
                    </div>
                    <div>
                            <label for="code"><?php echo $htmlCodePostDeuxPoints?></label>
                            <input class="zoneDeTextePopup" type="text" name="code" pattern="^\d{5}$" title="<?php echo $htmlConditionsCodePostal; ?>" value="<?php echo isset($row["CodePostal_Uti"]) ? htmlspecialchars($row["CodePostal_Uti"]) : '' ?>" required>
                    </div>
                    <div>
                        <label for="ville"><?php echo $htmlVilleDeuxPoints?></label>
                        <input class="zoneDeTextePopup" type="text" name="ville" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" title="<?php echo $htmlConditionsVille; ?>" value="<?php echo isset($row["Ville_Uti"]) ? htmlspecialchars($row["Ville_Uti"]) : '' ?>" required>
                    </div>
                    <div>
                        <label for="ville"> mot de passe actuel </label>
                        <input class="zoneDeTextePopup" type="password" name="pwd" required>
                    </div>
                    <div>
                        <?php
                        if (isset($_SESSION['erreur'])) {
                            $erreur = $_SESSION['erreur'];
                            echo '<p class="erreur">'.$erreur.'</p>';
                        }
                        ?>
                    </div>
                    <input class="boutonPopup" type="submit" name="formClicked" value="<?php echo $htmlModifier?>">
                </form>
                <button class="btn btn-danger mt-3" onclick="confirmDeleteAccount()">
                    <?php echo $htmlSupprimerCompte; ?>
                </button>

                <form id="deleteAccountForm" action="traitements/del_acc.php" method="post" style="display:none;">
                    <input type="hidden" name="deleteAccount" value="true">
                </form>

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
                
                <?php if((isset($_SESSION['isProd']) and $_SESSION['isProd'])){?> 
                <a href="./ViewAddProfilPicture.php"><button><?php echo 'ajouter une photo de profil'?></button></a>
                <?php } ?>
                <?php
            }
        } else {
            ?><p><?php echo $htmlAucunResultatCompte?></p><?php
        }
        ?>
        </div>
    </div>
</div>
