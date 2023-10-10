<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\SearchIngredientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchIngredientController extends AbstractController
{
    #[Route('/search/ingredient', name: 'search_ingredient')]
    public function SearchIngredient(Request $req, ManagerRegistry $doctrine): Response
    {
        $form= $this-> createForm(SearchIngredientType::class);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){
            $rep=$doctrine->getRepository(Ingredient::class);
            $resultats=$rep->SearchIngredient($form->getData());
        }

        
        return $this->render('search_ingredient/index.html.twig', [
            'searchIngForm' => $form->createView()
        ]);
    }
}
