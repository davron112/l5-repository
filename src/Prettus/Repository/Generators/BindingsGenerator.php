<?php
namespace Prettus\Repository\Generators;

/**
 * Class BindingsGenerator
 * @package Prettus\Repository\Generators
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class BindingsGenerator extends Generator
{

    /**
     * The placeholder for repository bindings
     *
     * @var string
     */
    public $bindPlaceholder = '//:end-bindings:';
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'bindings/bindings';

    public function run()
    {


        // Add entity repository binding to the repository service provider
        $provider = \File::get($this->getPath());
        $serviceInterface = '\\' . $this->getService() . "::class";
        $serviceEntity = '\\' . $this->getEntityService() . "::class";
        \File::put($this->getPath(), str_replace($this->bindPlaceholder, "\$this->app->bind({$serviceInterface}, $serviceEntity);" . PHP_EOL . '        ' . $this->bindPlaceholder, $provider));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/Providers/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app()->path());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'provider';
    }

    /**
     * Gets repository full class name
     *
     * @return string
     */
    public function getService()
    {
        $serviceGenerator = new ServiceInterfaceGenerator([
            'name' => $this->name,
        ]);

        $service = $serviceGenerator->getRootNamespace() . '\\' . $serviceGenerator->getName();

        return str_replace([
            "\\",
            '/'
        ], '\\', $service) . 'Service';
    }

    /**
     * Gets eloquent repository full class name
     *
     * @return string
     */
    public function getEloquentRepository()
    {
        $repositoryGenerator = new RepositoryEloquentGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace() . '\\' . $repositoryGenerator->getName();

        return str_replace([
            "\\",
            '/'
        ], '\\', $repository) . 'RepositoryEloquent';
    }

    /**
     * Gets eloquent repository full class name
     *
     * @return string
     */
    public function getEntityService()
    {
        $serviceGenerator = new ServiceEntityGenerator([
            'name' => $this->name,
        ]);

        $service = $serviceGenerator->getRootNamespace() . '\\' . $serviceGenerator->getName();

        return str_replace([
            "\\",
            '/'
        ], '\\', $service) . 'ServiceEntity';
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return parent::getRootNamespace() . parent::getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {

        return array_merge(parent::getReplacements(), [
            'service' => $this->getService(),
            'eloquent' => $this->getEloquentRepository(),
            'entity' => $this->getEntityService(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }
}
