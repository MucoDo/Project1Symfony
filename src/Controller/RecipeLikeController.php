<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeLikeController extends AbstractController{
    #[Route('/recipe/like', name: 'recipe_like')]
    public function recipeLike(ManagerRegistry $doctrine,UserRepository $rep,RecipeRepository $rep2,  Request $req){
        $id=$req->get('id');
        // dd($id);
        $recipe=$rep2->find($id);
        // dd($recipe);
        $user = $this->getUser();
        // dd($user);
        
        $user->addRecipe($recipe);

        $em = $doctrine->getManager();
        $em->flush();

        $favoris= $user->getRecipes()->toArray();

        dd($favoris);
        $vars=['favoris' => $favoris];

        return $this -> render('recipe_like/index.html.twig',$vars);
        
    }


}