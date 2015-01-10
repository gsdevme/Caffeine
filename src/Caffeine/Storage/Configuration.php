<?php

namespace Caffeine\Storage;

use Symfony\Component\Filesystem\Filesystem;

class Configuration extends Filesystem implements File
{
    /**
     * @inheritdoc
     */
    public function getConfigurationFilePath($channel)
    {
        return $this->getFolderStoragePath($channel) . '/' . $this->getFileName();
    }

    /**
     * @param $channel
     * @return string
     */
    public function getFolderStoragePath($channel)
    {
        return $this->getCurrentWorkingDirectory() . '/' . $this->getFolderStorageName($channel);
    }

    /**
     * @inheritdoc
     */
    public function getFileName()
    {
        return 'configuration.php';
    }

    /**
     * @inheritdoc
     */
    public function getCurrentWorkingDirectory()
    {
        return getcwd();
    }

    /**
     * @param $channel
     * @return string
     */
    public function getFolderStorageName($channel)
    {
        return sprintf('.caffeine-data-%s', $channel);
    }

    public function exists($channel)
    {
        return parent::exists([$this->getConfigurationFilePath($channel)]);
    }

    public function touch($channel, $time = null, $atime = null)
    {
        parent::touch([$this->getConfigurationFilePath($channel)], $time = null, $atime = null);
    }
}
