<?php

namespace App\Controller;

use App\Entity\Cuatrimestre;
use App\Form\CuatrimestreType;
use App\Repository\CuatrimestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cuatrimestre')]
class CuatrimestreController extends AbstractController
{
    #[Route('/', name: 'app_cuatrimestre_index', methods: ['GET'])]
    public function index(CuatrimestreRepository $cuatrimestreRepository): Response
    {
        return $this->render('cuatrimestre/index.html.twig', [
            'cuatrimestres' => $cuatrimestreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cuatrimestre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cuatrimestre = new Cuatrimestre();
        $form = $this->createForm(CuatrimestreType::class, $cuatrimestre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cuatrimestre);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cuatrimestre/new.html.twig', [
            'cuatrimestre' => $cuatrimestre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cuatrimestre_show', methods: ['GET'])]
    public function show(Cuatrimestre $cuatrimestre): Response
    {
        return $this->render('cuatrimestre/show.html.twig', [
            'cuatrimestre' => $cuatrimestre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cuatrimestre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cuatrimestre $cuatrimestre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CuatrimestreType::class, $cuatrimestre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cuatrimestre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cuatrimestre/edit.html.twig', [
            'cuatrimestre' => $cuatrimestre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cuatrimestre_delete', methods: ['POST'])]
    public function delete(Request $request, Cuatrimestre $cuatrimestre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cuatrimestre->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($cuatrimestre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cuatrimestre_index', [], Response::HTTP_SEE_OTHER);
    }
}
