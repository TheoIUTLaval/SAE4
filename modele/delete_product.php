<?php
     function dbConnect(){
      $utilisateur = "etu";
      $serveur = "localhost";
      $motdepasse = "Achanger!";
      $basededonnees = "sae";
      // Connect to database
      return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
      }
      $bdd=dbConnect();
      $Id_Produit=htmlspecialchars($_POST["deleteIdProduct"]);

      $delContenu=$bdd->prepare('DELETE FROM CONTENU WHERE Id_Produit=:Id_Produit;');
      $delContenu->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
      $delContenu->execute();


      $delProduct=$bdd->prepare('DELETE FROM PRODUIT WHERE Id_Produit=:Id_Produit;');
      $delProduct->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
      $delProduct->execute();

      // suppression de l'image (path à changer sur le serveur !!!!)
      $imgpath = "asset/img/img_produit/".$Id_Produit.".png";
      //echo $imgpath;
      unlink( $imgpath ); 
    header('Location: /SAE4/produits.php');
?>
