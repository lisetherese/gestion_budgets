<?php

namespace App\Http\ApiControllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    
    public function homePage(){
        $budgets = [];
        $depenses = [];
    
        if(Auth::check()){
            $user = User::findOrFail(Auth::user()->id);
            $budgets = $user->budgets()->get(); //budgets() returns a relationship to multiple Budget rows, not a single instance!!! then use ->get() to convert into collection
            $depenses = $user->depenses()->get();
        }
        //$budgets = Budget::where('user_id', auth()->id())->get();
        return view('home', ['budgets' => $budgets, 'depenses' => $depenses]);
    }

    public function login(Request $request){
        $input = $request->validate([
            'loginname' => 'required|max:15',
            'loginpassword' => 'required',
        ], [
            'max' => 'The :attribute field must not exceed :max characters.',
        ]);

        if (Auth::attempt(['name' => $input['loginname'], 'password' => $input['loginpassword']])) {
            // $request->session()->regenerate();
            return redirect('/');
        }
        // auth()->login();
        // return redirect('/');
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput(); // or using ->withInput() to show all input vaalues of user when not succeeded
        // then we can display this validation error in 'view'
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }

    public function register(Request $request){
        $inputfields = Validator::make($request->all(),[
            'name' => ['required', 'min:3', 'max:15'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:4', 'max:15']
        ]);
        if ($inputfields->fails()) {
            return ['message' => `Input values are not correct. Validation failed!`];
        }
        $inputfields['password'] = hash('sha256', $inputfields['password']);
        $inputfields['api-token'] = Str::random(60);
        $newUser = User::create($inputfields);
        $newUser->save();
        auth()->login($newUser);
        return redirect('/');
    }

    
    /*public function index( Request $request ) {
        //return response()->json( User::all() );
        return User::all();
        //return User::with(['profile'])->get(); //->get() to get all columns of table Profile associated with User or get('name') only for column 'name'
    }*/

    /**
    * Display a listing of the resource.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function index(Request $request)
    {
        $users = User::query();

        if ($request->has('id')) {
            $users->where('id', $request->id);
        }

        if ($request->has('name')) {
            $users->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('email')) {
            $users->where('email', 'like', '%' . $request->email . '%');
        }

        return $users->get();
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request ) {

        $inputfields = $request->validate([
            'name' => ['required', 'min:3', 'max:15'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:4', 'max:15']
        ]);
        $inputfields['password'] = hash('sha256', $inputfields['password']);
        $inputfields['api-token'] = Str::random(60);
        $newUser = User::create($inputfields);
        $newUser->save();

        return $newUser;

    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show( User $user ) {
       
        return $user->load(['depenses', 'revenus', 'budgets', 'budgets.activites', 'budgets.toDoLists', 'budgets.toDoLists.taches']);
       
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, User $user ) {
        $rules = [];
        $input = $request->all();
        if (isset($input['name'])) {
            $rules['name'] = ['required', 'min:3', 'max:15'];
        }
    
        if (isset($input['email'])) {
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)];
        }
    
        if (isset($input['password'])) {
            $rules['password'] = ['required', 'min:4', 'max:15'];
        }
    
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user->update( $request->all() );
        return $user;
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function destroy( User $user ) {
        $user_to_delete = User::findOrFail($user->id);
        $json_info_user = $user_to_delete->load(['depenses', 'revenus', 'budgets', 'budgets.activites', 'budgets.toDoLists', 'budgets.toDoLists.taches']);
        // returns boolean value as the result of the delete operation
        $deleted = $user_to_delete->delete();
        //User::destroy($id);
        if($deleted == true){
            return response()->json([
                'message' => "L'utisateur {$user->name} a été effacé avec succès!",
                "relations" => $json_info_user
            ]);
        }else{
            return response()->json(['message' => "Impossible trouver l'utilisateur {$user->name} pour effacer!"]);
        }
    }
}