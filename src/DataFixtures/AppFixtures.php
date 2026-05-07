<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Entity\Speaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl_PL');

        // Jedna główna pętla dla 10 konferencji
        for ($i = 0; $i < 10; $i++) {
            $conference = new Conference();
            $conference->setCity($faker->city());
            $conference->setYear($faker->year());
            $conference->setIsInternational($faker->boolean());
            
            // Tutaj możesz dodać opis, jeśli Twoja encja go posiada:
            // $conference->setDescription($faker->paragraph());

            // 1. Dodaj prelegentów (2-4 osoby) do TEJ konferencji
            for ($k = 0; $k < $faker->numberBetween(2, 4); $k++) {
                $speaker = new Speaker();
                $speaker->setFirstName($faker->firstName());
                $speaker->setLastName($faker->lastName());
                $speaker->setBio($faker->sentence(15));
                
                $conference->addSpeaker($speaker); 
                $manager->persist($speaker);
            }

            // 2. Dodaj komentarze (20-50 sztuk) do TEJ SAMEJ konferencji
            $commentsCount = $faker->numberBetween(20, 50);
            for ($j = 0; $j < $commentsCount; $j++) {
                $comment = new Comment();
                $comment->setConference($conference);
                $comment->setAuthor($faker->name());
                $comment->setEmail($faker->email());

                if ($j === 0) {
                    $comment->setText($faker->text(600)); // Gwiazda
                } else {
                    $comment->setText($faker->text(200));
                }

                $datePeriod = $faker->numberBetween(1, 3);
                if ($datePeriod === 1) {
                    $date = $faker->dateTimeBetween('-1 week', 'now');
                } elseif ($datePeriod === 2) {
                    $date = $faker->dateTimeBetween('-2 months', '-1 month');
                } else {
                    $date = $faker->dateTimeBetween('-1 year', '-3 months');
                }

                $comment->setCreatedAt(\DateTimeImmutable::createFromMutable($date));
                $manager->persist($comment);
            }

            $manager->persist($conference);
        }

        $manager->flush();
    }
}