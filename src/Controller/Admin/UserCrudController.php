<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class UserCrudController extends AbstractCrudController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $statusField = Field::new('status', 'Statut')->hideOnForm()->setSortable(true);

        return [
            IdField::new('id')->onlyOnDetail(),
            TextField::new('firstname', 'Prénom'),
            TextField::new('name', 'Nom'),
            EmailField::new('email', 'Email'),
            TextField::new('password', 'Mot de passe')->hideOnIndex(),
            ChoiceField::new('gender', 'Genre')->setChoices([
                'Homme' => 'male',
                'Femme' => 'female',
                'Autre' => 'other',
            ]),
            DateTimeField::new('created_at', 'Créé le')->hideOnForm(),
            DateTimeField::new('updated_at', 'Mis à jour le')->hideOnForm(),
            DateTimeField::new('deleted_at', 'Supprimé le')->hideOnIndex()->hideOnForm(),
            $statusField,
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $softDeleteAction = Action::new('softDelete', 'Supprimer')
            ->linkToCrudAction('softDelete') ;

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Editer');
            })
            ->add(Crud::PAGE_INDEX, $softDeleteAction);
    }

    public function softDelete(EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->query->get('entityId');
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('L\'utilisateur avec l\'ID "%s" n\'existe pas.', $id));
        }

        $user->softDelete();
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé en douceur.');

        return $this->redirectBack();
    }

    private function redirectBack(): RedirectResponse
    {
        $request = $this->requestStack->getCurrentRequest();
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }
}
