<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:module {namespace}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $codeStubFolder = '';
    protected $moduleNameSpace = '';
    protected $ModuleName = ''; //ucfirst
    protected $moduleName = ''; //camel case
    protected $module_name = ''; //snake case
    protected $module_names = ''; //plural snake case
    protected $modulename = '';  //kebab case
    protected $modulenames = '';  //plural

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $codeStubFolder = base_path('CodeStubs');

        $namespace = $this->argument('namespace');
        $folders = explode('\\', $namespace);

        $baseDirectory = app_path();
        $relativeNamespace = 'App';
        foreach ($folders as $folder) {
            if ($folder == 'app' || $folder == 'App') {
                continue;
            }
            $baseDirectory .= DIRECTORY_SEPARATOR . ucfirst($folder);
            $relativeNamespace .= '\\' . ucfirst($folder);
            if (!File::exists($baseDirectory)) {
                File::makeDirectory($baseDirectory, 0755, true);
            }
        }

        if ($this->isDirectoryNotEmpty($baseDirectory)) {
            $this->info("Sorry, could not generate module because the directory ({$baseDirectory}) is not empty.");
            return;
        }

        $this->moduleNameSpace = $relativeNamespace;
        $this->ModuleName = Str::of(ucfirst(end($folders)))->singular();
        $this->moduleName = Str::of($this->ModuleName)->camel();
        $this->module_name = Str::of($this->ModuleName)->snake();
        $this->module_names = Str::of($this->ModuleName)->plural()->snake();
        $this->modulename = Str::of($this->ModuleName)->kebab();
        $this->modulenames = Str::of($this->modulename)->plural();

        //copy all folders and files from stub to module destination
        File::copyDirectory($codeStubFolder, $baseDirectory);
        $this->replaceModuleNameInFiles($baseDirectory);
        $this->renameFiles($baseDirectory);
        $this->info(ucfirst(end($folders)) . ' module has been successfully created!🎉');
        $this->info("Get ready for some awesome coding ahead.");
        $this->info("This module is here to make your work easier, your code cleaner, and your projects bug-free. Happy coding!🎉");
    }


    private function getReplaceKeyValues()
    {
        return [
            "<<moduleNameSpace>>" => $this->moduleNameSpace,
            "<<ModuleName>>" => $this->ModuleName,
            "<<moduleName>>" => $this->moduleName,
            "<<module_name>>" => $this->module_name,
            "<<module_names>>" => $this->module_names,
            "<<modulename>>" => $this->modulename,
            "<<modulenames>>" => $this->modulenames,
        ];
    }


    private function getFileNameReplaceKeyValues()
    {
        return [
            "moduleNameSpace" => $this->moduleNameSpace,
            "ModuleName" => $this->ModuleName,
            "moduleName" => $this->moduleName,
            "module_name" => $this->module_name,
            "modulenames" => $this->modulenames,
            "modulename" => $this->modulename,
        ];
    }

    private function replaceModuleNameInFiles($directory)
    {
        $files = File::allFiles($directory);
        $replacements = $this->getReplaceKeyValues();

        foreach ($files as $file) {
            // Read the file content
            $fileContent = File::get($file->getPathname());
            // Replace the strings
            $newFileContent = str_replace(array_keys($replacements), array_values($replacements), $fileContent);


            // Write the modified content back to the same file
            File::put($file->getPathname(), $newFileContent);
        }

        // Recursively process subdirectories
        $directories = File::directories($directory);
        foreach ($directories as $subdirectory) {
            $this->replaceModuleNameInFiles($subdirectory);
        }
    }

    private function renameFiles($directory)
    {
        $files = File::allFiles($directory);
        $replacements = $this->getFileNameReplaceKeyValues();
        $newExtension = 'php';
        foreach ($files as $file) {
            // Get the original file path and name
            $filePath = $file->getPathname();
            $fileName = $file->getFilename();

            // Replace the string in the file name
            $newFileName = str_replace(array_keys($replacements), array_values($replacements), $fileName);
            $newFileName = pathinfo($newFileName, PATHINFO_FILENAME) . '.' . $newExtension;
            // Construct the new file path
            $newFilePath = $file->getPath() . DIRECTORY_SEPARATOR . $newFileName;
            // Rename the file
            File::move($filePath, $newFilePath);
        }

        // Recursively process subdirectories
        $directories = File::directories($directory);
        foreach ($directories as $subdirectory) {
            $this->renameFiles($subdirectory);
        }
    }

    private function isDirectoryNotEmpty($directory)
    {
        return count(File::allFiles($directory)) > 0 || count(File::directories($directory)) > 0;
    }
}
