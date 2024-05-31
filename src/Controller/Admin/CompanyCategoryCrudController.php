<?php

namespace App\Controller\Admin;

use App\Entity\CompanyCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CompanyCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CompanyCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
              
            ->setEntityLabelInPlural('Catégories');
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
