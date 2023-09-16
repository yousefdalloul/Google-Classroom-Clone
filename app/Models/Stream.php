<?php

namespace App\Models;

use DragonCode\Support\Facades\Helpers\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
            'user_id','classroom_id','content','link'
    ];

    public static function booted()
    {
//        static::creating(function (Stream $stream){
//            $stream->id = Str::uuid();
//        });
    }

    /*
    public function uniqueIds()
    {
        return [
            'id'
        ];
    }
    */
    public function getUpdatedAtColumn()
    {
    }
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
