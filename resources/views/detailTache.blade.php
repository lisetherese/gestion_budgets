<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerer Tâche</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    @if(isset($tache))
        <h1>Edit la tâche</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/edit-tache/{{$tache->id}}" method="POST">
            @csrf
            @method('PUT')
            <label for="libelle">Libelle: </label>
            <input type="text" name="libelle" value="{{$tache->libelle}}">
            <label for="etat_fait">L'état fait: </label>
            <select for="etat_fait" name="etat_fait">
                <option value="1" @php if ($tache->etat_fait == true || $tache->etat_fait == 1){echo "selected";}@endphp>Oui</option>
                <option value="0" @php if ($tache->etat_fait == false || $tache->etat_fait == 0){echo "selected";}@endphp>Non</option>
            </select>
            <label for="ordre">Ordre: </label>
            <input type="number" name="ordre" value="{{$tache->ordre}}"> 
            <input type="text" name="url" value="{{url()->previous()}}" hidden>
            <button>Savegarder</button>
        </form>
        <br>
        <form action="/delete-tache/{{$tache->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger show_confirm" data-toggle="tooltip" title="Delete">
            Delete
            </button>
        </form>
    @elseif(isset($budget_id))
        <h2 style="color: rgb(32, 220, 154)">Créer une activité pour le budget '{{$budget_libelle}}'</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/create-tache" method="POST">
            @csrf
            <input type="text" name="libelle" placeholder="Libelle de l'activité" required>
            <input type="number" name="seuil" placeholder="Seuil de l'activité" required>
            <label for="date">Date :</label>
            <input type="date" name= "date" id="date" required/>
            <input type="number" name="montant" placeholder="Montant de l'activité" required>
            <input type="text" name="budget_id" value="{{$budget_id}}" hidden>
            
            <br>
            <button style ="margin: 5px;" class="btn btn-info">Sauvegarder</button>
        </form>
    @endif

    <br>
    <a href="{{ url()->previous() }}" class="btn btn-outline-danger"><button>Back</button></a>
</body>
{{-- Option 1: Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
{{--Sweet alert--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
{{--    Load compiled Javascript    --}}
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
 
    $('.show_confirm').click(function(event) {
         var form =  $(this).closest("form");
         var name = $(this).data("name");
         event.preventDefault();
         swal({
             title: `Are you sure you want to delete this record?`,
             text: "If you delete this, it will be gone forever!",
             icon: "warning",
             buttons: true,
             dangerMode: true,
         })
         .then((willDelete) => {
           if (willDelete) {
             form.submit();
           }
         });
     });
 
</script>
</html>