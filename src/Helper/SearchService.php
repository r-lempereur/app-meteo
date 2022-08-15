<?php

namespace App\Helper;

use App\Helper\ApiWeather;

class SearchService
{
    private $apiWeather;
    private $dateManager;

    public function __construct(ApiWeather $apiWeather, DateManager $dateManager)
    {
        $this->apiWeather = $apiWeather;
        $this->dateManager = $dateManager;
    }

    public function searchByLocalisation(string $localisation, array $dateNow)
    {
        $result = null;
        try{
            $dateSearch = $this->dateManager->getDateFormatSearch($dateNow);
            $result = $this->apiWeather->getWeatherForLocation($localisation, $dateSearch, $dateSearch);

            if(!$result["currentConditions"]){
                $result["currentConditions"] = $result["days"][0];
            }
        }catch (\Exception $ex){

        }
        return $result;
    }
}