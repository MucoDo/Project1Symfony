<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\IngredientRecipe;
use App\Form\SearchIngredientType;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\serializer;

class SearchIngredientController extends AbstractController
{
    #[Route('/search/ingredient', name: 'search_ingredient')]
    public function searchIngredient(Request $req, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $form = $this->createForm(SearchIngredientType::class);
        $form->handleRequest($req);



        if ($form->isSubmitted() && $form->isValid()) {
            $rep = $doctrine->getRepository(Ingredient::class);

            // ici ça fait référence à la méthode propre qu'on va ajouter dans le repo IngredientRepositoty.php
            //car on fera appel à la base de données
            $resultats = $rep->searchIngredient($form->getData());
            // dd($resultats);

            //     $ingredients = [];
            //     foreach ($resultats as $plante) {
            //         $arrIng = [];

            //         $arrIng['nom'] = $plante->getTitre();

            //         // rajouter le livre ayant l'array d'auteurs incrusté
            //         $ingredients[] = $arrIng;
            //    }


            $response = $serializer->serialize($resultats, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['ingredientRecipes', 'category']]);
            return new Response($response);
        }
        


        // serialization sinon erreur "Cannot use object of type App\Entity\Ingredient as array"
        // renvois du résultat JSON


        $vars = ['form' => $form];
        // dd($vars);
        return $this->render('form_search_ingredient_filtre_ajax/index.html.twig', $vars);
        // return $this->render('search_ingredient/index.html.twig',$vars);


    }

    #[Route('/show/recipe/{id}', name: 'show_recipe')]

    public function showRecipe(RecipeRepository $rep,  Request $req)
    {
        $id = $req->get('id');
        $recipe = $rep->showRecipe($id);
        $ingRecette = $rep->showIngRecipe($id);
        $vars = [
            'recipe' => $recipe,
            'ingRecette' => $ingRecette
        ];
        // dd($vars);
        return $this->render("form_search_ingredient_filtre_ajax/show_recipe.html.twig", $vars);
    }

    // public function showRecipe (RecipeRepository $rep,  Request $req){

    //     // $id=$req->get('id');
    //     // $recipe=$rep->find($id);
    //     // $ingRecipe=$recipe->getIngredientRecipes();

    // à demander - - - - -pourquoi le getIngredient ne fonctionne pas alors qu'il exite bien dans IngredientRecipe??- - - - -

    //     // $ingredient=[];
    //     // foreach ($ingRecipe as $ing){
    //     //     $ingredienT=$ing->getIngredient();
    //     //     $ingredient[]=$ingredienT;
    //     // }

    //     // dd($quantities);
    //     // $ingredient=$quantity->getIngredient();

    //     // dd($ing);
    //     $vars=['recipe'=>$recipe,
    //     'ingRecipe'=>$ingRecipe,
    //         'ingredient'=>$ingredient];

    //     return $this->render("form_search_ingredient_filtre_ajax/show_recipe.html.twig",$vars);

    // }
}