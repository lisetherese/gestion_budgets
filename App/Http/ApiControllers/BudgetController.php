<?php

namespace App\Http\ApiControllers;

use App\Models\Budget;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class BudgetController extends Controller
{   
    
    /* public function __construct()
    {
        $this->middleware('auth');
    } */
    public function deleteBudget(Budget $budget){
        if(Auth::user()->id === $budget['user_id']){
            $budget->delete();//built-in functions: update() delete()...
        }
        return redirect('/');
    }
    public function createBudget(Request $request) {
        $validator = Validator::make($request->all(),[
            'libelle' => 'required',
            'montant' => ['required','min:1', 'gt:0'],
        ]);

        if ($validator->fails()) {
            return Redirect::to('/')->withErrors($validator);
        }else {
            $input['libelle'] = strip_tags($request->input('libelle'));
            $input['montant'] = floatval(strip_tags($request->input('montant')));
            $input['nature'] = strip_tags($request->input('nature'));
            $input['frequence'] = $request->input('frequence');
            $input['user_id'] = auth()->id();
            Budget::create($input);
            return redirect('/');
        }
    }
    public function editBudget(Budget $budget) {
        if(Auth::user()->id !== $budget['user_id']){
            return redirect('/');
        }
        return view('editBudget', ['budget' => $budget]);
    }

    public function updateBudget(Budget $budget, Request $request) {
        if(Auth::user()->id !== $budget['user_id']){
            return redirect('/');
        }

        $input = $request->validate([
            'libelle' => 'required',
            'montant' => 'required',
        ]);
        $input['libelle'] = strip_tags($input['libelle']);
        $input['montant'] = floatval(strip_tags($input['montant']));
        $input['nature'] = strip_tags($request->input('nature'));
        $input['frequence'] = $request->input('frequence');
        $budget->update($input);
        return redirect('/');
    }
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $budgets = Budget::query();

        if ($request->has('id')) {
            $budgets->where('id', $request->id);
        }

        if ($request->has('libelle')) {
            $budgets->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if ($request->has('nature')) {
            $budgets->where('nature', 'like', '%' . $request->nature . '%');
        }

        if ($request->has('frequence')) {
            $budgets->where('frequence', 'like', '%' . $request->frequence . '%');
        }

        if ($request->has('montant')) {
            $range = $request->montant;
            $budgets->whereBetween('montant', [$range - 50, $range + 50]);
        }

        if ($request->has('user_id')) {
            $budgets->where('user_id', $request->user_id);
        }

        return $budgets->get();
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
            'montant' => ['required','min:1', 'gt:0'],
            'nature' => 'required',
            'frequence' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $input['libelle'] = strip_tags($request->libelle);
        $input['montant'] = floatval(strip_tags($request->montant));
        $input['nature'] = strip_tags($request->nature);
        $input['frequence'] = strip_tags($request->frequence);
        $input['user_id'] = intval(strip_tags($request->user_id));
        $newBudget = Budget::create($input);
        return $newBudget;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        return $budget->load(['activites', 'toDoLists', 'toDoLists.taches']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Budget $budget)
    {
        $rules = [];
        $input = $request->all();
        if (isset($input['libelle'])) {
            $rules['libelle'] = 'required';
        }

        if (isset($input['nature'])) {
            $rules['nature'] = 'required';
        }

        if (isset($input['frequence'])) {
            $rules['frequence'] = 'required';
        }

        if (isset($input['montant'])) {
            $rules['montant'] = ['required', 'min:1', 'gt:0'];
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $budget->update( $request->input() );
        return $budget;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget)
    {
        $object_to_deleted = Budget::findOrFail($budget->id);
        $jsonData = $object_to_deleted->load(['activites', 'toDoLists', 'toDoLists.taches']);
        $isdeleted = $object_to_deleted->delete();
        if($isdeleted == true){
            //return response()->json(['message' => "Le budget {$budget->libelle} avec ses {$nbrActivites} activité(s) {$namesAllActivites}ont été effacés avec succès!. ".(count($object_to_deleted->toDoList()->get())?"Ce budget a un to-do-list nommé {$nameToDoList} qui a {$nombreTaches} tâche(s) {$namesAllTaches}. Ils sont tous supprimés aussi.":"")]);
            return response()->json([
                "message" => "Le budget {$budget->libelle} avec ses relations ont été effacés avec succès dans la base de données!",
                "relations" => $jsonData
            ]);
        }
        return response()->json(['message' => "Impossible trouver le budget {$budget->libelle} pour effacer!"]);
        
    }
}
