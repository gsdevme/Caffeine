<?php

namespace Caffeine\Storage\Loader;

use Symfony\Component\Config\Loader\FileLoader;

class PhpFileLoader extends FileLoader
{

    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        try {
            $data = require $resource;

            if (!$this->checkFileIsEmpty($data)) {
                return $data;
            }

            return [];
        } catch (\Exception $e) {
            throw new \Exception('Failed to load file ' . $resource, 0, $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }

    /**
     * An empty PHP file `required` in will just equal 'int(1)'
     * @param $data
     * @return bool
     */
    private function checkFileIsEmpty($data)
    {
        return ($data === 1);
    }
}
