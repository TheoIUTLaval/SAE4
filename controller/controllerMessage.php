<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'modele/modeleMessage.php';
if (isset($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], $_POST['content'])){
    if ($_POST['content']!=""){
        envoyerMessage($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], htmlspecialchars($_POST['content']));
    }
    unset($_POST['content']);
    header("Refresh:0");
}
    
?>