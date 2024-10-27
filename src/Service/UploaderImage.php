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
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        $destination = $this->uploadDir . '/animaux_image';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $this->slugger->slug($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFilename);

        return $newFilename;
    }
}

