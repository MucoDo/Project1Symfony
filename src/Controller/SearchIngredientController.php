<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\SearchIngredientType;
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
            $response = $serializer->serialize($resultats, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['ingredientRecipes']]);

            return new Response($response);
        }

        // serialization sinon erreur "Cannot use object of type App\Entity\Ingredient as array"
        // renvois du résultat JSON


        $vars = ['searchIngForm' => $form];
        return $this->render('search_ingredient/index.html.twig', [
            'searchIngForm' => $vars
        ]);
    }
}
