<?php

use Illuminate\Support\Facades\Route;
use App\Http\ApiControllers\UserController;
use App\Http\ApiControllers\RevenuController;
use App\Http\ApiControllers\DepenseController;
use App\Http\ApiControllers\BudgetController;
use App\Http\ApiControllers\ActiviteController;
use App\Http\ApiControllers\TacheController;
use App\Http\ApiControllers\ToDoListController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

/*Route::get('/users', function() {
	// Le retour de notre requête http
	return "afficher les utilisateurs";
});*/
/* 'prefix' => 'api',*/
// Route::group(['middleware' => 'auth:api'], function() {

	// Login + authentification routes
	// Route::get('/', [UserController::class, "homePage"]);
	// Route::post('/register',[UserController::class, "register"]);
	// Route::post('/logout',[UserController::class, "logout"]);
	// Route::post('/login',[UserController::class, "login"]);

	// // Budget related routes
	// Route::post('/create-budget',[BudgetController::class, "createBudget"]);
	// Route::get('/edit-budget/{budget}',[BudgetController::class, "editBudget"]); //to return new view(new page) to edit budget
	// Route::put('/edit-budget/{budget}',[BudgetController::class, "updateBudget"]);
	// Route::delete('/delete-budget/{budget}',[BudgetController::class, "deleteBudget"]);

	// // Depense related routes
	// Route::post('/create-depense',[DepenseController::class, "createDepense"]);
	// Route::get('/edit-depense/{depense}',[DepenseController::class, "editDepense"]); //to return new view(new page) to edit depense
	// Route::put('/edit-depense/{depense}',[DepenseController::class, "updateDepense"]);
	// Route::delete('/delete-depense/{depense}',[DepenseController::class, "deleteDepense"]);

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

	// Depense related routes
	Route::get('/depenses', [DepenseController::class, 'index']);
	Route::get('/depenses/{depense}', [DepenseController::class, 'show']);
	Route::post('/depenses', [DepenseController::class, 'store']);
	Route::put('/depenses/{depense}', [DepenseController::class, 'update']);
	Route::delete('/depenses/{depense}', [DepenseController::class, 'destroy']);

	// Revenu related routes
	Route::get('/revenus', [RevenuController::class, 'index']);
	Route::get('/revenus/{revenu}', [RevenuController::class, 'show']);
	Route::post('/revenus', [RevenuController::class, 'store']);
	Route::put('/revenus/{revenu}', [RevenuController::class, 'update']);
	Route::delete('/revenus/{revenu}', [RevenuController::class, 'destroy']);

	// Activite related routes
	Route::get('/activites', [ActiviteController::class, 'index']);
	Route::get('/activites/{activite}', [ActiviteController::class, 'show']);
	Route::post('/activites', [ActiviteController::class, 'store']);
	Route::put('/activites/{activite}', [ActiviteController::class, 'update']);
	Route::delete('/activites/{activite}', [ActiviteController::class, 'destroy']);

	// Tache related routes
	Route::get('/taches', [TacheController::class, 'index']);
	Route::get('/taches/{tache}', [TacheController::class, 'show']);
	Route::post('/taches', [TacheController::class, 'store']);
	Route::put('/taches/{tache}', [TacheController::class, 'update']);
	Route::delete('/taches/{tache}', [TacheController::class, 'destroy']);

	// To_do_list related routes
	Route::get('/toDoLists', [ToDoListController::class, 'index']);
	Route::get('/toDoLists/{toDoList}', [ToDoListController::class, 'show']);
	Route::post('/toDoLists', [ToDoListController::class, 'store']);
	Route::put('/toDoLists/{toDoList}', [ToDoListController::class, 'update']);
	Route::delete('/toDoLists/{toDoList}', [ToDoListController::class, 'destroy']);
// });