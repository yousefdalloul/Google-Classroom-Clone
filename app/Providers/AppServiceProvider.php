<?php

namespace App\Providers;

use App\Models\Classwork;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        ResourceCollection::withoutWrapping();

        Relation::morphMap([
            'post'=>Post::class,
            'classwork'=> Classwork::class,
        ]);
    }
}
