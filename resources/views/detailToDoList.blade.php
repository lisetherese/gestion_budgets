<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerer To-Do-List</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    @if(isset($toDoList))
        <h1>Edit la to-do-list</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/edit-toDoList/{{$toDoList->id}}" method="POST">
            @csrf
            @method('PUT')
            <label for="libelle">Libelle: </label>
            <input type="text" name="libelle" value="{{$toDoList->libelle}}">
            
            <label for="date_creation">Date de création:</label>
            @php
            $toDoList->date_creation = date('Y-m-d', strtotime($toDoList->date_creation));;   // value default of datepicker is: value="Y-m-d"
            @endphp
            <input type="date" name= "date_creation" value="{{$toDoList->date_creation}}"/>
            

            <button>Savegarder</button>
        </form>
        <br>
        <form action="/delete-toDoList/{{$toDoList->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger show_confirm" data-toggle="tooltip" title="Delete">
            Delete
            </button>
        </form>
        <br>
        @if (count($taches) > 0)
        @php
            $tacheNonComplete = [];
            foreach ($taches as $tache) {
                if ($tache->etat_fait == 0 || $tache->etat_fait == false){
                    $tacheNonComplete[] = $tache;
                }
            }
        @endphp
        
        @if(empty($tacheNonComplete))
            <div class="alert alert-success" role="alert">
                Vous n'avez aucune tâche non-complétée dans cette to-do-list!
            </div>
        @endif
        @endif
        <br>
        <div style="border: 3px solid rgb(16, 16, 16); padding : 5px;">
            <div style="border: 3px solid rgb(23, 226, 43);padding : 10px;">
                 <h2 style ="color: rgb(23, 226, 43)">Créer une tâche dans cette to-do-list</h2>
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
                     <input type="text" name="libelle" placeholder="Libelle de la tâche" required>
                     <label for="etat_fait">Choisir l'état fait: </label>
                     <select name="etat_fait" id="etat_fait">
                         <option value="1">Oui</option>
                         <option value="0">Non</option>
                     </select>
                     <input type="number" name="ordre" placeholder="Ordre de cette tâche" required>
                     <input type="text" name="to_do_list_id" value="{{$toDoList->id}}" hidden>
                     <button type="submit" class="btn btn-info">Sauvegarder</button>
                 </form>
               
            
                 @if (count($taches) !== 0)
            
                 <h3>Tous les tâches de {{auth()->user()->name}}</h3>{{--co the viet $depense->user->name de di den function user() belongsTo trong Model Budget--}}
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                 <table class="table">
                    <thead>
                     <tr>
                       <th scope="col">No</th>
                       <th scope="col">Libelle</th>
                       <th scope="col">Etat fait</th>
                       <th scope="col">Ordre</th>
                       <th scope="col">Actions</th>
                     </tr>
                    </thead>
                    <tbody>
                        @foreach($taches as $tache)
                        <tr>
                            <th scope="row">{{$tache['id']}}</th>
                            <td>{{$tache['libelle']}}</td>
                            @php
                                $tache_etat_fait = ($tache->etat_fait) ? "Oui" : "Non";
                            @endphp
                            <td>{{$tache_etat_fait}}</td>
                            <td>{{$tache['ordre']}}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-2">
                                        <a href="/detail-tache/{{$tache->id}}"><button class="btn btn-success">Edit</button></a>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="/delete-tache/{{$tache->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
             </table>
             @else
             <h3>Aucune tâche pour {{auth()->user()->name}} en ce moment!</h3>
             @endif
            </div>
             
        </div>
    @elseif(isset($budget_id))
        <h2 style="color: rgb(204, 9, 152)">Créer une to-do-list pour le budget '{{$budget_libelle}}'</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/create-toDoList" method="POST">
            @csrf
            <input type="text" name="libelle" placeholder="Libelle de la to-do-list" required>
            <label for="date_creation">Date de création:</label>
            <input type="date" name= "date_creation" id="date_creation" required/>
            <input type="text" name="budget_id" value="{{$budget_id}}" hidden>
            
            <br>
            <button style ="margin: 5px;" class="btn btn-info">Sauvegarder</button>
        </form>
    @endif

    <br>
    <a href="{{ url('/') }}" class="btn btn-outline-danger"><button>Back</button></a>
</body>
{{-- Option 1: Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
{{--Sweet alert--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
{{--    Load compiled Javascript    --}}
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script>
    $('.show_confirm').click(function(event) {
         var form =  $(this).closest("form");
         var name = $(this).data("name");
         event.preventDefault();
         swal({
             title: `Are you sure you want to delete this record?`,
             text: "If you delete this, it will be gone forever with all of its relations.",
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
 
    if ({{ empty($tacheNonComplete) ? 'true' : 'false' }}) {
      $('.alert').show();
    }
</script>
</html>