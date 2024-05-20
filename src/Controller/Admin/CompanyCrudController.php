<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class CompanyCrudController extends AbstractCrudController
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                fn(?Company $company, ?string $pageName) => $company ? $company->getName() : 'Entreprise'
            )
            ->setEntityLabelInPlural('Entreprises');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextField::new('website_url', 'Site web')->hideOnIndex(),
            ImageField::new('picto_image', 'Pictogramme')
                ->setUploadedFileNamePattern(
                    fn(UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension())
                )
                ->setUploadDir('public/assets/images/companies')
                ->setBasePath('assets/images/companies')
                ->setRequired($pageName != 'edit'),
            ImageField::new('large_image', 'Image large')
                ->setUploadedFileNamePattern(
                    fn(UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension())
                )
                ->setUploadDir('public/assets/images/companies')
                ->setBasePath('assets/images/companies')
                ->setRequired($pageName != 'edit'),
            TextField::new('social_reason', 'Statut juridique'),
            TextField::new('siret', 'Siret')->hideOnIndex(),
            TextField::new('address', 'Adresse')->hideOnIndex(),
            NumberField::new('numberOfOffers', 'Nombre d\'offres')->hideOnForm(),
            TextField::new('zip_code', 'Code postal'),
            TextField::new('city', 'Ville'),
            DateField::new('creation_date', 'Date de création')->hideOnIndex(),
            TextField::new('phone_num', 'Téléphone')->hideOnIndex(),
            TextEditorField::new('description', 'Description')->hideOnIndex(),
            TextField::new('activity', 'Activité')->hideOnIndex(),
            DateTimeField::new('created_at', 'Crée le')->hideOnIndex()->hideOnForm(),
            DateTimeField::new('updated_at', 'Mis à jour le')->hideOnIndex()->hideOnForm(),
            DateTimeField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
            Field::new('status', 'Statut')->hideOnForm()->setSortable(true)
        ];
    }


    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->getOffers()->initialize();
        $offers = $entityInstance->getOffers();
        foreach ($offers as $offer) {
            $offer->setDeletedAt(new \DateTimeImmutable());
        }
        $entityManager->flush();

        $entityInstance->getAdministrators()->initialize();
        $administrators = $entityInstance->getAdministrators();
        foreach ($administrators as $administrator) {
            $administrator->setDeletedAt(new \DateTimeImmutable());
        }
        $entityManager->flush();

        $entityInstance->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();
        $this->addFlash('success', 'Entreprise désactivée.');
    }

    public function restoreEntity(EntityManagerInterface $entityManager, AdminContext $context): RedirectResponse
    {
        $entityInstance = $context->getEntity()->getInstance();
        $entityInstance->setDeletedAt(null);
        $entityManager->flush();
        $this->addFlash('success', 'Entreprise restauré.');
        $request = $this->requestStack->getCurrentRequest();
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }

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
