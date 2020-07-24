<?php

namespace Mukja\LaravelFilters\Traits;

trait FilterHelperTrait
{
    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return base_path() . '/app/Filters/' . $name . '.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Splits the string into array based on
     * slash or backslash.
     *
     * @param  string $name
     * @return array
     */
    private function splitNamespace($name)
    {
        if (str_contains($name, '\\')) {
            return explode('\\', $name);
        }

        return explode('/', $name);
    }

     /**
     * Generate the desired filter.
     */
    protected function makeFilter()
    {
        $name = $this->argument('name');
        if (is_array($name)) {
            foreach ($name as $filter) {
                $this->createFile($filter);
            }
        }else {
            $this->createFile($name);
        }
    }

    protected function createFile($name)
    {
        if ($this->files->exists($path = $this->getPath($name))) {
            return $this->error($this->type . ' already exists!');
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->compileFilterStub($name));
        $this->info('Filter created successfully.');
        $this->composer->dumpAutoloads();
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileFilterStub($name)
    {
        $stub = $this->files->get($this->getStub());
        $this->replaceClassName($stub, $name)->replaceNamespace($stub, $name);
        return $stub;
    }
    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceClassName(&$stub, $name)
    {
        $className = ucwords(camel_case($name));
        $className = $this->splitNamespace($className);
        $stub = str_replace('{{class}}', end($className), $stub);
        return $this;
    }

    protected function replaceNamespace(&$stub, $name)
    {
        $namespace = ucwords(camel_case($name));
        $namespace = $this->splitNamespace($namespace);
        if (count($namespace) > 1) {
            array_pop($namespace);
        }
        $namespace = '\\' . implode('\\', $namespace);
        $stub = str_replace('{{namespace}}', $namespace, $stub);
        return $this;
    }
}
