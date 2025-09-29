<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ImageFixtures extends Fixture
{
    public const IMAGE_REFERENCE = 'Image';
    
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i < 5; $i++) {      
            $image = new Image();
            $image->setUrl(sprintf('images/burger-%d.png', $i));
            $image->setAltText('Une image d\'un burger exceptionnel! C\'est le n°'. $i . '.');
            $manager->persist($image);
            $this->addReference(self::IMAGE_REFERENCE . '_' . $i, $image);
        }

        $manager->flush();
    }
}
