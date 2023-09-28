<?php

namespace App\Models;

use App\Concerns\HasPrice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use DragonCode\Contracts\Http\Builder;

class Subscription extends Model
{
    use HasFactory, HasPrice, Prunable;

    protected $fillable = [
        'user_id','plan_id','price','expires_at',
    ];

    public function prunable(): Builder
    {
        return static::where('expires_at', '<=', now()->subYear());
    }
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
