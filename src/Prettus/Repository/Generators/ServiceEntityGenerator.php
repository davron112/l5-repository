<?php
namespace Prettus\Repository\Generators;

use Prettus\Repository\Generators\Migrations\SchemaParser;

/**
 * Class ServiceEntityGenerator
 * @package Prettus\Repository\Generators
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class ServiceEntityGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'service/service';

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
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'services';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath() . '/' . parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true) . '/' . $this->getName() . 'ServiceEntity.php';
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
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        $service = parent::getRootNamespace() . parent::getConfigGeneratorClassPath('service_interfaces') . '\\' . $this->name . 'Service;';
        $service = str_replace([
            "\\",
            '/'
        ], '\\', $service);

        return array_merge(parent::getReplacements(), [
            'fillable'      => $this->getFillable(),
            'use_validator' => $this->getValidatorUse(),
            'validator'     => $this->getValidatorMethod(),
            'service'       => $service
        ]);
    }

    /**
     * Get the fillable attributes.
     *
     * @return string
     */
    public function getFillable()
    {
        if (!$this->fillable) {
            return '[]';
        }
        $results = '[' . PHP_EOL;

        foreach ($this->getSchemaParser()->toArray() as $column => $value) {
            $results .= "\t\t'{$column}'," . PHP_EOL;
        }

        return $results . "\t" . ']';
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->fillable);
    }

    public function getValidatorUse()
    {
        $validator = $this->getValidator();

        return "use {$validator};";
    }


    public function getValidator()
    {
        $validatorGenerator = new ValidatorGenerator([
            'name'  => $this->name,
            'rules' => $this->rules,
            'force' => $this->force,
        ]);

        $validator = $validatorGenerator->getRootNamespace() . '\\' . $validatorGenerator->getName();

        return str_replace([
            "\\",
            '/'
        ], '\\', $validator) . 'Validator';

    }


    public function getValidatorMethod()
    {
        if ($this->validator != 'yes') {
            return '';
        }

        $class = $this->getClass();

        return '/**' . PHP_EOL . '    * Specify Validator class name' . PHP_EOL . '    *' . PHP_EOL . '    * @return mixed' . PHP_EOL . '    */' . PHP_EOL . '    public function validator()' . PHP_EOL . '    {' . PHP_EOL . PHP_EOL . '        return ' . $class . 'Validator::class;' . PHP_EOL . '    }' . PHP_EOL;

    }
}
