<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CodeGenerator extends Command
{
    protected $signature = 'code-generator {namespace}';
    protected $description = 'Generate CRUD files based on namespace';

    protected $serviceInterfaceMethods = <<<'EOD'
            public function create(array $attributes);
            public function update($id,array  $attributes);
            public function find($id);
            public function delete($id);
            public function all();
EOD;

    protected $repositoryInterfaceMethods = <<<'EOD'
            public function create(array $attributes);
            public function update($id,array  $attributes);
            public function find($id);
            public function delete($id);
            public function all();
EOD;

    protected $repositoryMethods = <<<'EOD'
            public function create(array $attributes)  {
                return <<model>>::create($attributes);
            }
            public function update($id,array  $attributes)  {
                return <<model>>::findOrFail($id)->update($attributes);
            }
            public function find($id) {
               return <<model>>::findOrFail($id);
            }
            public function delete($id) {
                return <<model>>::findOrFail($id)->delete();
            }
            public function all(){
                return <<model>>::all();
            }
EOD;


    protected $serviceMethods = <<<'EOD'
        public function create(array $attributes)  {
            return $this-><<repository>>->create($attributes);
        }
        public function update($id,array  $attributes)  {
            return $this-><<repository>>->update($id, $attributes);
        }
        public function find($id) {
        return $this-><<repository>>->find($id);
        }
        public function delete($id) {
            return $this-><<repository>>->delete();
        }
        public function all(){
            return $this-><<repository>>->all();
        }
EOD;

    private $modelName = '';
    private $baseNameSpace = '';

    public function handle()
    {
        $namespace = $this->argument('namespace');
        $folders = explode('\\', $namespace);

        $baseDirectory = app_path();
        $relativeNamespace = 'App';
        foreach ($folders as $folder) {
            if ($folder == 'app') {
                continue;
            }
            $baseDirectory .= DIRECTORY_SEPARATOR . ucfirst($folder);
            $relativeNamespace .= '\\' . ucfirst($folder);
            if (!File::exists($baseDirectory)) {
                File::makeDirectory($baseDirectory, 0755, true);
            }
        }

        $directories = [
            'Contracts',
            'Domain',
            'Presentation',
            'Presentation/Requests',
            'Presentation/Controllers',
            'Presentation/Resources',
            'Infrastructure',
            'Services',
            'Repositories',
            'Routes',
            'Config'
        ];

        foreach ($directories as $directory) {
            $path = $baseDirectory . '/' . $directory;
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
        }

        $endOfNamespace = ucfirst(end($folders));
        $this->baseNameSpace = $relativeNamespace;
        $this->generateFiles($baseDirectory, $relativeNamespace, $endOfNamespace);
    }

    private function generateFiles($baseDirectory, $relativeNamespace, $endOfNamespace)
    {
        $contractsNamespace = $relativeNamespace . '\\Contracts';
        $this->generateFile($baseDirectory . '/Contracts/', $contractsNamespace, $endOfNamespace, 'ServiceInterface', $endOfNamespace . 'ServiceInterface');
        $this->generateFile($baseDirectory . '/Contracts/', $contractsNamespace, $endOfNamespace, 'RepositoryInterface', $endOfNamespace . 'RepositoryInterface');

        $domainNamespace = $relativeNamespace . '\\Domain';
        $this->generateFile($baseDirectory . '/Domain/', $domainNamespace, $endOfNamespace, 'CreateValidator', 'CreateValidator');
        $this->generateFile($baseDirectory . '/Domain/', $domainNamespace, $endOfNamespace, 'UpdateValidator', 'UpdateValidator');


        $infrastructureNamespace = $relativeNamespace . '\\Infrastructure';
        $this->generateFile($baseDirectory . '/Infrastructure/', $infrastructureNamespace, $endOfNamespace, 'Model', $endOfNamespace);

        $repositoriesNamespace = $relativeNamespace . '\\Repositories';
        $this->generateFile($baseDirectory . '/Repositories/', $repositoriesNamespace, $endOfNamespace, 'Repository', $endOfNamespace . 'Repository');

        $presentationRequestsNamespace = $relativeNamespace . '\\Presentation\\Requests';
        $presentationResourcesNamespace = $relativeNamespace . '\\Presentation\\Resources';
        $presentationControllersNamespace = $relativeNamespace . '\\Presentation\\Controllers';
        $this->generateFile($baseDirectory . '/Presentation/Requests/', $presentationRequestsNamespace, $endOfNamespace, 'CreateFormRequest');
        $this->generateFile($baseDirectory . '/Presentation/Requests/', $presentationRequestsNamespace, $endOfNamespace, 'UpdateFormRequest');
        $this->generateFile($baseDirectory . '/Presentation/Resources/', $presentationResourcesNamespace, $endOfNamespace, 'Resource');

        $routesNamespace = $relativeNamespace . '\\Routes';
        $this->generateFile($baseDirectory . '/Routes/', $routesNamespace, $endOfNamespace, $endOfNamespace, strtolower($endOfNamespace));


        $servicesNamespace = $relativeNamespace . '\\Services';
        $this->generateFile($baseDirectory . '/Services/', $servicesNamespace, $endOfNamespace, $endOfNamespace);



        $controllersNamespace = $relativeNamespace . '\\Presentation\\Controllers';
        $this->generateFile($baseDirectory . '/Presentation/Controllers/', $controllersNamespace, $endOfNamespace, $endOfNamespace . 'Controller');


        $configNamespace = $relativeNamespace . '\\Config';
        $this->generateServiceProvider($baseDirectory . '/Config/', $configNamespace, $endOfNamespace, $endOfNamespace);
    }

    private function generateFile($namespace, $fullNamespace, $endOfNamespace, $fileType, $fileName = '')
    {
        if (empty($fileName)) {
            $fileName = $endOfNamespace . $fileType . '.php';
        } elseif (!(str_ends_with($fileName, '.php'))) {
            $fileName .=  '.php';
        }
        $content = $this->getFileContent($fullNamespace, $endOfNamespace, $fileType, $fileName);
        file_put_contents($namespace . $fileName, $content);
    }

    private function generateServiceProvider($namespace, $fullNamespace, $endOfNamespace, $fileType)
    {
        $fileName = $endOfNamespace . 'ServiceProvider.php';
        $content = $this->getServiceProviderContent($fullNamespace, $endOfNamespace, $fileType);
        file_put_contents($namespace . $fileName, $content);
    }

    private function getFileContent($namespace, $endOfNamespace, $fileType, $fileName)
    {
        $fileNameWithoutSuffix = pathinfo($fileName, PATHINFO_FILENAME);
        // Generate file content based on file type
        switch ($fileType) {
            case 'CreateFormRequest':
            case 'UpdateFormRequest':
                return $this->getFormRequest($namespace, $fileNameWithoutSuffix);
            case 'Resource':
                return $this->getResource($namespace, $fileNameWithoutSuffix);
            case 'CreateValidator':
            case 'UpdateValidator':
                return $this->getValidator($namespace, $fileNameWithoutSuffix);
            case 'ServiceInterface':
                return $this->getServiceInterface($namespace, $fileNameWithoutSuffix);
            case 'RepositoryInterface':
                return $this->getRepositoryInterface($namespace, $fileNameWithoutSuffix);
            case 'Model':
                return $this->getModelContent($namespace, $fileNameWithoutSuffix);
            case 'Repository':
                return $this->getRepositoryContent($namespace, $endOfNamespace, $fileNameWithoutSuffix);
            default:
                return "<?php\n\nnamespace $namespace;\n\nclass {$fileNameWithoutSuffix}\n{\n    // Add your content here\n}\n";
        }
    }

    private function getModelContent($namespace, $modelName)
    {
        $this->modelName = $modelName;
        return
        "<?php\n\nnamespace $namespace;\n use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\n use Illuminate\\Database\\Eloquent\\Model;\nclass {$modelName}  extends Model\n{\n use HasFactory; \n    // Add your content here\n}\n";
    }

    private function getServiceInterface($namespace, $serviceName)
    {
        return
        "<?php\n\nnamespace $namespace;\n\n \ninterface {$serviceName}\n{\n  {$this->serviceInterfaceMethods}  \n}\n";
    }

    private function getRepositoryInterface($namespace, $repositoryName)
    {
        return
        "<?php\n\nnamespace $namespace;\n\n \ninterface {$repositoryName}\n{\n  {$this->repositoryInterfaceMethods}  \n}\n";
    }


    private function getValidator($namespace, $validatorName)
    {
        return
        "<?php\n\nnamespace $namespace;\n\nuse App\\API\\Common\\Contracts\\ValidationInterface;\n\nclass {$validatorName} implements ValidationInterface\n{\n    public function validate(): bool\n    {\n        // Implement your validation logic\n    }\n}\n";
    }

    private function getResource($namespace, $resourceName)
    {
        return
        "<?php\n\nnamespace $namespace;\n\nuse Illuminate\Http\Resources\Json\JsonResource;\n\nclass {$resourceName} extends JsonResource\n{\n    public function toArray(\$request)\n    {\n        return parent::toArray(\$request);\n    }\n}\n";
    }


    private function getFormRequest($namespace, $formRequestName)
    {
        return
        "<?php\n\nnamespace $namespace;\n\nuse Illuminate\Foundation\Http\FormRequest;\n\nclass {$formRequestName} extends FormRequest\n{\n    public function authorize()\n    {\n        return true;\n    }\n\n    public function rules()\n    {\n        return [];\n    }\n}\n";
    }

    private function getRepositoryContent($namespace, $endOfNamespace, $repositoryName)
    {
        return "<?php\n\nnamespace $namespace;\nuse {$this->baseNameSpace}\\Infrastructure\\{$this->modelName}; \n\nuse {$this->baseNameSpace}\\Contracts\\{$endOfNamespace}RepositoryInterface; \n\nclass {$repositoryName} implements {$endOfNamespace}RepositoryInterface \n{\n  {$this->getRepositoryMethods()}  \n}\n";
    }

    private function getRepositoryMethods()
    {
        return str_replace("<<model>>", $this->modelName, $this->repositoryMethods);
    }

    private function getServiceContent($namespace, $endOfNamespace, $serviceName)
    {
        return "<?php\n\nnamespace $namespace;\nuse {$this->baseNameSpace}\\Repositories\\{$this->modelName}; \n\nuse {$this->baseNameSpace}\\Contracts\\{$endOfNamespace}ServiceInterface; \n\nclass {$serviceName} implements {$endOfNamespace}ServiceInterface \n{\n  {$this->getServiceMethods('')}  \n}\n";
    }

    private function getServiceMethods($serviceName)
    {
        return str_replace("<<repository>>", $serviceName . 'Service', $this->repositoryMethods);
    }


    private function getServiceProviderContent($namespace, $endOfNamespace, $fileType)
    {
        return "<?php\n\nnamespace $namespace;\n\nuse Illuminate\Support\ServiceProvider;\n\nclass {$endOfNamespace}ServiceProvider extends ServiceProvider\n{\n    public function register()\n    {\n        \$this->app->bind({$endOfNamespace}RepositoryInterface::class, {$endOfNamespace}Repository::class);\n        \$this->app->bind({$endOfNamespace}ServiceInterface::class, {$endOfNamespace}Service::class);\n    }\n}\n";
    }
}
