<?php

namespace App\Services;

use Throwable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class UploadFilesService extends AbstractController
{

    // Cette méthode va permettre de générer un nom unique pour le fichier uploadé.Cela permet d'éviter les doublons
    private function generateUniqueFileName()
    {
        $name = bin2hex(random_bytes(16)) . '' . uniqid();

        return $name;
    }

    // Cette méthode va permettre de déplacer le fichier uploadé dans le dossier public/uploads

    public function saveFileUpload($file)
    {
        // Récupération du nom du fichier
        $fileName = $file->getClientOriginalName();

        // Génération d'un nom unique pour le fichier
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        // Déplacement du fichier dans le dossier public/uploads
        $file->move(
            $this->getParameter('uploads_directory'),
            $fileName
        );

        return $fileName;
    }

    // Cette méthode va permettre modifier un fichier uploadé dans le dossier public/uploads et de supprimer l'ancien fichier si il existe

    public function updateFileUpload($file, $oldFile)
    { // Traitement du fichier uploadé par la méthode saveFileUpload
        $fileName = $this->saveFileUpload($file);

        $this->deleteFileUpload($oldFile);

        return $fileName;
    }

    // Méthode permettant de supprimer un fichier uploadé dans le dossier public/uploads
    public function deleteFileUpload($file)
    {
        try {
            // Suppression de l'ancien fichier si il existe et si ce n'est pas le fichier par défaut
            if ($file != 'default.png') {
                unlink($this->getParameter('uploads_directory') . '/' . $file);
            }
        } catch (Throwable $th) {
            // Si le fichier n'existe pas, on ne fait rien
        }
    }
}
