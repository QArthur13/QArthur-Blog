<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /** @var  */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $admin = new User();

        $admin
            ->setLastName('Quaranta')
            ->setFirstName('Arthur')
            ->setPseudo('Admin')
            ->setEmail('admin@youpi.fr')
            ->setPassword($this->encoder->encodePassword($admin, 'root'))
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);

        $this->addReference('Admin', $admin);

        $manager->flush();

        $user
            ->setLastName('Marcel')
            ->setFirstName('Dupont')
            ->setPseudo('TesteurDu13')
            ->setEmail('dupont.marcel@youpi.fr')
            ->setPassword($this->encoder->encodePassword($user, 'Rt1!'))
        ;
        $manager->persist($user);

        $this->addReference('User', $user);

        $manager->flush();
    }
}
