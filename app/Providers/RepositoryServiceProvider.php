<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\CategoryContract;
use App\Contracts\ResourceContract;
use App\Repositories\CategoryRepository;
use App\Repositories\ResourceRepository;



class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        CategoryContract::class   =>  CategoryRepository::class,
        ResourceContract::class   =>  ResourceRepository::class,
        
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation)
        {
            $this->app->bind($interface, $implementation);
        }
    }
}
