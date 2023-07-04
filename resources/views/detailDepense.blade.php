<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerer Depense</title>
</head>
<body>
    <h1>Edit depense</h1>
    <form action="/edit-depense/{{$depense->id}}" method="POST">
        @csrf
        @method('PUT')
        <label for="libelle">Libelle: </label>
        <input type="text" name="libelle" value="{{$depense->libelle}}">
        
        @php
         $depense->montant = floatval($depense->montant);   
        @endphp
        <label for="montant">Montant: </label>
        <input type="number" name="montant" value="{{$depense->montant}}">
        <label for="nature">Nature: </label>
        <input type="text" name="nature" value="{{$depense->nature}}">
        <label for="frequence">Fréquence: </label>
        <select name="frequence" id="frequence">
            <option value="quotidien" @php if ($depense->frequence == "quotidien"){echo "selected";}@endphp>Quotidien</option>
            <option value="hebdomadaire" @php if ($depense->frequence == "hebdomadaire"){echo "selected";}@endphp>Hebdomadaire</option>
            <option value="mensuel" @php if ($depense->frequence == "mensuel"){echo "selected";}@endphp>Mensuel</option>
            <option value="annuel" @php if ($depense->frequence == "annuel"){echo "selected";}@endphp>Annuel</option>
            <option value="uneFois" @php if ($depense->frequence == "uneFois"){echo "selected";}@endphp>Une fois</option>
        </select>
        <button>Save changes</button>
    </form>
    <a href="{{ url()->previous() }}" class="btn btn-outline-danger"><button>Back</button></a>
</body>
</html>