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
            //dd($form["animaux"]->getData()); // arrayCollection of Animal objects sent by the form. toArray() to turn it to array
            /* foreach($animaux as $animal) {
                dd($animal);
            } */
            /* dd($request->get('personne')->getDisposes());
            
            //this gives an array of objects selected animals from the form
            $request->request->all()['animaux'] 
            */

            //this lines gives the following array:
            //dd($request->get('personne')->getDisposes()->toArray());
            /** this data comes from the database table 
             * array:4 [▼
             *   0 => App\Entity\Dispose {#1301
             *       -id: 23
             *       -animal: App\Entity\Animal {#1208 ▶}
             *       -personne: App\Entity\Personne {#826 ▶}
             *       -nb: 0
             *   }
             *  1 => App\Entity\Dispose {#1295 ▼
            *       -id: 24
            *       -animal: App\Entity\Animal {#1186 ▶}
            *       -personne: App\Entity\Personne {#826}
            *       -nb: 0
            *       }
        *       2 => App\Entity\Dispose {#1299 ▼
        *           -id: 25
        *           -animal: App\Entity\Animal {#1180 ▶}
        *           -personne: App\Entity\Personne {#826}
        *           -nb: 0
        *       }
        *       3 => App\Entity\Dispose {#1366 ▼
        *            -id: 26
        *            -animal: App\Entity\Animal {#1208 ▶}
        *            -personne: App\Entity\Personne {#826}
        *            -nb: 0
        *       }
            *]
             */
            //this gives the IDs of the animals that this person possess
            /* $animauxDispose = $request->get('personne')->getDisposes()->toArray();
            foreach($animauxDispose as $animal) {
                dd($animal->getAnimal()->getId());
            } */

            //this is the array of number of animals of a person
            /* $array = $request->get('personne')->getDisposes()->toArray();
            foreach($array as $el) {
                dd($el->getNb());
            } */

            //array with animal ids' [ [0] => "52" [1] => "54" ] that are selected from the form
            /* $data = $request->request->all();
            dd($data['personne']['animaux']); */

            //this gives the person's ID
            //dd((($request->get('personne')->getDisposes()->toArray())[0])->getPersonne()->getId());
            
            //this gives the person's ID from the form
            /* $personne = $form->getData();
            dd($personne->getId()); */

            /* $animauxDispose = $request->get('personne')->getDisposes()->toArray();
            foreach($animauxDispose as $animalBDD) {
                $animauxIdBDD = $animalBDD->getAnimal()->getId();

                $animauxIdForm = ($request->request->all())['personne']['animaux'];
                foreach($animauxIdForm as $animalForm) {
                    if($animauxIdBDD == $animalForm) {
                        echo "Identique " . $animalForm;
                    } else {
                        "Pas identique " . $animalForm . " " . $animauxIdBDD;
                    }
                }
            } */

            //this gives an array of App\Entity\Dispose objects: id, animal object, personne object and nb
            //dd($request->get('personne')->getDisposes()->toArray());

            /* dd(($request->request->all())); */
            //dd($animauxForm = $form['animaux']->getData()->toArray());

            //personne object from the database
            //dd($personne->getDisposes()->toArray());
            $personneDisposes = $personne->getDisposes()->toArray();
            $animauxPersonneArray = [];
            foreach ($personneDisposes as $dispose) {
                //animals' id from the database for the selected person $dispose->getAnimal()->getId()
                //array_diff_assoc($animauxForm = $form['animaux']->getData()->toArray(), $dispose->getAnimal());
                $animauxPersonneArray[] = $dispose->getAnimal();
                
                /* dd($dispose->getAnimal()); */
            }
            /* dd($animauxPersonneArray); */
            $animauxFormArray = $form['animaux']->getData()->toArray();
            /* function compareCars(Animal $objA, Animal $objB) {
                return $objA->getId() <=> $objB->getId();
              } */
            //dd($animauxFormArray);

            $compareId = function (Animal $animal1, Animal $animal2) {
                return $animal1->getId() <=> $animal2->getId();
              };
            //if not empty: there are animals selected from the form that person does not have  
            $diff = array_udiff($animauxFormArray, $animauxPersonneArray, $compareId);
            
            //if not empty: only all animals that the person already has; if empty: only all animals that she does not have were selected
            $diffArray = array_udiff($animauxFormArray, $diff, $compareId);

            /* dd($diff); */
            //array_diff(($animauxForm = $form['animaux']->getData()->toArray()), $animauxArray);

            //array of animal objects from the form
            //dd(($form['animaux']->getData())->toArray());

            //array of animals' id from the form
            //$animauxIdForm = ($request->request->all())['personne']['animaux'];

            //za svaki animal id iz formulara koji se za datu osobu ne nalazi u array animaux date personne treba napraviti novi red u Dispose: new Dispose()

            //array of animal objects from the form
            //$animauxForm = $form['animaux']->getData()->toArray();
            if($animauxFormArray) {
                foreach ($animauxFormArray as $animalForm) {
                    $personneDisposes = $personne->getDisposes()->toArray();
                    //no difference: only all animals that already belong to the person have been selected from the form
                    if(!$diff && $diffArray) {
                    //array of animal objects from the request (form), dispose table
                    //$animauxDispose = $request->get('personne')->getDisposes()->toArray();
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
                            // or, in the Dispose entity to add cascade={"persist"} in $animal and $personne annotations
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
                        // or, in the Dispose entity to add cascade={"persist"} in $animal and $personne annotations
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
