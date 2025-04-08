<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des services supplémentaires</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="card shadow mt-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Liste des Services Supplementaires</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Libelle</th>
                        <th>Tarif</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($supplementaires as $supp )
                    <tr>
                        <td>{{ $supp->id }}</td>
                        <td>{{ $supp->libelle }}</td>
                        <td>{{ $supp->tarif }}</td>  
                        <td>
                            <!-- Bouton de modification -->
                            <a href="{{ route('supp.edit', $supp->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </a>

                            <!-- Bouton de suppression -->
                            <form action="{{ route('supp.destroy', $supp->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>  

                        </td>
                    </tr>              
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="text-center mt-4">
        <a href="{{ route('supp.form') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un service supplémentaire
        </a>
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



