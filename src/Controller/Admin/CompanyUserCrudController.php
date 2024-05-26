<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\CompanyUser;
use App\Enum\Gender;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class CompanyUserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $hasher, private RequestStack $requestStack)
    {
    }

    public static function getEntityFqcn(): string
    {
        return CompanyUser::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                fn(?CompanyUser $companyUser, ?string $pageName) => $companyUser ? $companyUser->__toString() : 'Administrateur d\'entreprise'
            )
            ->setEntityLabelInPlural('Administrateurs d\'entreprise')
            ->setSearchFields(['firstname', 'name', 'email', 'city', 'company.name']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'Prénom'),
            TextField::new('name', 'Nom'),
            EmailField::new('email', 'Email'),
            ImageField::new('profile_image', 'Image de profil')
                ->setUploadedFileNamePattern(
                    fn(UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension())
                )
                ->setUploadDir('public/assets/images/users')
                ->setBasePath('assets/images/users')
                ->setRequired(false),
            TextField::new('cellphone', 'Téléphone portable'),
            NumberField::new('zipCode', 'Code Postal'),
            TextField::new('city', 'Ville'),
            TextField::new('plainPassword', 'Mot de passe')->hideOnIndex(),
            ChoiceField::new('gender', 'Genre')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => Gender::class,
                    'choice_label' => static function (Gender $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (Gender $choice): string {
                    return $choice->value;
                }),
            AssociationField::new('company', 'Entreprise')->setFormTypeOption('choice_label', 'name'),
            TextField::new('position', 'Poste'),
            TextField::new('officePhone', 'Téléphone de bureau'),
            DateTimeField::new('created_at', 'Créé le')->hideOnForm(),
            DateTimeField::new('updated_at', 'Mis à jour le')->hideOnForm(),
            DateTimeField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
            Field::new('status', 'Statut')->hideOnForm()
        ];
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

    // -- START logic to  smooth delete entity
    private function countActiveAdminsOfCompany(EntityManagerInterface $entityManager, Company $company): int
    {
        return $entityManager->createQueryBuilder()
            ->select('COUNT(admin.id)')
            ->from(CompanyUser::class, 'admin')
            ->where('admin.deleted_at IS NULL')
            ->andWhere('admin.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof CompanyUser) {
            $totalAdmins = $this->countActiveAdminsOfCompany($entityManager, $entityInstance->getCompany());

            if ($totalAdmins == 1) {
                $this->addFlash('danger', 'Impossible de supprimer le dernier administrateur actif de cette entreprise.');
                return;
            }
        }

        $entityInstance->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();
        $this->addFlash('success', 'Entité supprimée en douceur.');
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
                    ->displayIf(static function ($entity) {
                        return !$entity->getDeletedAt();
                    })
            )
            ->update(
                Crud::PAGE_DETAIL,
                Action::DELETE,
                fn(Action $action) => $action
                    ->setLabel('Supprimer')
                    ->displayIf(static function ($entity) {
                        return !$entity->getDeletedAt();
                    })
            );
    }

}
