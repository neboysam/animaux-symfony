<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Dispose;
use App\Entity\Personne;
use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPersonneController extends AbstractController
{
    /**
     * @Route("/admin/personnes", name="adminPersonnes")
     */
    public function adminPersonnes(PersonneRepository $repository): Response
    {
        $personnes = $repository->findAll();
        return $this->render('admin_personne/adminPersonnes.html.twig', [
            'personnes' => $personnes,
        ]);
    }

    /**
     * @Route("/admin/creation/personne", name="adminCreatPersonne")
     * @Route("/admin/personne/{id}", name="adminModifPersonne")
     */
    public function adminModifPersonnes(Personne $personne = null, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$personne) {
            $personne = new Personne();
        }
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /* $disposes = $form->getData()->getDisposes(); */
            /* foreach($disposes as $dispose) {
                dd($dispose);
            } */
            /* dd($form->getData()->getDisposes()); */
            /* dd($form->all()['animaux']->viewData); */
            /* $animauxArray = $form->all()['animaux']->viewData;
            foreach($animauxArray as $animal) {
                dd($animal);
            } */
            /* dd($form["nom"]->getData()); Leo */
            //dd($form->getData()); // Personne object sent by the form
            //dd($form["animaux"]->getData()); // array of Animal object sent by the form
            /* foreach($animaux as $animal) {
                dd($animal);
            } */
            /* dd($request->get('personne')->getDisposes()); */
            
            $personne = $form->getData();
            $animaux = $form['animaux']->getData(); // array of animal objects sent by the form
            if($animaux) {
                foreach ($animaux as $animal) {
                    $d = new Dispose();
                    $d->setPersonne($personne)
                      ->setAnimal($animal)
                      ->setNb(0)
                    ;
                    $manager->persist($personne); // or, in the Dispose entity to add cascade={"persist"} in $animal and $personne annotations
                    $manager->persist($d);
                }
            }
            $manager->persist($personne);
            $manager->flush();
            return $this->redirectToRoute('adminPersonnes');
        }
        return $this->render('admin_personne/adminModifPersonne.html.twig', [
            'personne' => $personne,
            'form' => $form->createView()
        ]);
    }
}
