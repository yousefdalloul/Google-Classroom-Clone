<?php

namespace App\Models;

use App\Models\Scopes\UserClassroomScope;
use App\Observers\ClassroomObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        static::observe(ClassroomObserver::class);

        //static::addGlobalScope('user',function (Builder $query){
        //  $query->where('user_id','=',Auth::id());
        //});
        static::addGlobalScope(new UserClassroomScope);

        //events listener:
        //creating,created,updating,updated,saving,saved
        //deleting,deleted,restoring,restored,ForceDeleting,ForceDeleted
        //retrieved
        static::creating(function (Classroom $classroom){
            $classroom->code = Str::random(8);
            $classroom->user_id = Auth::id();
        });

        static::forceDeleted(function (Classroom $classroom){
            Classroom::deleteCoverImage($classroom->cover_image_path);
        });

        static::deleting(function (Classroom $classroom){
            $classroom->status = 'deleted';
            $classroom->save();
        });

        static::restored(function (Classroom $classroom){
            $classroom->status = 'active';
            $classroom->save();
        });
    }

    //local scope
    public function scopeActive(Builder $query)
    {
        $query->where('status','=','active');
    }

    public function scopeRecent(Builder $query)
    {
        $query->orderBy('updated_at','DESC');
    }

    public function scopeStatus(Builder $query,$status)
    {
        $query->where('status','=',$status);
    }

    public function join($user_id,$role = 'student')
    {
        DB::table('classroom_user')->insert([
            'classroom_id' => $this->id,
            'user_id' =>$user_id,
            'role' => $role,
            'created_at' => now(),
        ]);
    }

    //Accessor : get{AttributeName}Attribute
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    //Accessor: $classroom->cover_image_url
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image_path){
            return Storage::disk(static::$disk)->url($this->cover_image_path);
        }
        return 'https://placehold.co/800x300';
    }

    //Accessor: url => {{ route('classrooms.show',$classroom->id) }}
    public function getUrlAttribute()
    {
        return route('classrooms.show',$this->id);
    }
}



    //Accessor : try to edit the value when read the attribute
    //Mutators : while write the value of attribute edit it