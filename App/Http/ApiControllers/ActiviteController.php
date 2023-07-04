<?php

namespace App\Http\ApiControllers;

use App\Models\Activite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activites = Activite::query();

        if ($request->has('id')) {
            $activites->where('id', $request->id);
        }

        if ($request->has('libelle')) {
            $activites->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if ($request->has('seuil')) {
            $range = $request->seuil;
            $activites->whereBetween('seuil', [$range - 10, $range + 10]);
        }

        if ($request->has('date')) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            $activites->whereDate('date', 'LIKE', "%$date%");
        }

        if ($request->has('montant')) {
            $range = $request->montant;
            $activites->whereBetween('montant', [$range - 10, $range + 10]);
        }

        if ($request->has('budget_id')) {
            $activites->where('budget_id', $request->budget_id);
        }

        return $activites->get();
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
            'seuil' => ['required','min:1', 'gt:0'],
            'date' => ['required', 'date_format:d-m-Y'],
            'budget_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $input['libelle'] = strip_tags($request->libelle);
        $input['montant'] = floatval(strip_tags($request->montant));
        $input['seuil'] = floatval(strip_tags($request->seuil));
        $input['date'] = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d H:i:s');
        $input['budget_id'] = intval(strip_tags($request->budget_id));
        $newActivite = Activite::create($input);
        return $newActivite;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function show(Activite $activite)
    {
        return $activite;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activite $activite)
    {
        $rules = [];
        $input = $request->all();
        if (isset($input['libelle'])) {
            $rules['libelle'] = 'required';
            $input['libelle'] = strip_tags($request->libelle);
        }

        if (isset($input['seuil'])) {
            $rules['seuil'] = ['required', 'min:1', 'gt:0'];
            $input['seuil'] = floatval(strip_tags($request->seuil));
        }

        if (isset($input['montant'])) {
            $rules['montant'] = ['required', 'min:1', 'gt:0'];
            $input['montant'] = floatval(strip_tags($request->montant));
        }

        if (isset($input['date'])) {
            $rules['date'] = ['required', 'date_format:d-m-Y'];
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (isset($input['date'])) {
            $input['date'] = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d H:i:s');
        }
        $activite->update( $input );
        return $activite;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activite  $activite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activite $activite)
    {
        $row_deleted = Activite::findOrFail($activite->id)->delete();
        if($row_deleted == 1){
            return response()->json(['message' => "L'activité {$activite->libelle} a été effacée avec succès!"]);
        }
        return response()->json(['message' => "Impossible trouver l'activité {$activite->libelle} pour effacer!"]);
    }
}
