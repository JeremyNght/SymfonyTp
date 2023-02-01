<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Form\AllergenType;
use App\Repository\AllergenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/allergen')]
class AllergenController extends AbstractController
{
    #[Route('/', name: 'app_allergen_index', methods: ['GET'])]
    public function index(AllergenRepository $allergenRepository): Response
    {
        return $this->render('allergen/index.html.twig', [
            'allergens' => $allergenRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_allergen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AllergenRepository $allergenRepository): Response
    {
        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergenRepository->save($allergen, true);

            return $this->redirectToRoute('app_allergen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('allergen/new.html.twig', [
            'allergen' => $allergen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_allergen_show', methods: ['GET'])]
    public function show(Allergen $allergen): Response
    {
        return $this->render('allergen/show.html.twig', [
            'allergen' => $allergen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_allergen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Allergen $allergen, AllergenRepository $allergenRepository): Response
    {
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allergenRepository->save($allergen, true);

            return $this->redirectToRoute('app_allergen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('allergen/edit.html.twig', [
            'allergen' => $allergen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_allergen_delete', methods: ['POST'])]
    public function delete(Request $request, Allergen $allergen, AllergenRepository $allergenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$allergen->getId(), $request->request->get('_token'))) {
            $allergenRepository->remove($allergen, true);
        }

        return $this->redirectToRoute('app_allergen_index', [], Response::HTTP_SEE_OTHER);
    }
}
