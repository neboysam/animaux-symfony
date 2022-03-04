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
     * @Route("/admin/creation/continent", name="adminCreatContinent")
     * @Route("/admin/continent/{id}", name="adminModifContinent", methods={"GET|POST"})
     */
    public function modifContinent(Continent $continent = null, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$continent) {
            $continent = new Continent();
        }
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $modifCont = $continent->getId() !== null;
            $manager->persist($continent);
            $manager->flush();
            $this->addFlash('success', ($modifCont) ? "Le continent a été modifié." : "Le continent a été ajouté.");
            return $this->redirectToRoute('adminContinents');
        }
        return $this->render('admin_continent/adminModifContinent.html.twig', [
            'continent' => $continent,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/continent/suppression/{id}", name="adminSuppContinent", methods={"POST"})
     */
    public function suppressionContinent(Continent $continent, Request $request, EntityManagerInterface $manager): Response
    {
        if($this->isCsrfTokenValid('SUP' . $continent->getId(), $request->get('_token'))) {
            $manager->remove($continent);
            $manager->flush();
            $this->addFlash('success', "Le continent a ete supprime");
            return $this->redirectToRoute('adminContinents');
        }
    }
}
