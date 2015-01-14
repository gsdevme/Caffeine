<?php

namespace Caffeine\Storage;

interface FileInterface
{
    /**
     * @param $v
     * @return mixed
     */
    public function getConfigurationFilePath($v);

    /**
     * @return mixed
     */
    public function getFileName();
}
