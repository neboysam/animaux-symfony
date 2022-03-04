<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
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
            $modifFamille = $famille->getId() !== null;
            $animaux = $form['animaux']->getData(); //Doctrine\Common\Collections\ArrayCollection
            if($animaux) {
                foreach($animaux as $animal) {
                    $animal->setFamille($famille);
                    $manager->persist($animal);
                }
            }
            $manager->persist($famille);
            $manager->flush();
            $this->addFlash('success', ($modifFamille) ? 'La famille a été modifiée.' : 'La famille a été ajoutée');
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
        /* if($this->isCsrfTokenValid('SUP' . $famille->getId(), $request->get('_token'))) {
            try {
                $manager->remove($famille);
                $manager->flush();
                $this->addFlash('success', 'La famille a ete supprime');
                return $this->redirectToRoute('adminFamilles');
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'La famille ' . $famille->getLibelle() . ' ne peux pas etre supprime car il contient les animaux');
                return $this->redirectToRoute('adminFamilles');
            }
        } */
        if($this->isCsrfTokenValid('SUP' . $famille->getId(), $request->get('_token'))) {
            /* $famille->getAnimaux(); */
            $manager->remove($famille);
            $manager->flush();
            $this->addFlash('success', 'La famille a ete supprime');
            return $this->redirectToRoute('adminFamilles');
        }
    }   
}
