<?php

namespace App\Controller;

use App\Entity\Cronometro;
use App\Entity\Materia;
use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\CronometroRepository;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tarea')]
class TareaController extends AbstractController
{

    #funcion para formatear tiempo
    private function formatTime(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        $formattedTime = '';

        if ($hours > 0) {
            $formattedTime .= $hours . ' Horas : ';
        }
        if ($minutes > 0) {
            $formattedTime .= $minutes . ' Minutos : ';
        }
        if ($remainingSeconds > 0) {
            $formattedTime .= $remainingSeconds;
        }

        return trim($formattedTime) . ' Segundos : ';
    }
        

    #[Route('/api', name: 'app_tarea_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Recuperar todas las tareas desde el repositorio
        $tareas = $entityManager->getRepository(Tarea::class)->findAll();
        // Serializar las tareas en formato JSON
        $data = [];
        foreach ($tareas as $tarea) {
            $data[] = [
                'id' => $tarea->getId(),
                'titulo' => $tarea->getTitulo(),
                'descripcion' => $tarea->getDescripcion(),
                'fecha' => $tarea->getFecha()->format('Y-m-d'), // Formatear la fecha como string
            ];
        }
    
        // Devolver la respuesta JSON
        return new JsonResponse($data, 200);
    }
    

    #[Route('/new', name: 'app_tarea_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tarea = new Tarea();
    
        $fechaParam = $request->query->get('fecha');
        if ($fechaParam) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $fechaParam);
            if ($fecha) {
                $tarea->setFecha($fecha); 
            }
        }
    
        $materiaId = $request->query->get('materia_id'); 
        if ($materiaId) {
            $materia = $entityManager->getRepository(Materia::class)->find($materiaId);
            if ($materia) {
                $tarea->setMateria($materia); 
            }
        }
    
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tarea);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_materia_show', ['id' => $materiaId], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
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

    #[Route('/cronometro/finish', name: 'app_cronometro_finish', methods: ['POST'])]
    public function finishCronometro(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Obtener los datos enviados por AJAX en formato JSON
        $data = json_decode($request->getContent(), true);
    
        // Verificar si el tiempo fue enviado
        if (isset($data['timeElapsed'])) {
            $timeElapsed = $data['timeElapsed']; // Tiempo en segundos
    
            // Crear una nueva instancia de la entidad Cronometro
            $cronometro = new Cronometro();
            $cronometro->setTime($timeElapsed); // Establecer el tiempo
            $cronometro->setDate(new \DateTime()); // Establecer la fecha actual
    
            // Guardar la entidad en la base de datos
            $entityManager->persist($cronometro);
            $entityManager->flush(); // Realizar la inserción en la base de datos
    
            // Devolver una respuesta de éxito
            return new JsonResponse(['message' => 'Tiempo registrado correctamente', 'timeElapsed' => $timeElapsed]);
        }
    
        // Si no se recibió el tiempo, devolver un error
        return new JsonResponse(['error' => 'No se envió tiempo'], 400);
    }

    #[Route('/cronometros', name: 'app_cronometros')]
    public function listCronometros(CronometroRepository $cronometroRepository): Response
    {
        $cronometros = $cronometroRepository->findAll();

        // Sumar el tiempo total en segundos
        $totalTime = 0;
        foreach ($cronometros as $cronometro) {
            $totalTime += $cronometro->getTime(); // Asumiendo que getTime() devuelve el tiempo en segundos
        }

        // Puedes dividir el tiempo total en partes (ejemplo: horas, minutos, segundos)
        $totalHours = floor($totalTime / 3600);
        $totalMinutes = floor(($totalTime % 3600) / 60);
        $totalSeconds = $totalTime % 60;

        return $this->render('cronometros/list.html.twig', [
            'cronometros' => $cronometros,
            'totalTime' => $totalTime, // Pasamos el tiempo total a la vista
            'totalHours' => $totalHours,
            'totalMinutes' => $totalMinutes,
            'totalSeconds' => $totalSeconds,
        ]);
    }
}
