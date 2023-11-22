<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\IngredientRecipe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('api/test/trois')]
    public function apiTestTrois(HttpClientInterface $client, ManagerRegistry $doctrine)
    {
        $client = $client->withOptions([
            'headers' => [
                // 'x-app-key' => 'f9073faf6bdc00e3b9f2f2cbe099f464',
                // 'x-app-id' =>  'a4edc91f'
            ],

        ]);

        // Récupération des données sur les CATEGORIES
        $cat = $client->request(
            'GET',
            // 'https://trackapi.nutritionix.com/v2/search/instant?query=chocolate'
            'https://www.themealdb.com/api/json/v1/1/list.php?c=list'
        );
        $categories = ($cat->toArray())['meals'];
        // dd($categories);

        // CREATION des variables et INSERTION des données dans la db

        // pour chaque catégorie

        $arrcategories = [];
        foreach ($categories as $categorie) {
            $arrcategories[] = $categorie['strCategory'];
        }

        // dd($arrcategories);

        for ($i = 0; $i < count($arrcategories); $i++) {
            $em = $doctrine->getManager();
            $cat = new Category();
            $cat->setTitre($arrcategories[$i]);
            $em->persist($cat);
        }
        $em->flush();


        // dd();



        // Récupération des données sur les RECETTES PAR ORDRE ALPHABETIQUE

        // $allLetters = range('c', 'd');
        // $allLetters =  range('a', 'b');
        // LETTRES QUI RENVOIT DES DONNEES SANS PROBLEME (a,d,e,g,i,j, k, l,m, n, o,p,s,t, w)
        $letter = 't';
        // $recipes = [];
        // foreach ($allLetters as $letter) {
        $response = $client->request(
            'GET',
            // 'https://trackapi.nutritionix.com/v2/search/instant?query=chocolate'
            'https://www.themealdb.com/api/json/v1/1/search.php?f=' . $letter
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $recipes = ($response->toArray())['meals'];

        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        // }
        dump($recipes);



        // Recupération de toutes les recettes

        // for ($i = 0; $i < count($recipes); $i++) {
        //     $rec = $recipes[$i];
        // }
        // dd($rec);

        // pour chaque recette
        foreach ($recipes as $recipe) {

            // array d'INGREDIENTS, nouveau pour chaque repas
            $arrIngredients = [];
            // array de MESURE, nouveau pour chaque repas
            $arrAllMesures = [];
            $arrFr_qte = [];
            $arrFr_mesure = [];
            // array de RECIPEINFO, nouveau pour chaque repas
            $arrRecipeInfo = [];

            foreach ($recipe as $key => $val) {

                // creer un array de RECIPEINFO pour apres faire une boucle et faire addRecipeInfo
                if ($key == "idMeal" || $key == "strMeal" || $key == "strInstructions" || $key == "strMealThumb" || $key == "strCategory") {
                    $arrRecipeInfo[$key] = $val;
                } else if (strpos($key, "strIngredient") !== false) {
                    // creer un array d'INGREDIENTS pour apres faire une boucle et faire addINGREDIENT
                    if ($val != "" && !is_null($val)) {
                        $arrIngredients[] = $val;
                    }
                } else if (strpos($key, "strMeasure") !== false) {
                    // creer un array de MESURE pour apres faire une boucle et faire addMESURE
                    if ($val != "" && $val != " " && !is_null($val)) {
                        $arrAllMesures[] = $val;
                        /*
                        $arrMesures =  $val;
                        // Séparer les nombres des lettres (cas g/; )
                        $arrMeasFr = (preg_split('/(?<=[0-9])(?=[a-zA-Z])/', $arrMesures, -1, PREG_SPLIT_NO_EMPTY));
                        if (isset($arrMeasFr[1])) {
                            $arrFr_qte[] = $arrMeasFr[0];
                            $arrFr_mesure[] = $arrMeasFr[1];
                        } else {
                            $arrFr_qte[] = $arrMeasFr[0];
                            $arrFr_mesure[] = "";
                        }*/
                       
                        $arrMesures =  $val;
                        $caractere = "g/";
                        if (str_contains($arrMesures, $caractere)) {
                            $string = substr($arrMesures, 0, strpos($arrMesures, $caractere));
                            $arrFr_qte[] = preg_replace('/[^0-9\/.]/', ' ', $string);

                            // Isoler les valeurs textuelles
                            // $arrFr_mesure[] = preg_replace('/[0-9\/. ]/', '', $string);
                            $arrFr_mesure[] = 'g';

                            // echo "Valeur numérique : " . $numericValue . "<br>";
                            // echo "Valeur textuelle : " . $textValue;
                        } else {
                            $string = $arrMesures;
                            $arrFr_qte[] = preg_replace('/[^0-9\/.]/', ' ', $string);

                            // Isoler les valeurs textuelles
                            $arrFr_mesure[] = preg_replace('/[0-9\/. ]/', '', $string);

                            // echo "Valeur numérique : " . $numericValue . "<br>";
                            // echo "Valeur textuelle : " . $textValue;
                        }

                    }
                }
            }


            $em = $doctrine->getManager();
            $rep = $em->getRepository(Category::class);

            $catRecipe = $arrRecipeInfo['strCategory'];
            $cat = $rep->findOneByTitre($catRecipe);
            // dd($cat);
            $recipe = new Recipe();
            $recipe->setTitre($arrRecipeInfo['strMeal']);
            $recipe->setInstruction($arrRecipeInfo['strInstructions']);
            $recipe->setRecipeId($arrRecipeInfo['idMeal']);
            $recipe->setImage($arrRecipeInfo['strMealThumb']);
            $recipe->setCategory($cat);
            $em->persist($recipe);

            for ($i = 0; $i < count($arrIngredients); $i++) {
                $rep = $em->getRepository(Ingredient::class);
                $ingredientExistant = $rep->findOneByNom($arrIngredients[$i]);
                if (!$ingredientExistant) {
                    $ingredient = new Ingredient();
                    $ingredient->setNom($arrIngredients[$i]);
                    $em->persist($ingredient);
                } else {
                    $ingredient = $ingredientExistant;
                }

                $ingredientRecipe = new IngredientRecipe();
                $ingredientRecipe->setQuantityMeasure($arrAllMesures[$i]);
                $ingredientRecipe->setQuantity($arrFr_qte[$i]);
                $ingredientRecipe->setMeasure($arrFr_mesure[$i]);
                $recipe->addingredientRecipe($ingredientRecipe);
                $ingredient->addingredientRecipe($ingredientRecipe);
                $em->persist($ingredientRecipe);
            }

            // dump($arrIngredients);
            // dump($arrFr_qte);
            // dump($arrMesures);
            // dump($arrFr_mesure);
            //dump($arrRecipeInfo);
            // dump($arrIngredients); 


            $em->flush();
        }

        dd();
    }
}
