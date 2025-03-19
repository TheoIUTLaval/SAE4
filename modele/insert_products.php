<?php
    require "../language/language.php" ; 
?>
<?php
     function dbConnect(){
        $utilisateur = "etu";
        $serveur = "localhost";
        $motdepasse = "Achanger!";
        $basededonnees = "sae";
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
        }
        if(!isset($_SESSION)){
            session_start();
            }
    $Id_Uti=htmlspecialchars($_SESSION["Id_Uti"]);
    $bdd=dbConnect();
    $queryNbProduits = $bdd->query(('SELECT MAX(Id_Produit) FROM PRODUIT;'));
    $returnqueryNbProduits = $queryNbProduits->fetchAll(PDO::FETCH_ASSOC);
    $nbProduits = $returnqueryNbProduits[0]["MAX(Id_Produit)"]+1;

    $queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti=:Id_Uti;');
    $queryIdProd->bindParam(":Id_Uti", $Id_Uti, PDO::PARAM_STR);
    $queryIdProd->execute();

    $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
    $IdProd = $returnQueryIdProd[0]["Id_Prod"];
    $Nom_Produit=$_POST["nomProduit"];
    $Type_De_Produit=$_POST["categorie"];
    $Prix=$_POST["prix"];
    $Unite_Prix=$_POST["unitPrix"];
    $Quantite=$_POST["quantite"];
    $Unite_Quantite=$_POST["unitQuantite"];
    $insertionProduit = "INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix) VALUES (:nbProduits, :Nom_Produit, :Type_De_Produit, :IdProd, :Quantite, :Unite_Quantite, :Prix, :Unite_Prix)";
    echo $insertionProduit;
    //echo '<br>';
    //var_dump($_SESSION);

    //insertion de l'image
    // Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . "/img_produit/";
        $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
        // Vérifier l'extension
        if (!in_array($extension, $allowedExtensions)) {
            echo "Extension non autorisée.";
            exit;
        }
    
        // Générer un nouveau nom de fichier
        $newFileName = $nbProduits . '.' . $extension;
        $targetPath = $targetDir . $newFileName;
    
        // Vérifier si le dossier existe
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    
            // Déplacer le fichier
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "Image téléchargée avec succès : " . $newFileName;
        } else {
            echo "Erreur lors du déplacement du fichier.";
            exit;
        }
     } else {
        echo "Erreur lors de l'upload : " . $_FILES["image"]["error"];
        exit;
}
}
    $_SESSION["Id_Produit"]=htmlspecialchars($nbProduits);

    $bindInsertProduct = $bdd->prepare($insertionProduit);
    $bindInsertProduct->bindParam(':nbProduits', $nbProduits, PDO::PARAM_INT);
    $bindInsertProduct->bindParam(':Nom_Produit', $Nom_Produit, PDO::PARAM_STR);
    $bindInsertProduct->bindParam(':Type_De_Produit', $Type_De_Produit, PDO::PARAM_INT);
    $bindInsertProduct->bindParam(':IdProd', $IdProd, PDO::PARAM_INT);
    $bindInsertProduct->bindParam(':Quantite', $Quantite, PDO::PARAM_INT);
    $bindInsertProduct->bindParam(':Unite_Quantite', $Unite_Quantite, PDO::PARAM_INT);
    $bindInsertProduct->bindParam(':Prix', $Prix, PDO::PARAM_INT);
    $bindInsertProduct->bindParam(':Unite_Prix', $Unite_Prix, PDO::PARAM_INT);
    $bindInsertProduct->execute();

    header('Location: ../produits.php');
?>
