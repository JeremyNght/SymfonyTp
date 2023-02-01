<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home', methods: ['GET'])]
    public function admin_home(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/import-dishes', name: 'import_dish')]
    public function importDishesAction(EntityManagerInterface $em): Response
    {
      $json = file_get_contents("../public/upload/dish.json");
      $data = json_decode($json,true);

      $dishRepo = $em->getRepository(Dish::class);
      $categoryRepo = $em->getRepository(Category::class);
      $allergenRepo = $em->getRepository(Allergen::class);
      $userRepo = $em->getRepository(User::class);

      foreach(["desserts","entrees","plats" ] as $type)
      {
        $category = $categoryRepo->findOneBy(array("Name" => ucfirst($type)));
        if ($category && isset($data[$type])) {
          foreach ($data[$type] as $dishArray) {
            $dish = $dishRepo->findOneBy(
                array("Name" => $dishArray["name"])
            );
            if (!$dish) {
              $dish = new Dish();
            }
            $user = $userRepo->findAll();
            $dish->setName($dishArray["name"]);
            $dish->setCategory($category);
            $dish->setDescription($dishArray["text"]);
            $dish->setCalories($dishArray["calories"]);
            $dish->setImage($dishArray["image"]);
            $dish->setSticky($dishArray["sticky"]);
            $dish->setPrice($dishArray["price"]);
            $dish->setUser($user[0]);
            foreach ($dishArray["allergens"] as $allergenArray) {
              $allergen = $allergenRepo->findOneBy(
                  array("name" => $allergenArray)
              );
              if (!$allergen) {
                $allergen = new Allergen();
              }
              $allergen->setName($allergenArray);
              $dish->addAllergen($allergen);
            }
            $em->persist($dish);
            $em->flush();
          }
        }
      }
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}