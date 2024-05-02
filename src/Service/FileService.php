<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private const UPLOADS_DIR = 'uploads';

    public function __construct(
        private readonly Filesystem $filesystem
    )
    {
        if (!($this->filesystem->exists(self::UPLOADS_DIR))) {
            $this->filesystem->mkdir(self::UPLOADS_DIR);
        }
    }

    public function upload(UploadedFile $file, string $path): string
    {
        $fileHash = md5_file($file->getPathname());
        $fileName = $fileHash . '.' . $file->guessExtension();
        $file->move(self::UPLOADS_DIR . $path, $fileName);

        return $fileName;
    }
}