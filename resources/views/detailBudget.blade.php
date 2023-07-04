<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerer Budget</title>
</head>
<body>
    <h1>Edit budget</h1>
    <form action="/edit-budget/{{$budget->id}}" method="POST">
        @csrf
        @method('PUT')
        <label for="libelle">Libelle: </label>
        <input type="text" name="libelle" value="{{$budget->libelle}}">
        @php
         $budget->montant = floatval($budget->montant);   

        @endphp
        <label for="montant">Montant: </label>
        <input type="number" name="montant" value="{{$budget->montant}}">
        <label for="nature">Nature: </label>
        <input type="text" name="nature" value="{{$budget->nature}}">
        <label for="frequence">Fréquence: </label>
        <select name="frequence" id="frequence">
            <option value="quotidien" @php if ($budget->frequence == "quotidien"){echo "selected";}@endphp>Quotidien</option>
            <option value="hebdomadaire" @php if ($budget->frequence == "hebdomadaire"){echo "selected";}@endphp>Hebdomadaire</option>
            <option value="mensuel" @php if ($budget->frequence == "mensuel"){echo "selected";}@endphp>Mensuel</option>
            <option value="annuel" @php if ($budget->frequence == "annuel"){echo "selected";}@endphp>Annuel</option>
            <option value="uneFois" @php if ($budget->frequence == "uneFois"){echo "selected";}@endphp>Une fois</option>
        </select>
        <button>Savegarder</button>
    </form>
    <a href="{{ url()->previous() }}" class="btn btn-outline-danger"><button>Back</button></a>
</body>
</html>