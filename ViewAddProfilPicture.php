<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel"><?php echo $htmlSInscrire; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <form action="modele/upload.php" method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="imageUpload" class="form-label">Sélectionnez une image (PNG uniquement) :</label>
                        <input class="form-control" type="file" name="image" id="imageUpload" accept=".png" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Téléverser</button>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>

        </div>
    </div>
</div>
