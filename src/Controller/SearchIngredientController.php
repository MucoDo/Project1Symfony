<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\IngredientRecipe;
use App\Form\SearchIngredientType;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
    public function searchIngredient(Request $req, ManagerRegistry $doctrine, SerializerInterface $serializer,  PaginatorInterface $paginator,Request $request): Response
    {
        $form = $this->createForm(SearchIngredientType::class);
        $form->handleRequest($req);

        $em = $doctrine->getManager();
        $rep = $em->getRepository(Recipe::class);
        $recipesAll = $paginator->paginate($rep->findAll(),
        $request->query->getInt('page', 1), 
        20 );
        
        // $vars = ['form' => $form];
        // dd($recipesAll);
        return $this->render('form_search_ingredient_filtre_ajax/index.html.twig',['recettes'=> $recipesAll,'form' => $form]);
        // return $this->render('search_ingredient/index.html.twig',$vars);


    }
    #[Route('/search/ingredient/ajax/traitement', name: 'search_ingredient_ajax_traitement')]
    public function searchIngredientAjaxTraitement(Request $req, ManagerRegistry $doctrine, SerializerInterface $serializer,  PaginatorInterface $paginator,Request $request): Response
    {
        $form = $this->createForm(SearchIngredientType::class);
        $form->handleRequest($req);


        $rep = $doctrine->getRepository(Ingredient::class);

        $resultats = $rep->searchIngredient($form->getData());
        // dd($resultats);
        $numeroPage=$request->query->getInt('page', 1);

        $recipesAll = $paginator->paginate($resultats,
        $numeroPage,
        20);
        // dd($recipesAll);
        
        $response= [
            'recipesAll' => $recipesAll,
            'form' => $form
            // 'pagination' => $responseALL
        ];
        
        // dd($response);

        // return new Response($response);
        return new Response($this->renderView('form_search_ingredient_filtre_ajax/index.ajax.html.twig',$response));
    }


    #[Route('/show/recipe/{id}', name: 'show_recipe')]

    public function showRecipe(RecipeRepository $rep,  Request $req)
    {
        $id = $req->get('id');
        $recipe = $rep->showRecipe($id);
        // dump( $recipe);
        $favRecipes=$this->getUser()->getRecipes()->toArray();
        // $favRecipes=$this->getUser()->getRecipes();
        $i=0;
        // dump( $favRecipes[$i]->getId());
        // dd( $recipe[0]->getId());
        $isfavorite=false;
        while ($i<count($favRecipes) && $isfavorite==false){
            if ($favRecipes[$i]->getId()==$recipe[0]->getId()){
                $isfavorite=true;
            }
            $i++;
        }
        // dd( $isfavorite);

        $ingRecette = $rep->showIngRecipe($id);
        $vars = [
            'recipe' => $recipe,
            'ingRecette' => $ingRecette,
            'isfavorite'=>$isfavorite
        ];
        // dd($vars);
        return $this->render("form_search_ingredient_filtre_ajax/show_recipe.html.twig", $vars);
    }
}
