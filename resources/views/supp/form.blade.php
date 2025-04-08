<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ajouter un Service Supplémentaire</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center text-primary mb-4">
                        <i class="bi bi-plus-circle"></i> Ajouter un Service Supplémentaire
                    </h3>

                    <form action="{{ route('supp.store') }}" method="POST">
                        @csrf
                        
                        <!-- Champ Libelle -->
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé :</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-pencil"></i></span>
                                <input type="text" class="form-control" name="libelle" placeholder="Ex: Petit-déjeuner" required>
                            </div>
                        </div>

                        <!-- Champ Tarif -->
                        <div class="mb-3">
                            <label for="tarif" class="form-label">Tarif :</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-cash"></i></span>
                                <input type="number" class="form-control" name="tarif" placeholder="Ex: 10" required>
                            </div>
                        </div>

                        <!-- Bouton de Soumission -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons et JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
