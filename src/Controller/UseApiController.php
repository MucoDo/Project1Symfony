<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UseApiController extends AbstractController
{

    #[Route('api/test')]
    public function apiTest(HttpClientInterface $client)
    {

        $client = $client->withOptions([
            'headers' => [
                // 'x-app-key' => 'f9073faf6bdc00e3b9f2f2cbe099f464',
                // 'x-app-id' =>  'a4edc91f'
            ],

        ]);

        $response = $client->request(
            'GET',
            // 'https://trackapi.nutritionix.com/v2/search/instant?query=chocolate'
            'https://www.themealdb.com/api/json/v1/1/search.php?f=a'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $recipees = ($response->toArray())['meals'];
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        dump($recipees);

        // pour chaque recette
        foreach ($recipees as $recipee) {

            // array d'ingredients, nouveau pour chaque repas
            $arrIngredients = [];

            // creer un objet Recipee
            foreach ($recipee as $key => $val) {
                if (strpos($key, "strIngredient") !== false) {
                    // creer un array d'ingredients pour apres faire une boucle et faire addIngredient
                    if ($val != "" && !is_null($val)){
                        $arrIngredients[] = $val;
                    }
                }
            }
            // ici on a tous les ingredients de chaque recette
            dump ($arrIngredients);
        }
        
        dd();
    }
}
