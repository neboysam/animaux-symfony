<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
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
     * @Route("/admin/animal/{id}", name="adminModifAnimal")
     */
    public function modifAnimal(Animal $animal = null, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$animal) {
            $animal = new Animal();
        }
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $modifAnimal = $animal->getId() !== null;
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
}
