<?php
// Détruisez toutes les variables de session
if (!isset($_SESSION)) {
    session_start();
}
//require "../language.php" ; 

$_SESSION = array();
// Effacez le cookie de session
$_SESSION['erreur'] = $htmlDeconnectionReussie;
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    @setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
if (isset($_GET["msg"])){
    echo $_GET["msg"];
}
// Détruisez la session
session_destroy();
echo "<script type='text/javascript'>window.location.href = '/SAE4/index.php';</script>";
?>