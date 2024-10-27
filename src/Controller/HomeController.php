<?php

namespace App\Controller;

use App\Repository\CarreraRepository;
use App\Repository\CuatrimestreRepository;
use App\Repository\MateriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CarreraRepository $carreraRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'carreras' => $carreraRepository->findAll(),
        ]);
    }
}
