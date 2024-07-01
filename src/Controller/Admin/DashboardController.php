<?php

namespace App\Controller\Admin;

use App\Entity\Administrator;
use App\Entity\Company;
use App\Entity\CompanyActivity;
use App\Entity\CompanyCategory;
use App\Entity\CompanyUser;
use App\Entity\JobProfile;
use App\Entity\Offer;
use App\Entity\Request;
use App\Entity\SocialNetwork;
use App\Entity\Student;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
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

    /**
     * @param UserInterface|User $user
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->displayUserName()
            // you can return an URL with the avatar image
            // ->setAvatarUrl('https://...')
            ->setAvatarUrl($user->getProfileImage())
            ->displayUserAvatar();
    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Super administrateurs', 'fas fa-users', Administrator::class);

        yield MenuItem::section('Entreprises');
        yield MenuItem::linkToCrud('Entreprises', 'fas fa-building', Company::class);
        yield MenuItem::linkToCrud('Administateurs d\'entreprise', 'fas fa-users', CompanyUser::class);
        yield MenuItem::linkToCrud('Offres', 'fas fa-briefcase', Offer::class);

        yield MenuItem::section('Etudiants');
        yield MenuItem::linkToCrud('Etudiants', 'fas fa-users', Student::class);
        yield MenuItem::linkToCrud('Demandes','fa fa-graduation-cap', Request::class);

        yield MenuItem::section('Gestion des listes');
        yield MenuItem::linkToCrud('Profils metiers', 'fas fa-table', JobProfile::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-table', CompanyCategory::class);
        yield MenuItem::linkToCrud('Activités', 'fas fa-table', CompanyActivity::class);
        yield MenuItem::linkToCrud('Réseaux sociaux', 'fas fa-table', SocialNetwork::class);

    }
}
