<?php

namespace App\Helper;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiWeather
{

    const UNITGROUP = "metric";

    private $client;
    private $pathWeatherApi;
    private $apiKey;
    private $dataSaveManager;

    public function __construct(HttpClientInterface $client, $pathWeatherApi, $apiKey, DataSaveManager $dataSaveManager)
    {
        $this->client = $client;
        $this->pathWeatherApi = $pathWeatherApi;
        $this->apiKey = $apiKey;
        $this->dataSaveManager = $dataSaveManager;
    }

    private function getRequest(array $pathUrl, array $parameters = [], $method = "GET")
    {
        $parameters[] = "key=$this->apiKey";

        $response = $this->client->request(
            $method,
            $this->pathWeatherApi . "/" . implode('/', $pathUrl) . "?" . implode('&', $parameters)
        );

        return array(
            "code" => $response->getStatusCode(),
            "content" => $response->toArray()
        );
    }

    /**
     * @param string $location
     * @param string $dateStart format Y-m-d
     * @param string $dateEnd format Y-m-d
     * @return array
     */
    public function getWeatherForLocation(string $city, string $dateStart, string $dateEnd)
    {
        // Si les données ont déjà été récupérées
        $datas = $this->dataSaveManager->getCityData();

        if(count($datas) > 0 && isset($datas[$city]) && new \DateTime($datas[$city]["expire"]["date"]) > new \DateTime('now')) {
            // Retourne les données sauvegardées
            $response = $datas[$city];
        } else {
            // Appel API
            $result = $this->getRequest(array("VisualCrossingWebServices", "rest", "services", "timeline", $city, $dateStart, $dateEnd), array("unitGroup=metric","contentType=json"));

            if($result["code"] === Response::HTTP_OK){
                if(!$result["content"]["currentConditions"]){
                    // Récupération de l'historique
                    $result["content"]["currentConditions"] = $result["content"]["days"][0];
                }

                $response = array(
                    "new" => true,
                    "icon" => $result["content"]["currentConditions"]["icon"],
                    "temp" => $result["content"]["currentConditions"]["temp"]
                );
            } else {
                $response = null;
            }
        }
        return $response;
    }
}