<?php

namespace App\Controller\Admin;

use App\Entity\OfferRequiredProfile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OfferRequiredProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OfferRequiredProfile::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('text'),
        ];
    }
}
