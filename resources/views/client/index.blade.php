<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour votre profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container py-4">

    <h2 class="text-center mb-4">Formulaire de remplissage des informations pour une réservation en ligne</h2>

    <form action="{{ route('client.form.create') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Nom -->
            <div class="col-md-6">
                <label class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control">
            </div>

            <!-- Prénom -->
            <div class="col-md-6">
                <label class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control" >
            </div>
        </div>

        <div class="row mt-3">
            <!-- Pays -->
            <div class="col-md-6">
                <label class="form-label">Pays :</label>
                <input type="text" name="pays" class="form-control">
            </div>

            <!-- Région -->
            <div class="col-md-6">
                <label class="form-label">Région :</label>
                <input type="text" name="region" class="form-control" >
            </div>
        </div>

        <div class="row mt-3">
            <!-- Numéro de téléphone -->
            <div class="col-md-6">
                <label class="form-label">Numéro de téléphone :</label>
                <input type="text" name="numTel" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Type ID :</label>
                <select name="typeId" id="typeId" class="form-control">
                    <option value="CIN">CIN</option>
                    <option value="passeport">Passeport</option>
                </select>
            </div>
            
            <!-- Champ pour CIN -->
            <div class="col-md-6 mt-3" id="cinField" style="display: none;">
                <label class="form-label">Numéro CIN :</label>
                <input type="text" id="CIN" name="CIN" class="form-control">
            </div>
            
            <!-- Champ pour Passeport -->
            <div class="col-md-6 mt-3" id="passportField" style="display: none;">
                <label class="form-label">Numéro Passeport :</label>
                <input type="text" id="passeport" name="passeport" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Suivant</button>
    </form>

    <script>
        $(document).ready(function () {
            // Fonction pour afficher/cacher les champs en fonction du type d'identité sélectionné
            function toggleFields() {
                var type = $('#typeId').val();
                
                if (type === "CIN") {
                    $('#cinField').show();
                    $('#passportField').hide();
                    $('#Passeport').val(''); // Réinitialiser le champ Passeport
                } else if (type === "passeport") {
                    $('#passportField').show();
                    $('#cinField').hide();
                    $('#CIN').val(''); // Réinitialiser le champ CIN
                } else {
                    $('#cinField, #passportField').hide();
                    $('#CIN, #Passeport').val('');
                }
            }

            // Initialiser les champs lors du chargement de la page
            toggleFields();

            // Ajouter l'écouteur d'événement pour le changement
            $('#typeId').on('change', toggleFields);
        });
    </script>

</body>
</html>
