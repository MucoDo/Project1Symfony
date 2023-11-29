<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class RecipeLikeController extends AbstractController{
    #[Route('/recipe/like', name: 'recipe_like')]
    public function recipeLike(ManagerRegistry $doctrine,UserRepository $user,RecipeRepository $rep2,  Request $req){
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('msg',"Veuillez vous connecter ou vous inscrire pour ajouter aux favoris");
            return $this->redirectToRoute('app_login');
        } else {

            $id=$req->get('id');
            // dd($id);
            $recipe=$rep2->find($id);
            // dd($recipe);
            $user = $this->getUser();
            $containsRecipe =  $user->getRecipes()->contains($recipe);
            // dd($containsRecipe);
            $heartColor='bleu';
            if ($containsRecipe){
                $user->removeRecipe($recipe);
                $heartColor='bleu';
                
            }
            else{
                $user->addRecipe($recipe); 
                $heartColor='red';
            }

            $em = $doctrine->getManager();

            $em->flush();


            $response= ['heartColor'=>$heartColor];


            // Renvoyer une response vide sinon symfony va générer une erreur disant que la réponse n'a pas été envoyée
            // return new Response();
            return new JsonResponse($response);
    }

        
}



    // #[Route('/recipe/unlike', name: 'recipe_unlike')]
    // public function recipeUnlike(ManagerRegistry $doctrine,UserRepository $rep,RecipeRepository $rep2,  Request $req){
    //     $id=$req->get('id');
    //     // dd($id);
    //     $recipe=$rep2->find($id);
    //     // dd($recipe);
    //     $user = $this->getUser();
    //     // dd($user);
        
    //     $user->removeRecipe($recipe);

    //     $em = $doctrine->getManager();
    //     $em->flush();

    //     // Renvoyer une response vide sinon symfony va générer une erreur disant que la réponse n'a pas été envoyée
    //     return new Response();
    //     // $favoris= $user->getRecipes()->toArray();


        
    // }

    #[Route('/recipe/show/likes', name: 'recipe_show_likes')]
    public function recipeShowLikes(UserRepository $rep, ManagerRegistry $doctrine){
        $user = $this->getUser();
        if (!$user) {
            return new Response('Veuillez vous connectez pour ajouter aux favoris');
        } else {
        $favoris= $user->getRecipes()->toArray();

        // dd($favoris);
        $vars=['favoris' => $favoris];

        return $this -> render('recipe_like/recipe_show_likes.html.twig',$vars);
        }
    }

}