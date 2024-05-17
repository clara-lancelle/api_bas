<?php

namespace App\Controller\Admin;

use App\Entity\JobProfile;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobProfile::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Profil metier')
            ->setEntityLabelInPlural('Profils metiers')
            ->setSearchFields(['name']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Intitulé'),
            ColorField::new('color', 'Couleur')
        ];
    }

}
