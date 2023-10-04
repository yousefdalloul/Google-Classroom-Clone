<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Classroom;
use App\Models\Classwork;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use \Spatie\Ignition\Solutions\OpenAi\OpenAiSolutionProvider;


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
        $aiSolutionProvider = new OpenAiSolutionProvider(config('app.openai.key'));
        $aiSolutionProvider->useCache(app('cache'));
        \Spatie\Ignition\Ignition::make()
            ->addSolutionProviders([
                $aiSolutionProvider,
                // other solution providers...
            ])
            ->register();
        // $user = Auth::user();
        // App::setLocale($user->profile->locale);
        // Paginator::useBootstrapFive();
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');

        Relation::enforceMorphMap([
            'classwork' => classwork::class,
            'Post' => Post::class,
            'user' =>User::class,
            'classroom'=>Classroom::class,
            'admin'=>Admin::class,
        ]);
    }
}
