<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte de receptionniste</title>
</head>
<body>
    <form action="{{route('admin.store')}}" method="POST">
        @csrf
        <label for="">Nom : </label>
        <input type="text" name="nomRec"><br>

        <label for="">Prénom : </label>
        <input type="text" name="prenomRec"><br>

        <label for="">Le mot de passe : </label>
        <input type="text" name="password"><br>

        <label for="">Adresse mail : </label>
        <input type="text" name="email"><br>
        <!--pour le mot de passe on va mettre un par défaut afin d'être changer par les receptionnistes lors de leurs usage de la platforme-->

        <label for="">CIN : </label>
        <input type="text" name="CIN"><br>

        <label for="">Numéro de telephone : </label>
        <input type="text" name="numTel"><br>

        <button type="submit">Ajouter ce réceptionniste</button>
        


    </form>
</body>
</html>