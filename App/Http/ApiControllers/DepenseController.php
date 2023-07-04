<?php

namespace App\Http\ApiControllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class DepenseController extends Controller
{   

    public function deleteDepense(Depense $depense){
        if(auth()->user()->id === $depense['user_id']){
            $depense->delete();//built-in functions: update() delete() login() logout()...
            //Depense::find(1)->delete(); another way to delete using find object
        }
        return redirect('/');
    }
    public function createDepense(Request $request) {
        // $input = $request->validate([
        //     'libelle' => 'required',
        //     'montant' => ['required','min:1'],
        // ]);

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
            Depense::create($input);
            return redirect('/');
        }
        
    }
    public function editDepense(Depense $depense) {
        if(auth()->user()->id !== $depense['user_id']){
            return redirect('/');
        }
        return view('editDepense', ['depense' => $depense]);
    }

    public function updateDepense(Depense $depense, Request $request) {
        if(auth()->user()->id !== $depense['user_id']){
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
        $depense->update($input);
        return redirect('/');
    }
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $depenses = Depense::query();

        if ($request->has('id')) {
            $depenses->where('id', $request->id);
        }

        if ($request->has('libelle')) {
            $depenses->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        if ($request->has('nature')) {
            $depenses->where('nature', 'like', '%' . $request->nature . '%');
        }

        if ($request->has('frequence')) {
            $depenses->where('frequence', 'like', '%' . $request->frequence . '%');
        }

        if ($request->has('montant')) {
            $range = $request->montant;
            $depenses->whereBetween('montant', [$range - 50, $range + 50]);
        }

        if ($request->has('user_id')) {
            $depenses->where('user_id', $request->user_id);
        }

        return $depenses->get();
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
        $newDepense = Depense::create($input);
        return $newDepense;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function show(Depense $depense)
    {
        return $depense;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Depense $depense)
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
        $depense->update( $request->input() );
        return $depense;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depense $depense)
    {
        $row_deleted = Depense::findOrFail($depense->id)->delete();
        if($row_deleted == 1){
            return response()->json(['message' => "La dépense {$depense->libelle} a été effacée avec succès!"]);
        }
        return response()->json(['message' => "Impossible trouver la dépense {$depense->libelle} pour effacer!"]);
    }
}
