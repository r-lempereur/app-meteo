<?php


namespace App\Helper;

use Knp\Snappy\Image;

class GenerateImage
{
    private $snappyUImage;
    private $fileManager;
    private $tmpFolder;

    function __construct(Image $snappyUImage, FileManager $fileManager, string $tmpFolder)
    {
        $this->snappyUImage = $snappyUImage;
        $this->fileManager = $fileManager;
        $this->tmpFolder = $tmpFolder;
    }

    /**
     * Retourne le contenu de l'image généré par Snappy
     * @param string $filename
     * @param string $content
     * @return string
     */
    public function generateSnappyImage(string $filename, string $content)
    {
        $contentJpg = $this->snappyUImage->getOutputFromHtml($content);

        $this->fileManager->createFile($this->tmpFolder.$filename.".jpg", $contentJpg);

        return $this->saveImage($filename);
    }

    /**
     * Retourne l'icône correspondante
     * @param string $path
     * @return false|string
     */
    public function getIcone(string $path)
    {
        try{
            $content = $this->fileManager->getContentFile($path);
        } catch(\Exception $ex){
            $content = "";
        }

        return $content ;
    }

    /**
     * Sauvegarde de l'image au format png
     * @param string $filename
     * @param string $extension
     * @return string
     */
    public function saveImage(string $filename, string $extension = "jpg")
    {
        $pathImage = $this->tmpFolder.$filename.".".$extension;
        $image = $this->fileManager->getContentFile($pathImage);

        // Calcul des nouvelles dimensions
        list($width, $height) = getimagesize($pathImage);
        $newwidth = $width * 1;
        $newheight = $height * 1;

        // Initialisation des images
        $thumb = imagecreatetruecolor(600, 600);
        $source = imagecreatefromstring($image);

        // Redimensionnement
        imagecopyresampled($thumb, $source, -300, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Création du png
        $pathPng = $this->tmpFolder.$filename.".png";
        imagepng($thumb, $pathPng);

        // Suppression du jpg
        $this->fileManager->removeFile($pathImage);

        return $pathPng;
    }
}