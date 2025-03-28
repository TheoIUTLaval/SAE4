<?php
    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASSWORD'];
    $basededonnees = $_ENV['DB_NAME'];
    // Connect to database
         
    
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    //var_dump($_POST);
    $Id_Produit = htmlspecialchars($_POST["IdProductAModifier"]);
    $Nom_Produit = htmlspecialchars($_POST["nomProduit"]);
    $Categorie = htmlspecialchars($_POST["categorie"]);
    $Prix = htmlspecialchars($_POST["prix"]);
    $Prix_Unite = htmlspecialchars($_POST["unitPrix"]);
    $Quantite = htmlspecialchars($_POST["quantite"]);
    $Quantite_Unite = htmlspecialchars($_POST["unitQuantite"]);


    $updateProduit = "UPDATE PRODUIT SET Nom_Produit = :Nom_Produit, Id_Type_Produit = :Categorie, Qte_Produit = :Quantite, Id_Unite_Stock = :Quantite_Unite, Prix_Produit_Unitaire = :Prix, Id_unite_Prix = :Prix_Unite WHERE Id_Produit = :Id_Produit";
    $stmt = $bdd->prepare($updateProduit);
    $stmt->bindParam(':Nom_Produit', $Nom_Produit);
    $stmt->bindParam(':Categorie', $Categorie);
    $stmt->bindParam(':Quantite', $Quantite);
    $stmt->bindParam(':Quantite_Unite', $Quantite_Unite);
    $stmt->bindParam(':Prix', $Prix);
    $stmt->bindParam(':Prix_Unite', $Prix_Unite);
    $stmt->bindParam(':Id_Produit', $Id_Produit);
    $stmt->execute();

    //modification de l'image
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES["image"])) {
        // Spécifier le chemin du dossier de destination
        $targetDir = __DIR__ . "/../asset/img/img_produit/";
        // Obtenir le nom du fichier téléchargé
        if(!isset($_SESSION)){
            session_start();
            }


        // Obtenir l'extension du fichie
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        // Utiliser l'extension dans le nouveau nom du fichier
        $newFileName = $_SESSION["Id_Produit"] . '.' . $extension;

        // Créer le chemin complet du fichier de destination
        $targetPath = $targetDir . $newFileName;
        
        unlink( $targetPath ); 
        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "<br>' $htmlImgTelecSucces  $newFileName<br>";
        } else {
            echo $htmlImgTelecRate . error_get_last()['message'] . "<br>";
            header('Location: produits.php?erreur='. error_get_last()['message'] );
        }
    } else {
        echo $htmlSelecImg."<br>";
    }
    header('Location: ../produits.php');    
}
    header('Location: ../produits.php');
?>
