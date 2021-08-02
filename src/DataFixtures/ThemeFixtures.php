<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Themsetting;
class ThemeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $theme = new Themsetting();
        $theme->setId(1);
        $theme->setWebsitename("College/university System");
        $manager->persist($theme);
        $manager->flush();
    }
}
