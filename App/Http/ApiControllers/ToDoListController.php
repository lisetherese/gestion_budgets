<?php

namespace App\Http\ApiControllers;

use App\Models\ToDoList;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ToDoListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $toDoLists = ToDoList::query();

        if ($request->has('id')) {
            $toDoLists->where('id', $request->id);
        }

        if ($request->has('libelle')) {
            $toDoLists->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if ($request->has('date_creation')) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date_creation)->format('Y-m-d');
            $toDoLists->whereDate('date_creation', 'LIKE', "%$date%");
        }

        if ($request->has('budget_id')) {
            $toDoLists->where('budget_id', $request->budget_id);
        }

        return $toDoLists->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'libelle' => 'required',
            'date_creation' => ['required', 'date_format:d-m-Y'],
            'budget_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $input['libelle'] = strip_tags($request->libelle);
        $input['date_creation'] = Carbon::createFromFormat('d-m-Y', $request->date_creation)->format('Y-m-d H:i:s');
        $input['budget_id'] = intval(strip_tags($request->budget_id));
        $newToDoList = ToDoList::create($input);
        return $newToDoList;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function show(ToDoList $toDoList)
    {
        return $toDoList->load('taches');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ToDoList $toDoList)
    {
        $rules = [];
        $input = $request->all();
        if (isset($input['libelle'])) {
            $rules['libelle'] = 'required';
            $input['libelle'] = strip_tags($request->libelle);
        }


        if (isset($input['date_creation'])) {
            $rules['date_creation'] = ['required', 'date_format:d-m-Y'];
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (isset($input['date_creation'])) {
            $input['date_creation'] = Carbon::createFromFormat('d-m-Y', $request->date_creation)->format('Y-m-d H:i:s');
        }
        $toDoList->update( $input );
        return $toDoList;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ToDoList $toDoList)
    {
        $objectToDelete = ToDoList::findOrFail($toDoList->id);
        $nombreTaches = 0;
        $namesAllTaches = "";
        foreach ($objectToDelete->taches()->cursor() as $tache) {
            $namesAllTaches = $namesAllTaches.$tache->libelle.', ';
            $tache->delete();
            $nombreTaches += 1;
        }
        $isdeleted = $objectToDelete->delete();
        if($isdeleted == true){
            return response()->json(['message' => "Le to-do-list '{$toDoList->libelle}' ainsi que ses {$nombreTaches} tâche(s) {$namesAllTaches}ont été effacés avec succès!"]);
        }
        return response()->json(['message' => "Impossible trouver le to-do-list {$toDoList->libelle} pour effacer!"]);
    }
}
