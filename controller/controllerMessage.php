<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require "language/language.php" ;
include 'modele/modeleMessage.php';
if (isset($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], $_POST['content'])){
    if ($_POST['content']!=""){
        envoyerMessage($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur'], htmlspecialchars($_POST['content']));
    }
    unset($_POST['content']);
    header("Refresh:0");
}

function afficheContacts($id_user){
    require "language/language.php" ; 
    $bdd = dbConnect();
    $query = $bdd->query(('CALL listeContact('.$id_user.');'));
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (count($contacts)==0){
        $test = $htmlPasDeConversation;
        echo($test);
    }else{
        foreach($contacts as $contact){
            afficherContact($contact);
        }
    }    
}

function afficherContact($contact){
    $str = $contact['Prenom_Uti'].' '.$contact['Nom_Uti'];
    ?>
    <form method="get">
        <input type="submit" value="<?php echo($str);?>">
        <input type="hidden" name="Id_Interlocuteur" value="<?php echo($contact['Id_Uti'])?>">
    </form>
    <?php
}

if (isset($_SESSION['Id_Uti'])){
    afficheContacts($_SESSION['Id_Uti']);
} 
?>