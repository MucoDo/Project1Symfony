<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImportImagesController extends AbstractController
{
    #[Route('/import/images', name: 'app_import_images')]
    public function index(ManagerRegistry $doctrine)
    {

        // REGARDER VIDEO https://www.youtube.com/watch?v=3ziRqzDcG0w
        $url_to_image = 'https://www.themealdb.com/images/media/meals/xvsurr1511719182.jpg';
          // obtenir le entity manager
        $em = $doctrine->getManager();
        // obtenir le repository
        $rep = $em->getRepository(Recipe::class);
        $recImages = $rep->findAll();
        // dd($recImage);
        $my_save_dir = 'C:\xampp\htdocs\Project1Symfony\assets\images';

        foreach($recImages as $recImage ){
            $url_to_image=$recImage->getImage();
            $filename = basename($url_to_image);
            $complete_save_loc = $my_save_dir . $filename;
            file_put_contents($complete_save_loc, file_get_contents($url_to_image));
        }

    }
}