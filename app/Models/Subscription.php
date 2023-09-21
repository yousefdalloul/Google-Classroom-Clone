<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function MongoDB\BSON\toJSON;

class Subscription extends Model
{
    use HasFactory;
    public function Price(): Attribute
    {
        return new Attribute(
            get: fn($price) => $price / 100,
            set: fn($price) => $price * 100,
        );
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
