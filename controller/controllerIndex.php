<?php
include "model/modelIndex.php";


 if(!isset($_SESSION)){
    session_start();
}
if (isset($_GET["rechercheVille"])==true){
    $rechercheVille=htmlspecialchars($_GET["rechercheVille"]);
}
else{
    $rechercheVille="";
}
if (isset($_GET["categorie"])==false){
    $_GET["categorie"]="Tout";
}
if (isset($_SESSION["Id_Uti"])==false){
    $utilisateur=-1;
}
else{
    $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
}
if (isset($_GET["rayon"])==false){
    $rayon=100;
}
else{
    $rayon=htmlspecialchars($_GET["rayon"]);
}
if (isset($_GET["tri"])==true){
    $tri=htmlspecialchars($_GET["tri"]);
}
else{
    $tri="nombreDeProduits";
}
if (isset($_SESSION["language"])==false){
    $_SESSION["language"]="fr";
}
        /*---------------------------------------------------------------*/
        /*
            Titre : Calcul la distance entre 2 points en km                                                                       
                                                                                                                                
            URL   : https://phpsources.net/code_s.php?id=1091
            Auteur           : sheppy1                                                                                            
            Website auteur   : https://lejournalabrasif.fr/qwanturank-concours-seo-qwant/                                         
            Date édition     : 05 Aout 2019                                                                                       
            Date mise à jour : 16 Aout 2019                                                                                      
            Rapport de la maj:                                                                                                    
            - fonctionnement du code vérifié                                                                                    
        */
        /*---------------------------------------------------------------*/

        function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
        {
            $pi80 = M_PI / 180;
            $lat1 *= $pi80;
            $lng1 *= $pi80;
            $lat2 *= $pi80;
            $lng2 *= $pi80;
    
            $r = 6372.797; // rayon moyen de la Terre en km
            $dlat = $lat2 - $lat1;
            $dlng = $lng2 - $lng1;
            $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin(
    $dlng / 2) * sin($dlng / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $km = $r * $c;
        
            return ($miles ? ($km * 0.621371192) : $km);
    }
?>