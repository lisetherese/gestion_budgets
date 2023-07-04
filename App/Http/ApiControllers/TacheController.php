<?php

namespace App\Http\ApiControllers;

use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TacheController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $taches = Tache::query();

        if ($request->has('id')) {
            $taches->where('id', $request->id);
        }

        if ($request->has('libelle')) {
            $taches->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if ($request->has('etat_fait')) {
            $taches->where('etat_fait', filter_var($request->etat_fait, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('ordre')) {
            $taches->where('ordre', intval($request->ordre));
        }

        if ($request->has('to_do_list_id')) {
            $taches->where('to_do_list_id', intval($request->to_do_list_id));
        }

        return $taches->get();
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
            'ordre' => 'required',
            'etat_fait' => 'required',
            'to_do_list_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $input['libelle'] = strip_tags($request->libelle);
        $input['ordre'] = intval(strip_tags($request->ordre));
        $input['etat_fait'] = filter_var(strip_tags($request->etat_fait), FILTER_VALIDATE_BOOLEAN);
        $input['to_do_list_id'] = intval(strip_tags($request->to_do_list_id));
        $newTache = Tache::create($input);
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
        return $tache;
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
        $rules = [];
        $input = $request->all();
        if (isset($input['libelle'])) {
            $rules['libelle'] = 'required';
            $input['libelle'] = strip_tags($request->libelle);
        }

        if (isset($input['etat_fait'])) {
            $rules['etat_fait'] = 'required';
        }

        if (isset($input['ordre'])) {
            $rules['ordre'] = 'required';
            $input['ordre'] = intval(strip_tags($request->ordre));
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (isset($input['etat_fait'])) {
            $input['etat_fait'] = filter_var(strip_tags($request->etat_fait), FILTER_VALIDATE_BOOLEAN);
        }

        $tache->update( $input );
        return $tache;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tache $tache)
    {
        $row_deleted = Tache::findOrFail($tache->id)->delete();
        if($row_deleted == 1){
            return response()->json(['message' => "La tâche {$tache->libelle} a été effacée avec succès!"]);
        }
        return response()->json(['message' => "Impossible trouver la tâche {$tache->libelle} pour effacer!"]);
    }
}
