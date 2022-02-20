<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonneController extends AbstractController
{
    /**
     * @Route("/personnes", name="personnes")
     */
    public function personnes(PersonneRepository $repository): Response
    {
        $personnes = $repository->findAll();
        return $this->render('personne/personnes.html.twig', [
            'personnes' => $personnes,
        ]);
    }
}
