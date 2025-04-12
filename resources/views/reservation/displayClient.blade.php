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
        <h2 class="mb-4">Modifier les données du client</h2>

        <form action="{{ route('edit.client', $client->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="{{ $client->nom }}" required>
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prenom</label>
                <input type="text" class="form-control" name="prenom" value="{{ $client->prenom }}" required>
            </div>

            <div class="mb-3">
                <label for="pays" class="form-label">Pays</label>
                <input type="text" class="form-control" name="pays" value="{{ $client->pays }}" required>
            </div>

            <div class="mb-3">
                <label for="region" class="form-label">Region</label>
                <input type="text" class="form-control" name="region" value="{{ $client->region }}" required>
            </div>

            <div class="mb-3">
                <label for="numTel" class="form-label">Numéro de téléphone</label>
                <input type="text" class="form-control" name="numTel" value="{{ $client->numTel }}" required>
            </div>

            @if ($client->typeId == "CIN")
                <div class="mb-3">
                    <label for="CIN" class="form-label">CIN</label>
                    <input type="text" class="form-control" name="CIN" value="{{ $client->CIN }}" required>
                </div>
            @endif

            @if ($client->typeId == "passeport")
                <div class="mb-3">
                    <label for="passeport" class="form-label">Passeport</label>
                    <input type="text" class="form-control" name="passeport" value="{{ $client->passeport }}" required>
                </div>
            @endif

            <input type="hidden" name="client_id" value="{{ $client->id }}">

            <input type="hidden" name="typeId" value="{{ $client->typeId }}">


            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="{{ route('reservation.liste') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
