<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\ToDoList;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class TacheController extends Controller
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

    public function createTache(Request $request){
        $validator = Validator::make($request->all(),[
            'libelle' => 'required',
            'ordre' => ['required', 'min:1', 'gt:0'],
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }else {
            $input['libelle'] = strip_tags($request->libelle);
            $input['ordre'] = intval($request->ordre);
            $input['etat_fait'] = $request->etat_fait;
            $input['to_do_list_id'] = intval(strip_tags($request->to_do_list_id));
            Tache::create($input);
            return Redirect::back()
            ->withMessage('Task created!');
        }
    }

    public function detailTache(Tache $tache){
        $toDoList = ToDoList::findOrFail($tache->to_do_list_id);
        $budget = Budget::findOrFail($toDoList->budget_id);
        if(auth()->user()->id !== $budget['user_id']){
            return Redirect::back()
            ->withErrors(['message' => 'user has no right to view this task']);
        }
        return view('detailTache', ['tache' => $tache, 'toDoList' => $toDoList]);
    }

    public function updateTache(Request $request, Tache $tache) {
        $toDoList = ToDoList::findOrFail($tache->to_do_list_id);
        $budget = Budget::findOrFail($toDoList->budget_id);
        if(auth()->user()->id !== $budget['user_id']){
            return Redirect::back()
            ->withErrors(['message' => 'User has no right to update this task']);
        }
        $validator = Validator::make($request->all(),[
            'libelle' => 'required',
            'ordre' => ['required', 'min:1', 'gt:0'],
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $input['libelle'] = strip_tags($request->libelle);
        $input['ordre'] = intval($request->ordre);
        $input['etat_fait'] =intval($request->etat_fait);
        $input['to_do_list_id'] = $tache->to_do_list_id;
        $tache->update($input);
        $previousURL = $request->input('url');
        return redirect($previousURL)->with('success', 'Données sauvegardées avec succès');
        
    }

    public function deleteTache(Tache $tache) {
        $toDoList = ToDoList::findOrFail($tache->to_do_list_id);
        $budget = Budget::findOrFail($toDoList->budget_id);
        if(auth()->user()->id === $budget['user_id']){
            $tache->delete();
            return Redirect::back();
        } else {
            return Redirect::back()
            ->withErrors(['message' => 'user has no right to delete this task']);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $newTache = Tache::create([
            'libelle' => $request->input('libelle'),
            'etat_fait' => $request->input('etat_fait'),
            'ordre' => $request->input('ordre'),
        ]);
        $newTache->save();
        return $newTache;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function show(Tache $tache)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tache $tache)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tache $tache)
    {
        //
    }
}
