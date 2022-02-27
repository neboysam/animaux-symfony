<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use App\Repository\ContinentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAnimalController extends AbstractController
{
    /**
     * @Route("/admin/animaux", name="adminAnimaux")
     */
    public function animaux(AnimalRepository $repository): Response
    {
        $animaux = $repository->findAll();
        return $this->render('admin_animal/adminAnimaux.html.twig', [
            'animaux' => $animaux,
        ]);
    }

    /**
     * @Route("/admin/animal/creation", name="adminCreatAnimal")
     * @Route("/admin/animal/{id}", name="adminModifAnimal", methods={"GET|POST"})
     */
    public function modifAnimal(Animal $animal = null, ContinentRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        $continentsTous = $repository->findAll();
        if(!$animal) {
            $animal = new Animal();
        }
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $modifAnimal = $animal->getId() !== null;
            $continents = $form['continents']->getData();
            if($continents) {
                foreach($continents as $continent) {
                    $animal->addContinent($continent);
                    $manager->persist($animal);
                    /* dd($continent); */
                }
                /* $continentsSupp = array_diff($continentsTous, $continents->toArray());
                var_dump($continentsSupp); */
            }
            $manager->persist($animal);
            $manager->flush();
            $this->addFlash('success', ($modifAnimal) ? "L'animal a été modifié." : "L'animal a été creé");
            return $this->redirectToRoute('animaux');
        }
        return $this->render('admin_animal/adminModifAnimal.html.twig', [
            'animal' => $animal,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/animal/suppression/{id}", name="adminSuppAnimal", methods={"POST"})
     */
    public function suppressionAnimal(Animal $animal, Request $request, EntityManagerInterface $manager): Response
    {
        if($this->isCsrfTokenValid('SUP' . $animal->getId(), $request->get('_token'))) {
            $manager->remove($animal);
            $manager->flush();
            $this->addFlash('success', "L'animale a été supprimée.");
            return $this->redirectToRoute('adminAnimaux');
        }
    }
}
