<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Administrator;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AdministratorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Administrator::class;
    }
    public function __construct(private UserPasswordHasherInterface $hasher, private RequestStack $requestStack)
    {
    }


    // --START  logic to hash password when creating or updating entity

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->getPlainPassword() && $this->upgradePassword($entityInstance, $entityInstance->getPlainPassword());
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->getPlainPassword() && $this->upgradePassword($entityInstance, $entityInstance->getPlainPassword());
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

    // -- END hash password logic

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                fn(?Administrator $administrator, ?string $pageName) => $administrator ? $administrator->__toString() : 'Super Administrateur'
            )
            ->setEntityLabelInPlural('Super Administrateurs');

        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'email',
            TextField::new('plainPassword', 'Mot de passe')->onlyOnForms(),
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
            DateTimeField::new('created_at', 'Créé le')->hideOnForm(),
            DateTimeField::new('updated_at', 'Mis à jour le')->hideOnForm(),
            DateTimeField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
            Field::new('status', 'Statut')->hideOnForm()->setSortable(true)
        ];
    }


    // -- START logic to  smooth delete entity

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur supprimé en douceur.');
    }

    public function restoreEntity(EntityManagerInterface $entityManager, AdminContext $context): RedirectResponse
    {
        $entityInstance = $context->getEntity()->getInstance();
        $entityInstance->setDeletedAt(null);
        $entityManager->flush();
        $this->addFlash('success', 'Utilisateur restauré.');
        $request = $this->requestStack->getCurrentRequest();
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

    // -- END smooth delete

    public function configureActions(Actions $actions): Actions
    {
        $displayRestoreAction = Action::new('restaurer', 'Restaurer', 'fas fa-file-invoice')->linkToCrudAction('restoreEntity')
            ->displayIf(static function ($entity) {
                return $entity->getDeletedAt();
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $displayRestoreAction)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn(Action $action) => $action->setLabel('Afficher'))
            ->update(
                Crud::PAGE_INDEX,
                Action::DELETE,
                fn(Action $action) => $action
                    ->setLabel('Supprimer')
                    ->displayIf(function ($entity) {
                        return !$entity->getDeletedAt() && $entity != $this->getUser();
                    })
            )
            ->update(
                Crud::PAGE_DETAIL,
                Action::DELETE,
                fn(Action $action) => $action
                    ->setLabel('Supprimer')
                    ->displayIf(function ($entity) {
                        return !$entity->getDeletedAt() && $entity != $this->getUser();
                    })
            );
    }
}
