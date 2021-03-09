<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $time = new \DateTime();
        $time->setTimezone(new DateTimeZone('EUROPE/Paris'));

        $article
            ->setDate($time)
            ->setCategory('Manga')
            ->setTitle('Résumer du premier Tome de Berserk!')
            ->setUnderTitle('Aujourd\'hui je vais vous raconter le premier Tome de Berserk.')
            ->setContents('Lorem ipsum dolor sit amet.')
            ->setImage('image/berserk_T1.jpg')
            ->setUser($this->getReference('Admin'))
        ;

        $manager->persist($article);

        $this->addReference('TheArticle', $article);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [

            UserFixtures::class,
        ];
    }
}
