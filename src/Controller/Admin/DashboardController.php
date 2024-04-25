<?php

namespace App\Controller\Admin;

use App\Entity\Administrator;
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
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bourse aux stages');
    }
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

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
                MenuItem::linkToLogout('déconnection', 'fa fa-sign-out'),
            ]);
    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Etudiants', 'fas fa-users', Student::class);
        yield MenuItem::linkToCrud('Utilisateurs Entreprises', 'fas fa-users', CompanyUser::class);
        yield MenuItem::linkToCrud('Administrateurs', 'fas fa-users', Administrator::class);
    }
}
