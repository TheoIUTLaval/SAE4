<?php
    // Connexion à la base de données
    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
    $dotenv->load();

    function dbConnect() {
        $utilisateur = $_ENV['DB_USER'];
        $serveur = $_ENV['DB_HOST'];
        $motdepasse = $_ENV['DB_PASSWORD'];
        $basededonnees = $_ENV['DB_NAME'];
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    $bdd = dbConnect();

    // Requête pour les producteurs
    $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti 
                FROM PRODUCTEUR 
                JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti';
    $stmt = $bdd->prepare($requete);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (!empty($result) && ($_SESSION["isAdmin"] == true)) {
        echo '<div class="container">';
        echo '<div class="titre"><label><h4>Producteurs :</h4></label><br></div>';
        echo '<div class="row">';
        foreach ($result as $row) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3 mb-3">';
            echo '<div class="card h-100">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">Compte de ' . htmlspecialchars($row["Nom_Uti"]) . ' ' . htmlspecialchars($row["Prenom_Uti"]) . '</h5>';
            echo '<p class="card-text">';
            echo htmlspecialchars($htmlNomDeuxPoints) . htmlspecialchars($row["Nom_Uti"]) . "<br>";
            echo htmlspecialchars($htmlPrénomDeuxPoints) . htmlspecialchars($row["Prenom_Uti"]) . "<br>";
            echo htmlspecialchars($htmlMailDeuxPoints) . htmlspecialchars($row["Mail_Uti"]) . "<br>";
            echo htmlspecialchars($htmlAdresseDeuxPoints) . htmlspecialchars($row["Adr_Uti"]) . "<br>";
            echo htmlspecialchars($htmlProfessionDeuxPoints) . htmlspecialchars($row["Prof_Prod"]) . "<br>";
            echo '</p>';
            echo '<form method="post" action="traitements/del_acc.php">';
            echo '<input type="hidden" name="Id_Uti" value="' . htmlspecialchars($row["Id_Uti"]) . '">';
            echo '<input type="submit" name="submit" class="btn btn-danger" value="' . htmlspecialchars($htmlSupprimerCompte) . '">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo htmlspecialchars($htmlErrorDevTeam);
    }

    // Requête pour les utilisateurs non producteurs/admins
    $requete = 'SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti 
                FROM UTILISATEUR 
                WHERE UTILISATEUR.Id_Uti NOT IN (SELECT PRODUCTEUR.Id_Uti FROM PRODUCTEUR) 
                AND UTILISATEUR.Id_Uti NOT IN (SELECT ADMINISTRATEUR.Id_Uti FROM ADMINISTRATEUR) 
                AND UTILISATEUR.Id_Uti <> 0';
    $stmt = $bdd->prepare($requete);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (!empty($result) && ($_SESSION["isAdmin"] == true)) {
        echo '<div class="container">';
        echo '<div class="titre"><h4>' . htmlspecialchars($htmlUtilisateurs) . '</h4></div>';
        echo '<div class="row">';
        foreach ($result as $row) {
            echo '<div class="col-sm-6 col-md-4 col-lg-3 mb-3">';
            echo '<div class="card h-100">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">Compte de ' . htmlspecialchars($row["Nom_Uti"]) . ' ' . htmlspecialchars($row["Prenom_Uti"]) . '</h5>';
            echo '<p class="card-text">';
            echo htmlspecialchars($htmlNomDeuxPoints) . htmlspecialchars($row["Nom_Uti"]) . "<br>";
            echo htmlspecialchars($htmlPrénomDeuxPoints) . htmlspecialchars($row["Prenom_Uti"]) . "<br>";
            echo htmlspecialchars($htmlMailDeuxPoints) . htmlspecialchars($row["Mail_Uti"]) . "<br>";
            echo htmlspecialchars($htmlAdresseDeuxPoints) . htmlspecialchars($row["Adr_Uti"]) . "<br>";
            echo '</p>';
            echo '<form method="post" action="traitements/del_acc.php">';
            echo '<input type="hidden" name="Id_Uti" value="' . htmlspecialchars($row["Id_Uti"]) . '">';
            echo '<input type="submit" name="submit" class="btn btn-danger" value="Supprimer le compte">';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo htmlspecialchars($htmlErrorDevTeam);
    }
?>
