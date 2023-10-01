<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'recipient_id','recipient_type','sender_id','body'
    ];

    public function sender():BelongsTo
    {
        return $this->belongsTo(User::class,'sender_id');
    }
    public function recipient():MorphTo
    {
        return $this->morphTo();
    }
}
