<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeLikeController extends AbstractController{
    #[Route('/recipe/like', name: 'recipe_like')]
    public function recipeLike(UserRepository $rep,  Request $req){
        $id=$req->get('id');
        dd($id);
        return $this -> render('recipe_like/index.html.twig');
        
    }
}