<?php
    require "../../language/language.php" ; 
?>
<?php
$pwd1 = $_POST['pwd1'];
$pwd2 = $_POST['pwd2'];

if ($pwd1 == $pwd2 && $pwd1 !== null) {

    $utilisateur = "etu";
    $serveur = "localhost";
    $motdepasse = "Achanger!";
    $basededonnees = "sae";
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

    if(!isset($_SESSION)){
        session_start();
        }
    // Vérif d'abord si l'adr mail existe bien dans la table utilisateur
    $checkEmailQuery = "SELECT COUNT(*) AS count FROM UTILISATEUR WHERE Mail_Uti = :mail";
    $checkEmailStmt = $bdd->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':mail', $_SESSION["mailTemp"]);
    $checkEmailStmt->execute();
    $emailCount = $checkEmailStmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($emailCount > 0) {  
        $update="UPDATE UTILISATEUR SET Pwd_Uti = '".$pwd1."' WHERE Mail_Uti = '".$_SESSION["mailTemp"] ."';";
        echo ($update);
        $bdd->exec($update);
        header('Location: controller/controllerPwd.php?message==$'.$htmlMessageUrlMdpMajOk);

    } else {
        header('Location: controller/controllerPwd.php?message='.$htmlMessageUrlAdrInvalide);
    }
}
?>
