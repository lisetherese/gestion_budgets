<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoList extends Model
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
        'date_creation',
        'budget_id',
    ];

    public function budget(){
        return $this->belongsTo(Budget::class, 'id'); //define an inverse one-to-one or many relationship
    }
    public function taches(){
        return $this->hasMany(Tache::class, 'to_do_list_id');
    }

    public function delete() {
        $this->taches()->delete();
        $row_deleted = parent::delete();
        if($row_deleted == 1){
            return true;
        }
        return false;
    }

}
