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

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $this->slugger->slug($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFilename);

        return $newFilename;
    }
}


