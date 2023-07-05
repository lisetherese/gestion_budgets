<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerer Activité</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    @if(isset($activite))
        <h1>Edit l'activité</h1>
        Budget montant : {{$budget_montant}}<br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/edit-activite/{{$activite->id}}" method="POST">
            @csrf
            @method('PUT')
            <label for="libelle">Libelle: </label>
            <input type="text" name="libelle" value="{{$activite->libelle}}">
            @php
                $activite->seuil = floatval($activite->seuil);   
                $activite->montant = floatval($activite->montant);
            @endphp
            <label for="seuil">Seuil: </label>
            <input type="number" name="seuil" id="seuil-input" value="{{$activite->seuil}}">
            <label for="date">Date de l'activité:</label>
            @php
            $activite->date = date('Y-m-d', strtotime($activite->date));;   // value default of datepicker is: value="Y-m-d"
            @endphp
            <input type="date" name= "date" value="{{$activite->date}}"/>
            <label for="montant">Montant: </label>
            <input type="number" name="montant" id="montant-input" value="{{$activite->montant}}">  

            <button>Savegarder</button>
        </form>
        <br>
        <form action="/delete-activite/{{$activite->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger show_confirm" data-toggle="tooltip" title="Delete">
            Delete
            </button>
        </form>
    @elseif(isset($budget_id))
        <h2 style="color: rgb(32, 220, 154)">Créer une activité pour le budget '{{$budget_libelle}}'</h2>
        Budget montant : {{$budget_montant}}<br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/create-activite" method="POST" id="create-activite-form">
            @csrf
            <input type="text" name="libelle" placeholder="Libelle de l'activité" required>
            <input type="number" name="seuil" id="seuil-input" placeholder="Seuil de l'activité" required>
            <label for="date">Date :</label>
            <input type="date" name= "date" id="date-input" required/>
            <input type="number" name="montant" id="montant-input" placeholder="Montant de l'activité" required>
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
             title: `Êtes-vous sûr(e) de vouloir supprimer cet enregistrement?`,
             text: "Si vous supprimez ceci, il sera perdu définitivement !",
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

     $('#create-activite-form').submit(function(event) {
        var seuil = parseFloat($('#seuil-input').val());
        var montant = parseFloat($('#montant-input').val());
        var sumAllAc = parseFloat({{ $sumAllActivites }});
        var budgetAmount = parseFloat({{ $budget_montant }})

        if (montant > seuil) {
            event.preventDefault();
            swal({
                title: "Warning",
                text: "Le montant ne peut pas être supérieur au seuil.",
                icon: "warning",
                buttons: "OK",
            });
        } else if ((montant + sumAllAc )> budgetAmount ) {
            event.preventDefault();
            swal({
                title: "Warning",
                text: "Le montant que vous souhaitez ajouter fera dépasser le montant total de toutes les activités le montant du budget. " + "La somme de toutes les activités dans ce budget est déjà: " + sumAllAc + " et le montant du budget est: "+ budgetAmount,
                icon: "warning",
                buttons: "OK",
            });
        }
    });
 
</script>
</html>