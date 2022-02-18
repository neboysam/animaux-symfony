<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @Route("/", name="animaux")
     */
    public function index(AnimalRepository $repository): Response
    {
        $animaux = $repository->findAll();
        return $this->render('animal/animaux.html.twig', [
            'animaux' => $animaux
        ]);
    }

    /**
     * @Route("/animal/{id}", name="afficherAnimal")
     */
    public function animal(AnimalRepository $repository, $id): Response
    {
        $animal = $repository->findOneBy(['id' => $id]);
        return $this->render('animal/afficherAnimal.html.twig', [
            'animal' => $animal
        ]);
    }
}
