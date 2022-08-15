<?php


namespace App\Helper;

use Knp\Snappy\Image;

class GenerateImage
{
    private $snappyUImage;

    function __construct(Image $snappyUImage){
        $this->snappyUImage = $snappyUImage;
    }

    public function generateSnappyImage($content){
        return $this->snappyUImage->getOutputFromHtml($content);
    }

    public function getContentIcone($path){
        try{
            $content = file_get_contents($path);
        } catch(\Exception $ex){
            $content = "";
        }

        return $content ;
    }
}