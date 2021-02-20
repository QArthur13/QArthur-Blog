<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $contact = new Contact();

        $contact
            ->setLastName('')
            ->setFirstName('')
            ->setEmail('')
            ->setPhone('')
            ->setMessage('')
        ;

        $manager->persist($contact);

        $manager->flush();
    }
}
