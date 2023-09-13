<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'telephone', 'age', 'password'
    ];

// /**
//      * Get the identifier that will be stored in the subject claim of the JWT.
//      *
//      * @return mixed
//      */
//     public function getJWTIdentifier()
//     {
//         return $this->getKey();
//     }

//     /**
//      * Return a key value array, containing any custom claims to be added to the JWT.
//      *
//      * @return array
//      */
//     public function getJWTCustomClaims()
//     {
//         return [];
//     }

    public function orders(): HasMany 
    {
        return $this->hasMany(Order::class);
    }
}
