<?php

namespace App\Controller;

use App\Entity\Materia;
use App\Form\MateriaType;
use App\Repository\MateriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/materia')]
class MateriaController extends AbstractController
{
    #[Route('/', name: 'app_materia_index', methods: ['GET'])]
    public function index(MateriaRepository $materiaRepository): Response
    {
        return $this->render('materia/index.html.twig', [
            'materias' => $materiaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_materia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $materium = new Materia();
        $form = $this->createForm(MateriaType::class, $materium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($materium);
            $entityManager->flush();

            return $this->redirectToRoute('app_materia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materia/new.html.twig', [
            'materium' => $materium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materia_show', methods: ['GET'])]
    public function show(Materia $materium): Response
    {
        return $this->render('materia/show.html.twig', [
            'materium' => $materium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_materia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materia $materium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MateriaType::class, $materium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_materia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('materia/edit.html.twig', [
            'materium' => $materium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materia_delete', methods: ['POST'])]
    public function delete(Request $request, Materia $materium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materium->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($materium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_materia_index', [], Response::HTTP_SEE_OTHER);
    }
}
