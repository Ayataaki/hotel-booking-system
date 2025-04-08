<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Modifier la chambre</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('chambre.update', $chambre->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="numChambre" class="form-label">Numéro de Chambre</label>
                    <input type="text" class="form-control" name="numChambre" id="numChambre" value="{{ old('numChambre', $chambre->numChambre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="numEtg" class="form-label">Numéro d'Étage</label>
                    <input type="number" class="form-control" name="numEtg" id="numEtg" value="{{ old('numEtg', $chambre->numEtg) }}" required>
                </div>

                <div class="mb-3">
                    <label for="prixNuit" class="form-label">Prix par Nuit</label>
                    <input type="number" class="form-control" name="prixNuit" id="prixNuit" value="{{ old('prixNuit', $chambre->prixNuit) }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Statut</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Disponible" {{ old('status', $chambre->status) == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Occupée" {{ old('status', $chambre->status) == 'Occupée' ? 'selected' : '' }}>Occupée</option>
                        <option value="Indisponible" {{ old('status', $chambre->status) == 'Indisponible' ? 'selected' : '' }}>Indisponible</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="categorie_id" class="form-label">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="form-select" required>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id', $chambre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->typeChambre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('chambre.index') }}" class="btn btn-secondary">Retour à la liste des chambres</a>
    </div>
</body>
</html>
