<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la catégorie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Modifier la catégorie</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('categorie.update', $categorie->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="typeChambre" class="form-label">Type de Chambre</label>
                    <input type="text" class="form-control" name="typeChambre" id="typeChambre" value="{{ old('typeChambre', $categorie->typeChambre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" required>{{ old('description', $categorie->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('categorie.index') }}" class="btn btn-secondary">Retour à la liste des catégories</a>
    </div>
</body>
</html>
