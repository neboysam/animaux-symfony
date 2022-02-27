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
     * @Route("/admin/famille/{id}", name="adminModifFamille", methods={"GET|POST"})
     */
    public function adminModifFamilles(Famille $famille = null, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$famille) {
            $famille = new Famille();
        }
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $modif = $famille->getId() !== null;
            $animaux = $form['animaux']->getData(); //Doctrine\Common\Collections\ArrayCollection
            if($animaux) {
                foreach($animaux as $animal) {
                    $animal->setFamille($famille);
                    $manager->persist($animal);
                }
            }
            $manager->persist($famille);
            $manager->flush();
            $this->addFlash('success', ($modif) ? 'La famille a été modifiée.' : 'La famille a été supprimee');
            return $this->redirectToRoute('adminFamilles');
        } 
        return $this->render('admin_famille/adminModifFamille.html.twig', [
            'famille' => $famille,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/famille/suppression/{id}", name="adminSuppFamille", methods={"POST"})
     */
    public function adminSuppFamille(Famille $famille, Request $request, EntityManagerInterface $manager): Response
    {
        $manager->remove($famille);
        $manager->flush();
        return $this->redirectToRoute('adminFamilles');
    }   
}
