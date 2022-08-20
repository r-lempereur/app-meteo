<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\DateManager;
use App\Form\SearchType;
use App\Helper\DataSaveManager;
use App\Helper\GenerateImage;
use App\Helper\SearchService;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param GenerateImage $generateImage
     * @param SearchService $searchService
     * @param DateManager $dateManager
     * @param string $asset
     * @param DataSaveManager $dataSaveManager
     * @return Response
     */
    public function index(Request $request, GenerateImage $generateImage, SearchService $searchService, DateManager $dateManager, string $asset, DataSaveManager $dataSaveManager)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $infos = array("error" => false, "message" => "");

        if ($form->isSubmitted() && $form->getData()['search'] !== "") {
            setlocale(LC_TIME, 'fr_FR');
            date_default_timezone_set('Europe/Paris');
            $city = $form->getData()['search'];
            $dateNow = getdate();
            $searchResult = $searchService->searchByLocalisation($city, $dateNow);
            $filename = "meteo-".strtolower($city);

            if($searchResult){
                if(isset($searchResult['new'])){
                    // Création du template pour l'image
                    $content = $this->renderView('Image/index.html.twig', array(
                        "date" => $dateManager->getDateFormatFr($dateNow),
                        "data_icon" => base64_encode($generateImage->getIcone($asset . $searchResult["icon"] . ".png")),
                        "temp" => $searchResult["temp"],
                        "localisation" =>  $city
                    ));

                    // Génération de l'image
                    $pathPng = $generateImage->generateSnappyImage($filename, $content);

                    // Sauvegarde des données
                    $dataSaveManager->addCityData($city, $filename, $pathPng);
                    $dataSaveManager->saveCityData();
                } else {
                    // Retourne l'image directement
                    $pathPng = $dataSaveManager->getCityData()[$city]["pathPng"];
                }

                $stream = new Stream($pathPng);
                return new BinaryFileResponse($stream, 200, array(
                    "Content-Type" => "image/png",
                    "Content-Disposition" => "Attachment;filename=$filename.png"
                ));

            } else {
                // Erreur lors de la récupération des données API WEATHER
                $infos['error'] = true;
                $infos['message'] = "Il n'y a pas de données pour la ville saisie, veuillez essayer une autre ville.";
            }
        } else {
            // Erreur la saisie est vide
            $infos['error'] = true;
            $infos['message'] = "Le champ Localisation est obligatoire";
        }

        return $this->renderForm('Default/index.html.twig', array(
            "form" => $form,
            "infos" => $infos
        ));
    }
}