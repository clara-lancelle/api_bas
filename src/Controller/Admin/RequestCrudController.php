<?php

namespace App\Controller\Admin;

use App\Entity\Request;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestCrudController extends AbstractCrudController
{
    public function __construct(private RequestStack $requestStack)
    {
    }
    public static function getEntityFqcn(): string
    {
        return Request::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                fn(?Request $request, ?string $pageName) => $request ? $request->getName() : 'Demande'
            )
            ->setEntityLabelInPlural('Demandes')
            ->setSearchFields([ 'name', 'description', 'type', 'school']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Intitulé'),
            AssociationField::new('student', 'Etudiant'),
            ChoiceField::new('type', 'Type de demande')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => OfferType::class,
                    'choice_label' => static function (OfferType $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (OfferType $choice): string {
                    return $choice->value;
                }),
            ChoiceField::new('study_level', 'Niveau recherché')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => StudyLevel::class,
                    'choice_label' => static function (StudyLevel $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (StudyLevel $choice): string {
                    return $choice->value;
                }),
            ChoiceField::new('duration', 'Durée')->setFormType(EnumType::class)
                ->setFormTypeOptions([
                    'class'        => Duration::class,
                    'choice_label' => static function (Duration $choice): string {
                        return $choice->value;
                    }
                ])
                ->formatValue(function (Duration $choice): string {
                    return $choice->value;
                }),
            AssociationField::new('job_profiles', 'Profil metier')->setFormTypeOption('choice_label', 'name'),
            DateField::new('start_date', 'Date de début'),
            DateField::new('end_date', 'Date de fin'),
            TextField::new('school', 'Ecole'),
            TextEditorField::new('description', 'Description')->hideOnIndex(),
            DateField::new('created_at', 'Crée le')->hideOnIndex()->hideOnForm(),
            DateField::new('updated_at', 'Mis à jour le')->hideOnIndex()->hideOnForm(),
            DateField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
            Field::new('status', 'Statut')->hideOnForm()
        ];
    }
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();
        $this->addFlash('success', 'Demande désactivée.');
    }

    public function restoreEntity(EntityManagerInterface $entityManager, AdminContext $context): RedirectResponse
    {

        // Récupérer l'instance de la demande à restaurer
        $request = $context->getEntity()->getInstance();

        // Vérifier si l'entreprise associée à la demande est activée
        $student = $request->getStudent();
        if ($student && !$student->getDeletedAt()) {
            // Réactiver la demande
            $request->setDeletedAt(null);
            $entityManager->flush();
            $this->addFlash('success', 'demande restaurée avec succès.');
        } else {
            // L'entreprise n'est pas activée, afficher un message d'erreur
            $this->addFlash('danger', 'Impossible de restaurer la demande car l\'entreprise associée n\'est pas activée.');
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
