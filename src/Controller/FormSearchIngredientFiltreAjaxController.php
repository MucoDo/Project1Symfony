<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormSearchIngredientFiltreAjaxController extends AbstractController
{
    #[Route('/form/search/ingredient/filtre/ajax', name: 'app_form_search_ingredient_filtre_ajax')]
    public function index(): Response
    {
        return $this->render('form_search_ingredient_filtre_ajax/index.html.twig', [
            'controller_name' => 'FormSearchIngredientFiltreAjaxController',
        ]);
    }
}
