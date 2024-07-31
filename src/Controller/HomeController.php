<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAllWithCount();
        $recipes = $em->getRepository(Recipe::class)->findAll();
        $users = $em->getRepository(User::class)->findAll();
        $total = $em->getRepository(Recipe::class)->findTotalDuration();
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'recipes' => $recipes,
            'users' => $users,
            'total' => $total
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function home(EntityManagerInterface $em, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $categories = $em->getRepository(Category::class)->findAll();
        $recipes = $em->getRepository(Recipe::class)->findAll();
        $total = $em->getRepository(Recipe::class)->findTotalDuration();
        return $this->render('admin/home/index.html.twig', [
            'categories' => $categories,
            'recipes' => $recipes,
            'total' => $total
        ]);
    }
}
