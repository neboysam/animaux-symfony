<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFamilleController extends AbstractController
{
    /**
     * @Route("/admin/familles", name="adminFamilles")
     */
    public function familles(FamilleRepository $repository): Response
    {
        $familles = $repository->findAll();
        return $this->render('admin_famille/adminFamilles.html.twig', [
            'familles' => $familles,
        ]);
    }

    /**
     * @Route("/admin/famille/creation", name="adminCreatFamille")
     * @Route("/admin/famille/{id}", name="adminModifFamille")
     */
    public function adminModifFamilles(Famille $famille = null, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$famille) {
            $famille = new Famille();
        }
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($famille);
            $manager->flush();
            $this->addFlash('success', 'Famille a été modifiée.');
            return $this->redirectToRoute('adminFamilles');
        }
        return $this->render('admin_famille/adminModifFamille.html.twig', [
            'famille' => $famille,
            'form' => $form->createView()
        ]);
    }
}