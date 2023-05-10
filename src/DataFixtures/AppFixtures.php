<?php

namespace App\DataFixtures;

use App\Entity\Bar;
use App\Entity\Foo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $bar = new Bar();
        $manager->persist($bar);

        $foo = new Foo();
        $foo->setType('1')
            ->setProperty1('1')
            ->setProperty2('2')
        ;
        $manager->persist($foo);
        $bar->addFoo($foo);

        $foo = new Foo();
        $foo->setType('2')
            ->setProperty1('1')
            ->setProperty2('2')
        ;
        $manager->persist($foo);
        $bar->addFoo($foo);

        $manager->flush();
    }
}
