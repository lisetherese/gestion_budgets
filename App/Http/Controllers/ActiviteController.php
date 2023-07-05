<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createActivite(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'libelle' => 'required',
            'seuil' => ['required','min:1', 'gt:0'],
            'montant' => ['required','min:1', 'gt:0'],
        ]);

        if ($validator->fails()) {
            return Redirect::to('/')->withErrors($validator);
        }else {
            $input['libelle'] = strip_tags($request->libelle);
            $input['montant'] = floatval(strip_tags($request->montant));
            $input['seuil'] = floatval(strip_tags($request->seuil));
            $input['date'] = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d H:i:s');
            $input['budget_id'] = intval($request->budget_id);
            Activite::create($input);
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailActivite( Request $request, $id=null)
    {   
        $method = $request->method();
        if ($request->isMethod('get')){
            $activite = Activite::findOrFail($request->input('activite'));
            $budget = Budget::findOrFail($activite['budget_id']);
            $activites = $budget->activites;
            $sumAllActivites = $activites->sum('montant');
            if(Auth::user()->id !== $budget->user_id){
                return redirect('/')
                    ->withErrors(['editInfo' => 'User is not allowed to edit info']);
            }
            return view('detailActivite', ['activite' => $activite, 'sumAllActivites' => $sumAllActivites, 'budget_montant' => $budget->montant]);
        }
        if ($request->isMethod('post')){
            $budget = Budget::findOrFail($id);
            $activites = $budget->activites;
            $sumAllActivites = $activites->sum('montant');
            return view('detailActivite', ['budget_id' => $id, 'budget_libelle' => $request->input('budget_libelle'), 'sumAllActivites' => $sumAllActivites, 'budget_montant' => $budget->montant]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function updateActivite(Request $request, Activite $activite)
    {
        $budget = Budget::findOrFail($activite['budget_id']);
        if(Auth::user()->id !== $budget->user_id){
            return redirect('/')
                ->withErrors(['message' => 'User is not allowed to update info']);
        }
        $input = $request->validate([
            'libelle' => 'required',
            'seuil' => ['required','min:1', 'gt:0'],
            'montant' => ['required','min:1', 'gt:0'],
        ]);
        $input['libelle'] = strip_tags($input['libelle']);
        $input['montant'] = floatval(strip_tags($input['montant']));
        $input['seuil'] = floatval(strip_tags($input['seuil']));
        $input['date'] = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d H:i:s');
        $activite->update($input);
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function deleteActivite(Activite $activite)
    {
        $budget = Budget::findOrFail($activite['budget_id']);
        if(Auth::user()->id === $budget->user_id){
            $activite->delete();
            return redirect('/');
        }else{
            return Redirect::to('/')->withErrors(['deleteInfo' => 'User is not allowed to delete info']);
        }
        
    }
}
