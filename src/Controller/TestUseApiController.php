<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestUseApiController extends AbstractController
{

    #[Route('api/test/deux')]
    public function apiTestDeux(HttpClientInterface $client)
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
        $recipes = ($response->toArray())['meals'];
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        dump($recipes);
        // dump($content);
        // dd();

        // pour chaque recette
        foreach ($recipes as $recipe) {


            // array d'INGREDIENTS, nouveau pour chaque repas
            $arrIngredients = [];
            // array de MESURE, nouveau pour chaque repas
            $arrMesures = [];
            // array de RECIPEINFO, nouveau pour chaque repas
            // $arrRecipeInfo = [];

            foreach ($recipe as $key => $val) {

                // creer objet Recette
                // $recipe=new Recipe();

                // creer un array de RECIPEINFO pour apres faire une boucle et faire addRecipeInfo
                if ($key == "idMeal" || $key == "strMeal" || $key == "strInstructions") {
                    $arrRecipeInfo[$key] = $val;
                }

                else if (strpos($key, "strIngredient") !== false) {
                    // creer un array d'INGREDIENTS pour apres faire une boucle et faire addINGREDIENT
                    if ($val != "" && !is_null($val)) {
                        $arrIngredients[] = $val;
                    }
                } else if (strpos($key, "strMeasure") !== false) {
                    // creer un array de MESURE pour apres faire une boucle et faire addMESURE
                    if ($val != "" && $val != " " && !is_null($val)) {
                        $arrMesures[] = $val;
                    }
                }

                // parcourir les arrays ingredients-quantite pour rajouter des objets IngredientRecette

            }
        }
        dump($arrIngredients);
        dump($arrMesures);
        dump($arrRecipeInfo);
        dd();
    }
}