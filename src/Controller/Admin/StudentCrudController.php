<?php

namespace App\Controller\Admin;

use App\Enum\Gender;
use App\Entity\Student;
use App\Entity\User;
use App\Enum\StudyLevel;
use App\Enum\StudyYear;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


class StudentCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $hasher, private RequestStack $requestStack)
    {
    }
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                'Etudiant'
            )
            ->setEntityLabelInPlural('Etudiants')
            ->setSearchFields(['firstname', 'name', 'email', 'city']);
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
                })
            ,
            DateField::new('birthdate', 'Anniversaire'),
            Field::new('driver_license','Permis de conduire')->hideOnDetail(),
            Field::new('handicap','Handicap')->hideOnDetail(),
            Field::new('prepared_degree','Diplome préparé')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => StudyLevel::class,
                    'choice_label' => static function (StudyLevel $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (StudyLevel $choice): string {
                    return $choice->value;
                })
            ,
            Field::new('school_name', 'Nom de l\'établissement')->hideOnDetail(),
            ChoiceField::new('study_years', 'Années d\'études')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => StudyYear::class,
                    'choice_label' => static function (StudyYear $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (StudyYear $choice): string {
                    return $choice->value;
                })
            ,
            UrlField::new('personnal_website', 'Site personnel (portfolio, book, ..)')->hideOnDetail(),
            UrlField::new('linkedin_page', 'Page linkedin')->hideOnDetail(),
            AssociationField::new('skills', 'Compétences')->setFormTypeOption('choice_label', 'name'),
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