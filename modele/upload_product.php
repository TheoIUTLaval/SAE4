<?php
    require "../language/language.php" ; 
?>
<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES["image"])) {
        // Spécifier le chemin du dossier de destination
        $targetDir = __DIR__ . "asset/img/img_producteur/";
        // Obtenir le nom du fichier téléchargé
        $utilisateur = "etu";
        $serveur = "localhost";
        $motdepasse = "Achanger!";
        $basededonnees = "sae";
        session_start();


        // Obtenir l'extension du fichie
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        // Utiliser l'extension dans le nouveau nom du fichier
        $newFileName = htmlspecialchars($_SESSION["Id_Produit"]) . '.' . $extension;

        // Créer le chemin complet du fichier de destination
        $targetPath = $targetDir . $newFileName;
        
        unlink( $targetPath ); 
        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "<br><?php echo $htmlImgTelecSucces?> $newFileName<br>";
        } else {
            echo "$htmlImgTelecRate " . error_get_last()['message'] . "<br>";
            header('Location: /SAE4/mes_produits.php?erreur='. error_get_last()['message'] );
        }

    } else {
        echo $htmlSelecImg."<br>";
    }
    
    header('Location: /SAE4/produits.php');    
}
?>
