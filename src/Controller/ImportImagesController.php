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
        $em = $doctrine->getManager();
        $rep = $em->getRepository(Recipe::class);
        $recipeImages=$rep->findby(['id' => '<10']);
        // dd($recipeImages);
        $assetsDir = "/assets/images";
        // dd($assetsDir);
        foreach ($recipeImages as $recImg){
            $url=$recImg->getimage();
            // $filepath = $assetsDir . "/" . $url;
            $imageData = file_get_contents($url);
            $fileName = uniqid() . '.jpg';
            dump($imageData);

            file_put_contents($assetsDir . $fileName, $imageData);
            // Téléchargement de l'image depuis l'URL HTTPS
            // $ch = curl_init($url);
            // $fp = fopen($filepath, 'wb');
            // curl_setopt($ch, CURLOPT_FILE, $fp);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_exec($ch);
            // curl_close($ch);
            // fclose($fp);
        }
        $test="all is good";
        dd();

    }
}

/*
php

// Connexion à la base de données
$servername = "localhost";
$username = "votre_nom_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "votre_base_de_donnees";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Récupération des liens URL des images depuis la table
$sql = "SELECT lien_image FROM votre_table";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Création du dossier "assets" s'il n'existe pas déjà
    $assetsDir = __DIR__ . "/../public/assets";
    if (!is_dir($assetsDir)) {
        mkdir($assetsDir);
    }

    // Parcours des résultats et enregistrement des images
    while ($row = $result->fetch_assoc()) {
        $url = $row["lien_image"];
        $filename = basename($url);
        $filepath = $assetsDir . "/" . $filename;

        // Téléchargement de l'image depuis l'URL
        file_put_contents($filepath, file_get_contents($url));
    }

    echo "Les images ont été enregistrées avec succès dans le dossier 'assets'!";
} else {
    echo "Aucune image trouvée dans la base de données.";
}

// Fermeture de la connexion à la base de données
$conn->close();

*/