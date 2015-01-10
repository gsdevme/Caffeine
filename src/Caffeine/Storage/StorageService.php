<?php

namespace Caffeine\Storage;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

class StorageService
{
    private $loader;

    public function __construct(array $directories)
    {
        $locator        = new FileLocator($directories);
        $loaderResolver = new LoaderResolver(array(new Loader\PhpFileLoader($locator)));
        $this->loader   = new DelegatingLoader($loaderResolver);
    }

    public function load($file)
    {
        return $this->loader->load($file);
    }
}
