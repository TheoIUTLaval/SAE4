<?php
    require "../language/language.php" ; 
?>
<?php
     require __DIR__ . '/../vendor/autoload.php';

     $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
     $dotenv->load();
 
     function dbConnect(){
         $utilisateur = $_ENV['DB_USER'];
         $serveur = $_ENV['DB_HOST'];
         $motdepasse = $_ENV['DB_PASSWORD'];
         $basededonnees = $_ENV['DB_NAME'];
         // Connect to database
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
    $htmlNomProduitExiste = "Le nom du produit existe déjà";
    
    $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
    $IdProd = $returnQueryIdProd[0]["Id_Prod"];
    $Nom_Produit=$_POST["nomProduit"];
    $Type_De_Produit=$_POST["categorie"];
    $Prix=$_POST["prix"];
    $Unite_Prix=$_POST["unitPrix"];
    $Quantite=$_POST["quantite"];
    $Unite_Quantite=$_POST["unitQuantite"];


    $Produit_exist = false;
    $Noms_Produits = $bdd->prepare('SELECT Nom_Produit FROM PRODUIT WHERE Id_Prod = :IdProd;');
    $Noms_Produits->bindParam(':IdProd', $IdProd, PDO::PARAM_INT);
    $Noms_Produits->execute();

    foreach ($Noms_Produits as $nomproduit){
        $nomproduit = $nomproduit["Nom_Produit"];
        If ($Nom_Produit == $nomproduit){
            $Produit_exist = true;
            $_SESSION['erreur'] = $htmlNomProduitExiste;
            header('Location: ../produits.php');
            exit; // Arrêter l'exécution après la redirection
          
        }
    }
    if ($Produit_exist == false){
        $insertionProduit = "INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix) VALUES (:nbProduits, :Nom_Produit, :Type_De_Produit, :IdProd, :Quantite, :Unite_Quantite, :Prix, :Unite_Prix)";
        echo $insertionProduit;
    }
   
    //echo '<br>';
    //var_dump($_SESSION);

    //insertion de l'image
    // Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES["image"])) {
        // Spécifier le chemin du dossier de destination
        $targetDir = __DIR__ . "/../asset/img/img_produit/";
        // Obtenir le nom du fichier téléchargé
        $utilisateur = "etu";
        $serveur = "localhost";
        $motdepasse = "Achanger!";
        $basededonnees = "sae";

        // Obtenir l'extension du fichie
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        // Utiliser l'extension dans le nouveau nom du fichier
        $newFileName = $nbProduits . '.' . $extension;

            // Créer le chemin complet du fichier de destination
            $newFileName = $nbProduits . '.' . $extension;
            $targetPath = $targetDir . $newFileName;

            // Vérifier si le fichier existe avant de le supprimer
            if (file_exists($targetPath)) {
                unlink($targetPath);
                echo $htmlSuppImgSucces.".<br>";
            }

            // Déplacer le fichier téléchargé vers le dossier de destination
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
                echo "<br>".$htmlImgTelecSucces, $newFileName."<br>";
            } else {
                echo $htmlImgTelecRate . error_get_last()['message'] . "<br>";
                header('Location: ../produits.php?erreur='. error_get_last()['message']);
            }

    } else {
        echo $htmlSelecImg."<br>";
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
