<?php

namespace App\Controller\Admin;

use App\Entity\CompanyUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class CompanyUserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }
    public static function getEntityFqcn(): string
    {
        return CompanyUser::class;
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


    public function configureFields(string $pageName): iterable
    {
        return [
            'email',
            TextField::new('plainPassword', 'Mot de passe')->hideOnIndex(),
            'name',
            'firstname',
            'cellphone',
            'city',
            'zipCode',
            ChoiceField::new('gender', 'Genre')->setChoices([
                'Homme' => 'male',
                'Femme' => 'female',
                'Autre' => 'other',
            ]),
            TextField::new('position', 'Poste'),
            NumberField::new('officePhone', 'Téléphone de bureau'),
            DateTimeField::new('created_at', 'Créé le')->hideOnForm(),
            DateTimeField::new('updated_at', 'Mis à jour le')->hideOnForm(),
            DateTimeField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
        ];
    }

}
