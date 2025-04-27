<!-- resources/views/emails/facture.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #95714F;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Votre facture LA MI CASA</h1>
        </div>
        
        <p>Bonjour,</p>
        
        <p>Nous vous remercions pour votre réservation chez LA MI CASA. Veuillez trouver ci-joint votre facture n°{{ $facture->numero_facture }}.</p>
        
        <p><strong>Détails de la réservation:</strong></p>
        <ul>
            <li>Date d'arrivée: {{ \Carbon\Carbon::parse($facture->reservation->dateDeb)->format('d/m/Y') }}</li>
            <li>Date de départ: {{ \Carbon\Carbon::parse($facture->reservation->dateFin)->format('d/m/Y') }}</li>
            <li>Montant total: {{ number_format($facture->montant_total, 2) }} €</li>
        </ul>
        
        <p>Vous pouvez consulter tous les détails de votre réservation en vous connectant à votre espace client.</p>
        
        <center>
            <a href="{{ route('client.reservations') }}" class="button">Voir mes réservations</a>
        </center>
        
        <p>Nous nous réjouissons de vous accueillir prochainement à LA MI CASA.</p>
        
        <p>Cordialement,<br>
        L'équipe LA MI CASA</p>
        
        <div class="footer">
            <p>LA MI CASA - Hôtel & Spa<br>
            123 Avenue des Palmiers, 40000 Marrakech, Maroc<br>
            Tel: +212 5 00 40 67 89 | Email: contact@lamicasa.com</p>
        </div>
    </div>
</body>
</html>