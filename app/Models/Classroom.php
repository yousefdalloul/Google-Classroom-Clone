<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Classroom extends Model
{
    use HasFactory,SoftDeletes;
    public static string $disk = 'public';
    protected $fillable = [
        'name','section','subject','room','theme','cover_image_path','code','user_id'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function uploadCoverImage($file)
    {
        $path = $file->store('/covers',[
            'disk'=>static::$disk
        ]);
        return $path;
    }

    public static function deleteCoverImage($path)
    {
        return Storage::disk(static::$disk)->delete($path);
    }

    public static function booted()
    {

//        static::addGlobalScope('user',function (Builder $query){
//            $query->where('user_id','=',Auth::id());
//        });
        static::addGlobalScope(new UserClassroomScope);
    }

    //local scope
    public function scopeActive(Builder $builder)
    {
        $builder->where('status','=','active');
    }
    public function scopeRecent(Builder $builder)
    {
        $builder->orderBy('updated_at','DESC');
    }
    public function scopeStatus(Builder $builder,$status)
    {
        $builder->where('status','=',$status);
    }

}
