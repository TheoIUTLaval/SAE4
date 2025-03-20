<!-- Bootstrap 5 -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<!-- Modal d'Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <!-- En-tête du modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadModalLabel"><?php echo $htmlSInscrire; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <!-- Corps du modal -->
            <div class="modal-body">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="modele/upload.php" method="post" enctype="multipart/form-data">
                            
                            <!-- Aperçu de l'image -->
                            <div class="text-center mb-3">
                                <img id="previewImage" src="https://via.placeholder.com/150" alt="Aperçu" class="img-thumbnail d-none" style="max-width: 120px;">
                            </div>

                            <!-- Sélection de l’image -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sélectionnez une image (PNG uniquement) :</label>
                                <input class="form-control" type="file" name="image" id="imageUpload" accept=".png" required>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-upload"></i> Téléverser
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script pour prévisualiser l'image -->
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
