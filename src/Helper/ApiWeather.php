<?php

namespace App\Helper;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiWeather
{

    const UNITGROUP = "metric";

    private $client;
    private $pathWeatherApi;
    private $apiKey;

    public function __construct(HttpClientInterface $client, $pathWeatherApi, $apiKey)
    {
        $this->client = $client;
        $this->pathWeatherApi = $pathWeatherApi;
        $this->apiKey = $apiKey;
    }

    private function getRequest(array $pathUrl, array $parameters = [], $method = "GET")
    {
        $parameters[] = "key=$this->apiKey";

        $response = $this->client->request(
            $method,
            $this->pathWeatherApi . "/" . implode('/', $pathUrl) . "?" . implode('&', $parameters)
        );
        return $response->toArray();;
    }

    /**
     * @param string $location
     * @param string $dateStart format Y-m-d
     * @param string $dateEnd format Y-m-d
     * @return array
     */
    public function getWeatherForLocation(string $location, string $dateStart, string $dateEnd)
    {
        return $this->getRequest(array("VisualCrossingWebServices", "rest", "services", "timeline", $location, $dateStart, $dateEnd), array("unitGroup=metric","contentType=json"));
    }
}