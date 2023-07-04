// Tache related routes
Route::post('/create-tache',[TacheController::class, "createTache"]);
Route::match(array('GET','POST'), '/detail-tache/{id?}', [TacheController::class, "detailTache"]);
Route::put('/edit-tache/{tache}',[TacheController::class, "updateTache"]);
Route::delete('/delete-tache/{tache}',[TacheController::class, "deleteTache"]);
