<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>App gerer budget</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- Link to resource css --}}
    <link rel="stylesheet" href={{ asset('css/app.css') }}>
    {{-- Link to font Awesome--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


</head>
<body>

    @auth
        {{-- Modal émergé pop-up si tâches pas completé aujourd'hui --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">All Tâches</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    @if (count($tachesForToday) == 0)
                    <h5> Félicitation! Vous n'avez aucune tâche pour aujourd'hui </h5>
                    @else
                    <h5>Tâches à compléter aujourd'hui!</h5>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Libellé</th>
                        <th>Ordre</th>
                        <th>Complétée</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($tachesForToday as $tache)
                        <tr>
                            <td>{{ $tache->id }}</td>
                            <td>{{ $tache->libelle }}</td>
                            <td>{{ $tache->ordre }}</td>
                            <td>
                                <input type="checkbox" id="etat_fait" name="etat_fait" value="true">
                                <label for="etat_fait">Oui</label>
                            </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @endif
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
        {{-- End modal--}}

        {{-- Modal pop up si toutes les dépenses dans 1 mois (based on date_created) > revenus dans 1 mois (based on date_in)--}}
        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Attention!!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <p>Vous avez la somme de toutes les dépenses dans ce mois ci > vos revenues</p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
        {{-- End modal --}}

       <h3> Welcome {{auth()->user()->name}} to our application! </h3> 
       <form action="/logout" method="POST">
        {{--When the form is submitted, the CSRF token value will be included in the form data--}}
        @csrf
        <button style="margin-left: 15px;" class="btn btn-outline-warning">Log out</button>
       </form>
       <br>
       <div style="border: 3px solid rgb(16, 16, 16); padding : 5px;">
        <div style="border: 3px solid rgb(226, 128, 23);padding : 5px;margin-bottom:5px;">
            <h2 style="color: rgb(226, 128, 23)">Créer un budget</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="/create-budget" method="POST">
                @csrf
                <input type="text" name="libelle" placeholder="Libelle du budget" required>
                <input type="number" name="montant" placeholder="Montant du budget" required>
                <input type="text" name="nature" placeholder="Nature du budget">
                <label for="frequence">Choisir la fréquence: </label>
                <select name="frequence" id="frequence">
                    <option value="quotidien">Quotidien</option>
                    <option value="hebdomadaire">Hebdomadaire</option>
                    <option value="mensuel">Mensuel</option>
                    <option value="annuel">Annuel</option>
                    <option value="une fois">Une fois</option>
                </select>
                <button type="submit" class="btn btn-info">Sauvegarder</button>
            </form>
       

            @if (count($budgets) !== 0)
            <h3>Tous les budgets de {{auth()->user()->name}}</h3>{{--co the viet $budget->user->name de di den function user() belongsTo trong Model Budget--}}
            {{--@php
                $doneToDoList = [];
                foreach($toDoListsWithTaches as $aTDL){
                    foreach($aTDL->taches as $tache){
                        if($tache->etat_fait == 1 || $tache->etat_fait == true){
                            $doneToDoList[] = $aTDL;
                        }
                     }
                }
            @endphp--}}
            @if(count($toDoListsDone) !== 0)
                <div class="alert alert-success" role="alert">
                    Félicitations! Vous avez de(s) to-do-list(s) commplétée(s) comme en suite: 
                    <ul>
                    @foreach($toDoListsDone as $todolist)
                        <li>{{$todolist->libelle}}</li>
                    @endforeach
                    </ul>
                </div>
            @endif
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Libelle</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Nature</th>
                        <th scope="col">Fréquence</th>
                        <th scope="col">Actions</th>
                        <th scope="col">Activités</th>
                        <th scope="col">To-do-lists</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($budgets as $budget)
                    <tr>
                        <th scope="row">{{$budget['id']}}</th>
                        <td>{{$budget['libelle']}}</td>
                        <td>{{$budget['montant']}}</td>
                        <td>{{$budget['nature']}}</td>
                        <td>{{$budget['frequence']}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="/detail-budget/{{$budget->id}}"><button class="btn btn-success">Edit</button></a>
                                </div>
                                <div class="col-md-2">
                                    <form action="/delete-budget/{{$budget->id}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger show_confirm" data-toggle="tooltip" title="Delete">
                                        Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @if ($budget->activites->count() > 0)
                            <div class="row">
                                <div class="col-md-4">
                                    <form action="/detail-activite/" method="GET">
                                        @csrf
                                        <select name="activite" id="activite">
                                            {{--<option value=""> -- Choisir --</option>--}}
                                            @foreach($budget->activites()->get() as $activite)
                                                <option value="{{$activite->id}}" {{ (isset($activite->id) || old('id'))? "selected":"" }}>{{$activite['libelle']}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-2">
                                        <button type="submit" @disabled($errors->isNotEmpty()) class="btn btn-success"><i class="fas fa-eye"></i></button>
                                    </form>
                                </div>
                                
                                <div class="col-md-2">
                                    <form action="/detail-activite/{{$budget->id}}" method="POST">
                                        @csrf
                                        <input type="text" value="{{$budget->libelle}}" name="budget_libelle" hidden>
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-4">
                                    <p>No activité relative</p>
                                </div>
                                <div class="col-md-2">
                                    <form action="/detail-activite/{{$budget->id}}" method="POST">
                                        @csrf
                                        <input type="text" value="{{$budget->libelle}}" name="budget_libelle" hidden>
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td>
                            @if ($budget->toDoLists->count() > 0)
                            <div class="row">
                                <div class="col-md-4">
                                    <form action="/detail-toDoList/" method="GET">
                                        @csrf
                                        <select name="toDoList" id="toDoList">
                                            {{--<option value=""> -- Choisir --</option>--}}
                                            @foreach($budget->toDoLists()->get() as $toDoList)
                                                <option value="{{$toDoList->id}}" {{ (isset($toDoList->id) || old('id'))? "selected":"" }}>{{$toDoList['libelle']}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-2">
                                        <button type="submit" @disabled($errors->isNotEmpty()) class="btn btn-success"><i class="fas fa-eye"></i></button>
                                    </form>
                                </div>
                                
                                <div class="col-md-2">
                                    <form action="/detail-toDoList/{{$budget->id}}" method="POST">
                                        @csrf
                                        <input type="text" value="{{$budget->libelle}}" name="budget_libelle" hidden>
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-4">
                                    <p>No to-do-list relative</p>
                                </div>
                                <div class="col-md-2">
                                    <form action="/detail-toDoList/{{$budget->id}}" method="POST">
                                        @csrf
                                        <input type="text" value="{{$budget->libelle}}" name="budget_libelle" hidden>
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-plus"></i></button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
        @else
        <h3>Aucun budget pour {{auth()->user()->name}} en ce moment!</h3>
        @endif
        </div>
        
    </div>


    <div style="border: 3px solid rgb(16, 16, 16); padding : 5px;">
        <div style="border: 3px solid rgb(226, 23, 182);padding : 10px;">
             <h2 style ="color: rgb(226, 23, 182)">Créer une depense</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

             <form action="/create-depense" method="POST">
                 @csrf
                 <input type="text" name="libelle" placeholder="Libelle de la depense" required>
                 <input type="number" name="montant" placeholder="Montant de la depense" required>
                 <input type="text" name="nature" placeholder="Nature de la depense">
                 <label for="frequence">Choisir la fréquence: </label>
                 <select name="frequence" id="frequence">
                     <option value="quotidien">Quotidien</option>
                     <option value="hebdomadaire">Hebdomadaire</option>
                     <option value="mensuel">Mensuel</option>
                     <option value="annuel">Annuel</option>
                     <option value="une fois">Une fois</option>
                 </select>
                 <button type="submit" class="btn btn-info">Sauvegarder</button>
             </form>
           
        
             @if (count($depenses) !== 0)
        
             <h3>Tous les depenses de {{auth()->user()->name}}</h3>{{--co the viet $depense->user->name de di den function user() belongsTo trong Model Budget--}}
             <div class="alert alert-success" role="alert">
                Attention! Vous avez la somme des dépenses et des revenus dans ce mois ci comme en suite: 
                <ul>
                    <li>Total Dépenses : {{ $totalExpenses }}</li>
                    <li>Total Revenues: {{ $totalRevenues }}</li>
                </ul>
            </div>
             <table class="table">
                <thead>
                 <tr>
                   <th scope="col">No</th>
                   <th scope="col">Libelle</th>
                   <th scope="col">Montant</th>
                   <th scope="col">Nature</th>
                   <th scope="col">Fréquence</th>
                   <th scope="col">Actions</th>
                 </tr>
                </thead>
                <tbody>
                    @foreach($depenses as $depense)
                    <tr>
                        <th scope="row">{{$depense['id']}}</th>
                        <td>{{$depense['libelle']}}</td>
                        <td>{{$depense['montant']}}</td>
                        <td>{{$depense['nature']}}</td>
                        <td>{{$depense['frequence']}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="/detail-depense/{{$depense->id}}"><button class="btn btn-success">Edit</button></a>
                                </div>
                                <div class="col-md-4">
                                    <form action="/delete-depense/{{$depense->id}}" method="POST">
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
         <h3>Aucune dépense pour {{auth()->user()->name}} en ce moment!</h3>
         @endif
        </div>
         
    </div>

    <div style="border: 3px solid rgb(16, 16, 16); padding : 5px;">
        <div style="border: 3px solid rgb(113, 23, 250);padding : 10px;">
             <h2 style="color: rgb(113, 23, 250)">Créer un revenu</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
             <form action="/create-revenu" method="POST">
                 @csrf
                 <input type="text" name="libelle" placeholder="Libelle du revenu" required>
                 <input type="number" name="montant" placeholder="Montant du revenu" required>
                 <input type="text" name="nature" placeholder="Nature du revenu">
                 <input type="text" name="source" placeholder="Source du revenu" required>
                 <label for="frequence">Choisir la fréquence: </label>
                 <select name="frequence" id="frequence">
                     <option value="quotidien">Quotidien</option>
                     <option value="hebdomadaire">Hebdomadaire</option>
                     <option value="mensuel">Mensuel</option>
                     <option value="annuel">Annuel</option>
                     <option value="une fois">Une fois</option>
                 </select>
                 <label for="date_in">Date entrée dans le compte:</label>
                <input type="date" name= "date_in" id="date_in"/>
                <br>
                 <button type="submit" style ="margin: 5px;" class="btn btn-info">Sauvegarder</button>
             </form>
           
        
             @if (count($revenus) !== 0)
        
             <h3>Tous les revenus de {{auth()->user()->name}}</h3>
             <table class="table">
                <thead>
                 <tr>
                   <th scope="col">No</th>
                   <th scope="col">Libelle</th>
                   <th scope="col">Montant</th>
                   <th scope="col">Nature</th>
                   <th scope="col">Fréquence</th>
                   <th scope="col">Date entrée</th>
                   <th scope="col">Source</th>
                   <th scope="col">Actions</th>
                 </tr>
                </thead>
                <tbody>
                    @foreach($revenus as $revenu)
                    @php
                    $revenu->date_in = date('d-m-Y', strtotime($revenu->date_in));;   
                   @endphp
                    <tr>
                        <th scope="row">{{$revenu['id']}}</th>
                        <td>{{$revenu['libelle']}}</td>
                        <td>{{$revenu['montant']}}</td>
                        <td>{{$revenu['nature']}}</td>
                        <td>{{$revenu['frequence']}}</td>
                        <td>{{$revenu->date_in}}</td>
                        <td>{{$revenu['source']}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="/detail-revenu/{{$revenu->id}}"><button class="btn btn-success">Edit</button></a>
                                </div>
                                <div class="col-md-4">
                                    <form action="/delete-revenu/{{$revenu->id}}" method="POST">
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
         <h3>Aucun revenu pour {{auth()->user()->name}} en ce moment!</h3>
         @endif
        </div>
         
    </div>

    @else
    <div style="border: 3px solid blue; padding:10px;">
        <h2>S'incrire</h2>
        <form action="/register" method="POST">
            @csrf
            <input name = "name" type="text" placeholder="Nom">
            <input name="email" type="text" placeholder="Email">
            <input name="password" type="password" placeholder="Mot de passe">
            <button type="submit" class="btn btn-primary">S'incrire</button>
        </form>
    </div>
    <div style="border: 3px solid red; padding: 10px;">
        <h2>Se connecter</h2>
        <form action="/login" method="POST">
            @csrf
            <input name = "loginname" type="text" placeholder="Nom" value="{{ old('loginname') }}" required autocomplete="loginname" autofocus>
            <input name= "loginpassword" type="password" placeholder="Mot de passe">
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @endauth




   
</body>

{{-- Option 1: Bootstrap Bundle with Popper --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
{{--Sweet alert--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
{{--    Load compiled Javascript    --}}
<script src="{{ asset('js/app.js') }}"></script>
{{--   JQuery  --}}
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

<script type="text/javascript">
 
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
  
    @if (count($tachesForToday) >= 0)
        $(document).ready(function() {
            $('#exampleModal').modal('show');
        });
    @endif

    @if ($totalExpenses > $totalRevenues)
        $(document).ready(function() {
            $('#exampleModal1').modal('show');
        });
    @endif

</script>



</html>