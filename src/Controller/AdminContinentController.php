<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Form\ContinentType;
use App\Repository\ContinentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContinentController extends AbstractController
{
    /**
     * @Route("/admin/continents", name="adminContinents")
     */
    public function index(ContinentRepository $repository): Response
    {
        $continents = $repository->findAll();
        return $this->render('admin_continent/adminContinents.html.twig', [
            'continents' => $continents,
        ]);
    }

    /**
     * @Route("/admin/continent/{id}", name="adminModifContinent")
     */
    public function modifContinent(Continent $continent, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ContinentType::class, $continent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($continent);
            $manager->flush();
            $this->addFlash('success', "Le continent a été modifié.");
            return $this->redirectToRoute('continents');
        }

        return $this->render('admin_continent/adminModifContinent.html.twig', [
            'continent' => $continent,
            'form' => $form->createView()
        ]);
    }
}
