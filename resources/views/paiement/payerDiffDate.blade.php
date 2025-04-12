<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payer la nouvelle durée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2 class="text-center mb-4">Paiement après modification de la date de fin</h2>

    <form method="POST" action="{{ route('reservation.paiement.valider', $reservation->id) }}">
        @csrf
        @method('PUT')

        {{-- Affichage de la période de modification --}}
        <div class="mb-3">
            <label class="form-label">Ancienne date de fin :</label>
            <input type="text" class="form-control" value="{{ $ancienneDate }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Nouvelle date de fin :</label>
            <input type="text" class="form-control" name="nouvelleDate" value="{{ $nouvelleDate }}" disabled>
        </div>

        <input type="hidden" name="nouvelleDate" value="{{ $nouvelleDate }}">


        {{-- Montant à payer --}}
        <div class="mb-3">
            <label class="form-label">Montant à payer :</label>
            <input type="text" name="montant" class="form-control" value="{{ $montant }}" readonly>
        </div>

        {{-- Mode de paiement --}}
        <div class="mb-3">
            <label class="form-label">Mode de paiement :</label>
            <select name="modePaiement" class="form-control" required value="Veuillez choisir le mode de paiement">
                <option value="carte">Carte bancaire</option>
                <option value="cash">Espèces</option>
            </select>
        </div>

        {{-- Boutons --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('reservation.liste') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Payer</button>
        </div>
    </form>

</body>
</html>
