<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container py-4">

    <h2 class="text-center mb-4">Formulaire de Réservation</h2>
    
    <form id="reservationForm" action="{{ route('reservation.store') }}" method="POST">
        @csrf

            
    <script>
        $(document).ready(function () {
            // Afficher/Masquer la liste des services supplémentaires
            $('#enableServices').change(function () {
                if ($(this).is(':checked')) {
                    $('#servicesList').slideDown();
                } else {
                    $('#servicesList').slideUp();
                    $('.service-checkbox').prop('checked', false);
                    updateTotal();
                }
            });

            // Calcul du prix total incluant les services
            $('#chambre_id, #dateDeb, #dateFin, .service-checkbox').on('change', updateTotal);
            
            function updateTotal() {
                let total = 0;

                // Récupérer le prix de la chambre sélectionnée
                let selectedChambre = $('#chambre_id option:selected');
                let chambrePrix = parseFloat(selectedChambre.data('prixNuit')) || 0;

                // Récupérer les dates de début et de fin
                let dateDeb = new Date($('#dateDeb').val());
                let dateFin = new Date($('#dateFin').val());

                // Vérifier si les dates sont valides
                if (dateDeb && dateFin && dateFin >= dateDeb) {
                    let diffDays = (dateFin - dateDeb) / (1000 * 60 * 60 * 24);
                    diffDays = Math.max(diffDays, 1); // Minimum une nuit
                    total += chambrePrix * diffDays;
                }

                // Ajout des services supplémentaires au total
                $('.service-checkbox:checked').each(function() {
                    total += parseFloat($(this).data('price'));
                });

                // Affichage du prix total avec deux décimales
                $('#totalPrice').val(total.toFixed(2)); // Met à jour le total à payer
                $('#totalPayer').val(total.toFixed(2)); // Met à jour le champ caché du total (si nécessaire)
            }

            // Initialiser le calcul lors du changement de chambre
            $('#chambre_id').on('change', function() {
                updateTotal();
            });

            // Calcul initial si le formulaire est déjà rempli
            updateTotal();
        });

    </script>

            
            <div class="col-md-6">
                <label class="form-label">Type ID :</label>
                <select name="typeId" id="typeId" class="form-control">
                    <option value="">Sélectionnez un type</option>
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
                <input type="text" id="Passeport" name="Passeport" class="form-control">
            </div>


            <script>
                $(document).ready(function () {
                    $('#typeId').on('change', function () {
                        var type = $(this).val();
                        
                        if (type === "CIN") {
                            $('#cinField').show();
                            $('#passportField').hide();
                            $('#passeport').val(''); // Réinitialiser le champ Passeport
                        } else if (type === "passeport") {
                            $('#passportField').show();
                            $('#cinField').hide();
                            $('#CIN').val(''); // Réinitialiser le champ CIN
                        } else {
                            $('#cinField, #passportField').hide();
                            $('#CIN, #passeport').val('');
                        }
                    });
                });
            </script>
                        
        </div>

        <div class="row mt-3">
            <!-- Nom -->
            <div class="col-md-6">
                <label class="form-label">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control">
            </div>

            <!-- Prénom -->
            <div class="col-md-6">
                <label class="form-label">Prénom :</label>
                <input type="text" id="prenom" name="prenom" class="form-control">
            </div>
        </div>

        <div class="row mt-3">
            <!-- Pays -->
            <div class="col-md-6">
                <label class="form-label">Pays :</label>
                <input type="text" id="pays" name="pays" class="form-control">
            </div>

            <!-- Région -->
            <div class="col-md-6">
                <label class="form-label">Région :</label>
                <input type="text" id="region" name="region" class="form-control">
            </div>
        </div>

        <div class="row mt-3">
            <!-- Numéro de téléphone -->
            <div class="col-md-6">
                <label class="form-label">Téléphone :</label>
                <input type="text" id="numTel" name="numTel" class="form-control">
            </div>
        </div>

        <hr>

        <div class="row mt-3">
            <!-- Dates -->
            <div class="col-md-6">
                <label class="form-label">Date Début :</label>
                <input type="date" id="dateDeb" name="dateDeb" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Date Fin :</label>
                <input type="date" id="dateFin" name="dateFin" class="form-control">
            </div>
        </div>

        <hr>

        <!-- Services supplémentaires -->
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" id="enableServices">
            <label class="form-check-label" for="enableServices">
                Voulez-vous des services supplémentaires ?
            </label>
        </div>
        
        <!--pour passer les informations de la chambre en cachete-->
        <input type="hidden" name="chambres" value="{{json_encode($chambres)}}">

        <!-- Liste des services supplémentaires (masquée par défaut) -->
        <div id="servicesList" class="mt-3" style="display: none;">
            <label class="form-label">Sélectionnez vos services :</label>
            <div class="form-check">
                @foreach($supplementaires as $supp)
                <input class="form-check-input service-checkbox" type="checkbox" name="supplementaires[]" 
                       value="{{ $supp->id }}" data-price="{{ $supp->tarif }}">
                <label class="form-check-label">
                    {{ $supp->libelle }} ({{ $supp->tarif }} €)
                </label><br>
                @endforeach
            </div>
        </div>
        
        <!-- Total à payer -->
        <div class="mt-3">
            <label class="form-label fw-bold">Total à payer :</label>
            <input type="text" id="totalPrice" name="totalPayer" class="form-control" readonly>
        </div>
        
        <!-- Mode de paiement -->
        <div class="mt-3">
            <label class="form-label">Mode de paiement :</label>
            <select name="modePaiement" class="form-control">
                <option value="carte">Carte bancaire</option>
                <option value="cash">Espèces</option>
            </select>
        </div>

        
        <div class="col-md-6">
            <label class="form-label">Solde Payé :</label>
            <input type="text" id="soldePayer" name="soldePayer" class="form-control">
            </div>
        </div>

        <!--cette boucle avec select ont été prévu pour calculer la différence des jours avec le prix par nuit -->
        @foreach($chambres as $ch)
            <option value="{{ $ch->id }}" data-prix="{{ $ch->prixNuit }}">
            </option>
        @endforeach


        <script>
            $(document).ready(function () {
                // Afficher/Masquer la liste des services supplémentaires
                $('#enableServices').change(function () {
                    if ($(this).is(':checked')) {
                        $('#servicesList').slideDown();
                    } else {
                        $('#servicesList').slideUp();
                        $('.service-checkbox').prop('checked', false);
                        updateTotal();
                    }
                });
        /*
                // Calcul du prix total incluant les services
                $('#chambre_id, #dateDeb, #dateFin, .service-checkbox').on('change', updateTotal);

                function updateTotal() {
                    // Récupérer le prix de la chambre sélectionnée
                    let chambrePrix = 0;

                    // On récupère le prix de la chambre choisie dans la liste des options
                    let selectedChambre = $('#chambre_id option:selected');
                    chambrePrix = parseFloat(selectedChambre.data('prix')) || 0;

                    // Récupérer les dates de début et de fin
                    let dateDeb = new Date($('#dateDeb').val());
                    let dateFin = new Date($('#dateFin').val());

                    // Calcul du nombre de jours de réservation
                    let diffDays = (dateFin - dateDeb) / (1000 * 60 * 60 * 24);
                    diffDays = Math.max(diffDays, 1); // Minimum une nuit

                    // Calcul du total en multipliant le prix de la chambre par le nombre de jours
                    let total = chambrePrix * diffDays;

                    // Ajout des services supplémentaires au total
                    $('.service-checkbox:checked').each(function() {
                        total += parseFloat($(this).data('price'));
                    });

                    // Affichage du prix total avec deux décimales
                    $('#totalPrice').val(total.toFixed(2));
                }*/


            });

            
        </script>
        
        
        <button type="submit" class="btn btn-primary mt-3">Réserver</button>
    </form>

</body>
</html>
