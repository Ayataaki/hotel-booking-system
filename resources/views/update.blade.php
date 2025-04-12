<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Modifier le Service Supplémentaire</h2>

        <form action="{{ route('supp.update', $supp->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="libelle" class="form-label">Libellé</label>
                <input type="text" class="form-control" name="libelle" value="{{ $supp->libelle }}" required>
            </div>

            <div class="mb-3">
                <label for="tarif" class="form-label">Tarif</label>
                <input type="number" step="0.01" class="form-control" name="tarif" value="{{ $supp->tarif }}" required>
            </div>

            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="{{ route('supp.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
