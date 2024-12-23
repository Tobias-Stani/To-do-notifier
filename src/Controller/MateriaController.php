<?php

namespace App\Controller;

use App\Entity\Cronometro;
use App\Entity\Materia;
use App\Form\GoalHoursStudyType;
use App\Form\MateriaType;
use App\Repository\CronometroRepository;
use App\Repository\MateriaRepository;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function show(Materia $materium, TareaRepository $tareaRepository, CronometroRepository $cronometroRepository): Response
    {
        $tareas = $tareaRepository->findBy(['materia' => $materium->getId()]);
        $ultimasTareas = $tareaRepository->findBy(['materia' => $materium->getId()], ['fecha' => 'DESC'], 5);
    
        // Obtener los últimos 5 cronómetros de la materia usando el nuevo método
        $timers = $cronometroRepository->findLastFiveByMateria($materium->getId());
    

        $totalTimeSemana = $cronometroRepository->findTotalTimeByWeekAndMateria($materium->getId());
        $totalTimeDia = $cronometroRepository->findTotalTimeByDayAndMateria($materium->getId());

        $objectiveDayGoal = $materium->getDailyGoal();
        $objectiveWeekGoal = $materium->getWeekGoal();

        $totalMinutesCompleted = ($totalTimeSemana['hours'] * 60) + $totalTimeSemana['minutes'];
        $objectiveInMinutes = $objectiveWeekGoal * 60; // Asegúrate que $objectiveWeekGoal es 2 para este caso
        
        // Cálculo del porcentaje semanal
        if ($objectiveInMinutes > 0) {
            $percentage = number_format(($totalMinutesCompleted / $objectiveInMinutes) * 100, 2); // Asegúrate de usar $objectiveInMinutes
        } else {
            $percentage = 0; // O podrías usar null o una bandera para indicar que no se puede calcular
        }

        // Cálculo diario
        $totalMinutesCompletedDaily = ($totalTimeDia['hours'] * 60) + $totalTimeDia['minutes'];
        $objectiveInMinutesDaily = $objectiveDayGoal * 60;
        
        // Calcula porcentaje cumplido del tiempo de estudio diario
        if ($objectiveInMinutesDaily > 0) {
            $percentageDaily = number_format(($totalMinutesCompletedDaily / ($objectiveDayGoal * 60)) * 100, 2);
        } else {
            $percentageDaily = 0; // O usa otra lógica según tus necesidades
        }


        // Preparar eventos para el calendario
        $events = [];
        foreach ($tareas as $tarea) {
            $events[] = [
                'id' => $tarea->getId(),
                'title' => $tarea->getTitulo(),
                'start' => $tarea->getFecha()->format('Y-m-d'),
                'description' => $tarea->getDescripcion(), // También puedes agregar descripción si la necesitas
            ];
        }
    
        // Obtener tareas de la semana actual
        $inicioSemana = new \DateTime('monday this week');
        $finSemana = new \DateTime('sunday this week');
        $finSemana->setTime(23, 59, 59);
    
        $tareasSemana = $tareaRepository->createQueryBuilder('t')
            ->where('t.fecha >= :inicioSemana')
            ->andWhere('t.fecha <= :finSemana')
            ->andWhere('t.materia = :materia') // Filtrar por materia
            ->setParameter('inicioSemana', $inicioSemana)
            ->setParameter('finSemana', $finSemana)
            ->setParameter('materia', $materium) // Usar el objeto de materia directamente
            ->getQuery()
            ->getResult();
    
        // Preparar eventos para las tareas de la semana
        $eventsSemana = [];
        foreach ($tareasSemana as $tarea) {
            $eventsSemana[] = [
                'id' => $tarea->getId(),
                'title' => $tarea->getTitulo(),
                'start' => $tarea->getFecha()->format('Y-m-d'),
            ];
        }
    
        return $this->render('materia/show.html.twig', [
            'materium' => $materium,
            'events' => json_encode($events), // Aquí puedes enviar todos los eventos al calendario
            'timers' => $timers,
            'ultimasTareas' => $ultimasTareas,
            'tareasSemana' => $tareasSemana,
            'tareas' => $tareas,
            'totalTimeSemana' => $totalTimeSemana,
            'totalTimeDia' => $totalTimeDia, 
            'tiempoObjetivoDiario' => $objectiveDayGoal,
            'tiempoObjetivoSemanal' => $objectiveWeekGoal,
            'porcentajeSemanal' => $percentage,
            'porcentajeDiario' => $percentageDaily 
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

    #[Route('/{id}/cronometro', name: 'guardar_cronometro', methods: ['GET', 'POST'])]
    public function guardarCronometro(Request $request, Materia $materium, CronometroRepository $cronometroRepository): JsonResponse
    {
        if ($request->isMethod('POST')) {
            $time = $request->request->get('time');
            
            // Crear un nuevo cronómetro y asociarlo a la materia
            $cronometro = new Cronometro();
            $cronometro->setTime($time);
            $cronometro->setDate(new \DateTime());
            $cronometro->setMateria($materium);
    
            // Guardar en la base de datos
            $cronometroRepository->save($cronometro, true);
    
            return new JsonResponse(['status' => 'success']);
        }
    
        // Si es una solicitud GET, puedes devolver un mensaje o manejarlo de otra forma
        return new JsonResponse(['status' => 'This route only handles POST requests for saving the timer']);
    }    

    #[Route('/{id}/hoursStudy', name: 'goalHoursStudy')]
    public function goalHoursStudy(Request $request, Materia $materia, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoalHoursStudyType::class, $materia);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($materia);
            $entityManager->flush();
    
            // Redirigir a la ruta app_materia_show con el ID de la materia
            return $this->redirectToRoute('app_materia_show', ['id' => $materia->getId()]);
        }
    
        return $this->render('materia/goalHoursStudy.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

}
