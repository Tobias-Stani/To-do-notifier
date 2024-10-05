<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tarea')]
class TareaController extends AbstractController
{
    #[Route('/', name: 'app_tarea_index', methods: ['GET'])]
    public function index(TareaRepository $tareaRepository): Response
    {
        $tareas = $tareaRepository->findAll();
    
        $ultimasTareas = $tareaRepository->findBy([], ['fecha' => 'DESC'], 5);

        //calcula tareas de la semana.
        $inicioSemana = new \DateTime('monday this week');
        $finSemana = new \DateTime('sunday this week');
        $finSemana->setTime(23, 59, 59);

        // Obtener las tareas de la semana actual
        $tareasSemana = $tareaRepository->createQueryBuilder('t')
            ->where('t.fecha >= :inicioSemana')
            ->andWhere('t.fecha <= :finSemana')
            ->setParameter('inicioSemana', $inicioSemana)
            ->setParameter('finSemana', $finSemana)
            ->getQuery()
            ->getResult();

        $events = [];
        foreach ($tareas as $tarea) {
            $events[] = [
                'id' => $tarea->getId(),
                'title' => $tarea->getTitulo(),
                'start' => $tarea->getFecha()->format('Y-m-d'),
            ];
        }
    
        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareas,
            'ultimasTareas' => $ultimasTareas, // Enviamos las últimas 5 tareas
            'events' => json_encode($events), // Asegúrate de pasar los eventos a la vista
            'tareasSemana' => $tareasSemana,
        ]);
    }
    

    #[Route('/new', name: 'app_tarea_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tarea = new Tarea();
        
        // Obtener la fecha del parámetro de consulta
        $fechaParam = $request->query->get('fecha');
        if ($fechaParam) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $fechaParam);
            if ($fecha) {
                $tarea->setFecha($fecha); // Establecer la fecha en la nueva tarea
            }
        }
    
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tarea);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_tarea_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_tarea_show', methods: ['GET'])]
    public function show(Tarea $tarea): Response
    {
        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tarea_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tarea $tarea, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tarea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tarea_delete', methods: ['POST'])]
    public function delete(Request $request, Tarea $tarea, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tarea->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($tarea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tarea_index', [], Response::HTTP_SEE_OTHER);
    }
}
