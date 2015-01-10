<?php

namespace Caffeine\Storage;

interface File
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
