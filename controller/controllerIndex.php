
<?php
include("modele/modeleIndex.php");


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