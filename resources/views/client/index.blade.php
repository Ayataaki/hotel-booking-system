<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour votre profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2 class="text-center mb-4">Formulaire de réservation</h2>

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

            <!-- Type ID -->
            <div class="col-md-6">
                <label class="form-label">Type de document :</label>
                <select name="typeId" class="form-control">
                    <option value="CIN">CIN</option>
                    <option value="passeport">Passeport</option>
                </select>
            </div>
        </div>

        <div class="row mt-3" id="cinField">
            <div class="col-md-6">
                <label class="form-label">Numéro CIN :</label>
                <input type="text" name="CIN" class="form-control">
            </div>
        </div>

        <div class="row mt-3" id="passportField">
            <div class="col-md-6">
                <label class="form-label">Numéro Passeport :</label>
                <input type="text" name="passeport" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Suivant</button>
    </form>

    <script>
        // Afficher/Masquer les champs CIN et Passeport en fonction du type de document
        document.querySelector('[name="typeId"]').addEventListener('change', function () {
            if (this.value === 'CIN') {
                document.getElementById('cinField').style.display = 'block';
                document.getElementById('passportField').style.display = 'none';
            } else {
                document.getElementById('passportField').style.display = 'block';
                document.getElementById('cinField').style.display = 'none';
            }
        });
    </script>

</body>
</html>
