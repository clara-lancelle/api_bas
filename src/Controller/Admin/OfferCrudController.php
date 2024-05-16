<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Entity\OfferType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class OfferCrudController extends AbstractCrudController
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                fn(?Offer $offer, ?string $pageName) => $offer ? $offer->getName() : 'Offre'
            )
            ->setEntityLabelInPlural('Offres');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('company', 'Entreprise'),
            TextField::new('name', 'Intitulé'),
            DateField::new('start_date', 'Date de début'),
            DateField::new('end_date', 'Date de fin'),
            DateField::new('application_limit_date', 'Date limite de dépôt des candidatures'),
            ChoiceField::new('type', 'Type')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => OfferType::class,
                    'choice_label' => static function (OfferType $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (OfferType $choice): string {
                    return $choice->value;
                }),
            TextEditorField::new('description', 'Description')->hideOnIndex(),
            TextField::new('promote_status', 'Niveau d\'études')->hideOnIndex(),
            TextField::new('revenue', 'Salaire')->hideOnIndex(),
            TextField::new('remote', 'Télétravail')->hideOnIndex(),
            IntegerField::new('available_place', 'Places disponibles')->hideOnIndex(),
            DateField::new('created_at', 'Crée le')->hideOnIndex()->hideOnForm(),
            DateField::new('updated_at', 'Mis à jour le')->hideOnIndex()->hideOnForm(),
            DateField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
            Field::new('status', 'Statut')->hideOnForm()->setSortable(true)
        ];
    }


    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();
        $this->addFlash('success', 'Offre désactivée.');
    }

    public function restoreEntity(EntityManagerInterface $entityManager, AdminContext $context): RedirectResponse
    {

        // Récupérer l'instance de l'offre à restaurer
        $offer = $context->getEntity()->getInstance();

        // Vérifier si l'entreprise associée à l'offre est activée
        $company = $offer->getCompany();
        if ($company && !$company->getDeletedAt()) {
            // Réactiver l'offre
            $offer->setDeletedAt(null);
            $entityManager->flush();

            $this->addFlash('success', 'Offre restaurée avec succès.');
        } else {
            // L'entreprise n'est pas activée, afficher un message d'erreur
            $this->addFlash('danger', 'Impossible de restaurer l\'offre car l\'entreprise associée n\'est pas activée.');
        }

        // Rediriger vers la page précédente
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
            ->add(Crud::PAGE_DETAIL, $displayRestoreAction)
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
