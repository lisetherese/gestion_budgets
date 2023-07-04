<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Budget;
use App\Models\Revenu;
use App\Models\Depense;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api-token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast. Used on sending email to verify email only
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function revenus(){
        return $this->hasMany(Revenu::class, 'user_id');
    }
    public function depenses(){
        return $this->hasMany(Depense::class, 'user_id');
    }
    
    public function budgets(){
        return $this->hasMany(Budget::class, 'user_id');
    }

    public function delete() {
        $this->depenses()->delete();
        $this->revenus()->delete();
        $this->budgets()->delete();
        // call the delete() method of the parent class to delete the model instance itself from the database.
        $row_deleted = parent::delete();
        if ($row_deleted == 1){
            return true;
        }else{
            return false;
        }

    }
}
