<?php

namespace App\Controller\Admin;

use App\Entity\Administrator;
use App\Entity\Company;
use App\Entity\CompanyUser;
use App\Entity\Student;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bourse aux stages');
    }
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->displayUserName()
            // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            // ->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            ->displayUserAvatar()
            ->addMenuItems([
                MenuItem::linkToCrud('Mon profil', 'fa fa-id-card', Administrator::class)
                    ->setAction('detail')
                    ->setEntityId($user->getId()),
                MenuItem::section(),
                MenuItem::linkToLogout('déconnexion', 'fa fa-sign-out'),
            ]);
    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Etudiants', 'fas fa-users', Student::class);
        yield MenuItem::linkToCrud('Utilisateurs Entreprises', 'fas fa-users', CompanyUser::class);
        yield MenuItem::linkToCrud('Administrateurs', 'fas fa-users', Administrator::class);
        yield MenuItem::linkToCrud('Entreprises', 'fas fa-building', Company::class);
    }
}
