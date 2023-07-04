<?php

namespace App\Http\Controllers;

use App\Models\Budget;
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
            'nature' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::to('/')->withErrors($validator);
        }else {
            $input['libelle'] = strip_tags($request->libelle);
            $input['montant'] = floatval(strip_tags($request->montant));
            $input['nature'] = strip_tags($request->nature);
            $input['frequence'] = $request->frequence;
            $input['user_id'] = auth()->id();
            Budget::create($input);
            return redirect('/');
        }
    }
    public function detailBudget(Budget $budget) {
        if(Auth::user()->id !== $budget['user_id']){
            return redirect('/')
                ->withErrors(['editInfo' => 'User is not allowed to edit info']);
        }
        return view('detailBudget', ['budget' => $budget]);
    }

    public function updateBudget(Budget $budget, Request $request) {
        if(Auth::user()->id !== $budget['user_id']){
            return redirect('/')
                ->withErrors(['updateInfo' => 'User is not allowed to update info']);
        }
        $input = $request->validate([
            'libelle' => 'required',
            'montant' => ['required','min:1', 'gt:0'],
            'nature' => 'required',
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
            return ['message' => `Input values are not correct. Validation failed!`];
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
        return $budget;
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
        $row_deleted = Budget::findOrFail($budget->id)->delete();
        if($row_deleted == 1){
            return response()->json(['message' => `Le budget {$budget->libelle} a été effacé avec succès!`]);
        }
        return response()->json(['message' => `Impossible trouver le budget {$budget->libelle} pour effacer!`]);
        
    }
}
