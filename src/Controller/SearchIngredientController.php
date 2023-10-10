<?php

namespace App\Controller;

use App\Form\SearchIngredientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchIngredientController extends AbstractController
{
    #[Route('/search/ingredient', name: 'app_search_ingredient')]
    public function SearchIngredient(Request $req, ManagerRegistry $doctrine): Response
    {
        $form= $this-> createForm(SearchIngredientType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){
            dd($form->getData());
        }

        
        return $this->render('search_ingredient/index.html.twig', [
            'searchIngForm' => $form->createView()
        ]);
    }
}
