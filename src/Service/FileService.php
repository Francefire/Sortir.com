<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private string $targetDirectory = 'images/uploads/profile';

    public function upload(UploadedFile $file): string
    {

        $fileHash = md5_file($file->getPathname());
        $fileName = $fileHash . '.' . $file->guessExtension();
        $file->move($this->getTargetDirectory(), $fileName);

        // TODO: Optimiser les images téléchargées

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}