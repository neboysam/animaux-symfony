<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\AnimalRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animaux", name="animaux")
     */
    public function index(AnimalRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $animaux = $paginator->paginate(
            $repository->findAllWithPagination(),
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('animal/animaux.html.twig', [
            'animaux' => $animaux,
        ]);
    }

    /* public function index(AnimalRepository $repository): Response
    {
        $animaux = $repository->findAll();
        return $this->render('animal/animaux.html.twig', [
            'animaux' => $animaux
        ]);
    } */

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

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $manager)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($utilisateur);
            $manager->flush();
            return $this->redirectToRoute('');
        }
        return $this->render('inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
