<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Add Categorie</title>
</head>
<body>
    <form action="{{ route("categorie.form") }}" method="POST">
        <div class="container">
            @csrf
			<label for="nomCat" class="form-label">Nom d'une catégorie : </label>
			<input type="text" class="form-control" name="nom">       
            <label for="descCat" class="form-label">Description de la catégorie : </label>
			<textarea class="form-control" name="descr"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" value="submit" name="submit"></input>
    </form>
</body>
</html>