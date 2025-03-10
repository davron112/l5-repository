<?php
namespace Prettus\Repository\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package Prettus\Repository\Providers
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../resources/config/repository.php' => config_path('repository.php'),
            __DIR__ . '/../Generators/Stubs/service/base.stub' => app_path(str_replace('\\', '/', config('repository.generator.paths.services', 'Services') . '\BaseService.php')),
            __DIR__ . '/../Generators/Stubs/service/base_interface.stub' => app_path(str_replace('\\', '/', config('repository.generator.paths.service_interfaces', 'Services\Contracts') . '\BaseService.php')),
            __DIR__ . '/../Generators/Resources/DTOCollection.php' => app_path(str_replace('\\', '/', '\Http\Resources\DTOCollection.php')),
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../../../resources/config/repository.php', 'repository');

        $this->loadTranslationsFrom(__DIR__ . '/../../../resources/lang', 'repository');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Prettus\Repository\Generators\Commands\RepositoryCommand');
        $this->commands('Prettus\Repository\Generators\Commands\ServiceCommand');
        $this->commands('Prettus\Repository\Generators\Commands\TransformerCommand');
        $this->commands('Prettus\Repository\Generators\Commands\PresenterCommand');
        $this->commands('Prettus\Repository\Generators\Commands\EntityCommand');
        $this->commands('Prettus\Repository\Generators\Commands\ValidatorCommand');
        $this->commands('Prettus\Repository\Generators\Commands\ControllerCommand');
        $this->commands('Prettus\Repository\Generators\Commands\BindingsCommand');
        $this->commands('Prettus\Repository\Generators\Commands\CriteriaCommand');
        $this->app->register('Prettus\Repository\Providers\EventServiceProvider');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
