<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\UserCrudController;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->adminUrlGenerator
            ->setController(OrderCrudController::class)
            ->generateUrl();

        return $this->redirect($adminUrlGenerator);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('La Boutique Française');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Users', 'fas fa-user', User::class),
            MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class),
            MenuItem::linkToCrud('Product', 'fas fa-tag', Product::class),
            MenuItem::linkToCrud('Carriers', 'fas fa-truck', Carrier::class),
            MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Order::class),
            MenuItem::linkToCrud('Header', 'fas fa-desktop', Header::class),
        ];
    }
}
