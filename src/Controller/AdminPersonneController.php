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
            $modifPersonne = $personne->getId() !== null;
            
            //personne object from the database
            $personneDisposes = $personne->getDisposes()->toArray();
            $animauxPersonneArray = [];
            foreach ($personneDisposes as $dispose) {
                //animals' id from the database for the selected person $dispose->getAnimal()->getId()
                $animauxPersonneArray[] = $dispose->getAnimal();
            }
            
            $animauxFormArray = $form['animaux']->getData()->toArray();

            $compareId = function (Animal $animal1, Animal $animal2) {
                return $animal1->getId() <=> $animal2->getId();
              };
            //if not empty: there are animals selected from the form that person does not have  
            $diff = array_udiff($animauxFormArray, $animauxPersonneArray, $compareId);
            
            //if not empty: only all animals that the person already has; if empty: only all animals that she does not have were selected
            $diffArray = array_udiff($animauxFormArray, $diff, $compareId);

            //array of animal objects from the form
            if($animauxFormArray) {
                foreach ($animauxFormArray as $animalForm) {
                    $personneDisposes = $personne->getDisposes()->toArray();
                    //no difference: only all animals that already belong to the person have been selected from the form
                    if(!$diff && $diffArray) {
                    //array of animal objects from the request (form), dispose table
                        foreach($personneDisposes as $dispose) {
                            $animalIdBDD = $dispose->getAnimal()->getId();
                            $animalIdForm = $animalForm->getId();
                            if($animalIdBDD === $animalIdForm) {
                                $dispose->setNb(($dispose->getNb()) + 1);
                                $manager->persist($personne);
                            }                            
                        } 
                    }
                } 
                //there are animals selected from the form that either already belong to or do not belong to the person
                //if $diffArray not empty: only all animals that already belong to person
                if($diff && $diffArray) {
                    //dd($diff); //selected - does not have: new Dispose
                    //dd($diffArray); //selected - does have: setNb+1
                    if($diff) {
                        foreach($diff as $animalOne) {
                            $d = new Dispose();
                            $d->setPersonne($personne)
                            ->setAnimal($animalOne)
                            ->setNb(1);
                            $manager->persist($d);
                            $manager->persist($personne);
                        }
                    }
                    if($diffArray) {
                        foreach($diffArray as $animalTwo) {
                            foreach($personneDisposes as $dispose) {
                                $animalIdBDD = $dispose->getAnimal()->getId();
                                $animalIdForm = $animalTwo->getId();
                                if($animalIdBDD === $animalIdForm) {
                                    $dispose->setNb(($dispose->getNb()) + 1);
                                    $manager->persist($personne);
                                }
                            }
                        }
                    }    
                }
                //only all selected animals that do not belong to the person
                if($diff && !$diffArray) {
                    //dd($diff);
                    foreach($diff as $diffAnimal) {
                        $d = new Dispose();
                        $d->setPersonne($personne)
                        ->setAnimal($diffAnimal)
                        ->setNb(1);
                        $manager->persist($d);
                        $manager->persist($personne);
                    }
                }      
            }
            $manager->flush();
            $this->addFlash('success', ($modifPersonne) ? 'La personne a été modifiée.' : 'La personne a été ajoutée');
            return $this->redirectToRoute('adminPersonnes');
        }
        return $this->render('admin_personne/adminModifPersonne.html.twig', [
            'personne' => $personne,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/personne/suppression/{id}", name="adminSuppPersonne", methods={"POST"})
     */
    public function adminSuppPersonne (Personne $personne, Request $request, EntityManagerInterface $manager): Response
    {
        if($this->isCsrfTokenValid('SUP' . $personne->getId(), $request->get('_token'))) {
            /* $personne->getAnimaux(); */
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', 'La personne a ete supprime');
            return $this->redirectToRoute('adminPersonnes');
        }
    }   
}
