<?php

namespace App\Controller;

use App\Repository\FamilleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FamilleController extends AbstractController
{
    /**
     * @Route("/familles", name="familles")
     */
    public function familles(FamilleRepository $repository): Response
    {
        $familles = $repository->findAll();
        return $this->render('famille/familles.html.twig', [
            'familles' => $familles
        ]);
    }

    /**
     * @Route("/famille/{id}", name="afficherFamille")
     */
    public function afficherFamille(FamilleRepository $repository, $id): Response
    {
        $famille = $repository->findOneBy(['id' => $id]);
        return $this->render('famille/afficherFamille.html.twig', [
            'famille' => $famille,
        ]);
    }
}
