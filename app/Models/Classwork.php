<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classwork extends Model
{
        use HasFactory;

        const TYPE_ASSIGNMENT = 'assignment';
        const TYPE_MATERIAL = 'material';
        const TYPE_QUESTION = 'question';
        const TYPE_PUBLISHED = 'published';
        const TYPE_DRAFT = 'draft';


        protected $fillable = [
            'classroom_id','user_id','topic_id','title',
            'description','type','status','published_id','options',
        ];

        public function classroom(): BelongsTo
        {
            return $this->belongsTo(Classroom::class,'classroom_id','id');
        }
        public function topic(): BelongsTo
        {
            return $this->belongsTo(Topic::class, 'topic_id', 'id');
        }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['grade','submitted_at','status','created_at']);
    }

}
