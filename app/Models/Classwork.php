<?php

namespace App\Models;

use App\Enums\ClassworkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Classwork extends Model
{
        use HasFactory;

        const TYPE_ASSIGNMENT = ClassworkType::ASSIGNMENT;
        const TYPE_MATERIAL = ClassworkType::MATERIAL;
        const TYPE_QUESTION = ClassworkType::QUESTION;
        const TYPE_PUBLISHED = 'published';
        const TYPE_DRAFT = 'draft';


        protected $fillable = [
            'classroom_id','user_id','topic_id','title',
            'description','type','status','published_id','options',
        ];

        protected $casts = [
            'options' => 'array',
            'classroom_id'=>'integer',
            'published_at'=>'datetime:Y-m-d',
            'type'=>ClassworkType::class,
        ];

        protected static function booted()
        {
            static::creating(function (Classwork $classwork){
                if (!$classwork->published_at){
                    $classwork->published_at = now();
                }
            });
        }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['search'] ?? '', function ($builder, $value) {
            $builder->where(function ($builder) use ($value) {
                $builder->where('title', 'LIKE', "%{$value}%")
                    ->orWhere('description', 'LIKE', "%{$value}%");
            });
        })
            ->when($filters['type'] ?? '', function ($builder, $value) {
                $builder->where('title', 'LIKE', "%{$value}%");
            });
    }


    public function getPublishedDataAttribute()
        {
            if ($this->publisshed_at){
                return $this->published_at->format('Y-m-d');
            }
        }

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
                ->withPivot(['grade','submitted_at','status','created_at'])
                ->using(ClassworkUser::class);
        }

        public function comments()
        {
            return $this->morphMany(Comment::class, 'commentable')->latest();
        }

}
