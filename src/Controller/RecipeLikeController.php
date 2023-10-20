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
    public function recipeLike(UserRepository $rep,RecipeRepository $rep2,  Request $req){
        $id=$req->get('id');
        // dd($id);
        $recipe=$rep2->find($id);
        $user=$rep->$this->user;
        $user->addRecipe($recipe);
        return $this -> render('recipe_like/index.html.twig');
        
    }
}