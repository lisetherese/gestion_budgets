<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, mixed>
     */

     // with fillable attributs, we can use the create method to insert a new record in the database (mass assignment without changing structure of table is more columns added than required)
     protected $fillable = [
        'libelle',
        'montant',
        'nature',
        'frequence',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id'); //define an inverse one-to-one or many relationship
    }
    public function activites(){
        return $this->hasMany(Activite::class, 'budget_id');
    }
    public function toDoLists(){
        return $this->hasMany(ToDoList::class, 'budget_id');
    }

    public function delete() {
        $this->activites()->delete();
        $this->toDoLists()->delete();
        $row_deleted = parent::delete();
        if($row_deleted == 1){
            return true;
        }
        return false;
    }
}
