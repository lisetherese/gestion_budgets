<?php

namespace App\Http\Controllers;

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
    public function detailDepense(Depense $depense) {
        if(auth()->user()->id !== $depense['user_id']){
            return redirect('/');
        }
        return view('detailDepense', ['depense' => $depense]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function show(Depense $depense)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depense $depense)
    {
        //
    }
}
