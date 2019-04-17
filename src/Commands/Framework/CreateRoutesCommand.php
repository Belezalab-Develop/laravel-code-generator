<?php

namespace CrestApps\CodeGenerator\Commands\Framework;

use CrestApps\CodeGenerator\Support\Config;
use CrestApps\CodeGenerator\Support\Helpers;
use CrestApps\CodeGenerator\Support\Str;
use CrestApps\CodeGenerator\Traits\CommonCommand;
use CrestApps\CodeGenerator\Traits\GeneratorReplacers;
use CrestApps\CodeGenerator\Traits\RouteTrait;
use Exception;
use Illuminate\Console\Command;
use Route;

class CreateRoutesCommand extends Command
{
    use CommonCommand, GeneratorReplacers, RouteTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:routes
                            {model-name : The model name.}
                            {--controller-name= : The name of the controller where the route should be routing to.}
                            {--routes-prefix=default-form : Prefix of the route group.}
                            {--controller-directory= : The directory where the controller is under.}
                            {--without-route-clause : Create the routes without where clause for the id.}
                            {--routes-type= : The type of the route to create "api", "api-docs", "quasar" or web.}
                            {--api-version= : The api version to prefix your resurces with.}
                            {--template-name= : The template name to use when generating the code.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create "create, read, update and delete" routes for the model.';

    /**
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $input = $this->getCommandInput();

        $namePrefix = $this->getNamePrefix($input->prefix, $input->type, $input->apiVersion);

        if ($this->isRouteNameExists($this->getDotNotationName($this->getModelName($input->modelName), $namePrefix, 'index'))) {
            $this->warn("The routes already registered!");

            return;
        }

        $routesFile = $this->getRoutesFileName($input->type);

        if (!$this->isFileExists($routesFile)) {
            throw new Exception("The routes file does not exists. The expected location was " . $routesFile);
        }

        $stub = $this->getRoutesStub($input->type);
        $controllnerName = $this->getControllerName($input->controllerName, $input->controllerDirectory);

        $this->replaceModelName($stub, $input->modelName)
            ->replaceControllerName($stub, $controllnerName)
            ->replaceRouteNames($stub, $this->getModelName($input->modelName), $namePrefix)
            ->processRoutesGroup($stub, $input)
            ->replaceRouteIdClause($stub, $this->getRouteIdClause($input->withoutRouteClause))
            ->replacePrefix($stub, $namePrefix)
            ->replaceVersion($stub, $this->getVersion($input->apiVersion))
            ->appendToRoutesFile($stub, $routesFile, $input->type)
            ->info('The routes were added successfully.');
    }

    protected function getControllerDirectory()
    {
        $path = Config::getApiDocsControllersPath($input->controllerName);

        Str::trimStart($path, Config::getControllersPath());
    }
    /**
     * Gets the stub content for the route
     *
     * @param string $type
     *
     * @return string
     */
    protected function getRoutesStub($type)
    {
        $name = 'routes';

        if ($type == 'api') {
            $name = 'api-routes';
        }
        if ($type == 'api-docs') {
            $name = 'api-documentation-routes';
        }
        if ($type == 'quasar') {
            $name = 'quasar-routes';
        }

        return $this->getStubContent($name);
    }

    /**
     * Gets the version parameter
     *
     * @param string $version
     *
     * @return string
     */
    protected function getVersion($version)
    {
        if ($version) {
            return '/{version}';
        }

        return '';
    }

    /**
     * Gets a clean user inputs.
     *
     * @return object
     */
    protected function getCommandInput()
    {

        $modelName = trim($this->argument('model-name'));
        $controllerName = trim($this->option('controller-name')) ?: Str::postfix($modelName, 'Controller');
        $prefix = ($this->option('routes-prefix') == 'default-form') ? Helpers::makeRouteGroup($modelName) : $this->option('routes-prefix');
        $prefix = trim(str_replace('\\', '/', $prefix));
        $template = $this->getTemplateName();
        $type = strtolower($this->option('routes-type'));
        $apiVersion = $this->option('api-version');
        $controllerDirectory = trim($this->option('controller-directory')) ?: $this->getDefaultControllerDirectory($type, $apiVersion);
        $withoutRouteClause = $this->option('without-route-clause');

        return (object) compact(
            'modelName',
            'controllerName',
            'prefix',
            'template',
            'controllerDirectory',
            'withoutRouteClause',
            'apiVersion',
            'type'
        );
    }

    /**
     * Gets the default controller directory
     *
     * @param string $type
     * @param string $apiVersion
     *
     * @return string
     */
    protected function getDefaultControllerDirectory($type, $apiVersion)
    {
        $directory = Config::getControllersPath();

        if ($type == 'api') {
            $directory = Config::getApiControllersPath($apiVersion);
        } else if ($type == 'api-docs') {
            $directory = Config::getApiDocsControllersPath();
        }

        return ltrim(Str::trimStart($directory, Config::getControllersPath()), '/');
    }

    /**
     * Gets the where clause for the id
     *
     * @param bool $withClause
     *
     * @return string
     */
    protected function getRouteIdClause($withoutClause)
    {
        if (!$withoutClause) {
            return "->where('id', '[0-9]+')";
        }

        return '';
    }

    /**
     * Appends the new routes to a route file.
     *
     * @param string $stub
     * @param string $routesFile
     *
     * @return $this
     */
    protected function appendToRoutesFile($stub, $routesFile, $type)
    {
        if ($type == 'quasar') {
            $quasar_template = $this->getStubContent('quasar-routes-base');
            $stub = $this->replaceTemplate('route-groups', $stub, $quasar_template);
        }

        $this->appendContentToFile($routesFile, $stub);

        return $this;
    }

    /**
     * Gets the correct controller name with the namespace.
     *
     * @param string $name
     * @param string $namespace
     *
     * @return string
     */
    protected function getControllerName($name, $namespace)
    {
        if (empty($namespace)) {
            return $name;
        }

        $path = Str::postfix($namespace, '\\');

        return Helpers::fixNamespace($path . $name);
    }

    /**
     * Replaces the routes for the given stub.
     *
     * @param string $stub
     * @param string $routes
     *
     * @return $this
     */
    protected function replaceRoutes(&$stub, $routes)
    {
        return $this->replaceTemplate('routes', $routes, $stub);
    }

    /**
     * Replaces the routes' prefix for the given stub.
     *
     * @param string $stub
     * @param string $prefix
     *
     * @return $this
     */
    protected function replacePrefix(&$stub, $prefix)
    {
        return $this->replaceTemplate('prefix', $prefix, $stub);
    }

    /**
     * Replaces the version template for the given stub.
     *
     * @param string $stub
     * @param string $version
     *
     * @return $this
     */
    protected function replaceVersion(&$stub, $version)
    {
        return $this->replaceTemplate('version', $version, $stub);
    }

    /**
     * Replaces the routes' prefix for the given stub.
     *
     * @param string $stub
     * @param string $prefix
     *
     * @return $this
     */
    protected function replaceRouteIdClause(&$stub, $prefix)
    {
        return $this->replaceTemplate('route_id_clause', $prefix, $stub);
    }

    /**
     * Groups the routes with a prefix and namespace if prefix or namespace is provided.
     *
     * @param string $stub
     * @param object $input
     *
     * @return $this
     */
    protected function processRoutesGroup(&$stub, $input)
    {
        if ($input->type == 'api-docs') {
            return $this;
        }

        $prefix = $input->prefix;

        if ($input->type == 'api' && Helpers::isOlderThan('5.3')) {
            $prefix = Str::prefix($prefix, 'api/');
        }

        if (!empty($prefix) || !empty($input->controllerDirectory)) {
            if ($input->type == 'quasar') {
                $groupStub = $this->getStubContent('quasar-routes-group');
            } else {
                $groupStub = $this->getStubContent('routes-group');
            }

            $this->replacePrefix($groupStub, $this->getGroupPrefix($prefix))
                ->replaceRoutes($groupStub, $stub);

            $stub = $groupStub;
        }

        return $this;
    }

    /**
     * Gets array ready prefix string.
     *
     * @param string $prefix
     *
     * @return  string
     */
    protected function getGroupPrefix($prefix)
    {
        return empty($prefix) ? '' : sprintf("'prefix' => '%s',", $prefix);
    }

    /**
     * Gets array ready namespace string.
     *
     * @param string $namespace
     *
     * @return  string
     */
    protected function getGroupNamespace($namespace)
    {
        return empty($namespace) ? '' : sprintf("'namespace' => '%s',", $namespace);
    }

    /**
     * Checks if a route name is already registred.
     *
     * @param string $name
     *
     * @return  bool
     */
    protected function isRouteNameExists($name)
    {
        $existingRoutes = Route::getRoutes();

        foreach ($existingRoutes as $existingRoute) {
            if ($existingRoute->getName() == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the correct routes fullname based on current framework version.
     *
     * @param string $type
     *
     * @return string
     */
    protected function getRoutesFileName($type)
    {
        if (Helpers::isNewerThanOrEqualTo('5.3')) {

            switch ($type) {
                case 'api':
                    $file = 'routes/api.php';
                    break;
                case 'web':
                    $file = 'routes/web.php';
                    break;
                case 'quasar':
                    $file = 'frontend/src/router/routes.js';
                    break;
                default:
                    $file = 'routes/web.php';
                    break;
            }

            return base_path($file);
        }

        return app_path('Http/routes.php');
    }
}
