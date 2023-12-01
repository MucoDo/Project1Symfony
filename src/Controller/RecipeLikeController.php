<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeLikeController extends AbstractController{
    #[Route('/recipe/like', name: 'recipe_like')]
    public function recipeLike(ManagerRegistry $doctrine,UserRepository $user,RecipeRepository $rep2,  Request $req){
        $user = $this->getUser();


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
    public function recipeShowLikes(UserRepository $user, ManagerRegistry $doctrine,Security $security){
        // $user = $this->getUser();
        // if ($this->getUser()) {
        //     $favoris= $user->getRecipes()->toArray();
            
        //     // dd($favoris);
        //     $vars=['favoris' => $favoris];
            
        //     return $this -> render('recipe_like/recipe_show_likes.html.twig',$vars);
        // } else {
        //     return new Response('Veuillez vous connectez pour ajouter aux favoris');
        // }

     
    // Vérifier si l'utilisateur est connecté
    if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        
        // Récupérer la liste des favoris de l'utilisateur
        $favoris = $user->getRecipes()->toArray();
        $vars=['favoris' => $favoris];
        
        // Afficher la liste des favoris
        return $this->render('recipe_like/recipe_show_likes.html.twig',$vars);
    } else {

         // Récupérer l'URL de connexion ou d'inscription
         $loginUrl = $this->generateUrl('app_login');
         $registerUrl = $this->generateUrl('app_register');

         // Message flash avec le lien pour se connecter ou s'inscrire
         $response=$this->addFlash('warning', 'Vous devez vous connecter ou vous inscrire pour accéder à cette page. <a href="'.$loginUrl.'">Se connecter</a> | <a href="'.$registerUrl.'">S\'inscrire</a>');
        return new JsonResponse($response);

    }


    }

}