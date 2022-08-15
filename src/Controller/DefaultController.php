<?php
namespace App\Controller;

use App\Form\SearchType;
use App\Helper\GenerateImage;
use App\Helper\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\DateManager;

class DefaultController extends AbstractController
{
    #[Route('/')]
    public function index(Request $request, GenerateImage $generateImage, SearchService $searchService, DateManager $dateManager, string $asset)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getData()['search'] !== "") {
                setlocale(LC_TIME, 'fr_FR');
                date_default_timezone_set('Europe/Paris');
                $dateNow = getdate();
                $searchResult = $searchService->searchByLocalisation($form->getData()['search'], $dateNow);

                $content = $this->renderView('Image/index.html.twig', array(
                    "date" => $dateManager->getDateFormatFr($dateNow),
                    "data_icon" => base64_encode($generateImage->getContentIcone($asset . $searchResult["currentConditions"]["icon"] . ".png")),
                    "temp" => $searchResult["currentConditions"]["temp"],
                    "localisation" => $form->getData()['search']
                ));
                $filename = "meteo-".strtolower($form->getData()['search']);
                $response = new Response();
                $response->headers->set('Content-Disposition', "attachment; filename=$filename.jpg");
                $response->setContent($generateImage->generateSnappyImage($content));
                return $response;
            }
        }

        return $this->renderForm('Default/index.html.twig', array(
            "form" => $form
        ));
    }
}