<?php

namespace App\Controller\Admin;

use App\Entity\Request;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class RequestCrudController extends AbstractCrudController
{
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
            ChoiceField::new('type', 'Type de\'offre')->setFormType(EnumType::class)
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
        ];
    }

}
