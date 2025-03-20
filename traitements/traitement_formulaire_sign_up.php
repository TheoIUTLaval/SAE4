<?php
require "language/language.php";
echo(__DIR__);
?>
<?php
// Récupération des données du formulaire

session_start();
$_SESSION['test_pwd'] = 5;
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['rue'] .", ". $_POST['code']. " ".mb_strtoupper($_POST['ville']);
$pwd = $_POST['pwd'];
$Mail_Uti = $_POST['mail'];

$_SESSION['Mail_Temp']=$Mail_Uti;

// Connexion à la base de données 
require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASSWORD'];
    $basededonnees = $_ENV['DB_NAME'];
    // Connect to database
    
    
$bdd= new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

// Récupération de la valeur maximum de Id_Uti
$requete = "SELECT MAX(Id_Uti) AS id_max FROM UTILISATEUR";
$resultat = $bdd->query($requete);
$id_max = $resultat->fetch_assoc()['id_max'];

// Incrémentation de la valeur de $iduti
$iduti = $id_max + 1;
// Vérification de l'existence de l'adresse mail
$requete2 = "SELECT COUNT(*) AS nb FROM UTILISATEUR WHERE Mail_Uti = '$Mail_Uti'";
$resultat2 = $bdd->query($requete2);
$nb = $resultat2->fetch_assoc()['nb'];
// Exécution de la requête d'insertion si l'adresse mail n'est pas déjà utilisée
echo($nb);
if ($nb == 0) {
    $bdd->close();
   
    $bdd = new PDO("mysql:host=$serveur;dbname=$basededonnees", $utilisateur, $motdepasse);
    
    // Définir le mode d'erreur sur Exception
    $connexbddion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête d'insertion pour l'utilisateur
    $insertionUtilisateur = $connbddexion->prepare("INSERT INTO UTILISATEUR (Id_Uti, Prenom_Uti, Nom_Uti, Adr_Uti, Pwd_Uti, Mail_Uti) VALUES (?, ?, ?, ?, ?, ?)");
    $insertionUtilisateur->execute([$iduti, $prenom, $nom, $adresse, $pwd, $Mail_Uti]);

    echo $htmlEnregistrementUtilisateurReussi;

    // Création du producteur si la profession est définie
    if (isset($_POST['profession'])) {
        $profession = $_POST['profession'];

        // Récupérer le dernier Id_Prod
        $requeteIdProd = $connebddxion->query("SELECT MAX(Id_Prod) AS id_max1 FROM PRODUCTEUR");
        $id_max_prod = $requeteIdProd->fetch(PDO::FETCH_ASSOC)['id_max1'];
        $id_max_prod++;

        // Préparation de la requête d'insertion pour le producteur
        $insertionProducteur = $bdd->prepare("INSERT INTO PRODUCTEUR (Id_Uti, Id_Prod, Prof_Prod) VALUES (?, ?, ?)");
        $insertionProducteur->execute([$iduti, $id_max_prod, $profession]);

        echo $htmlEnregistrementProducteurReussi;
    }

    // Fermeture de la connexion
    $bdd = null;

    $bdd2=new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    $isProducteur = $bdd2->query('CALL isProducteur('.$iduti.');');
    $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
    $reponse=$returnIsProducteur[0]["result"];
    if ($reponse!=NULL){
        $_SESSION["isProd"]=true;
    } else {
        $_SESSION["isProd"]=false;
    }
    $_SESSION['Mail_Uti'] = $Mail_Uti;
    $_SESSION['Id_Uti'] = $iduti;
    $_SESSION['erreur'] = '';
    $_SESSION['loggedin'] = true; // Set session variable to indicate user is logged in

    echo "<script>window.location.href = 'index.php';</script>"; // Redirect to index page using JavaScript
} else {
    $_SESSION['erreur'] = $htmlAdrMailDejaUtilisee; 
    echo "<script>alert('Adresse mail déjà utilisée.'); window.location.href = 'index.php';</script>"; // Redirect to index page with an alert
}

// Fermeture de la connexion

?>
