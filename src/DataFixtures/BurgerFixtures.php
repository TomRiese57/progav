<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Burger;
use App\DataFixtures\ImageFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BurgerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $burger = new Burger();
        $burger->setNom('Krabby Burger');
        $burger->setPrix(5);
        $burger->setImage($this->getReference(ImageFixtures::IMAGE_REFERENCE . '_1', Image::class));
        $manager->persist($burger);

        $burger2 = new Burger();
        $burger2->setNom('Cheesy Burger');
        $burger2->setPrix(6.50);
        $burger2->setImage($this->getReference(ImageFixtures::IMAGE_REFERENCE . '_2', Image::class));
        $manager->persist($burger2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ImageFixtures::class,
        ];
    }
}
