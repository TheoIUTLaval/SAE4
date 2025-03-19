<?php
// filepath: [traitement_formulaire_sign_in.php](http://_vscodecontentref_/1)
<?php
session_start();
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
    $utilisateur = "etu";
    $serveur = "localhost";
    $motdepasse = "Achanger!";
    $basededonnees = "sae";

    // Connect to database
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if user email exists
    $queryIdUti = $bdd->prepare('SELECT Id_Uti FROM UTILISATEUR WHERE Mail_Uti = :mail');
    $queryIdUti->execute(['mail' => $Mail_Uti]);
    $returnQueryIdUti = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);

    // Handle invalid email
    if ($returnQueryIdUti == NULL) {
        unset($Id_Uti);
        $_SESSION['erreur'] = $htmlAdresseMailInvalide;
    } else {
        // Extract user ID
        $Id_Uti = $returnQueryIdUti[0]["Id_Uti"];

        // Verify password using stored procedure
        $query = $bdd->prepare('CALL verifMotDePasse(:id, :pwd)');
        $query->execute(['id' => $Id_Uti, 'pwd' => $pwd]);
        $test = $query->fetchAll(PDO::FETCH_ASSOC);

        // Handle password verification
        if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > -10) {
            if ((isset($test[0][1]) && $test[0][1] == 1) || (isset($test[0][0]) && $test[0][0] == 1)) {
                echo $htmlMdpCorrespondRedirectionAccueil;
                $_SESSION['Mail_Uti'] = $Mail_Uti;
                $_SESSION['Id_Uti'] = $Id_Uti;

                // Check user role
                $queryRole = $bdd->prepare('SELECT Role_Uti FROM UTILISATEUR WHERE Id_Uti = :id');
                $queryRole->execute(['id' => $Id_Uti]);
                $roleResult = $queryRole->fetch(PDO::FETCH_ASSOC);

                if ($roleResult) {
                    $_SESSION['role'] = $roleResult['Role_Uti'];
                } else {
                    $_SESSION['role'] = 'client'; // Default role
                }

                // Redirect based on role
                if ($_SESSION['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                    exit;
                } elseif ($_SESSION['role'] === 'producteur') {
                    header('Location: producteur_dashboard.php');
                    exit;
                } else {
                    header('Location: client_dashboard.php');
                    exit;
                }

                $_SESSION['erreur'] = '';
            } else {
                $_SESSION['test_pwd']--;
                $_SESSION['erreur'] = $htmlMauvaisMdp . $_SESSION['test_pwd'] . $htmlTentatives;
            }
        } else {
            $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
        }
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo "An error occurred: " . $e->getMessage();
}
?>