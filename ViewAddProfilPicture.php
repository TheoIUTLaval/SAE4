<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/template.css">
</head>
<body>
    <?php session_start(); ?>
    <div class="container-fluid d-flex">
        <!-- Colonne gauche -->
        <div class="leftColumn bg-light p-3">
            <img class="logo img-fluid" href="index.php" src="asset/img/logo.png" alt="Logo">
            <div class="contenuBarre mt-3">
                <p>Truc à afficher</p>
            </div>
        </div>
        
        <!-- Colonne droite -->
        <div class="rightColumn flex-grow-1">
            <div class="topBanner p-3 bg-primary text-white">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">Accueil</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto">
                                <?php if (isset($_SESSION["Id_Uti"])): ?>
                                    <li class="nav-item"><a class="nav-link" href="ViewMessagerie.php">Messagerie</a></li>
                                    <li class="nav-item"><a class="nav-link" href="ViewAchats.php">Achats</a></li>
                                <?php endif; ?>
                                <?php if (!empty($_SESSION["isProd"])): ?>
                                    <li class="nav-item"><a class="nav-link" href="produits.php">Produits</a></li>
                                    <li class="nav-item"><a class="nav-link" href="ViewDelivery.php">Commandes</a></li>
                                <?php endif; ?>
                                <?php if (!empty($_SESSION["isAdmin"])): ?>
                                    <li class="nav-item"><a class="nav-link" href="ViewPanelAdmin.php">Panel Admin</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Contenu principal -->
            <div class="content p-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Ouvrir le Modal</button>
            </div>

            <!-- Modal d'Upload -->
            <div class="modal fade" id="uploadModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Téléverser une image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="modele/upload.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Sélectionnez une image (PNG uniquement) :</label>
                                    <input class="form-control" type="file" name="image" id="imageUpload" accept=".png" required>
                                </div>
                                <div class="text-center">
                                    <img id="previewImage" src="#" class="img-thumbnail d-none" style="max-width: 150px;">
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit" class="btn btn-success">Téléverser</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bas de page -->
            <div class="basDePage p-3 bg-light">
                <button class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#termsCollapse">
                    Conditions générales d'utilisation
                </button>
                <div class="collapse mt-2" id="termsCollapse">
                    <div class="card card-body">
                        <p>Texte des conditions générales...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script Bootstrap & Preview Image -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            let image = document.getElementById('previewImage');
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                    image.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>