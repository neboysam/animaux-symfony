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
            //dd($form["animaux"]->getData()); // arrayCollection of Animal objects sent by the form. toArray() to turn it to array
            /* foreach($animaux as $animal) {
                dd($animal);
            } */
            /* dd($request->get('personne')->getDisposes());
            
            //this gives an array of objects selected animals from the form
            $request->request->all()['animaux'] 
            */

            //this lines gives the following array:
            dd($request->get('personne')->getDisposes()->toArray());
            /**
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
            
            $data = $request->request->all();
            dd($data['personne']['animaux']); //array with animal ids' [ [0] => "52" [1] => "54" ]

            //this gives the person's ID
            dd((($request->get('personne')->getDisposes()->toArray())[0])->getPersonne()->getId());
            
            //this gives the person's ID
            /* $personne = $form->getData();
            dd($personne->getId()); */


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
