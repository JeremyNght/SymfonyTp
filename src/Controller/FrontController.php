<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Dish;
use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    #[Route('/equipe', name: 'front_team', methods: ['GET'])]
    public function front_team(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('front/front_team.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/carte', name: 'front_dishes', methods: ['GET'])]
    public function front_dishes(DishRepository $dishRepository, CategoryRepository $categoryRepository): Response
    {
        $countDishesByCategory = $dishRepository->getCountDishesByCategories2();

        return $this->render('front/front_dishes.html.twig', [
            'countDishesByCategory' => $countDishesByCategory,
        ]);
    }

    #[Route('/carte/{id}', name: 'front_dishes_category', methods: ['GET'])]
    public function front_dishes_category(ManagerRegistry $doctrine, int $id): Response
    {
      $category = $doctrine->getRepository(Category::class)->find($id);
      $dishes = $doctrine->getRepository(Dish::class)->findBy(array('category' => $id));
      foreach ($dishes as $dish) {
        $dish->allergens = $dish->getAllergen();
      }

      if (!$category) {
        return $this->render('404.html.twig', [
            'error' => 'No category found for id '.$id
        ]);
      }
      return $this->render( 'front/front_dish_id.html.twig', [
        'category' => $category,
        'dishes' => $dishes,
        ]
      );
    }
}
