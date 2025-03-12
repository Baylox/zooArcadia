<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderImage
{
    private SluggerInterface $slugger;
    private string $uploadDir;

    public function __construct(SluggerInterface $slugger, string $uploadDir)
    {
        $this->slugger = $slugger;
        $this->uploadDir = $uploadDir; //public/uploads
    }

    public function uploadAnimalImage(UploadedFile $uploadedFile): string
    {
        return $this->upload($uploadedFile, 'animaux_image');
    }

    public function uploadHabitatImage(UploadedFile $uploadedFile): string
    {
        return $this->upload($uploadedFile, 'habitats_image');
    }

    public function uploadServiceImage(UploadedFile $uploadedFile): string
    {
        return $this->upload($uploadedFile, 'services_image');
    }
    // Encapsulation de la logique de l'upload
    private function upload(UploadedFile $uploadedFile, string $subdirectory): string 
    {
        $destination = $this->uploadDir . '/' . $subdirectory;

        // Vérifie le type MIME réel pour éviter les fichiers déguisés
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = mime_content_type($uploadedFile->getPathname());

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new \Exception("Format d'image non autorisé !");
        }

        // Génère un nom aléatoire pour éviter d'utiliser l'original
        $newFilename = uniqid('img_', true) . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);

        return $newFilename;
    }
}


