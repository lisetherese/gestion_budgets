<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
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
        'seuil',
        'date',
        'montant',
        'budget_id',
    ];

    public function budget(){
        return $this->belongsTo(Budget::class, 'id'); //define an inverse one-to-one or many relationship
    }
}
