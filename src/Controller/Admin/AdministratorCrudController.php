<?php

namespace App\Controller\Admin;

use App\Entity\Administrator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AdministratorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Administrator::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(
                fn(?Administrator $administrator, ?string $pageName) => $administrator ? $administrator->__toString() : 'Administrateur'
            )
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'email',
            TextField::new('password')->onlyOnForms(),
            'name',
            'firstname',
            'cellphone',
            'city',
            'zipCode',
            DateTimeField::new('created_at', 'Créé le')->hideOnForm(),
            DateTimeField::new('updated_at', 'Mis à jour le')->hideOnForm(),
            DateTimeField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // Update actions so the admin can't delete himself
        return $actions
            ->update(
                Crud::PAGE_DETAIL,
                Action::DELETE,
                fn(Action $action) =>
                $action
                    ->setLabel('Supprimer')
                    ->displayIf(function ($entity) {
                        return $entity != $this->getUser();
                    })
            )
            ->remove(
                Crud::PAGE_INDEX,
                Action::DELETE
            );
    }
}
