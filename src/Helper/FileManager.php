<?php


namespace App\Helper;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    private $filesystem;

    public function __construct(FileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Création du fichier
     * @param $path
     * @param $content
     */
    public function createFile($path, $content)
    {
        try {
            $this->filesystem->dumpFile($path, $content);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }
    }

    /**
     * Retourne le contenu du fichier si celui-ci existe
     * @param $path
     * @return false|string|null
     */
    public function getContentFile($path)
    {
        if(!$this->filesystem->exists($path)) {
            return null;
        }

        try{
            $content = file_get_contents($path);
        } catch (\Exception $exception) {
            $content = null;
        }
        return $content;
    }

    /**
     * Supprime le fichier
     * @param string $path
     */
    public function removeFile(string $path){
        try{
            $this->filesystem->remove($path);
        } catch (\Exception $exception) {

        }
    }
}