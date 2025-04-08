<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Ajouter une chambre</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('chambre.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="numCh" class="form-label">Numéro de la chambre</label>
                        <input type="text" class="form-control" name="numCh">
                    </div>

                    <div class="mb-3">
                        <label for="numEtg" class="form-label">Numéro de l'étage</label>
                        <input type="text" class="form-control"  name="numEtg" >
                    </div>

                    <div class="mb-3">
                        <label for="prixNuit" class="form-label">Prix par nuit</label>
                        <input type="number" class="form-control" name="prixNuit" >
                    </div>

                    <div class="mb-3">
                        <label for="categorie_id" class="form-label">Catégorie</label>
                        <select class="form-select" name="categorie_id" >
                            <option selected disabled>Choisir une catégorie</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->typeChambre }}</option>     
                            @endforeach
                        </select>
                    </div>



                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Ajouter la chambre" name="submit"></input> 
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
