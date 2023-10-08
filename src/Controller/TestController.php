<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test/measure')]
    public function testMeasure() {
        $array = ["175gr/6oz"]; // Votre tableau contenant l'élément à séparer

        // Séparation de l'élément en deux parties
        $elements = explode('/', $array[0]);
        dump($elements);
        
        // Stockage des parties séparées dans des variables distinctes
        $premierElement = $elements[0];
        $deuxiemeElement = $elements[1];
        
        // Affichage des résultats
        echo "Premier élément : " . $premierElement . "<br>";
        echo "Deuxième élément : " . $deuxiemeElement;
        dd();
    }
    
}
