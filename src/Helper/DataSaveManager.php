<?php


namespace App\Helper;


class DataSaveManager
{
    private $cityData;
    private $fileManager;
    private $jsonSaveData;

    public function __construct(FileManager $fileManager, string $jsonSaveData){
        $this->fileManager = $fileManager;
        $this->jsonSaveData = $jsonSaveData;
        $this->setCityData();
    }

    /**
     * Initialise le tableau $cityData
     * Setter $cityData
     * @return void
     */
    private function setCityData(){
        $content = $this->fileManager->getContentFile($this->jsonSaveData);
        if($content) {
            $this->cityData = json_decode($content, true);
        } else {
            $this->cityData = [];
        }
    }

    /**
     * Getter $cityData
     * @return array
     */
    public function getCityData(){
        return $this->cityData;
    }

    /**
     * Ajoute un élément dans le tableau $cityData
     * @param $city
     * @param $filename
     * @param $contentPng
     */
    public function addCityData(string $city, string $filename, string $contentPng){
        $dateNow = new \DateTime('now');
        $expire = clone $dateNow;
        $expire->modify('+2 hours');

        $this->cityData[$city] = array(
            "filename" => $filename,
            "expire" => $expire,
            "pathPng" => $contentPng
        );
    }

    /**
     * Sauvegarde des données dans le fichier json
     */
    public function saveCityData(){
        $this->fileManager->createFile($this->jsonSaveData, json_encode($this->cityData));
    }
}