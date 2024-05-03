<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private const UPLOADS_DIR = '/avatars';

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly FileService                 $fileService
    )
    {
    }

    public function editUser(User $user, ?UploadedFile $file): void
    {
        if (!empty($user->getPlainPassword())) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
        }

        if ($file) {
            if ($user->getAvatarFileName() != null) {
                $this->fileService->remove(self::UPLOADS_DIR, $user->getAvatarFileName());
            }
            
            $fileName = $this->fileService->upload($file, self::UPLOADS_DIR);
            $user->setAvatarFileName($fileName);
        }

        $this->entityManager->flush();
    }
}