<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
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
            set: fn($value) => strtolower($value)      //raw function single line
        );
    }

    public function cratedClassrooms()
    {
        return $this->hasMany(Classroom::class,'user_id');
    }

    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class, // Related model
            'classroom_user', // pivot table
            'user_id', // FK for current model in the pivot table
            'classroom_id', // FK for related model in the pivot table
            'id', // PK for current model
            'id', // PK for related model
        )->withPivot(['role', 'created_at']);
    }

    public function classworks()
    {
        return $this->belongsToMany(classwork::class)
            ->using(ClassworkUser::class)
            ->withPivot(['grade', 'status', 'submitted_at', 'created_at']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class,'user_id','id')
            ->withDefault();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class,);
    }


    public function routeNotificationForMail($notification = null)
    {
        return $this->email;
    }
    public function routeNotificationForVonage($notification = null)
    {
        // return $this->mobile;
        return '+97259218001';
    }

    public function routeNotificationForHadara($notification = null)
    {
        // return $this->mobile;
        return '+97259218001';
    }

    public function receivesBroadcastNotificationOn()
    {
        return 'Notifications.' . $this->id;
    }

    public function preferredLocale()
    {
        return $this->profile->locale;
    }

}
