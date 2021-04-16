<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $article2 = new Article();

        //$time = new \DateTime();

        $time = DateTime::createFromFormat('Y-m-d H:i:s', '2021-03-05 10:00:00');
        $time2 = new \DateTime();
        $time2->setTimezone(new DateTimeZone('EUROPE/Paris'));

        $article
            ->setDate($time)
            ->setCategory('Manga')
            ->setTitle('Résumer du premier Tome de Berserk!')
            ->setUnderTitle('Aujourd\'hui je vais vous raconter le premier Tome de Berserk.')
            ->setContents('Lorem ipsum dolor sit amet.')
            ->setImage('image/berserk_T1.jpg')
            ->setVisibility(true)
            ->setUser($this->getReference('Admin'))
        ;

        $manager->persist($article);

        $this->addReference('TheArticle', $article);

        $manager->flush();

        $article2
            ->setDate($time2)
            ->setCategory('Manga')
            ->setTitle('Résumer du premier Tome de Parasyte (Kiseiju)')
            ->setUnderTitle('Aujourd\'hui je vais vous raconter le premier Tome de Parsyte (Kiseiju)')
            ->setContents('Lorem ipsum dolor sit amet.')
            ->setImage('image/parasyteT1.jpg')
            ->setVisibility(true)
            ->setUser($this->getReference('Admin'))
        ;

        $manager->persist($article2);

        //$this->addReference('ParasyteT1Article', $article2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [

            UserFixtures::class,
        ];
    }
}
