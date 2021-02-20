<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $time = new \DateTime();

        $article
            ->setAutorName('Arthur Quaranta')
            ->setDate($time)
            ->setCategory('Manga')
            ->setTitle('Résumer du premier Tome de Berserk!')
            ->setUnderTitle('Aujourd\'hui je vais vous raconter le premier Tome de Berserk.')
            ->setContents('Lorem ipsum dolor sit amet.')
            ->setImage('public/image/berserk_T1.jpg')
        ;

        $manager->persist($article);

        $manager->flush();
    }
}
