<?php

namespace Caffeine\Storage;

class PhpFilePublisher
{
    const FILE = <<<FILE
<?php

return %s;
FILE;

    /**
     * @param $file
     * @param $data
     * @return bool
     */
    public function write($file, $data)
    {
        $file = new \SplFileObject($file, 'w+');
        $bytes = $file->fwrite(sprintf(self::FILE, var_export($data, true)));

        return ($bytes !== null);
    }
}
