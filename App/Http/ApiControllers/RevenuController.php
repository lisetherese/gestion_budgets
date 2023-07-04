<?php

namespace App\Http\ApiControllers;
use App\Models\Revenu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class RevenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $revenus = Revenu::query();

        if ($request->has('id')) {
            $revenus->where('id', $request->id);
        }

        if ($request->has('libelle')) {
            $revenus->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if ($request->has('nature')) {
            $revenus->where('nature', 'like', '%' . $request->nature . '%');
        }

        if ($request->has('frequence')) {
            $revenus->where('frequence', 'like', '%' . $request->frequence . '%');
        }

        if ($request->has('montant')) {
            $range = $request->montant;
            $revenus->whereBetween('montant', [$range - 50, $range + 50]);
        }

        if ($request->has('source')) {
            $revenus->where('source', 'like', '%' . $request->source . '%');
        }

        if ($request->has('date_in')) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date_in)->format('Y-m-d');
            $revenus->whereDate('date_in', 'LIKE', "%$date%");
        }

        if ($request->has('user_id')) {
            $revenus->where('user_id', $request->user_id);
        }

        return $revenus->get();
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
            'source' => 'required',
            'date_in' => ['required', 'date_format:d-m-Y'],
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $input['libelle'] = strip_tags($request->libelle);
        $input['montant'] = floatval(strip_tags($request->montant));
        $input['nature'] = strip_tags($request->nature);
        $input['frequence'] = strip_tags($request->frequence);
        $input['source'] = strip_tags($request->source);
        $input['date_in'] = Carbon::createFromFormat('d-m-Y', $request->date_in)->format('Y-m-d H:i:s');
        $input['user_id'] = intval(strip_tags($request->user_id));
        $newRevenu = Revenu::create($input);
        return $newRevenu;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Revenu  $revenu
     * @return \Illuminate\Http\Response
     */
    public function show(Revenu $revenu)
    {
        return $revenu;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Revenu  $revenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revenu $revenu)
    {
        $rules = [];
        $input = $request->all();
        if (isset($input['libelle'])) {
            $rules['libelle'] = 'required';
            $input['libelle'] = strip_tags($request->libelle);
        }

        if (isset($input['nature'])) {
            $rules['nature'] = 'required';
            $input['nature'] = strip_tags($request->nature);
        }

        if (isset($input['frequence'])) {
            $rules['frequence'] = 'required';
            $input['frequence'] = strip_tags($request->frequence);
        }

        if (isset($input['montant'])) {
            $rules['montant'] = ['required', 'min:1', 'gt:0'];
            $input['montant'] = floatval(strip_tags($request->montant));
        }

        if (isset($input['source'])) {
            $rules['source'] = 'required';
            $input['source'] = strip_tags($request->source);
        }

        if (isset($input['date_in'])) {
            $rules['date_in'] = ['required', 'date_format:d-m-Y'];
        }

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (isset($input['date_in'])) {
            $input['date_in'] = Carbon::createFromFormat('d-m-Y', $request->date_in)->format('Y-m-d H:i:s');
        }
        $revenu->update( $input );
        return $revenu;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revenu $revenu)
    {
        $row_deleted = Revenu::findOrFail($revenu->id)->delete();
        if($row_deleted == 1){
            return response()->json(['message' => "Le revenu {$revenu->libelle} a été effacé avec succès!"]);
        }
        return response()->json(['message' => "Impossible trouver le revenu {$revenu->libelle} pour effacer!"]);
    }
}
