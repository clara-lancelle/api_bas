<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AdministratorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Administrator::class;
    }
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->upgradePassword($entityInstance, $entityInstance->getPlainPassword());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
    public function upgradePassword(User $user, string $plainPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }
        $hashedPassword = $this->hasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);
    }
}
