<?php

namespace App\Controller\Admin;

use App\Entity\SocialNetwork;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SocialNetworkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialNetwork::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Réseau social')
            ->setEntityLabelInPlural('Réseaux sociaux')
            ->setSearchFields(['name']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            ImageField::new('logo', 'Pictogramme')
            ->setUploadedFileNamePattern(
                fn(UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension())
            )
            ->setUploadDir('public/assets/images/social')
            ->setBasePath('assets/images/social')
            ->setRequired($pageName != 'edit'),
        ];
    }
    
}
