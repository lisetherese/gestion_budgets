<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerer Revenu</title>
</head>
<body>
    <h1>Edit revenu</h1>
    <form action="/edit-revenu/{{$revenu->id}}" method="POST">
        @csrf
        @method('PUT')
        <label for="libelle">Libelle: </label>
        <input type="text" name="libelle" value="{{$revenu->libelle}}">
        
        @php
         $revenu->montant = floatval($revenu->montant);   
        @endphp
        <label for="montant">Montant: </label>
        <input type="number" name="montant" value="{{$revenu->montant}}">
        <label for="nature">Nature: </label>
        <input type="text" name="nature" value="{{$revenu->nature}}">
        <label for="source">Source: </label>
        <input type="text" name="source" value="{{$revenu->source}}">
        <label for="frequence">Fréquence: </label>
        <select name="frequence" id="frequence">
            <option value="quotidien" @php if ($revenu->frequence == "quotidien"){echo "selected";}@endphp>Quotidien</option>
            <option value="hebdomadaire" @php if ($revenu->frequence == "hebdomadaire"){echo "selected";}@endphp>Hebdomadaire</option>
            <option value="mensuel" @php if ($revenu->frequence == "mensuel"){echo "selected";}@endphp>Mensuel</option>
            <option value="annuel" @php if ($revenu->frequence == "annuel"){echo "selected";}@endphp>Annuel</option>
            <option value="uneFois" @php if ($revenu->frequence == "uneFois"){echo "selected";}@endphp>Une fois</option>
        </select>
        <label for="date_in">Date entrée: </label>
        @php
         $revenu->date_in = date('Y-m-d', strtotime($revenu->date_in));;   // value of datepicker is: value="Y-m-d"
        @endphp
        <input type="date" name= "date_in" value="{{$revenu->date_in}}"/>

        <button>Savegarder</button>
    </form>
    <a href="{{ url()->previous() }}" class="btn btn-outline-danger"><button>Back</button></a>
</body>
</html>