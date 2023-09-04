<?php

namespace App\Models;

use http\Exception\UnexpectedValueException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    //Mutators
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function email()
    {
        return Attribute::make(

            get: fn($value) => strtoupper($value),
            set: fn($value) => strtolower($value),      //raw function single line
        );
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,                   //Related Model
            'classroom_user',               //Pivot table
            'classroom_id',          //FK for current model in pivot model
            'user_id',               //FK for related model in pivot model
            'id',                        //PK for current model
            'id'                         //PK for related model
        )->withPivot(['role','created_at']);
    }
    public function cratedClassrooms()
    {
        return $this->hasMany(Classroom::class,'user_id');
    }

    public function classworks()
    {

    }
}
