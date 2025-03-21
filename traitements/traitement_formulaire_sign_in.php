<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
require "language/language.php";

try {
    // Retrieve form data
    $pwd = $_POST['pwd'];
    $Mail_Uti = $_POST['mail'];

    // Handle password attempts
    if (!isset($_SESSION['test_pwd'])) {
        $_SESSION['test_pwd'] = 5;
    }

    // Database connection
    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    $utilisateur = $_ENV['DB_USER'];
    $serveur = $_ENV['DB_HOST'];
    $motdepasse = $_ENV['DB_PASSWORD'];
    $basededonnees = $_ENV['DB_NAME'];
    
       
    
    $bdd= new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user email exists
    $queryIdUti = $bdd->prepare('SELECT Id_Uti FROM UTILISATEUR WHERE Mail_Uti = :mail');
    $queryIdUti->execute(['mail' => $Mail_Uti]);
    $returnQueryIdUti = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);

    if ($returnQueryIdUti == NULL) {
        unset($Id_Uti);
        $_SESSION['erreur'] = $htmlAdresseMailInvalide;
    } else {
        $Id_Uti = $returnQueryIdUti[0]["Id_Uti"];

        // Verify password using stored procedure
        $query = $bdd->prepare('CALL verifMotDePasse(:id, :pwd)');
        $query->execute(['id' => $Id_Uti, 'pwd' => $pwd]);
        $test = $query->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > -10) {
            if ((isset($test[0][1]) && $test[0][1] == 1) || (isset($test[0][0]) && $test[0][0] == 1)) {
                $_SESSION['Mail_Uti'] = $Mail_Uti;
                $_SESSION['Id_Uti'] = $Id_Uti;
                
                // Check user role
                $bdd2= new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
                $isProducteur = $bdd2->query('CALL isProducteur('.$Id_Uti.');');
                $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
                $reponse = $returnIsProducteur[0]["result"];
                if ($reponse != NULL) {
                    $_SESSION["isProd"] = true;
                } else {
                    $_SESSION["isProd"] = false;
                }

                $bdd3= new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
                $isAdmin = $bdd3->query('CALL isAdministrateur('.$Id_Uti.');');
                $returnIsAdmin = $isAdmin->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($returnIsAdmin) && isset($returnIsAdmin[0]["result"])) {
                    $reponse2 = $returnIsAdmin[0]["result"];
                    if ($reponse2 != NULL) {
                        $_SESSION['isAdmin'] = true;
                    } else {
                        $_SESSION['isAdmin'] = false;
                    }
                } else {
                    $_SESSION['isAdmin'] = false; 
                }

                // Redirection
                header('Location: index.php');
                echo '<script type="text/javascript">',
                    'window.location.href="index.php";',
                    '</script>';
                exit;
            } else {
                $_SESSION['test_pwd']--;
                $_SESSION['erreur'] = $htmlMauvaisMdp . $_SESSION['test_pwd'] . $htmlTentatives;
            }
        } else {
            $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
        }
    }
} catch (Exception $e) {
    echo "Une erreur est survenue : " . $e->getMessage();
}
?>