<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $course = new Course();
        $course->setName('Symfony');
        $course->setContent('Server-side web development with Symfony');
        $course->setDuration(10);
        $course->setDateCreated(new \DateTimeImmutable());
        $course->setPublished(true);

        $manager->persist($course);

//        using faker
        $faker = \Faker\Factory::create('en-GB');

//        creation of 10 courses with faker
        for ($i = 0; $i < 10; $i++) {
            $coursPHP = new Course();
            $coursPHP->setName($faker->word());
            $coursPHP->setContent($faker->realText());
            $coursPHP->setDuration(mt_rand(1, 10));

            $dateCreated = $faker->dateTimeBetween('-2 months', 'now');
//            Datetime has to be converted to DatetimeImmutable
            $coursPHP->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
            $dateModified = $faker->dateTimeBetween($course->getDateCreated()->format('Y-m-d'), 'now');
            $coursPHP->setDateModified(\DatetimeImmutable::createFromMutable($dateModified));

            $coursPHP->setPublished(false);
            $manager->persist($coursPHP);
        }

        // penser a flush sinon l'objet n'est pas enregistre dans la BD
        $manager->flush();
    }
}
