<?php

namespace App\Controller\Admin;

use App\Entity\CompanyUser;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompanyUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CompanyUser::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            'email',
            'password',
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
