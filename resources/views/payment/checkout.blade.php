<!DOCTYPE html>
<html>
<head>
    <title>Paiement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Paiement de la réservation</h2>
    <form action="{{ route('payment.session') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Payer maintenant </button>
    </form>
</body>
</html>
