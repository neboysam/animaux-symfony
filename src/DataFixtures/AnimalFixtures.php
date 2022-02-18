<?php

namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Famille;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $f1 = new Famille();
        $f1->setLibelle('mammiferes')
            ->setDescription('Animaux vertebres nourrissant leur petits avec du lait');
        $manager->persist($f1);

        $f2 = new Famille();
        $f2->setLibelle('reptiles')
            ->setDescription('Animaux vertebres qui rampent');
        $manager->persist($f2);

        $f3 = new Famille();
        $f3->setLibelle('poissons')
            ->setDescription('Animaux vertebres du monde aquatique');
        $manager->persist($f3);

        $a1 = new Animal();
        $a1->setNom('Chien')
            ->setDescription('Un animal domestique')
            ->setImage('chien.png')
            ->setPoids(10)
            ->setDangereux(false)
            ->setFamille($f1)
        ;
        $manager->persist($a1);

        $a2 = new Animal();
        $a2->setNom('Cochon')
            ->setDescription('Un animal d\'elevage')
            ->setImage('cochon.png')
            ->setPoids(30)
            ->setDangereux(false)
            ->setFamille($f1)
        ;
        $manager->persist($a2);

        $a3 = new Animal();
        $a3->setNom('Serpent')
            ->setDescription('Un animal dangereux')
            ->setImage('serpent.png')
            ->setPoids(5)
            ->setDangereux(true)
            ->setFamille($f2)
        ;
        $manager->persist($a3);

        $a4 = new Animal();
        $a4->setNom('Crocodile')
            ->setDescription('Un animal tres dangereux')
            ->setImage('crocodile.png')
            ->setPoids(70)
            ->setDangereux(true)
            ->setFamille($f2)
        ;
        $manager->persist($a4);

        $a4 = new Animal();
        $a4->setNom('Requin')
            ->setDescription('Un animal marin tres dangereux')
            ->setImage('requin.png')
            ->setPoids(200)
            ->setDangereux(true)
            ->setFamille($f3)
        ;
        $manager->persist($a4);

        $manager->flush();
    }
}
