<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'modele/modeleMessage.php';
if (isset($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], $_POST['content'])){
    if ($_POST['content']!=""){
        envoyerMessage($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], htmlspecialchars($_POST['content']));
    }
    unset($_POST['content']);
    header("Refresh:0");
}

function afficherContact($contact){
    $str = $contact['Prenom_Uti'].' '.$contact['Nom_Uti'];
    ?>
    <form method="get" style="margin-left:25px;">
    <button type="submit" class="btn btn-secondary" style="background-color:#305514; border-color:#305514; margin-bottom:10px;">
        <?php echo($str); ?>
    </button>
    <input type="hidden" name="Id_Interlocuteur" value="<?php echo($contact['Id_Uti'])?>">
    </form>
    <?php
}


function afficheMessage($message){
    $contenu = $message['Contenu_Msg'];
    $date = $message['Date_Msg'];
    if ($message['Emetteur']==$_SESSION['Id_Uti']){
        echo('<div><div class="messageEnvoye message"><a>'.$contenu.'</a></div></div>');
    }else {
        echo('<div><div class="messageRecu message"><a>'.$contenu.'</a></div></div>');
    }
    
}


if (isset($_SESSION['Id_Uti'])){
    afficheContacts($_SESSION['Id_Uti']);
} 

?>