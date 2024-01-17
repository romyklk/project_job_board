<?php 

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class UploadFilesService extends AbstractController
{

    // Cette méthode va permettre de générer un nom unique pour le fichier uploadé.Cela permet d'éviter les doublons
    private function generateUniqueFileName()
    {
        $name = bin2hex(random_bytes(16)).''. uniqid();

        return $name;
    }

    // Cette méthode va permettre de déplacer le fichier uploadé dans le dossier public/uploads

    public function saveFileUpload($file)
    {
        // Récupération du nom du fichier
        $fileName = $file->getClientOriginalName();

        // Génération d'un nom unique pour le fichier
        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

        // Déplacement du fichier dans le dossier public/uploads
        $file->move(
            $this->getParameter('uploads_directory'),
            $fileName);

        return $fileName;
    }

}