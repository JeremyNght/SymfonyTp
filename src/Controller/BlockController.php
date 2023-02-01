<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\DishRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlockController extends AbstractController
{
  public function dayDishes(ManagerRegistry $doctrine, DishRepository $dishRepository, $max = 3)
  {
    $category = $doctrine->getRepository(Category::class)->findOneBy(['Name' => 'Plats']);
    $dishes = $dishRepository->findStickies($category, $max);
    return $this->render(
      'partials/day_dishes.html.twig',
      array('dishes' => $dishes)
    );
  }
}
