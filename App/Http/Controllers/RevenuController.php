<?php

namespace App\Http\Controllers;
use App\Models\Revenu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class RevenuController extends Controller
{
    public function deleteRevenu(Revenu $revenu){
        if(auth()->user()->id === $revenu['user_id']){
            Revenu::findOrFail($revenu->id)->delete();//built-in functions: update() delete() login() logout()...
        }
        return redirect('/');
    }
    public function createRevenu(Request $request) {

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
            $input['source'] = strip_tags($request->input('source'));
            $input['date_in'] = Carbon::createFromFormat('Y-m-d', $request->date_in)->format('Y-m-d H:i:s');
            $input['user_id'] = auth()->id();
            Revenu::create($input);
            return redirect('/');
        }
        
    }
    public function detailRevenu(Revenu $revenu) {
        if(auth()->user()->id !== $revenu['user_id']){
            return redirect('/');
        }
        return view('detailRevenu', ['revenu' => $revenu]);
    }

    public function updateRevenu(Revenu $revenu, Request $request) {
        if(auth()->user()->id !== $revenu['user_id']){
            return redirect('/')->withErrors(['updateInfo' => 'User is not allowed to update info']);
        }

        $input = $request->validate([
            'libelle' => 'required',
            'montant' => 'required',
        ]);
        $input['libelle'] = strip_tags($input['libelle']);
        $input['montant'] = floatval(strip_tags($input['montant']));
        $input['nature'] = strip_tags($request->input('nature'));
        $input['frequence'] = $request->input('frequence');
        $input['source'] = strip_tags($request->input('source'));
        $input['date_in'] = Carbon::createFromFormat('Y-m-d', $request->date_in)->format('Y-m-d H:i:s');
        $revenu->update($input);
        return redirect('/');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Revenu::all();
        //return response()->json( Revenu::all() );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // this create method calls save() behind the scenes
        $newRevenu = Revenu::create([
            'libelle' => $request->input('libelle'),
            'montant' => $request->input('montant'),
            'nature' => $request->input('nature'),
            'frequence' => $request->input('frequence'),
            'date_in' => $request->input('date_in'),
            'source' => $request->input('source'),
            //'user_id' => $request->input(),
        ]);
        return $newRevenu;
    }

    /**
     * Display the specified resource.
     *
     * @param  Revenu  $revenu
     * @return \Illuminate\Http\Response
     */
    public function show(Revenu $revenu)
    {
        $revenu->load(['user'],['revenu']);
        return $revenu;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Revenu  $revenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revenu $revenu)
    {
        $revenu->update( $request->input() );
        return $revenu;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Revenu  $revenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revenu $revenu)
    {
        $deleted = Revenu::find($revenu->id)->delete();
        if($deleted == 1){
            return `Le revenu avec l'id {$revenu->id} a été effacé avec succès!`;
        }else{ return `Impossible trouver le revenu l'id {$revenu->id} pour effacer!`;}
    }
}
