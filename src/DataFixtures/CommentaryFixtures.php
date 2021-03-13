<?php

namespace App\DataFixtures;

use App\Entity\Commentary;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentaryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /* $commentary = new Commentary();

        $commentary
            ->setUser($this->getReference('User'))
            ->setArticle($this->getReference('TheArticle'))
        ;

        $manager->persist($commentary);

        $manager->flush(); */
    }

    public function getDependencies()
    {
        return [

            UserFixtures::class,
            ArticleFixtures::class
        ];
    }
}
