<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Commentary;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentaryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $commentary = new Commentary();
        $time = DateTime::createFromFormat('Y-m-d H:i:s', '2021-03-05 10:00:00');

        $commentary
            ->setUser($this->getReference('User'))
            ->setArticle($this->getReference('TheArticle'))
            ->setMessage('Ca à l\'air vachement bien ce manga! :)')
            ->setDate($time)
            ->setApprove(true)
        ;

        $manager->persist($commentary);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [

            UserFixtures::class,
            ArticleFixtures::class
        ];
    }
}
