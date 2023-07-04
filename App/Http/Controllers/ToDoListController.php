<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class ToDoListController extends Controller
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
    public function createToDoList(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'libelle' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::to('/')->withErrors($validator);
        }else {
            $input['libelle'] = strip_tags($request->libelle);
            $input['date_creation'] = Carbon::createFromFormat('Y-m-d', $request->date_creation)->format('Y-m-d H:i:s');
            $input['budget_id'] = intval($request->budget_id);
            ToDoList::create($input);
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailToDoList( Request $request, $id=null)
    {   
        $tachesList = [];
        $method = $request->method();
        if ($request->isMethod('get')){
            $toDoList = ToDoList::findOrFail($request->input('toDoList'));
            $budget = Budget::findOrFail($toDoList['budget_id']);
            $tachesList = $toDoList->taches()->get();
            if(Auth::user()->id !== $budget->user_id){
                return redirect('/')
                    ->withErrors(['editInfo' => 'User is not allowed to edit info']);
            }
            return view('detailToDoList', ['toDoList' => $toDoList, 'taches' => $tachesList]);
        }
        if ($request->isMethod('post')){
            return view('detailToDoList', ['budget_id' => $id, 'budget_libelle' => $request->input('budget_libelle')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function updateToDoList(Request $request, ToDoList $toDoList)
    {
        $budget = Budget::findOrFail($toDoList['budget_id']);
        if(Auth::user()->id !== $budget->user_id){
            return redirect('/')
                ->withErrors(['updateInfo' => 'User is not allowed to update info']);
        }
        $input = $request->validate([
            'libelle' => 'required',
        ]);
        $input['libelle'] = strip_tags($input['libelle']);
        $input['date_creation'] = Carbon::createFromFormat('Y-m-d', $request->date_creation)->format('Y-m-d H:i:s');
        $toDoList->update($input);
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoList  $toDoList
     * @return \Illuminate\Http\Response
     */
    public function deleteToDoList(ToDoList $toDoList)
    {
        $budget = Budget::findOrFail($toDoList['budget_id']);
        if(Auth::user()->id === $budget->user_id){
            $toDoList->delete();
            return redirect('/');
        }else{
            return Redirect::to('/')->withErrors(['deleteInfo' => 'User is not allowed to delete info']);
        }
        
    }
}
