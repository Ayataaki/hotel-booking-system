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
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Total Payer</th>
                        <th>ID client</th>
                        <th>Nom Client</th>
                        <th>Prénom Client</th>
                        <th>Détail sur client</th><!--en cas de confusion s'il existe plusieurs personne ayant le même nom, on peut parir vers la page contenant plus de détail sur le client-->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $res )
                    <tr> 
                        <td>{{ $res->id }}</td>
                        <td>{{ $res->dateDeb }}</td>
                        <td>{{ $res->dateFin }}</td>  
                        <td>{{ $res->totalPayer }}</td>  
                        <td>{{ $res->client_id }}</td>  
                        <!--cette approche peut ne pas être optimale mais on va l'adopter jusqu'on trouve une meilleure méthode-->
                        @foreach ($clients as $client )
                        @if ($client->id==$res->client_id)
                        <td>{{ $client->nom }}</td>  
                        <td>{{ $client->prenom }}</td>  
                        <td>
                            <!--pour avoir plus de détail sur le client effectuant la réservation-->
                            <!--La route ci-dessous nous redirige vers un form rempli des informations du client, si jamais il désire les modifier-->
                            <a href="{{ route('reservation.client', $client->id) }}" class="btn btn-primary btn-sm">
                                Voir plus de détail
                            </a>
                        </td>
                        <td>
                            <!-- Bouton de modification, not yet DO ITT-->
                            <a href="{{route('reservation.editDate', $res->id)}}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </a>

                            <!-- Bouton de suppression -->
                            <form action="{{ route('reservation.destroy', $res->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>  

                        </td>
                    </tr>        
                    
                    @endif
                    @endforeach      
                    @endforeach
                </tbody>
            </table>
        </div>
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



