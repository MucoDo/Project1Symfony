<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestUseApiController extends AbstractController
{

    #[Route('api/test/deux')]
    public function apiTestDeux(HttpClientInterface $client, ManagerRegistry $doctrine)
    {

        $client = $client->withOptions([
            'headers' => [
                // 'x-app-key' => 'f9073faf6bdc00e3b9f2f2cbe099f464',
                // 'x-app-id' =>  'a4edc91f'
            ],

        ]);

        // API pour récupérer les données du site themealdb

        // API du site pour récupérer les RECETTES

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

        /*        // API du site pour récupérer les CATEGORIES et les PAYS

        $responseCat = $client->request(
            'GET',
            // 'https://trackapi.nutritionix.com/v2/search/instant?query=chocolate'
            'https://www.themealdb.com/api/json/v1/1/list.php?c=list '
        );

        $content = $responseCat->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $categories = $responseCat->toArray()['meals'];

        dump($categories);


        // Creation des variables et insertion des données dans la db
           // pour chaque catégorie
        
            $arrcategories = [];
            foreach ($categories as $categorie) {
                $arrcategories[] = $categorie['strCategory'];
            }
    
    
            for ($i = 0; $i < count($arrcategories); $i++) {
                $em = $doctrine->getManager();
                $cat = new Category();
                $cat->setCategoryTitle($arrcategories[$i]);
                $em->persist($cat);
            }
*/

        // pour chaque recette

        foreach ($recipes as $recipe) {
            // array d'INGREDIENTS, nouveau pour chaque repas
            $arrIngredients = [];
            // array de MESURE, nouveau pour chaque repas
            $arrMesures = [];
            // array de RECIPEINFO, nouveau pour chaque repas
            $arrRecipeInfo = [];
            // array de CATEGORY, nouveau pour chaque repas
            $arrCategory = [];


            foreach ($recipe as $key => $val) {

                // creer un array de RECIPEINFO pour apres faire une boucle et faire addRecipeInfo
                if ($key == "idMeal" || $key == "strMeal" || $key == "strInstructions" || $key == "strMealThumb") {
                    $arrRecipeInfo[$key] = $val;
                } else if ($key == "strCategory") {
                    $arrCategory[$key] = $val;
                } else if (strpos($key, "strIngredient") !== false) {
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
            }

            $em = $doctrine->getManager();
            $recipe = new Recipe();
            $recipe->setNom($arrRecipeInfo['strMeal']);
            $recipe->setInstruction($arrRecipeInfo['strInstructions']);
            $recipe->setIdRecipe($arrRecipeInfo['idMeal']);
            $recipe->setImageRecette($arrRecipeInfo['strMealThumb']);


            $em->persist($recipe);


            for ($i = 0; $i < count($arrIngredients); $i++) {
                $ingredient = new Ingredient();
                $ingredient->setNom($arrIngredients[$i]);
                $ingredient->setIdIngredient($i + 1);
                $em->persist($ingredient);

                $recipeIngredient = new RecipeIngredient();
                $recipeIngredient->setQuantity($arrMesures[$i]);
                $recipeIngredient->setMeasure($i + 1);
                $recipe->addRecipeIngredient($recipeIngredient);
                $ingredient->addRecipeIngredient($recipeIngredient);
                $em->persist($recipeIngredient);


                /*$category = new Category();
                $category->setCategoryTitle($arrCategory[$i]);
                $recipe->addCategory($category);
                $em->persist($category);*/
            }
        }

        dump($arrRecipeInfo);
        dump($arrIngredients);
        dump($arrMesures);
        dd($arrCategory);
        dd();

        // $em->flush();
    }
}
