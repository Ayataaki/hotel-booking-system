<!-- resources/views/factures/template.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .header {
            border-bottom: 1px solid #95714F;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
            height: auto;
        }
        .facture-info {
            margin-bottom: 30px;
        }
        .facture-info table {
            width: 100%;
        }
        .facture-info td {
            padding: 5px;
        }
        .client-info {
            margin-bottom: 30px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .details th {
            background-color: #F3ECE3;
        }
        .total {
            text-align: right;
            margin-top: 30px;
        }
        .total .amount {
            font-size: 18px;
            font-weight: bold;
            color: #6d4927;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Mi Casa" class="logo">
        <h1>Facture</h1>
    </div>

    <div class="facture-info">
        <table>
            <tr>
                <td><strong>Numéro de facture:</strong></td>
                <td>{{ $facture->numero_facture }}</td>
                <td><strong>Date d'émission:</strong></td>
                <td>{{ \Carbon\Carbon::parse($facture->date_emission)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Statut:</strong></td>
                <td>{{ ucfirst($facture->statut) }}</td>
                <td><strong>Référence réservation:</strong></td>
                <td>{{ $reservation->id }}</td>
            </tr>
        </table>
    </div>

    <div class="client-info">
        <h3>Coordonnées du client</h3>
        <p>
            <strong>{{ $client->nom }} {{ $client->prenom }}</strong><br>
            Téléphone: {{ $client->numTel }}<br>
            Email: {{ $client->user->email }}
        </p>
    </div>

    <div class="details">
        <h3>Détails de la réservation</h3>
        <p>Période de séjour: {{ \Carbon\Carbon::parse($reservation->dateDeb)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($reservation->dateFin)->format('d/m/Y') }}</p>
        <p>Nombre de nuits: {{ \Carbon\Carbon::parse($reservation->dateDeb)->diffInDays(\Carbon\Carbon::parse($reservation->dateFin)) }}</p>

        <h4>Chambres</h4>
        <table>
            <thead>
                <tr>
                    <th>Chambre</th>
                    <th>Type</th>
                    <th>Prix par nuit</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservation->chambres as $chambre)
                <tr>
                    <td>Chambre {{ $chambre->NumCh }}</td>
                    <td>{{ $chambre->categorie->typeChambre }}</td>
                    <td>{{ number_format($chambre->prixNuit, 2) }} €</td>
                    <td>{{ number_format($chambre->prixNuit * \Carbon\Carbon::parse($reservation->dateDeb)->diffInDays(\Carbon\Carbon::parse($reservation->dateFin)), 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($services) > 0)
            <h4>Services additionnels</h4>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>{{ $service->libelle }}</td>
                            <td>{{ number_format($service->tarif, 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p><strong>Total services :</strong> {{ number_format($services->sum('tarif'), 2) }} €</p>
        @endif

    </div>

    <div class="total">
        <!-- <p>Montant total: <span class="amount">{{ number_format($facture->montant_total, 2) }} €</span></p> -->

        <!-- Ce qui marche :  -->
        <!-- <p>Montant total: <span class="amount">{{ number_format($facture->montant_soldePayer, 2) }} €</span></p> -->
             @if($facture->montant_total == $facture->montant_soldePayer)
                <p>Montant total: <span class="amount">{{ number_format($facture->montant_total, 2) }} €</span></p>
            @else
                <p>Montant supplémentaire (solde ajouté) : <span class="amount">{{ number_format($facture->montant_soldePayer, 2) }} €</span></p>
            @endif

    </div>

    <div class="footer">
        <p>Mi Casa - Hôtel & Spa<br>
        123 Avenue des Palmiers, 40000 Marrakech, Maroc<br>
        Tel: +212 5 00 40 67 89 | Email: contact@lamicasa.com</p>
        <p>Merci pour votre confiance!</p>
    </div>
</body>
</html>
