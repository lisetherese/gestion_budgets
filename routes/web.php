<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\RevenuController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\ToDoListController;
use App\Http\Controllers\TacheController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Login + authentification routes
Route::get('/', [UserController::class, "homePage"]);
Route::post('/register',[UserController::class, "register"]);
Route::post('/logout',[UserController::class, "logout"]);
Route::post('/login',[UserController::class, "login"]);

// Budget related routes
Route::post('/create-budget',[BudgetController::class, "createBudget"]);
Route::get('/detail-budget/{budget}',[BudgetController::class, "detailBudget"]); //to return new view(new page) to edit budget
Route::put('/edit-budget/{budget}',[BudgetController::class, "updateBudget"]);
Route::delete('/delete-budget/{budget}',[BudgetController::class, "deleteBudget"]);

// Depense related routes
Route::post('/create-depense',[DepenseController::class, "createDepense"]);
Route::get('/detail-depense/{depense}',[DepenseController::class, "detailDepense"]); //to return new view(new page) to edit depense
Route::put('/edit-depense/{depense}',[DepenseController::class, "updateDepense"]);
Route::delete('/delete-depense/{depense}',[DepenseController::class, "deleteDepense"]);

// Revenu related routes
Route::post('/create-revenu',[RevenuController::class, "createRevenu"]);
Route::get('/detail-revenu/{revenu}',[RevenuController::class, "detailRevenu"]); //to return new view(new page) to edit revenu
Route::put('/edit-revenu/{revenu}',[RevenuController::class, "updateRevenu"]);
Route::delete('/delete-revenu/{revenu}',[RevenuController::class, "deleteRevenu"]);

// Activite related routes
Route::post('/create-activite',[ActiviteController::class, "createActivite"]);
Route::match(array('GET','POST'), '/detail-activite/{id?}', [ActiviteController::class, "detailActivite"]);
Route::put('/edit-activite/{activite}',[ActiviteController::class, "updateActivite"]);
Route::delete('/delete-activite/{activite}',[ActiviteController::class, "deleteActivite"]);

// To-Do-List related routes
Route::post('/create-toDoList',[ToDoListController::class, "createToDoList"]);
Route::match(array('GET','POST'), '/detail-toDoList/{id?}', [ToDoListController::class, "detailToDoList"]);
Route::put('/edit-toDoList/{toDoList}',[ToDoListController::class, "updateToDoList"]);
Route::delete('/delete-toDoList/{toDoList}',[ToDoListController::class, "deleteToDoList"]);

// Tache related routes
Route::post('/create-tache',[TacheController::class, "createTache"]);
Route::get('/detail-tache/{tache}', [TacheController::class, "detailTache"]);
Route::put('/edit-tache/{tache}',[TacheController::class, "updateTache"]);
Route::delete('/delete-tache/{tache}',[TacheController::class, "deleteTache"]);

// User related routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

// Budget related routes
Route::get('/budgets', [BudgetController::class, 'index']);
Route::get('/budgets/{budget}', [BudgetController::class, 'show']);
Route::post('/budgets', [BudgetController::class, 'store']);
Route::put('/budgets/{budget}', [BudgetController::class, 'update']);
Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy']);
