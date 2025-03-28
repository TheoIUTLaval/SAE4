<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES["image"])) {
        // Spécifier le chemin du dossier de destination
    
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/SAE4/asset/img/img_producteur/";
        // Obtenir le nom du fichier téléchargé
        $utilisateur = "etu";
        $serveur = "localhost";
        $motdepasse = "Achanger!";
        $basededonnees = "sae";
        session_start();
        // Connect to database
        $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

        if (isset($_SESSION["Mail_Uti"])) {
            $mailUti = $_SESSION["Mail_Uti"];
        } else {
            $mailUti = $_SESSION["Mail_Temp"];
        }
        $requete = 'SELECT PRODUCTEUR.Id_Prod FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti WHERE UTILISATEUR.Mail_Uti = :mail';
        $queryIdProd = $bdd->prepare($requete);
        $queryIdProd->bindParam(':mail', $mailUti, PDO::PARAM_STR);
        $queryIdProd->execute();
        $returnqueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
        $Id_Prod=$returnqueryIdProd[0]["Id_Prod"];

        // Obtenir l'extension du fichie
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        // Utiliser l'extension dans le nouveau nom du fichier
        $newFileName = $Id_Prod . '.' . $extension;

        // Créer le chemin complet du fichier de destination
        $targetPath = $targetDir . $newFileName;
        
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "<script>
            window.location.href = '/SAE4/index.php';
            </script>";
        } else {
            echo "Le déplacement du fichier a échoué. Erreur : " . error_get_last()['message'] . "<br>";
        }
        

    } else {
        echo "Veuillez sélectionner une image.<br>";
    }
    
      

}

?>
