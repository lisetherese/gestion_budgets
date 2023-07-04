<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Tache;
use App\Models\ToDoList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller {
    
    public function homePage(){
        $budgets = [];
        $depenses = [];
        $revenus = [];
        $toDoListsDone = [];
        $tachesForToday = [];
    
        if(Auth::check()){
            $user = User::findOrFail(Auth::user()->id);
            $budgets = $user->budgets; //budgets() returns a relationship to multiple Budget rows, not a single instance!!! then use ->get() to convert into collection
            $depenses = $user->depenses;
            $revenus = $user->revenus;

            /*$tachesForToday =  Tache::where('etat_fait', 0)
            ->whereHas('toDoList', function ($query) {
                $query->whereDate('date_creation', today()->format('Y-m-d'))
                      ->whereHas('budget', function ($query) {
                          $query->where('user_id', Auth::id());
                      });
            })->get();*/
            $tachesForToday = DB::table('taches')
            ->join('to_do_lists', 'taches.to_do_list_id', '=', 'to_do_lists.id')
            ->join('budgets', 'budgets.id', '=', 'to_do_lists.budget_id')
            ->join('users', 'users.id', '=', 'budgets.user_id')
            ->whereDate('to_do_lists.date_creation', today()->format('Y-m-d'))
            ->where('users.id', '=', $user->id)
            ->where('taches.etat_fait', '=', 0)
            ->select('taches.*')
            ->distinct()
            ->get();

            $toDoListsDone = DB::table('to_do_lists')
            ->join('taches', 'to_do_lists.id', '=', 'taches.to_do_list_id')
            ->join('budgets', 'budgets.id', '=', 'to_do_lists.budget_id')
            ->join('users', 'users.id', '=', 'budgets.user_id')
            ->where('taches.etat_fait', '=', 1)
            ->where('users.id', '=', $user->id)
            ->select('to_do_lists.*')
            ->distinct()
            ->get();
            
        } 

        //$budgets = Budget::where('user_id', auth()->id())->get();
        return view('home', ['budgets' => $budgets, 'depenses' => $depenses, 'revenus' => $revenus, 'tachesForToday' => $tachesForToday, 'toDoListsDone' => $toDoListsDone]);
    }

    public function login(Request $request){
        $input = $request->validate([
            'loginname' => 'required|max:15',
            'loginpassword' => 'required',
        ]);
        /*if (auth()->attempt(['name' => $input['loginname'], 'password' =>$input['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/');
        }*/
        if (Auth::attempt(['name' => $input['loginname'], 'password' => $input['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/');
        }
        
        //auth()->login();
        // return redirect('/');
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput(); // or using ->withInput() de show all input khi pas réussit
    }

    public function logout(){
        //auth()->logout();
        Auth::logout();
        return redirect('/');
    }

    public function register(Request $request){
        $inputfields = $request->validate([
            'name' => ['required', 'min:3', 'max:15'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:4', 'max:15']
        ]);
        //$inputfields['password'] = hash('sha256', $inputfields['password']);
        $inputfields['password'] = bcrypt($inputfields['password']);
        $inputfields['api-token'] = Str::random(60);
        $newUser = User::create($inputfields);
        auth()->login($newUser);
        return redirect('/');
    }

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

        $validator = Validator::make($request->all(),[
            'name' => ['required', 'min:3', 'max:15'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:4', 'max:15']
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash('sha256', $request->password),
            'api-token' => Str::random(60),
        ]);

        return $newUser;
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function show( User $user ) {
        //$user->profile (day la cach pointer vao attribut cua 1 class)
        //$user->revenu() => goi command get() trong function propre of model User de lay relations voi cac tables
        //return response()->json( User::where('id', $id) );
        //return $user;
       // $user->load(['depense'],['revenu'],['budget']); //for recuperer relation between tables and send back return $user!
        return $user;
        /* return $user->profile()->where()->save(new Profile([
            'avatar' => 'test',
            'user_id' => 3,
        ])); */ //to use a function defined in User model, it will call the whole profile and save this table as a part of this model in database
        // no goi function profile de lay cai relation bang get(), roi chon loc voi where, roi save trong database
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
        $user->update( $request->input() );
        return $user;
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */

    public function destroy( User $user ) {
        //
       $deleted = User::findOrFail($user->id)->delete();
        //User::destroy($id);
        if($deleted == 1){
            return response()->json(['message' => "L'utisateur {$user->name} a été effacé avec succès!"]);
        }
        return response()->json(['message' => "Impossible trouver l'utilisateur {$user->name} pour effacer!"]);
        
    }
}