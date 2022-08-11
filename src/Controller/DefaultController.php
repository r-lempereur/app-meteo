<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Helper\ApiWeather;

class DefaultController extends AbstractController
{
    #[Route('/')]
    public function index(ApiWeather $apiWeather)
    {
        $location = "Lyon";
        $dateNow = date_format(new \DateTime(),"Y-m-d");
        $result = $apiWeather->getWeatherForLocation("Lyon", $dateNow, $dateNow);
        $datas = array($result["currentConditions"]["temp"], $result["currentConditions"]["icon"], $location);

        $image = imagecreate(400, 400);
        imagecolorallocate($image, "200", "200", "200");
        imagecolorallocate($image, "0", "0", "0");
        $text_color = imagecolorallocate($image, 233, 14, 91);
        imagestring($image, 1, 5, 5,  "A Simple Text String", $text_color);
        header("Content-Type: image/png");
        $img = imagepng($image);
        dump($img); die;
        return $this->render('default/index.html.twig');
    }
}