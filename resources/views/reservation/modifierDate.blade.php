<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Liste des Réservations</h4>
        </div>
        <div class="card-body">
            <h2>Modifier la date de fin de réservation</h2>

            <form action="{{ route('reservation.updateDate', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="dateDeb" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="dateDeb" value="{{ $reservation->dateDeb }}" disabled>
                </div>

                <input type="hidden" name="ancienneDate" value="{{ $reservation->dateFin }}">
                <input type="hidden" name="id" value="{{ $reservation->id }}">


                <div class="mb-3">
                    <label for="dateFin" class="form-label">Nouvelle date de fin</label>
                    <input type="date" class="form-control @error('dateFin') is-invalid @enderror" id="dateFin" name="dateFin" value="{{ old('dateFin', $reservation->dateFin) }}" required>
                    @error('dateFin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('reservation.liste') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>                

    <div class="text-center mt-4">
        <form action="{{ route('logout.post') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
        </form>
    </div>
</div>
</body>
</html>



