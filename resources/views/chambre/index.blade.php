<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Chambres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


</head>
<body class="bg-light">
    
    <div class="container mt-5">        
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Filtrer les chambres</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('chambre.index') }}">
                    @csrf
                    <div class="row">
                        
                        <div class="col-md-3">
                            <label class="form-label">Numéro d'Étage :</label>
                            <input type="number" class="form-control" name="numEtg" value="{{ request('numEtg') }}" placeholder="Ex: 2">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Catégorie :</label>
                            <select class="form-select" name="categorie_id">
                                <option value="">Toutes</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->typeChambre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <label class="form-label">Trier par :</label>
                            <select class="form-select" name="sort_by">
                                <option value="prixNuit" {{ request('sort_by') == 'prixNuit' ? 'selected' : '' }}>Prix</option>
                                <option value="NumEtg" {{ request('sort_by') == 'NumEtg' ? 'selected' : '' }}>Étage</option>
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <label class="form-label">Ordre :</label>
                            <select class="form-select" name="sort_direction">
                                <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Rechercher</button>
                    </div>
                </form>
            </div>
        </div>


        <form action="{{route('reserver.post')}}" method="POST">
            @csrf
        <div class="card shadow mt-4">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">Liste des Chambres</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Numéro Chambre</th>
                            <th>Numéro Étage</th>
                            <th>Status</th>
                            <th>Prix Nuit</th>
                            <th>Catégorie</th>
                            <th>Action</th>
                            <th>Cocher les chambres à réserver</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($chambres as $ch )
                        <tr>
                            <td>{{ $ch->id }}</td>
                            <td>{{ $ch->NumCh }}</td>
                            <td>{{ $ch->NumEtg }}</td>
                            <td>
                                <a href="#" class="badge {{ $ch->status ? 'bg-success' : 'bg-danger' }} " style="text-decoration: none;">
                                    {{ $ch->status ? 'Occupée' : 'Libre' }}
                                </a>
                                    <!--On a abondonné cette approche parce qu'on traite une table de plusieurs chambre et non pas une par une
                                        <a href="{{-- route('reserver.post',$ch->id)--}}" class="badge {{-- $ch->status ? 'bg-success' : 'bg-danger' --}}">
                                        {{-- $ch->status ? 'Occupée' : 'Libre' --}}
                                    </a>
                                    -->
                            </td>
                            <td>{{ number_format($ch->prixNuit, 2) }} €</td>
                            <td>{{ $ch->categorie ? $ch->categorie->typeChambre : 'Non défini' }}</td>
                            
                            <td>
                            <!-- Bouton de modification -->
                            <a href="{{ route('chambre.edit', $ch->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </a>

                            <!-- Bouton de suppression -->
                            <form action="{{ route('chambre.destroy', $ch->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>  
                            </td>   
                            <td>
                                <!-- Case à cocher pour réserver la chambre -->
                                <input type="checkbox" name="chambres[]" value="{{ $ch->id }}" class="chambre-checkbox">
                            </td>

                            

                        </tr>
                        @endforeach                   
                    </tbody>
                </table>
            </div>
        </div>

        

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Réserver</button>
        </div>
        </form>
        <div class="text-center mt-4">
            <a href="{{ route('chambre.form') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Ajouter une chambre
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
