<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Repository\ContinentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContinentController extends AbstractController
{
    /**
     * @Route("/continents", name="continents")
     */
    public function continents(ContinentRepository $repository): Response
    {
        $continents = $repository->findAll();
        return $this->render('continent/continents.html.twig', [
            'continents' => $continents,
        ]);
    }

    /**
     * @Route("/continent/{id}", name="afficherContinent")
     */
    public function afficherContinent(Continent $continent): Response
    {
        return $this->render('continent/afficherContinent.html.twig', [
            'continent' => $continent,
        ]);
    }
}
